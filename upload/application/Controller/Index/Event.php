<?php
/**
 * 活动
 * @author reginx<reginx@qq.com>
 * $Id: Event.php 2965 2011-06-20 13:52:15Z cyp $
 */
class Controller_Index_Event extends Core_Controller_TAction
{

	var $emod = NULL;
	var $userInfo = array();
	function __construct($params){
		parent::__construct($params);
		parent::preDispatch();
		$local = Core_Config::get('localauth', 'certification.info');
		$platform = Core_Config::get('platformauth', 'certification.info');
		$localtext = Core_Config::get('localauthtext', 'certification.info');
		$authInfo = array('local' => $local, 'platform' => $platform, 'localtext' => $localtext);
		$this->assign('authtype', $authInfo);
		$this->assign('active', 'event');
		$menu['index']   = '热门推荐';
		$menu['new']     = '发起活动';
		$menu['myevent'] = '我发起的活动';
		$menu['join']    = '报名参加';
		$menu['myjoin']  = '我参与的活动';
		$this->emod = new Model_Event();
		$this->assign('newents',$this->emod->getNewEvents(5,array('Id','title','uname','uid',
		'sdate','edate','deadline','dateline','joins','status','addr','message'),25,50));
		$this->assign('curMenu',$menu[Core_Controller_TAction::getActionName()]);


	}
	/**
	 * 活动首页
	 *
	 */
	public function indexAction(){
		// 获取用户的活动(参与,发起)
		$pid = intval($this->getParam('p'));
		$pid = $pid ? $pid:1;
		$ents = $this->emod->getRecHotEvents(5,array(),50,150,$pid);
		$ents['size'] = sizeof($ents['events']);
		$this->assign('recents',$ents);

		$this->display('user/event_index.tpl');
	}

	/**
	 * 查看指定活动
	 *
	 */
	public function viewAction(){
		$id = $this->getParam('id');
		if(empty($id) || !is_numeric($id) || !$event = $this->emod->getEvent($id)){
			return  $this->indexAction();
		}
		// 获取ID活动
		$this->assign('joined',$this->emod->hasJoined($this->userInfo['uid'],$id));
		$this->assign('event',$event);
		$this->assign('defaulttext',"#".$event['title']."#");
		$users = $this->emod->getEventUsers($event['id'],1,100);
		$this->assign('users',$users);
		$client = Core_Open_Api::getClient();
		$f   = Core_Comm_Validator::getNumArg($this->getParam('f'), 0, 4, 0);
		$num = Core_Comm_Validator::getNumArg($this->getParam('num'), 1, 20, 20);
		$pageInfo = $this->getParam('pageinfo');
		$p = array(
		't' => $event['title'], 	//话题名
		'f' => $f, 			//分页标识（0：第一页，1：向下翻页，2向上翻页，3最后一页，4最前一页）
		'n' => $num, 		//每次请求记录的条数（1-20条）
		'p' => $pageInfo 		//分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
		);
		//检测话题名称正确性
		if(!Core_Comm_Validator::isTopicName($p['t'])){
			$this->error(Core_Comm_Modret::RET_ARG_ERR,'话题名称不正确');
		}
		//检测话题是否被屏蔽
		if(Model_Topic::isMasked($p['t'])){
			$this->error(Core_Comm_Modret::RET_ARG_ERR,'话题被屏蔽');
		}
		try{
			$msg = Core_Open_Api::getClient()->getTopic($p);
			Core_Lib_Base::formatTArr ($msg["data"]["info"]);
		}catch (Exception $e) {
			$msg = array();
		}
		$this->assign ('msglist', $msg["data"]["info"]);//数据

		$preUrl = '/event/view/id/'.$event['id'];
		$frontUrl = $nextUrl = "";
		$hasNext = $msg["data"]["hasnext"];//2表示不能往上翻 1 表示不能往下翻，0表示两边都可以翻
		if($hasNext === 2){
			$nextUrl  = Core_Fun::getParaUrl ($preUrl, array("f"=>1, "pageinfo"=>$msg["data"]["pageinfo"]));
		}elseif($hasNext === 1){
			$frontUrl = Core_Fun::getParaUrl ($preUrl, array("f"=>2, "pageinfo"=>$msg["data"]["pageinfo"]));
		}elseif($hasNext === 0){
			$nextUrl  = Core_Fun::getParaUrl ($preUrl, array("f"=>1, "pageinfo"=>$msg["data"]["pageinfo"]));
			$frontUrl = Core_Fun::getParaUrl ($preUrl, array("f"=>2, "pageinfo"=>$msg["data"]["pageinfo"]));
		}
		$pinfo =  array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		$this->assign ('pageinfo',$pinfo);//分页

		$this->assign('joins',$this->emod->getEventUsers($this->userInfo['uid'],$event['id'],1,7));

		$this->display('user/event_view.tpl');
	}

