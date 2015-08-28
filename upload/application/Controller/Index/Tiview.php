<?php
/**
 * 微直播
 * @author reginx<reginx@qq.com>
 * $Id: Tiview.php 3204 2011-06-23 04:41:09Z feixiang $
 */
class Controller_Index_Tiview extends Core_Controller_TAction {

	const CREATE = 1;  //原创广播
	const RETWEET = 2;  //转播
	const BOX = 3;   //私信
	const DIALOG = 4;  //对话
	const EMPTYT = 5;  //空回
	const MENTION = 6;  //提到

	var $tmod = NULL;
	var $id   = 0;
	var $pid  = 0;

	function __construct($params){
		parent::__construct($params);
		parent::preDispatch();
		$local = Core_Config::get('localauth', 'certification.info');
		$platform = Core_Config::get('platformauth', 'certification.info');
		$localtext = Core_Config::get('localauthtext', 'certification.info');
		$authInfo = array('local' => $local, 'platform' => $platform, 'localtext' => $localtext);
		$this->assign('authtype', $authInfo);
		$this->assign('active', 'tiview');
		$this->tmod = new Model_Tiview();
		$this->id   = intval($this->getParam('id'));
		$this->pid    = intval($this->getParam('page'));
		$this->pid	  = $this->pid ? $this->pid : 1;
	}

	/**
	 * 直播首页
	 *
	 */
	function indexAction(){
		$where = 'sdate < '.time().' and edate >= '.time();
		$order = "id asc";
		$topid = 0;
		$curtls = $this->tmod->getTIviewList(1,20,array(),$where,$order);
		if($curtls['size'] ===0){
			// 没有进行中的直播,取未开始直播
			$curtls = $this->tmod->getTIviewList(1,20,array(),' sdate >='.time().' ',$order);
			if(!empty($curtls['data'])){
				$topid  = $curtls['data'][0]['id'];
			}
		}
		$curtls   = isset($curtls['data'])?$curtls['data']:array();
		if(!empty($curtls) && is_array($curtls)){
			$top = array_shift($curtls);
			$this->assign('top',$top);
			$this->assign('joins',$this->tmod->getTIJoinList(1,30,array()," tid = $top[id] and utype = 1"));
		}
		$latertls = $this->tmod->getTIviewList(1,20,array(),' sdate >='.time().'  and id != '.$topid.' ',$order);
		$latertls   = isset($latertls['data'])?$latertls['data']:array();
		$this->assign('latertls',empty($latertls)?array():$latertls);
		$this->assign('curtls',empty($curtls)?array():$curtls);
		$this->display("user/tiview_index.tpl");
	}


	/**
	 * 嘉宾,主持人页
	 *
	 */
	function viewAction(){
		$this->assign('_action','view');
		if(!$this->tmod->isVip($this->id,$this->userInfo['name'])){
			header('Location: '.Core_Fun::getPathroot().'tiview/views/id/'.$this->id);
			exit();
		}
		$u = $this->getParam('u');
		$this->getViewMain();
		// 问题列表
		$tps = $this->tmod->getTIPostList($this->id,$this->pid,10,empty($u)?$this->userInfo['name']:NULL,FALSE,1,0);
		$mpurl = "/tiview/view/id/".$this->id."/".(($u=='')?"":"u/all/");
		$this->assign('msglist',$tps['data']);
		$this->assign('tps', $tps);
		$multipage = $this->multipage($tps['total'], $tps['psize'], $tps['pid'], $mpurl);
		$this->assign('multipage', $multipage);
		$this->display('user/tiview_view.tpl');
	}


	/**
	 * 参与人员页
	 *
	 */
	function viewsAction(){
		$this->assign('_action','views');
		$this->getViewMain();
		// 问题列表
		$tps = $this->tmod->getTIPostListByReply($this->id,$this->pid);
		$mpurl = "/tiview/views/id/".$this->id."/";
		$this->assign('msglist',$tps['data']);
		$this->assign('tps', $tps);
		$multipage = $this->multipage($tps['total'], $tps['psize'], $tps['pid'], $mpurl);
		$this->assign('multipage', $multipage);

		$this->display('user/tiview_views.tpl');
	}


