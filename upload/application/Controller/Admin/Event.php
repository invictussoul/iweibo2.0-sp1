<?php
/**
 * 活动管理模块
 * @author reginx<reginx@qq.com>
 * $Id: Event.php 2884 2011-06-19 12:31:43Z cyp $
 */
class Controller_Admin_Event extends Core_Controller_Action
{
	var $types = array(1=>'全部',2=>'推荐',3=>'进行中',4=>'用户关闭',5=>'管理封禁',6=>'已完成',7=>'未开始');
	function indexAction(){
		$this->searchAction();
	}

	function setAction(){
		$id  = $this->getParam('id');
		$id  = empty($id)?0:intval($id);
		$st  = $this->getParam('st');
		$val = 1;
		switch ($st){
			case 'lock': $val = 3;break;
			case 'rec' : $val = 4;break;
		}
		$emod  = new Model_Event();
		$curs = $emod->getStatus($id);
		if($curs === 0){
			$this->showmsg('已结束的活动无法进行操作!','admin/event/index',2);
		}elseif($curs === 3 && $val > 3){
			$this->showmsg('封禁的活动无法设置推荐!','admin/event/index',2);
		}
		$emod->setStatus($id,$val);
		$this->showmsg('操作成功!','admin/event/index',2);
	}

	function searchAction(){
		$emod = new Model_Event();
		$title  = $this->getParam('title');
		$type   = $this->getParam('type');
		$type   = empty($type)?1:$type;
		$sql    = ' 1=1 ';
		$title  = trim($title);
		if(!empty($title) && $title != ''){
			$sql .= "and title like '%".Core_Db::sqlescape($title)."%' ";
		}
		$sql .= ' and '.$emod->getCondStr($type);
		$fields = array('Id','title','uname','uid','deadline','sdate','edate','dateline','joins','status');
		$ents = $emod->getAllEvent($this->getParam('page'),$fields,$sql);
		$this->assign('events',$ents);
		$this->assign('total',$ents['total']);
		$this->assign('title',$title);
		$this->assign('types',$this->types);
		$this->assign('curTypeId',$type);
		$this->assign('curTypeText',$this->types[$type]);
		$this->assign('multipage', $this->multipage($ents['total'], $ents['psize'], $ents['pid'], '/admin/event/index/'));
		$this->display('admin/event_index.tpl');
	}

}