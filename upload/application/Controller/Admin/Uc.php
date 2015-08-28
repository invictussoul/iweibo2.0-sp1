<?php
/**
 * UCenter整合
 *
 * @author lvfeng
 */
class Controller_Admin_Uc extends Core_Controller_Action
{
	/**
	 * 设置页面
	 */
	public function indexAction()
	{
		$ucArr = Core_Config::get( null, 'uc', array());
		
		if ($this->getParam('action')=='setup')
		{
			$ucArr['key'] = $this->getParam('key');
			Core_Config::add($ucArr, 'uc');
			Core_Config::update('uc');
			$this->showmsg('设置成功');
		}
		
		$this->assign('ucArr', $ucArr);
		$this->display('admin/uc_index.tpl');
	}
}