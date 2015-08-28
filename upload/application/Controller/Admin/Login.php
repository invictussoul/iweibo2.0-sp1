<?php
/**
 * iweibo2.0
 * 
 * 后台登录
 *
 * @author lvfeng
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Admin_Login.php 2011-06-09 15:08:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Admin_Login extends Core_Controller_Action
{
	public function indexAction()
	{
		$isCode = Core_Config::get('code_on_adminlogin','basic',false);
		$this->assign('isCode', $isCode);
		$this->display('admin/login.tpl');
	}
	
	public function loginAction()
	{
		$username	= $this->getParam('username');
		$password	= $this->getParam('password');
		//验证附加码
		if($isCode = Core_Config::get('code_on_adminlogin','basic',false)){
			$gdkey = $this->getParam('gdkey');
			if(!Core_Lib_Gdcheck::check($gdkey))
				$this->showmsg('附加码验证失败', 'admin/login/index', 3);
		}
		//验证用户名密码格式
		if(empty($username) || empty($password))
			$this->showmsg('用户名或密码为空', 'admin/login/index', 3);
		//验证用户名密码
		$userModel = new Model_User_Member();
		//如果使用UC安装
        if(Core_Config::get('useuc', 'basic', false))
        {
            list($uid, $username, $password, $email) = Core_Outapi_Uc::call('user_login', $username, $password);
            if($uid > 0)
            {
                //本地用户不存在，则自动注册本地用户
                if(! $userModel->checkUsernameExists($username))
                    $userModel->onAutoRegister($uid, $username, $email);
                $user = $userModel->getUserInfoByUid($uid);
            }
        }
        else
        {
        	$user = $userModel->onLogin($username, $password);
        }
		if(!$user)
			$this->showmsg('用户名或密码不正确', 'admin/login/index', 3);
		//验证用户所在组权限
		if(!Model_User_Access::checkAccessByGidAndModel($user['gid'], 'a001'))
			$this->showmsg('你所在的用户组没有权限', 'admin/login/index', 3);
		//将管理员状态赋给用户
		if($_SESSION['isadmin'] = $user['uid'])
		{
			$_SESSION['adminname'] = $user['username'];
			$_SESSION['lastupdate'] = Core_Fun::time ();
			$this->showmsg('', 'admin/index/index', 0);
		}
		else
			$this->showmsg('登录失败', 'admin/login/index', 3);
	}
	
	public function logoutAction()
	{
		//清除管理员状态
		unset($_SESSION['isadmin']);
		unset($_SESSION['adminname']);
		if($_SESSION['isadmin'])
			$this->showmsg('登出失败', 'admin/index/index', 3);
		else
			$this->showmsg('', 'admin/login/index', 0);
	}
}