	/**
	 * 访谈页公用内容
	 *
	 */
	function getViewMain(){
		$this->assign('itu',Model_User_Member::isTrustUser());
		$u = $this->getParam('u');
		$active[(empty($u)&&$u!=='0')?'my':$u] = 'active';
		$this->assign('actives',$active);
		$tlive = $this->tmod->getTIviewList(1,1,array()," id = '$this->id'");
		if(empty($tlive['data'])){
			$this->showmsg('该访谈不存在!','/tlive/index');
		}
		$this->assign('tiview',$tlive['data'][0]);
		$this->assign('defaulttext',"#".$tlive['data'][0]['tname']."#");
		// 嘉宾/主持人
		$users[0] = $this->tmod->getTIJoinList(1,20,array(),'tid = '.$this->id.' and utype=0 ',' id desc');
		$users[1] = $this->tmod->getTIJoinList(1,20,array(),'tid = '.$this->id.' and utype=1 ',' id desc');
		$users[0] = empty($users[0]['data'])?array():$users[0]['data'];
		$users[1] = empty($users[1]['data'])?array():$users[1]['data'];
		$this->assign('pt',empty($u)?'myask':'allask');
		// 选择框
		$slt = '<span class="select"><a href="javascript:void(0);" class="selecttit">嘉宾列表</a><ul>';
		foreach ($users[0] as $v){
			if(strtolower($v['name']) != strtolower($this->userInfo['name'])){
				$slt .= '<li><a href="javascript:void(0);" class="altto" data-name="'.$v['name'].'"> '.($v['nick']?$v['nick']:$v['name']).' </a></li>';
			}
		}
		foreach ($users[1] as $v){
			if(strtolower($v['name']) != strtolower($this->userInfo['name'])){
				$slt .= '<li><a href="javascript:void(0);" class="altto" data-name="'.$v['name'].'"> '.($v['nick']?$v['nick']:$v['name']).' </a></li>';
			}
		}
		$this->assign('slt',$slt.'</ul></span>');
		$this->assign('join',$users);
	}


	/**
	 * 微薄提交入口
	 *
	 */
	function addAction(){
		if($this->id === 0){
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_ARG_ERR, '', '', $callback);
		}
		$type = Core_Comm_Validator::getNumArg ($this->getParam ('type'), 1, 4, 1); //1广播 2 转播(评论并转播) 3 对话 4 评论
		if ($type > 1) {
			$reId = Core_Comm_Validator::getTidArg ($this->getParam ('reid'));
		}
		$callback = $this->getParam ('callback');  //回调函数
		//消息内容

