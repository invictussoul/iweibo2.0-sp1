<?php
/**
 * 微直播 
 * @author reginx<reginx@qq.com>
 * $Id: Tlive.php 2884 2011-06-19 12:31:43Z cyp $
 */
class Controller_Admin_Tlive extends Core_Controller_Action{

	var $id     = 0;
	var $tmod   = NULL;
	var $submit = NULL;

	/**
	 * 初始化
	 *
	 * @param unknown_type $param
	 */
	function __construct($param){
		parent::__construct($param);
		$this->tmod   = new Model_Tlive();
		$this->pid    = intval($this->getParam('page'));
		$this->pid	  = $this->pid ? $this->pid : 1;
		$this->id     = intval($this->getParam('id'));
		$this->submit = $this->getParam('submit');
	}


	/**
	 * 管理首页
	 *
	 */
	function indexAction(){
		$ol = $this->getParam('ol');
		$where = $querstr = '';
		if(intval($ol) > 0){
			$where = 'sdate <= '.time().' and edate > '.time();
			$querstr = 'ol/1/';
		}
		$fields = array('id','title','tname','direct','sdate','edate','dateline');
		$tls = $this->tmod->getTliveList($this->pid,20,$fields,$where);
		$this->assign('tls',$tls);
		$this->assign('ol',intval($ol));
		$mpage = $this->multipage($tls['total'], $tls['psize'], $pid, '/admin/tlive/index/'.$ol);
		$this->assign('multipage',$mpage);
		$this->display("admin/tlive_index.tpl");
	}

	/**
	 * 新增直播
	 *
	 */
	function newAction(){

		if(!empty($this->submit)){
			$t = $this->getParam('tlive');
			$this->id = intval($t['id']);
			$ret = $this->tmod->saveTlive($t,$this->getParam('style'),$this->getParam('user'));
			if(isset($ret['error'])){
				$this->showmsg($ret['error'],$this->id?'/admin/tlive/modify/id/'.$this->id:'/admin/tlive/new');
			}else{
				$this->showmsg('操作成功!','/admin/tlive/index');
			}
		}
		$this->assign('colors',$this->getColors());
		$this->display('admin/tlive_new.tpl');
	}


	/**
	 * 修改直播
	 *
	 */
	function modifyAction(){
		$tlive = $this->tmod->getTlive(" id = '".$this->id."'",TRUE);
		if(!$tlive){
			$this->showmsg('该记录不存在','/admin/tlive/index/');
		}
		$this->assign('tlive',$tlive);
		$this->assign('colors',$this->getColors());
		$this->display('admin/tlive_new.tpl');
	}


	/**
	 * 审批
	 *
	 */
	function approvalAction(){
		$id = $this->getParam('id');
		$tplist = $this->tmod->getTPostList($this->id,NULL,$this->pid,30,empty($id),'all');
		$mpage = $this->multipage($tplist['total'],$tplist['psize'], $this->pid, '/admin/tlive/approval/');
		$this->assign('multipage',$mpage);
		$this->assign('tls',$tplist);
		$this->display('admin/tlive_approval.tpl');
	}

	/**
	 * 审批直播内容
	 *
	 */
	function passAction(){
		if(!empty($this->submit)){
			$msgid = $this->getParam('msgid');
			empty($msgid)&&$this->showmsg('参数错误!');
			$val = $this->submit == '通过'?1:0;
			$this->tmod->setTPostStatus($msgid,$val);
		}else{
			$t = $this->getParam('t');
			$id = $this->getParam('id');
			empty($id)&&$this->showmsg('参数错误!');
			if(intval($t) === 0){
				$this->tmod->setTPostStatus($id,0);
			}else{
				$this->tmod->setTPostStatus($id,1);
			}
		}
		$this->showmsg('操作成功!');
	}
	
	/**
	 * 删除
	 *
	 */
	function delAction(){
		if($this->tmod->delTLive($this->id)){
			$this->showmsg('操作成功!','/admin/tlive');
		}else{
			$this->showmsg('操作失败!','/admin/tlive');
		}
		
	}

	/**
	 * 获取配色方案
	 *
	 * @return Array
	 */
	private function getColors(){
		$color = array();
		$color[0]['bg'] = '#B02B2C';
		$color[0]['a']  = '#FFFFFF';
		$color[1]['bg'] = '#36393D';
		$color[1]['a']  = '#FF7400';
		$color[2]['bg'] = '#EEEEEE';
		$color[2]['a']  = '#4096EE';
		$color[3]['bg'] = '#F9F7ED';
		$color[3]['a']  = '#FF0084';
		$color[4]['bg'] = '#FFFFFF';
		$color[4]['a']  = '#C79810';
		return $color;
	}

}