	function membersAction(){
		$id = $this->getParam('id');
		if(empty($id) || !is_numeric($id) || !$event = $this->emod->getEvent($id)){
			return  $this->indexAction();
		}
		$pid = intval($this->getParam('page'));
		$pid = $pid?$pid:1;
		$this->assign('event',$event);
		$users = $this->emod->getEventUsers($event['id'],$pid,30);
		$users['size'] = sizeof($users['users']);
		$this->emod->getMsg($event['id'],$users['users']);

		$this->assign('username',$this->userInfo['name']);
		$this->assign('joins',$users);
		$this->assign('curMenu',$event['title']);
		$multipage = $this->multipage($users['total'], $users['psize'], $users['pid'], "/event/members/id/".$id.
		"/");
		$this->assign('multipage', $multipage);
		$this->display('user/event_members.tpl');

	}

	/**
	 * 参与活动
	 *
	 */
	function joinAction(){
		$id = intval($this->getParam('id'));
		if($id === 0 ){
			return  $this->showmsg('活动不存在!');
		}
		$event = $this->emod->getEvent($id);
		// 活动是否可参与
		if($event['statusCode'] === 0){
			return $this->showmsg($event['statusText'].'的活动无法参与!','/event/view/id/'.$id);
		}
		if(!$event['contact']){
			$this->emod->doJoin($this->userInfo['uid'],$this->userInfo['name'],$id);
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit();
		}
		$submit = $this->getParam('submit');
		if(!empty($submit)){
			$contact = $this->getParam('contact');
			$msg     = $this->getParam('msg');
			$callback= $this->getParam('callback');
			if(trim($contact) == ''){
				Core_Fun::iFrameExitJson (-1, '请填写联系方式!',array('eventid'=>$result), $callback);
			}
			$this->emod->doJoin($this->userInfo['uid'],$this->userInfo['name'],$id,$contact,$msg);
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '',array('eventid'=>$id), $callback);
		}
		$this->assign('event',$event);
		$this->display('user/event_join.tpl');

	}
	/**
	 * 参与活动
	 *
	 */
	function cjoinAction(){
		// 参与ID活动
		// 验证用户输入信息
		$id = intval($this->getParam('id'));
		if($id === 0 ){
			$this->showmsg('活动不存在!');
		}
		$this->emod->docjoin($this->userInfo['uid'],$id);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit();
	}

	/**
	 * 发起新的活动
	 *
	 */
	function newAction(){
		$submit = $this->getParam('submit');
		$isajax = $this->getParam('isajax');
		$callback = $this->getParam('callback');
		if(!empty($submit)){
			$result = $this->emod->save($this->userInfo,$this->getParams());
			if(is_numeric($result)){
				Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '',array('eventid'=>$result), $callback);
			}else{
				Core_Fun::iFrameExitJson (-1, $result,null, $callback);
			}
		}
		$this->display('user/event_new.tpl');
	}

	/**
	 * 关闭活动
	 *
	 */
	function closeAction(){
		$id = intval($this->getParam('id'));
		$event = $this->emod->getEvent($id);
		if($event['uid'] != $this->userInfo['uid']){
			$this->showmsg("只能操作自己发起的活动!");
		}
		if($event['status'] == 3){
			return $this->showmsg("无法操作管理封禁的活动!");
		}
		$this->emod->closeEvent($id);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit();
	}

	/**
	 * 活动删除
	 *
	 * @return unknown
	 */
	function delAction(){
		$id = intval($this->getParam('id'));
		$event = $this->emod->getEvent($id);
		if($event['uid'] != $this->userInfo['uid']){
			return $this->showmsg("只能操作自己发起的活动!");
		}
		$this->emod->delEvent($id);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit();
	}
	/**
	 * 修改活动信息
	 *
	 */
	function modifyAction(){
		$id    = intval($this->getParam('id'));
		$event = $this->emod->getEvent($id);
		if(!$event){
			$this->showmsg('活动不存在!');
		}
		if($this->userInfo['uid'] != $event['uid']){
			$this->showmsg('无法修改别人的活动!');
		}
		$submit = $this->getParam('submit');
		if(empty($submit)){
			$this->assign('event',$event);
			$this->display('user/event_new.tpl');
		}
	}

	/**
	 * 我加入的活动
	 *
	 */
	function myjoinAction(){
		$this->assign('events',$this->emod->getMyjoins($this->userInfo['uid']));
		$this->display('user/event_myjoin.tpl');
	}

	/**
	 * 我发起的活动
	 *
	 */
	function myeventAction(){
		$pid = intval($this->getParam('p'));
		$pid = $pid ? $pid:1;
		$all = $this->getParam('all');
		$all = empty($all) ? 1:0;
		if($all){
			$ents = $this->emod->getMyAll($this->userInfo['uid'],$pid);
		}else{
			$ents = $this->emod->getMyEvents($this->userInfo['uid']);
		}
		$mpg   = '/event/myevent/'.($all?'':'all/1/');
		$this->assign('multipage',$this->multipage($ents['total'],$ents['psize'], $ents['pid'], $mpg));
		$ents['size'] = sizeof($ents['events']);
		$this->assign('ents',$ents);
		$this->display('user/event_myevent.tpl');
	}



}