<?php

/**
 * iweibo2.0
 * 
 * 微博wap模块控制器基类
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Controller_WapAction.php 2011-06-12 15:36:00Z gionouyang $
 * @package Core
 * @since 2.0
 */
class Core_Controller_WapAction extends Core_Controller_Action
{
    
    //登录用户信息
    protected $userInfo = array();

    /**
     * 分发前执行的操作
     * 如有需要请重载
     * @author Icehu
     */
    public function preDispatch()
    {
        parent::preDispatch();
        
        //Wap是否关闭
        $wapOn = Core_Config::get('wap_on', 'basic', true);
        if(! $wapOn)
        {
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
            exit();
        }
        
        try
        {
            Model_Stat::addStat('wapvisit');
        }
        catch(Exception $e)
        {}
        
        $userModel = new Model_User_Member();
        $cUser = $userModel->onGetCurrentUser();
        $tokenArr = $userModel->onGetCurrentAccessToken();
        
        //正常用户访问此页面直接引导到微博首页
        if(empty($cUser['uid']))
        {
            //验证是否有令牌
            if(! $token = $this->getCookie('iwb_token'))
                $this->showmsg('', 'wap/login', 0);
            
     //验证令牌
            $tokenModel = new Model_Base_Token();
            $authTokenArr = explode("\t", Core_Comm_Token::authcode($token));
            if(! $tokenModel->getTokenInfoByUidAndSign($authTokenArr[0], $authTokenArr[1]))
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'wap/login', 0);
            }
            //验证用户是否存在
            if(! $localUser = $userModel->getUserInfoByUid($authTokenArr[0]))
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'wap/login', 0);
            }
            //验证用户是否在屏蔽组
            if($localUser['gid'] == 4)
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'wap/login', 0);
            }
            //将登录状态赋给用户
            $userModel->onSetCurrentUser($localUser['uid'], $localUser['nickname']);
            $userModel->onSetCurrentAccessToken($localUser['oauthtoken'], $localUser['oauthtokensecret'], 
            $localUser['name']);
        }
        else
        {
            //验证用户是否存在
            if(! $localUser = $userModel->getUserInfoByUid($cUser['uid']))
            {
                $userModel->onLogout();
                $this->showmsg('', 'wap/login', 0);
            }
            //验证用户是否在屏蔽组
            if($localUser['gid'] == 4)
            {
                $userModel->onLogout();
                $this->showmsg('', 'wap/login/index/msg/' . Core_Comm_Modret::RET_USER_BLOCKED, 0);
            }
            //验证用户是否已平台登录
            if(empty($tokenArr['name']))
                $this->showmsg('', 'wap/login/b', 0);
            
     //验证token是否有效
            try
            {
                $userInfo = Model_User_Util::getInfo($tokenArr['name']);
                $userInfo['head'] = Core_Lib_Base::formatHead($userInfo['head'], 100);
                
                $this->userInfo = $userInfo;
                $this->userInfo['uid'] = $localUser['uid'];
                $this->assign('username', $userInfo['name']);
                $this->assign('user', $this->userInfo);
                
                $this->assign('username', $userInfo['name']);
                $this->assign('userurl', Core_Fun::getUserShorturl($userInfo['name']));
                
                //个人认证设置
                $local = Core_Config::get('localauth', 'certification.info');
                $platform = Core_Config::get('platformauth', 'certification.info');
        
                $authtype = array('local' => $local, 'platform' => $platform);
                $this->assign('authtype', $authtype);
            }
            catch(Core_Api_Exception $e)
            {
                if($e->getCode() == 3001 || $e->getCode() == 3003)
                {
                    $ui = array('uid' => $cUser['uid'], 'oauthtoken' => '', 'oauthtokensecret' => '', 'name' => '');
                    $userModel->editUserInfo($ui);
                    $userModel->onSetCurrentAccessToken(null, null, null);
                    $this->showmsg('', 'wap/login/r/msg/' . Core_Comm_Modret::RET_ACCOUNT_INVALID, 0);
                }
            }
        }
        
        $front = Core_Controller_Front::getInstance();
        $pathinfo = $front->getPathinfo();
        $this->userInfo['pathinfo'] = $pathinfo;
        $this->assign('pathinfo', $pathinfo); //原始的pathinfo
        $this->assign('rawpathinfo', Core_Fun::iurlencode($pathinfo)); //做过安全转义的url，使用需要Core_Fun::iurldecode
    }

}