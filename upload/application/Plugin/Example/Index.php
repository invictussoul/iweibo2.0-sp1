<?php
/**
 * just a demo
 *
 * @author Icehu
 */
class Plugin_Example_Index extends Core_Controller_PluginAction {
	/**
	 * 插件首页
	 */
	public function indexAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$this->display('index/index.tpl');
	}
	
	/**
	 * 插件介绍页
	 */
	public function summaryAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$this->display('index/summary.tpl');
	}
	
	/**
	 * 插件管理后台
	 */
	public function adminAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$this->display('admin/index.tpl');
	}
	
	/**
	 * 用户自定义初始化函数
	 *
	 * @return boolean
	 */
	public function setup()
	{
		//todo
		return true;
	}

	/**
	 * 钩子方法
	 * 
	 * @return stirng
	 */
	public function summaryHack()
	{
		return '<b>什么是腾讯微博开放平台？</b><br /><br />
				腾讯微博开放平台，为广大开发者提供开放接口，可构建丰富多样的应用。
				你的应用能从微博获取海量资讯，或将信息传播到千万级用户的平台中，得到营销推广机会。
				提供开放的数据分享和传播服务，加上你的智慧，将创造无穷的功能与乐趣。<br />';
	}
}