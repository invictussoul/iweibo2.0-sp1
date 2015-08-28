<?php
/**
 * iweibo2.0
 *
 * 登录控制器
 *
 * @author gionouyang <gionouyang@tencent.com>, lvfeng
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Login_Index.php 2011-06-07 19:08:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Login extends Core_Controller_Action
{
	private $userModel;

	public function preDispatch() {
        parent::preDispatch();
		$this->userModel = new Model_User_Member();
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
     * 更新大家都在说
     */
    public function moreAction()
    {
        $this->getMsgList(1);
    }

    /**
     * 显示登录页
     */
    public function indexAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', $msgCode == Core_Comm_Modret::RET_SITE_CLOSED ? Core_Config::get('site_close_prompt','basic',false) : Core_Comm_Modret::getMsg($msgCode));

    	$allowReg = Core_Config::get('login_allow_new_user','basic',false);
		$this->assign('allowReg', $allowReg);

		$mailOpen = Core_Config::get('open', 'mail', false);
		$this->assign('mailOpen', $mailOpen);

    	//如果使用UC安装
        if(Core_Config::get('useuc', 'basic', false) && isset($_SESSION['ucsynlogout']) && $_SESSION['ucsynlogout'])
        {
            //生成同步登出的代码
            $ucsynlogout = Core_Outapi_Uc::call('user_synlogout');
            $this->assign('ucsynlogout', $ucsynlogout);
            unset($_SESSION['ucsynlogout']);
        }

        $this->getMsgList(5);
        //是否使用验证码
        $isCode = Core_Config::get('code_on_login', 'basic', false);
        $this->assign('isCode', $isCode);

        $this->display('index/login.tpl');
    }

    /**
     * 找回密码导航页
     */
    public function findnavAction()
    {
    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));

    	$this->assign('type', 6);
        $this->display('index/reg.tpl');
    }

    /**
     * 平台找回密码页
     */
    public function qqfindpwdAction()
    {
    	$errorBackUrl = 'login/qqfindpwd/msg/';

    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));

        if($this->getParam('op') == 'qqfind')
        {
        	$username = $this->getParam('username');
        	if(empty($username))
        		$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_NOTNULL , 0);
        	//用户不存在
            if(! $user = $this->userModel->getUserInfoByUsername($username))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_DOESNOTEXIST, 0);
            //用户未绑定
            if(empty($user['name']))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_UNBOUND, 0);
            $_SESSION['finduser'] = $user['username'];
            $this->showmsg('', 'login/r/op/findpwd', 0);
        }

        $this->assign('type', 7);
        $this->display('index/reg.tpl');
    }

    /**
     * 找回密码页
     */
    public function findpwdAction()
    {
    	$errorBackUrl = 'login/findpwd/msg/';

    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));

        if(Core_Config::get('open', 'mail', false) && $this->getParam('op') == 'find')
        {
        	$username = $this->getParam('username');
        	$email = $this->getParam('email');
        	//用户名为空
	        if(empty($username))
	        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_NOTNULL, 0);
	        //邮箱为空
	        if(empty($email))
	        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_NOTNULL, 0);
        	//用户不存在
            if(! $user = $this->userModel->getUserInfoByUsername($username))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_DOESNOTEXIST, 0);
            //邮箱不匹配
            if($user['email'] != $email)
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_DOESNOTMATCH, 0);
            //生成令牌
            $tokenModel = new Model_Base_Token();
            if(!$token = $tokenModel->getTokenInfoByUid($user['uid']))
            {
                $sign = Core_Comm_Token::_generate_key();
                if($tokenModel->addToken(array('uid' => $user['uid'], 'sign' => $sign)))
                    $tokenSign = $sign;
            }
            else
            {
            	$tokenSign = $token['sign'];
            }
            //令牌生成失败，报发送邮件失败
            if(!$tokenSign)
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_EMAIL_SENDFAILED, 0);
            //发送邮件
            $url = Core_Fun::getPathroot() . 'login/changepwd/uid/'.$user['uid'].'/sign/'.$tokenSign;
            $sendIsSucceed = Core_Mail::send($user['email'], '找回密码',
            						'亲爱的用户 '.$user['nickname'].' 你好<br /><br />
									请点击下面的链接修改你的密码，如果不能打开，请将地址拷贝到浏览器地址栏打开<br /><br />
									<a href="'.$url.'" target="_blank">'.$url.'</a><br /><br />
									如果这不是你发起的申请，请忽略该邮件<br /><br />谢谢', array(), false);
            //邮件已发送
            $sendIsSucceed && $this->showmsg('', 'login/index/msg/'.Core_Comm_Modret::RET_EMAIL_SENDSUCCEED, 0);
            //邮件发送失败
            $this->showmsg('', 'login/index/msg/'.Core_Comm_Modret::RET_EMAIL_SENDFAILED, 0);
        }

        $this->assign('type', 4);
        $this->display('index/reg.tpl');
    }

    /**
     * 修改密码页
     */
    public function changepwdAction()
    {
    	$errorBackUrl = 'login/changepwd/msg/';

    	$msgCode = intval($this->getParam('msg'));
    	$msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));

        if($this->getParam('uid') && $this->getParam('sign'))
        {
            $tokenModel = new Model_Base_Token();
            //令牌已失效
            if(! $tokenModel->getTokenInfoByUidAndSign($this->getParam('uid'), $this->getParam('sign')))
            	$this->showmsg('', 'login/index/msg/'.Core_Comm_Modret::RET_TOKEN_EXPIRED, 0);
            //用户被删除
            if(! $user = $this->userModel->getUserInfoByUid($this->getParam('uid')))
            	$this->showmsg('', 'login/index/msg/'.Core_Comm_Modret::RET_USER_DELETED, 0);
            $_SESSION['changeuser'] = $user['username'];
        }

        if($this->getParam('op') == 'change')
        {
        	$pwd = $this->getParam('pwd');
        	$pwdconfirm = $this->getParam('pwdconfirm');
            //用户被删除
            if(! $user = $this->userModel->getUserInfoByUsername($_SESSION['changeuser']))
            	$this->showmsg('', 'login/index/msg/'.Core_Comm_Modret::RET_USER_DELETED, 0);
            //密码格式不正确
            if(! Model_User_Validator::checkPassword($pwd))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_FORMATERROR, 0);
            //密码不匹配
            if($pwd != $pwdconfirm)
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_DOESNOTMATCH, 0);
            //如果使用UC安装
            if(Core_Config::get('useuc', 'basic', false))
            {
                if(Core_Outapi_Uc::call('user_edit', $user['username'], '', $pwd, '', 1) >= 0)
                {
                	//将登录状态赋给用户
		            $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
		            $this->userModel->onSetCurrentAccessToken($user['oauthtoken'], $user['oauthtokensecret'], $user['name']);
		            $_SESSION['ucsynlogin']=1;
		            $this->showmsg('', 'u/'.$user['name'].'/msg/'.Core_Comm_Modret::RET_PASSWORD_RESETSUCCEED, 0);
                }
                else
                {
                	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_RESETFAILED, 0);
                }
            }
            else
            {
                $conditions['uid'] = intval($user['uid']);
                $conditions['salt'] = $this->userModel->getSalt();
                $conditions['password'] = $this->userModel->formatPassword($pwd, $conditions['salt']);
                //将密码更新到数据库
                if($this->userModel->editUserInfo($conditions))
                {
                	//将登录状态赋给用户
		            $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
		            $this->userModel->onSetCurrentAccessToken($user['oauthtoken'], $user['oauthtokensecret'], $user['name']);
		            $_SESSION['ucsynlogin']=1;
		            $this->showmsg('', 'u/'.$user['name'].'/msg/'.Core_Comm_Modret::RET_PASSWORD_RESETSUCCEED, 0);
                }
                else
                {
                	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_RESETFAILED, 0);
                }
            }
        }

        if(!$_SESSION['changeuser'])
        	$this->showmsg('', 'login', 0);

        $this->assign('changeuser', $_SESSION['changeuser']);

        $this->assign('type', 5);
        $this->display('index/reg.tpl');
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
            $_SESSION['ucsynlogout'] = 1;
        }
        $this->showmsg('', 'login', 0);
    }

    /**
     * 本地登录
     */
    public function lAction()
    {
    	$errorBackUrl = strstr($_SERVER['HTTP_REFERER'], 'reg/b') ? 'reg/b/msg/' : 'login/index/msg/';

        $username = $this->getParam('username');
        $pwd = $this->getParam('pwd');
        $autologin = $this->getParam('autologin');
        //用户名为空
        if(empty($username))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USERNAME_NOTNULL, 0);
        //密码为空
        if(empty($pwd))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_PASSWORD_NOTNULL, 0);
        //验证附加码
        if(Core_Config::get('code_on_login', 'basic', false))
            if(! Core_Lib_Gdcheck::check($this->getParam('gdkey')))
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_CODE_CHECKFAILED, 0);

        if(Core_Config::get('useuc', 'basic', false))
        {
            list($uid, $username, $password, $email) = Core_Outapi_Uc::call('user_login', $username, $pwd);
            if($uid > 0)
            {
                //本地用户不存在，则自动注册本地用户
                if(! $this->userModel->checkUsernameExists($username))
                    $this->userModel->onAutoRegister($uid, $username, $email);
                $user = $this->userModel->getUserInfoByUid($uid);
                //生成同步登录的代码
                $_SESSION['ucsynlogin'] = 1;
            }
        }
        else
        {
            $user = $this->userModel->onLogin($username, $pwd);
        }
        if(empty($user))
        	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_LOGINFAILED, 0);
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
            	$this->showmsg('', $errorBackUrl.Core_Comm_Modret::RET_USER_BOUND, 0);
            $oauthToken = $user["oauthtoken"];
            $oauthTokenSecret = $user["oauthtokensecret"];
            $name = $user['name'];
        }
        //将登录状态赋给用户
        $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
        $this->userModel->onSetCurrentAccessToken($oauthToken, $oauthTokenSecret, $name);
        //已经绑定到我的主页，没绑定到绑定腾讯微博页
        if(empty($name))
        	$this->showmsg('', 'login/r', 0);
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
            $this->setCookie('iwb_token', Core_Comm_Token::authcode($user['uid'] . "\t" . $sign, 'ENCODE'), 3600 * 24 * 30);

		$this->userModel->onSetLastVisit($user['uid']);
        try{Model_Stat::addStat('login', $name);}catch(Exception $e){}
        $this->showmsg('', 'u/'.$name, 0);
    }

    /**
     * 验证用户登录
     */
    public function rAction()
    {
    	$msgCode = intval($this->getParam('msg'));

    	$msgCode && $this->showMessage(Core_Comm_Modret::getMsg($msgCode), Core_Fun::getPathroot().'login/r', '绑定腾讯微博');

        $op = $this->getParam('op');
        if($op && 'drlog'==$op)
        {
            $this->directLogin();
        }
        //腾讯微博帐号登录OAuth回调
        if($this->getParam("oauth_token") && $this->getParam("oauth_verifier") && $this->getParam("oauth_token") == $_SESSION["request_token"])
        {
            $oauth = new Core_Open_Opent(Core_Config::get('appkey', 'basic', false), Core_Config::get('appsecret', 'basic', false), $_SESSION["request_token"], $_SESSION["request_token_secret"]);
            //返回token信息
            $oauthKeys = $oauth->getAccessToken($this->getParam("oauth_verifier"));
            $oauthToken = $oauthKeys["oauth_token"];
            $oauthTokenSecret = $oauthKeys["oauth_token_secret"];
            $name = $oauthKeys["name"];
            //是否已经绑定用户
            $user = $this->userModel->getUserInfoByAccessToken($oauthToken, $oauthTokenSecret);
            //如果是通过平台找回密码
            if($op=='findpwd')
            {
            	//用户未绑定
            	if(!$user)
            		$this->showmsg('', 'login/findnav/msg/'.Core_Comm_Modret::RET_ACCOUNT_UNBOUND, 0);
            	//用户不匹配
            	if($_SESSION['finduser'] != $user['username'])
            		$this->showmsg('', 'login/findnav/msg/'.Core_Comm_Modret::RET_USER_DOESNOTMATCH, 0);
            	$_SESSION['changeuser'] = $user['username'];

            	$this->showmsg('', 'login/changepwd', 0);
            }
            $cUser = $this->userModel->onGetCurrentUser();
            //如果是注册或者登录回调，绑定用户
            if($cUser['uid'])
            {
                if($user)
                {
                    $directlogin = Core_Fun::getPathroot() . 'login/r/op/drlog';
                    $_SESSION["tmp_accesstoken"] =$oauthToken;
                    $_SESSION["tmp_accesstoken_secret"] = $oauthTokenSecret;
                    $btntext="绑定腾讯微博";
                    $this->assign('btntext', $btntext);
                	$this->showmsg(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_USER_BOUND).'<small><a href="'.$directlogin.'">使用绑定帐号登录</a></small>' , Core_Fun::getPathroot().'login/', 1000, true);
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
			        try{Model_Stat::addStat('login', $name);}catch(Exception $e){}
			        $this->showmsg('', 'u/'.$name, 0);
                }
            }
            else
            {
                //如果已经绑定，跳转的我的主页，否则到绑定页面
                if($user['uid'])
                {
                    //如果使用UC安装
                    if(Core_Config::get('useuc', 'basic', false))
                    {
                        //生成同步登录的代码
                        $_SESSION['ucsynlogin'] = 1;
                    }
                    //将登录状态赋给用户
                    $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
                    $this->userModel->onSetCurrentAccessToken($user['oauthtoken'], $user['oauthtokensecret'], $user['name']);

                    $this->userModel->onSetLastVisit($user['uid']);
			        try{Model_Stat::addStat('login', $user['name']);}catch(Exception $e){}
			        $this->showmsg('', 'u/'.$user['name'], 0);
                }
                else
                {
                      //设置当前accessToken
                    $this->userModel->onSetCurrentAccessToken($oauthToken, $oauthTokenSecret, $name);
                    if(Core_Config::get('login_allow_auto_register', 'basic', false)) //若系统开启系统自动分配帐号和密码，则跳转到reg/a进行帐号密码分配
                    {

                        $this->showmsg('', 'reg/a', 0);
                    }
                    else
                    {
                   	  	$this->showmsg('', 'reg/b', 0);
                    }
                }
            }
        }
        else
        {
            //启动授权流程
            $oauth = new Core_Open_Opent(Core_Config::get('appkey', 'basic', false),
            Core_Config::get('appsecret', 'basic', false));
            $callbackUrl = Core_Fun::getPathroot() . 'login/r';
            $op=='findpwd' && $callbackUrl .= '/op/findpwd';
	        '?'==Core_Fun::getPathinfoPre() && $callbackUrl .='?a=a';
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

    /**
     * 使用绑定帐号直接登录
     */
    public function directLogin()
    {
        $oauthToken = $_SESSION["tmp_accesstoken"];
        $oauthTokenSecret  = $_SESSION["tmp_accesstoken_secret"];
        $user = $this->userModel->getUserInfoByAccessToken($oauthToken, $oauthTokenSecret);
        if($user)
        {
            //如果使用UC安装
            if(Core_Config::get('useuc', 'basic', false))
            {
                //生成同步登录的代码
                $_SESSION['ucsynlogin'] = 1;
            }
            //将登录状态赋给用户
            $this->userModel->onSetCurrentUser($user['uid'], $user['nickname']);
            $this->userModel->onSetCurrentAccessToken($user['oauthtoken'], $user['oauthtokensecret'], $user['name']);

            $this->userModel->onSetLastVisit($user['uid']);
            try{Model_Stat::addStat('login', $user['name']);}catch(Exception $e){}
            $this->showmsg('', 'u/'.$user['name'], 0);
        }
        else
        {
            $this->showMessage('出错了');
        }
    }

    public function showMessage($message, $url='javascript:history.go(-1);', $btnText='返回', $type=2, $tpl='index/reg.tpl')
	{
		$this->assign('type', $type);
        $this->assign('url', $url);
        $this->assign('message', $message);
        $this->assign('btntext', $btnText);
        $this->display($tpl);
        exit();
	}
}
