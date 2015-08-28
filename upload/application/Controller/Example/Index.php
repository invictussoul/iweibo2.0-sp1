<?php
/**
 * just a demo
 *
 * @author Icehu
 */
class Controller_Example_Index extends Core_Controller_Action {

	public function indexAction()
	{
		$this->assign('abc', 'http://www.baidu.com');
		$this->assign('test','asdlasdioufaskdmfalsdflasldfasldflasdf');
		$this->display('example/index_index.tpl');
	}
	
	public function authtokenAction(){
		//生成token key配置
		Core_Config::add(array('authkey'=>Core_Comm_Token::_generate_key()));
		Core_Config::update('basic');
	}
	
	public function ucswitchAction(){
		//设置是否开启uc
		Core_Config::add(array('useuc'=>1));
		Core_Config::update('basic');
	}
	
	public function ucsettingAction(){
		//生成uc配置
		$configs = array(	'connect'=>'mysql'
							,'dbhost'=>'localhost'
							,'dbuser'=>'root'
							,'dbpw'=>'258369'
							,'dbname'=>'ucenter'
							,'dbcharset'=>'utf8'
							,'dbtablepre'=>'`ucenter`.uc_'
							,'key'=>'123456'
							,'api'=>'http://ucenter'
							,'charset'=>'utf-8'
							,'ip'=>''
							,'appid'=>'3'
							);
		Core_Config::add($configs, 'uc');
		Core_Config::update('uc');
	}
	
	public function uploadAction()
	{
		if($this->getParam('submit'))
		{
			$rt = Core_Util_Upload::upload('file');
			var_dump($rt);
		}
		else
		{
			$this->display('example/index_upload.tpl');
		}
	}

	public function gdAction()
	{
		$this->display('example/index_gd.tpl');
	}

	public function gdcheckAction()
	{
		$num = $this->getParam('gdkey');
		if(Core_Lib_Gdcheck::check($num))
		{
			echo 'success';
		}
		else
		{
			echo 'fail';
		}
	}

	public function ajaxAction()
	{
		$this->display('example/index_ajax.tpl');
	}

	public function ajaxpostAction()
	{
		$this->error(-1, 'test error');
	}

	public function userinfoAction()
	{
		$users = $this->getParam('users');
		var_dump(Model_User_Util::getInfos(explode(',',trim($users,', '))));
	}

	public function sessionAction()
	{
		var_dump($_SESSION);
	}

	public function setsessionAction()
	{
		$_SESSION['a'] = 'a';
		$_SESSION['b'] = 'b';
		$_SESSION['a'] = null;
		var_dump($_SESSION);
	}
}