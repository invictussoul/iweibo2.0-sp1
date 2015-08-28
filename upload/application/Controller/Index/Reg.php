<?php
/**
 * iweibo2.0
 * 
 * 注册控制器
 *
 * @author gionouyang <gionouyang@tencent.com>, lvfeng
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Reg.php 2011-05-24 22:00:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Reg extends Core_Controller_Action
{
	private $userModel;
	
	public function preDispatch() {
        parent::preDispatch();
		$this->userModel = new Model_User_Member();
	}
	
    /**
     * 显示注册页
     */
    public function indexAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
		
        $this->assign('type', 1);
        $isCode = Core_Config::get('code_on_reg', 'basic', false);
        $this->assign('isCode', $isCode);
        $this->display('index/reg.tpl');
    }

    /**
     * 绑定本地账户
     */
    public function bAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
    	
    	$allowReg = Core_Config::get('login_allow_new_user','basic',false);
		$this->assign('allowReg', $allowReg);
		
        $this->assign('type', 3);
        $isCode = Core_Config::get('code_on_login', 'basic', false);
        $this->assign('isCode', $isCode);
        $this->display('index/reg.tpl');
    }

	 /**
     * 完成授权，进行自动注册
	 */		
	public function  aAction()
	{
        $uInfo = Core_Open_Api::getClient()->getUserInfo();//取得云端的用户资料
			
		while($this->userModel->getUserInfoByName($uInfo['data']['name']) ) //判断是否帐号有重复，若有重复，则在帐号后增加4个随机数字
		{
			 $uInfo['data']['name'].= sprintf('%04d',rand(0,9999));
			 //rand(0,5);	
		}       
                
         $pwd	=	uniqid();
             
		 $uid = $this->userModel->onRegister($uInfo['data']['name'], $pwd, $uInfo['data']['email']);

		 $tokenArr 		= $this->userModel->onGetCurrentAccessToken();            
		 $oauthToken	    = $tokenArr['access_token'];
		 $oauthTokenSecret = $tokenArr['access_token_secret'];  
		 $name 			= $tokenArr['name'];
		  
          $user = $this->userModel->getUserInfoByAccessToken($oauthToken, $oauthTokenSecret);
	                //如果已经绑定，跳转的我的主页，否则到绑定页面
		  
		  if(!$oauthToken) 
		  {

			  $this->showmsg('', '', 0);
		 
		  }elseif($user) 
		  {

        	 $this->showmsg('', 'u/'.$user['name'], 0);
		  }
		
	      if($uid)
          {
 			
        	try{Model_Stat::addStat('reg');}catch(Exception $e){} //记录注册日志
            //设置会话保持
            $this->userModel->onSetCurrentUser($uid, null);	
        
            //绑定accessToken
            $this->userModel->onBindAccessToken($uid, $oauthToken, $oauthTokenSecret, $name);
				
            //绑定后清除本地缓存
            $name && Model_User_Local::delCache ($name);
        
			//从平台取得用户信息
            $uInfo = Core_Open_Api::getClient()->getUserInfo();
	
            $userInfo['uid'] = $uid;
            $userInfo['nickname'] = $uInfo['data']['nick'];
            $userInfo['gender'] = $uInfo['data']['sex'];
            $userInfo['birthyear'] = $uInfo['data']['birth_year'];
            $userInfo['birthmonth'] = $uInfo['data']['birth_month'];
            $userInfo['birthday'] = $uInfo['data']['birth_day'];
            $userInfo['nation'] = $uInfo['data']['country_code'];
            $userInfo['province'] = $uInfo['data']['province_code'];
            $userInfo['city'] = $uInfo['data']['city_code'];
            $userInfo['summary'] = $uInfo['data']['introduction'];
            $userInfo['fansnum'] = Model_User_FriendLocal::getFollowerCountByName($name, true);
            $userInfo['idolnum'] = Model_User_FriendLocal::getFolloweeCountByName($name, true);
            $userInfo['nickname'] = empty($userInfo['nickname']) ? $username : $userInfo['nickname'];
            $this->userModel->editUserInfo($userInfo);
            $this->userModel->onSetCurrentUser($uid, $userInfo['nickname']);

			$downUrl	= Core_Fun::getPathroot() . 'u/d/n/'.$uInfo['data']['name'].'/p/'.$pwd;
	
 			$this->assign('username', $uInfo['data']['name']);
		    $this->assign('pwd', $pwd); 
			$this->assign('auto', 1); 
			$this->assign('downUrl', $downUrl); 
			$this->display('index/showmsg.tpl');   


       }                
          
		
	}	

  
  
    /**
     * 注册
     */
    public function rAction()
    {
    	$errorBackUrl = 'reg/index/msg/';
    	
        $username = $this->getParam('username');
        $pwd = $this->getParam('pwd');
        $pwdconfirm = $this->getParam('pwdconfirm');
        $email = $this->getParam('email');
        //用户名格式不正确
        if(! Model_User_Validator::checkUsername($username))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_FORMATERROR, 0);
        //密码格式不正确
        if(! Model_User_Validator::checkPassword($pwd))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_FORMATERROR, 0);
        //密码不匹配
        if($pwd != $pwdconfirm)
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_DOESNOTMATCH, 0);
        //邮箱格式不正确
        if(! Core_Comm_Validator::isEmail($email))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_FORMATERROR, 0);
        //验证附加码
        if($isCode = Core_Config::get('code_on_reg','basic',false))
            if(! Core_Lib_Gdcheck::check($this->getParam('gdkey')))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_CODE_CHECKFAILED, 0);
        
        //如果使用UC安装
        if(Core_Config::get('useuc', 'basic',false))
        {
            $uid = Core_Outapi_Uc::call('user_register', $username, $pwd, $email);
            if($uid > 0)
            {
                //自动注册到本地失败
                if(! $this->userModel->onAutoRegister($uid, $username, $email))
                	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_REGISTERFAILED, 0);
                //生成同步登录的代码
                $_SESSION['ucsynlogin'] = 1;
            }
            else
            {
            	//用户名已被使用
                if($uid == - 3)
                	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_USED, 0);
                //邮箱已被使用
                if($uid == - 6)
                	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_USED, 0);
                //注册失败
                $this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_REGISTERFAILED, 0);
            }
        }
        else
        {
        	//用户名已被使用
            if($this->userModel->checkUsernameExists($username))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_USED, 0);
            //邮箱已被使用
            if($this->userModel->checkEmailExists($email))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_USED, 0);
            $uid = $this->userModel->onRegister($username, $pwd, $email);
        }
        //注册成功
        if($uid)
        {
        	try{Model_Stat::addStat('reg');}catch(Exception $e){}
            //设置会话保持
            $this->userModel->onSetCurrentUser($uid, null);
            //取得当前accessToken
            $tokenArr = $this->userModel->onGetCurrentAccessToken();
            //到平台取accessToken
            if(empty($tokenArr['name']))
            	$this->showmsg('', 'login/r', 0);
            $oauthToken = $tokenArr['access_token'];
            $oauthTokenSecret = $tokenArr['access_token_secret'];
            $name = $tokenArr['name'];
            //绑定accessToken
            $this->userModel->onBindAccessToken($uid, $oauthToken, $oauthTokenSecret, $name);
            //绑定后清除本地缓存
            $name && Model_User_Local::delCache ($name);
        	//从平台取得用户信息
            $uInfo = Core_Open_Api::getClient()->getUserInfo();
            $userInfo['uid'] = $uid;
            $userInfo['nickname'] = $uInfo['data']['nick'];
            $userInfo['gender'] = $uInfo['data']['sex'];
            $userInfo['birthyear'] = $uInfo['data']['birth_year'];
            $userInfo['birthmonth'] = $uInfo['data']['birth_month'];
            $userInfo['birthday'] = $uInfo['data']['birth_day'];
            $userInfo['nation'] = $uInfo['data']['country_code'];
            $userInfo['province'] = $uInfo['data']['province_code'];
            $userInfo['city'] = $uInfo['data']['city_code'];
            $userInfo['summary'] = $uInfo['data']['introduction'];
            $userInfo['fansnum'] = Model_User_FriendLocal::getFollowerCountByName($name, true);
            $userInfo['idolnum'] = Model_User_FriendLocal::getFolloweeCountByName($name, true);
            $userInfo['nickname'] = empty($userInfo['nickname']) ? $username : $userInfo['nickname'];
            $this->userModel->editUserInfo($userInfo);
            $this->userModel->onSetCurrentUser($uid, $userInfo['nickname']);
            
            $this->showmsg('', 'u/'.$name, 0);
        }
    }

    /**
     * 验证username是否存在
     */
    public function existAction()
    {
        $username = $this->getParam('n');
        $user = new Model_User_Member();
        $exist = $user->checkUsernameExists($n);
        if($exist)
            $this->exitJson(Core_Comm_Modret::RET_SUCC);
        else
            $this->exitJson(Core_Comm_Modret::RET_U_CHK_ERR);
    }
}