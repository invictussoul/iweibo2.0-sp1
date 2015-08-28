<?php
/**
 * iweibo2.0
 * 
 * wap登录控制器
 *
 * @author gionouyang <gionouyang@tencent.com>, lvfeng
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Login_Index.php 2011-05-24 16:15:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Login extends Core_Controller_Action
{
	private $userModel;
	
	public function preDispatch() {
        parent::preDispatch();
		$this->userModel = new Model_User_Member();
	}
	
    /**
     * 显示封面页
     * 
     */
    public function homeAction()
    {
        $this->getMsgList(10);
        $this->assign('requrl', '/wap/login/home');
        $this->display('wap/home.tpl');
    }

    /**
     * 获取大家都在说
     * 
     * @param int $num
     */
    private function getMsgList($num)
    {
        $apiClient = Core_Open_Api::getNoneTokenClient();
        
        $p = array('p' => 0, 'n' => $num);
        $openmsg = $apiClient->getPublic($p);
        
        $msglist = $openmsg['data']['info'];
        $msglist && Core_Lib_Base::formatTArr($msglist);
        
        $this->assign('msglist', $msglist);
    }

    /**
     * 显示登录页
     */
    public function indexAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->showMessage(Core_Comm_Modret::getMsg($msgCode));
		
    	if(Core_Config::get('site_closed','basic',false))
    		$this->assign('siteClosePrompt', Core_Config::get('site_close_prompt','basic',false));
        $this->display('wap/login.tpl');
    }

    /**
     * 登出
     */
    public function logoutAction()
    {
        $this->userModel->onLogout();
        //如果使用UC安装
        if(Core_Config::get('useuc', 'basic', false))
        {
            //生成同步登出的代码
            //$ucsynlogout = Core_Outapi_Uc::call('user_synlogout');
            //$this->assign('ucsyn', $ucsynlogout);
        }
        $this->showmsg('', 'wap/login', 0);
    }

    /**
     * 显示绑定页
     */
    public function bAction()
    {
        $this->assign('type', 2);
        $this->display('wap/login.tpl');
    }

    /**
     * 本地登录
     */
    public function lAction()
    {
        $username = $this->getParam('username');
        $pwd = $this->getParam('pwd');
        $autologin = $this->getParam('autologin');
        
        //如果使用UC安装
        if(Core_Config::get('useuc', 'basic',false))
        {
            //UC登录
            list($uid, $username, $password, $email) = Core_Outapi_Uc::call('user_login', $username, $pwd);
            if($uid > 0)
            {
                //本地用户不存在，则自动注册本地用户
                if(! $this->userModel->checkUsernameExists($username))
                    $this->userModel->onAutoRegister($uid, $username, $email);
                $user = $this->userModel->getUserInfoByUid($uid);
                //生成同步登录的代码
                //$ucsynlogin = Core_Outapi_Uc::call('user_synlogin', $uid);
                //$this->assign('ucsyn', $ucsynlogin);
            }
        }
        else
        {
            $user = $this->userModel->onLogin($username, $pwd);
        }
        //用户不存在
        if(empty($user))
        	$this->showMessage(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_USER_LOGINFAILED));
        //取得当前accessToken
        $tokenArr = $this->userModel->onGetCurrentAccessToken();
        //如果没绑定
        if(empty($user["name"]))
        {
            $oauthToken = $tokenArr['access_token'];
            $oauthTokenSecret = $tokenArr['access_token_secret'];
            $name = $tokenArr['name'];
            if(! empty($name))
            {
                $this->userModel->onBindAccessToken($user['uid'], $oauthToken, $oauthTokenSecret, $name);
                //绑定后清除本地缓存
                Model_User_Local::delCache ($name);
            }
        }
        else
        {
            if(! empty($tokenArr['name']))
            	$this->showMessage(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_USER_BOUND), Core_Fun::getPathroot().'wap/login/b');
            $oauthToken = $user["oauthtoken"];
            $oauthTokenSecret = $user["oauthtokensecret"];
            $name = $user['name'];
        }
        //用户登陆成功，设置会话保持
        $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
        $this->userModel->onSetCurrentAccessToken($oauthToken, $oauthTokenSecret, $name);
        //没绑定到绑定腾讯微博页
        if(empty($name))
        	$this->showmsg('', 'wap/login/r', 0);
        //如果本地用户昵称为空则将平台用户信息赋给本地
        if(empty($user['nickname']))
        {
            //从平台取得用户信息
            $uInfo = Core_Open_Api::getClient()->getUserInfo();
            $userInfo['uid'] = $user['uid'];
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
            $userInfo['nickname'] = empty($userInfo['nickname']) ? $user['username'] : $userInfo['nickname'];
            $this->userModel->editUserInfo($userInfo);
            $this->userModel->onSetCurrentUser($user['uid'], $userInfo['nickname']);
        }
        //如果用户选中自动登录 保存令牌
        $tokenModel = new Model_Base_Token();
        $sign = Core_Comm_Token::_generate_key();
        if($autologin && $tokenModel->addToken(array('uid' => $user['uid'], 'sign' => $sign)))
        {
            $authcode = Core_Comm_Token::authcode($user['uid'] . "\t" . $sign, 'ENCODE');
            $this->setCookie('iwb_token', $authcode, 3600 * 24 * 30);
        }
        
        $this->userModel->onSetLastVisit($user['uid']);
        $this->showmsg('', 'wap/u/'.$name, 0);
    }

    /**
     * 验证用户登录
     */
    public function rAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	
    	$msgCode && $this->showMessage(Core_Comm_Modret::getMsg($msgCode), Core_Fun::getPathroot().'wap/login/r', '绑定腾讯微博');
		
        //腾讯微博帐号登录OAuth回调
        if($this->getParam("oauth_token") && $this->getParam("oauth_verifier") && $this->getParam("oauth_token") == $_SESSION["request_token"])
        {
            $oauth = new Core_Open_Opent(Core_Config::get('appkey', 'basic'), Core_Config::get('appsecret', 'basic'), 
            $_SESSION["request_token"], $_SESSION["request_token_secret"]);
            //返回token信息
            $oauthKeys = $oauth->getAccessToken($this->getParam("oauth_verifier"));
            $oauthToken = $oauthKeys["oauth_token"];
            $oauthTokenSecret = $oauthKeys["oauth_token_secret"];
            $name = $oauthKeys["name"];
            //是否已经绑定用户
            $user = $this->userModel->getUserInfoByAccessToken($oauthToken, $oauthTokenSecret);
            //取得当前用户
            $cUser = $this->userModel->onGetCurrentUser();
            //如果是注册或者登录回调，绑定用户
            if($cUser['uid'])
            {
                if($user)
                {
                	$this->showMessage(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_ACCOUNT_BOUND), Core_Fun::getPathroot().'wap/login/r', '绑定腾讯微博');
                }
                else
                {
                    $this->userModel->onBindAccessToken($cUser['uid'], $oauthToken, $oauthTokenSecret, $name);
                    //绑定后清除本地缓存
                    $name && Model_User_Local::delCache ($name);
                    //设置当前accessToken
                    $this->userModel->onSetCurrentAccessToken($oauthToken, $oauthTokenSecret, $name);
                    //从数据库取得用户信息
                    $user = $this->userModel->getUserInfoByUid($cUser['uid']);
                    //如果本地用户昵称为空则将平台用户信息赋给本地
                    if(empty($user['nickname']))
                    {
                    	//从平台取得用户信息
			            $uInfo = Core_Open_Api::getClient()->getUserInfo();
			            $userInfo['uid'] = $user['uid'];
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
            			$userInfo['nickname'] = empty($userInfo['nickname']) ? $user['username'] : $userInfo['nickname'];
			            $this->userModel->editUserInfo($userInfo);
			            $this->userModel->onSetCurrentUser($cUser['uid'], $userInfo['nickname']);
                    }
                    else
                    {
                    	$this->userModel->onSetCurrentUser($cUser['uid'], $user['nickname']);
                    }
                    
                    $this->userModel->onSetLastVisit($cUser['uid']);
                    $this->showmsg('', 'wap/u/'.$name, 0);
                }
            }
            else
            {
                //如果已经绑定，跳转的我的主页，否则到绑定页面
                if($user)
                {
                    //如果使用UC安装
                    if(Core_Config::get('useuc', 'basic',false))
                    {
                        //生成同步登录的代码
                        //$ucsynlogin = Core_Outapi_Uc::call('user_synlogin', $user['uid']);
                        //$this->assign('ucsyn', $ucsynlogin);
                    }
                    //设置会话保持
                    $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
                    $this->userModel->onSetCurrentAccessToken($user['oauthtoken'], $user['oauthtokensecret'], $user['name']);
    				
                    $this->userModel->onSetLastVisit($user['uid']);
                    $this->showmsg('', 'wap/u/'.$user['name'], 0);
                }
                else
                {
                    //设置当前accessToken
                    $this->userModel->onSetCurrentAccessToken($oauthToken, $oauthTokenSecret, $name);
                    $this->showmsg('', 'wap/login/b', 0);
                }
            }
        }
        else
        {
            //启动授权流程
            $oauth = new Core_Open_Opent(Core_Config::get('appkey', 'basic'), 
            Core_Config::get('appsecret', 'basic'));
            $callbackUrl = Core_Fun::getPathroot() . 'wap/login/r';
            $callbackUrl = Core_Comm_Util::trailUrl($callbackUrl);
            $tokenKeys = $oauth->getRequestToken($callbackUrl);
            if(empty($tokenKeys['oauth_token']) || empty($tokenKeys['oauth_token_secret']))
            {
                //显示错误页面
                Core_Fun::error('无法启动授权，请检查服务器时间是否设置正确', - 2);
            }
            
            $_SESSION["request_token"] = $tokenKeys['oauth_token'];
            $_SESSION["request_token_secret"] = $tokenKeys['oauth_token_secret'];
            if(empty($_SESSION["request_token"]) || empty($_SESSION["request_token_secret"]))
            {
                //显示错误页面
                Core_Fun::error('无法启动授权，请检查PHP Session功能是否正常', - 2);
            }
            
            //跳授权页面
            Core_Comm_Util::location($oauth->getAuthorizeURL($tokenKeys['oauth_token'], false));
        }
    }
    
    public function showMessage($message, $url=null, $btnText='返回', $type=1, $tpl='wap/login.tpl')
	{
		$url == null && $url = Core_Fun::getPathroot().'wap/login';
		$this->assign('type', $type);
        $this->assign('url', $url);
        $this->assign('message', $message);
        $this->assign('btntext', $btnText);
        $this->display($tpl);
        exit();
	}
}