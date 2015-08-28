<?php
/**
 * iweibo2.0
 * 
 * 微博模块控制器基类
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_U.php 2011-06-14 18:16:00Z gionouyang $
 * @package Core
 * @since 2.0
 */
class Core_Controller_TAction extends Core_Controller_Action
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
        if(Core_Comm_Validator::isMobile() && $wapOn)
        {
            $this->showmsg('', 'wap', 0);
            exit();
        }
        
        $userModel = new Model_User_Member();
        $cUser = $userModel->onGetCurrentUser();
        $tokenArr = $userModel->onGetCurrentAccessToken();
        
        //验证用户是否已本地登录
        if(empty($cUser['uid']))
        {
            //验证是否有令牌
            if(! $token = $this->getCookie('iwb_token'))
                $this->showmsg('', 'login', 0);
            
     //验证令牌
            $tokenModel = new Model_Base_Token();
            $authTokenArr = explode("\t", Core_Comm_Token::authcode($token));
            if(! $tokenModel->getTokenInfoByUidAndSign($authTokenArr[0], $authTokenArr[1]))
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'login', 0);
            }
            //验证用户是否存在
            if(! $localUser = $userModel->getUserInfoByUid($authTokenArr[0]))
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'login', 0);
            }
            //验证用户是否在屏蔽组
            if($localUser['gid'] == 4)
            {
                Core_Fun::setcookie('iwb_token', null);
                $this->showmsg('', 'login', 0);
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
                $this->showmsg('', 'login', 0);
            }
            //验证用户是否在屏蔽组
            if($localUser['gid'] == 4)
            {
                $userModel->onLogout();
                $this->showmsg('', 'login/index/msg/' . Core_Comm_Modret::RET_USER_BLOCKED, 0);
            }
            //验证用户是否已平台登录
            if(empty($tokenArr['name']))
                $this->showmsg('', 'login/r', 0);
        }
        if(! ($this->getModelName() == 'index' && $this->getControllerName() == 'setting' &&
         ($this->getActionName() == 'getnation' || $this->getActionName() == 'getprovince' ||
         $this->getActionName() == 'getcity')))
        {
            //用户信息
            //	        $userInfo = Core_Open_Api::getClient()->getUserInfo();
            //	        $userInfo = Core_Lib_Base::formatU($userInfo['data'], 100);
            $userInfo = Model_User_Util::getInfo($tokenArr['name']);
            $userInfo['head'] = Core_Lib_Base::formatHead($userInfo['head'], 100);
            
            $this->userInfo = $userInfo;
            $this->userInfo['uid'] = $localUser['uid'];
            $this->userInfo['gid'] = $localUser['gid'];
            $this->assign('user', $this->userInfo);
            //微博帐号
            $this->assign('username', $userInfo['name']);
            $this->assign('userurl', Core_Fun::getUserShorturl($userInfo['name']));
        }
        //设置搜索关键字
        $hotWords = explode(' ', Core_Config::get('hot_words', 'basic', 'iWeibo 腾讯微博'));
        $this->assign('hotWords', $hotWords);
        //设置导航栏
        $topNav = Model_Nav::getNavByType(Model_Nav::TYPE_TOP);
        $mainNav = Model_Nav::getNavByType(Model_Nav::TYPE_MAIN);
        $footNav = Model_Nav::getNavByType(Model_Nav::TYPE_FOOT);
        $this->assign('topNav', $topNav);
        $this->assign('mainNav', $mainNav);
        $this->assign('footNav', $footNav);
        if(Model_User_Access::checkAccessByGidAndModel($localUser['gid'], 'a001'))
            $this->assign('hasPermission', 1);
        
     //显示在导航的插件列表
        $pluginModel = new Model_Mb_Plugin();
        $pluginList = $pluginModel->getPluginList(2);
        $this->assign('pluginList', $pluginList);
        //是否本地化
        $this->assign('islocal', Model_Friend::getFriendSrc());
    }
}