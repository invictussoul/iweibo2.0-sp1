<?php
/**
 * 禁止IP设置
 *
 * @author lvfeng
 */
class Controller_Admin_Banned extends Core_Controller_Action
{
	private $bannedModel;
	
	public function preDispatch() {
        parent::preDispatch();
		$this->bannedModel = new Model_User_Banned();
	}
	
	/**
	 * 设置禁止IP
	 */
	public function manageAction()
	{
		if (trim($this->getParam('action'))=='manage')
		{
			$deleteids = is_array($this->getParam('delete')) ? $this->getParam('delete') : array();
			foreach ($deleteids as $id)
			{
				$this->bannedModel->delBanned(intval($id));
			}
			
			$ip1new = trim($this->getParam('ip1new'));
			$ip2new = trim($this->getParam('ip2new'));
			$ip3new = trim($this->getParam('ip3new'));
			$ip4new = trim($this->getParam('ip4new'));
			if(!empty($ip1new) && !empty($ip1new) && !empty($ip1new) && !empty($ip1new))
			{
				$ip = $ip1new.'.'.$ip2new.'.'.$ip3new.'.'.$ip4new;
				//验证表单取得的数据
				$errorMessage = '';
				$checkResult = array();
				$checkResult[] = $this->bannedModel->checkIp($ip);
				foreach ($checkResult as $result)
				{
					!empty($result) && $errorMessage .= $result['message'];
				}
				if(empty($errorMessage))
				{
					$this->bannedModel->addBanned($ip);
				}
				else
				{
					$this->showmsg($errorMessage);
				}
			}
			$this->showmsg('IP列表更新成功');
		}
		//IP数量
		$bannedCount = $this->bannedModel->getBannedCount();
		//分页
		$perpage = 20;
		$curpage = $this->getParam('page') ? intval($this->getParam('page')) : 1;
		$mpurl = '/admin/banned/manage/';
		$multipage = $this->multipage($bannedCount, $perpage, $curpage, $mpurl);
		$this->assign('multipage', $multipage);
		//IP列表
		$bannedList = $this->bannedModel->getBannedList(null, 'id desc', array($perpage, $perpage*($curpage-1)));
		$this->assign('ipbanneds', $bannedList);
		
		$this->display('admin/banned_manage.tpl');
	}
}