		$content = $this->getParam ('content');
		$content = str_replace('#输入话题标题#','',$content);//此话题无效
		if ($type!=2 && !Core_Comm_Validator::checkT ($content)) {
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_MISS, '', '', $callback);
		}


		//三个话题以上返回错误
		$wellArray = Model_Blog::getTopicByContent($content);
		$wellNum = is_array($wellArray)? count($wellArray) :0;
		$wellNum>2 && Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_TOPIC_ERR, '', '', $callback);

		//包含被锁定话题，禁止发送
        if($wellArray)
        {
            foreach ($wellArray as $topic)
            {
                if(Model_Topic::isMasked($topic)){
                    Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_TOPIC_REFUSE, '', '', $callback);
                }
            }
        }


		//包含敏感词语，禁止发送
		$_filter = Model_Filter::checkContent ($content);
		if(2==$_filter)
		{
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_FILTER_REFUSE, '', '', $callback);
		}

		//客户端ip
		$clientIp = Core_Comm_Util::getClientIp ();
		//图片
        $pic = '';
		if (Core_Comm_Validator::isUploadFile ($_FILES["pic"])) {
			$len = intval ($_FILES["pic"]["size"]);
			if ($len < 2 || $len > 2 * 1024 * 1024) {  //图片最大2M
				Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_PIC_SIZE, '', '', $callback);
			}
			$fileContent = file_get_contents ($_FILES["pic"]["tmp_name"]);
			$picType = Core_Comm_Util::getFileType (substr ($fileContent, 0, 2));
			if ($picType != "jpg" && $picType != "gif" && $picType != "png") {
				Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_PIC_TYPE, '', '', $callback);
			}
			//pic参数是个数组
			$pic = array ($_FILES["pic"]["type"], $_FILES["pic"]["name"], $fileContent);
		}

		$music = array ();
		$post_music = $this->getParam ('music');
		if (!empty ($post_music)) {
			$musicInfo = pathinfo ($post_music);
			$musicName = empty ($musicInfo['filename']) ? ( empty ($musicInfo['basename']) ? '' : $musicInfo['basename'] ) : $musicInfo['filename'];
			$music["url"] = $post_music;
			$music["title"] = urldecode ($musicName);
			$music["author"] = '佚名';
		}

		$video = '';
		$post_video = $this->getParam ('video');
		if (!empty ($post_video))
		{
			$video = $post_video;
		}
		$p = array (
		"type" => $type
		, "c" => $content
		, "ip" => $clientIp
		, "j" => ""	//经度，忽略
		, "w" => ""	//纬度，忽略
		, "r" => $reId   //对话转播id
		, "p" => $pic   //图片参数
		, "audio" => $music   //音乐参数
		, "video" => $video   //视频参数
		);
		try{
			$addRet = Core_Open_Api::getClient ()->postOne ($p);
		}
		catch(Core_Api_Exception $e)
		{
			Core_Fun::iFrameExitJson ($e->getCode(), $e->getMessage(), '', $callback);
		}catch (Core_Exception $e) {
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_API_ARG_ERR, '', '', $callback);
		}



		//获取该条微博的信息
		$tId = $addRet["data"]["id"];

		$tInfo = Core_Open_Api::getClient ()->getOne (array ("id" => $tId));

		if(empty($addRet["data"]["id"]) || empty($tInfo['data']))
		{
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_API_ARG_ERR, '', '', $callback);
		}

		$data = Core_Lib_Base::formatT ($tInfo["data"], 50, 160);

		if (!empty ($data) && !empty ($data['id'])) {
			$data['originopentid'] = $p['r'];//对话转播id
			$data['ip'] = $clientIp;
			$data['txt'] = $content;
			Model_Blog::addBlog ($data);//本地化消息
			unset ($data['originopentid']);

			if ($_filter > 0) {
				if ($_filter == 1) {
					//含敏感词直接进审核箱
					Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_FILTER_WAIT, '', '', $callback);
				} elseif ($_filter == 2) {
					//含禁止内容，禁止发布
					Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_FILTER_REFUSE, '', '', $callback);
				}
			}
			//先审后发
			if (Core_Config::get ('censor', 'basic', false)) {
				Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_CENSOR, '', '', $callback);
			}
		}

		if ($this->getParam ('format') == "html") {   //返回html dom
			$this->assign ('user', array ("name" => $this->userInfo['name']));
			$data['msgid'] = $data['id'];
			if(intval($reId) > 0){
				$this->assign ('msg', array('msgid'=>$reId));
				$this->assign ('amsg', $data);
				$data = $this->fetch ('user/tiview_answerbox.tpl');
			}else{
				$this->assign ('msg', $data);
				$data = $this->fetch ('user/tiview_msg.tpl');
			}
		}

		//统计代码
		try{
			//1广播 2 转播 3 对话 4 评论
			if(!empty($type))
			{
				switch($type)//1广播 2 转播(评论并转播) 3 对话/对话 4 评论
				{
					case 2://2 转播
					$skey = 'forward';
					break;
					case 3://3 对话/对话
					$skey = 'dialog';
					break;
					case 4://4 评论
					$skey = 'comment';
					break;
					default:
						if( !empty($p['p']) ) //原创图片
						{
							$skey = 'oripic';
						}elseif( !empty($p['audio']) ){//原创音乐
							$skey = 'orimp3';
						}elseif( !empty($p['video']) ){//原创视频
							$skey = 'orivod';
						}else{//原创文本
							$skey = 'oritxt';
						}
						break;
				}
				$skey && Model_Stat::addStat($skey);

				$p['c'] && $atNum = substr_count($p['c'], '@');
				for($i=0;$i<$atNum; $i++)
				{
					Model_Stat::addStat('alt');//统计@次数
				}
				//话题统计数
				$wellNum = empty ($wellNum) ? 0 : $wellNum;
				$wellNum > 2 && $wellNum = 2;
				for ($i = 0; $i < $wellNum; $i++){
					Model_Stat::addStat('topic');//统计@次数
				}
			}


		}catch(Exception $e){
			//pass
		}

		$tiview = $this->tmod->getTIview(" id = '".$this->id."'",FALSE,FALSE);
		$istu  = Model_User_Member::isTrustUser();
		if((time()>=$tiview['sdate'] && time() <= $tiview['edate'])||(time()< $tiview['sdate'])){
			// 进行中 | 未开始

			// 区分 普通微薄, 提问及嘉宾回答
			$pid = isset($tInfo['data']['source']['id'])?$tInfo['data']['source']['id']:0;
			$isVIP = $this->tmod->isVip($this->id,$this->userInfo['name']);
			unset($tInfo['data']['user'][$this->userInfo['name']]);
			$tvip = array_shift(array_keys($tInfo['data']['user'])); // 获取第一个@对象
			if(($pid>0) &&!preg_match('/\@.+?\s+/i',$content) &&$isVIP && $this->tmod->addTIPost($this->userInfo['name'],$this->id,$tId,($tiview['direct']||$istu),$pid,$addRet['data']['time'])){
				// 嘉宾回答
				if($tiview['direct']||$istu){
					Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '', $data, $callback);
				}else{
					// 非直接发送, 不返回内容
					Core_Fun::iFrameExitJson ('-1','内容已提交,请等待审核!', $data, $callback);
				}
			}else{
				// 提问
				if(preg_match('/\@.+?\s+/i',$content) && !isset($tInfo['data']['source']['id']) && $this->tmod->isVip($this->id,$tvip)){
					// 过滤评论 , 新增提问
					$this->tmod->addTIPost($this->userInfo['name'],$this->id,$tId,$tiview['direct']||$istu,0,$addRet['data']['time'],$tvip);
					if($tiview['direct']||$istu){
						Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '', $data, $callback);
					}else{
						Core_Fun::iFrameExitJson (-1,'内容已提交,请等待审核!', $data, $callback);
					}
				}

			}
			//普通微薄内容
			Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '',NULL, $callback);
		}

	}
}