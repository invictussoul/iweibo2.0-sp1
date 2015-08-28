<?php
/**
 * iweibo2.0
 * 
 * 数据调用
 *
 * @author lvfeng
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Datatransfer.php 2011/05/26
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Datatransfer extends Core_Controller_Action
{
	public function recommenduserAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$userModel = new Model_User_Member();
		//取得样式数据
		$css = array();
		$css['width'] = htmlspecialchars(trim($this->getParam('bw')));
		$css['height'] = htmlspecialchars(trim($this->getParam('bh')));
		$css['titlecolor'] = '#'.htmlspecialchars(trim($this->getParam('tc')));
		$css['shadecolor'] = "#FFFFFF";
		$css['bgcolor'] = '#'.htmlspecialchars(trim($this->getParam('bc')));
		$css['fontcolor'] = '#'.htmlspecialchars(trim($this->getParam('fc')));
		$css['bordercolor'] = '#'.htmlspecialchars(trim($this->getParam('sc')));
		$this->assign('css', $css);
		//取得title
		$title = htmlspecialchars(trim($this->getParam('tt')));
		$this->assign('title', $title);
		//取得推荐用户类型
		$recommendtype = intval($this->getParam('rt'));
		$recommendtype = $recommendtype==1 ? 1 : 2;
		//取得推荐用户数量
		$recommendnumber = intval($this->getParam('tn'));
		$recommendnumber = ($recommendnumber<1 || $recommendnumber>30) ? 1 : $recommendnumber;
		//取得签名
		//$sign = trim($this->getParam('si'));
		//取得当前access token
		$hasAccessToken = 0;
		$cAccessToken = $userModel->onGetCurrentAccessToken();
		if(!empty($cAccessToken['access_token']) && !empty($cAccessToken['access_token_secret']) && !empty($cAccessToken['name']))
		{
			$hasAccessToken = 1;
			$this->assign('accessToken', $cAccessToken);
		}
		$this->assign('hasAccessToken', $hasAccessToken);
		//从cache取推荐用户
		//$openUserInfo = Core_Cache::read('_dt_ru_'.md5($recommendtype).'.php');
		if(empty($openUserInfo))
		{
			//根据类型取推荐用户列表
			if($recommendtype==1)
			{
				$recommendModel = new Model_Recommend();
	        	$userList = $recommendModel->getRecommendList();
			}
			else 
				$userList = $userModel->getUserList(array(array('localauth', 1)), 'RAND()', 30);
			//取得拉数据access token
			if($hasAccessToken)
			{
				$accessToken = $cAccessToken['access_token'];
				$accessTokenSecret = $cAccessToken['access_token_secret'];
			}
			else
			{
				//随机取本地用户access token
				$rUser = $userModel->getUserList(array(array('oauthtoken', '', '!='), array('oauthtokensecret', '', '!=')), 'RAND()', 1);
				$accessToken = $rUser[0]['oauthtoken'];
				$accessTokenSecret = $rUser[0]['oauthtokensecret'];
			}
			//获取安装时候的key
	        $akey = Core_Config::get('appkey', 'basic');
	        $skey = Core_Config::get('appsecret', 'basic');
	        $apiClient = new Core_Open_Client($akey, $skey, $accessToken, $accessTokenSecret);
	        //从平台拉推荐用户信息
	        $openUserInfo = array();
	        foreach ($userList as $user)
	        {
	        	if(!empty($user['name']) || !empty($user['account']))
	        	{
		        	$p = array("n" => empty($user['name']) ? $user['account'] : $user['name']);
		        	$openmsg = $apiClient->getUserInfo($p);
		        	if($openmsg['ret']==0)
		        		$openUserInfo[] = $openmsg['data'];
	        	}
	        }
	        //生成cache
	        //$openUserInfo && Core_Cache::write('_dt_ru_'.md5($recommendtype).'.php', $openUserInfo, 3600);
		}
		//随机排序后保留推荐数量的用户信息
		shuffle($openUserInfo);
		$openUserInfo = array_slice($openUserInfo, 0, $recommendnumber);
        $this->assign('openUserInfo', $openUserInfo);
		$this->display('datatransfer/recommenduser.tpl');
	}
	
	public function recommendtopicAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$userModel = new Model_User_Member();
		$css = array();
		$css['width'] = htmlspecialchars(trim($this->getParam('bw')));
		$css['height'] = htmlspecialchars(trim($this->getParam('bh')));
		$css['titlecolor'] = '#'.htmlspecialchars(trim($this->getParam('tc')));
		$css['shadecolor'] = "#FFFFFF";
		$css['bgcolor'] = '#'.htmlspecialchars(trim($this->getParam('bc')));
		$css['fontcolor'] = '#'.htmlspecialchars(trim($this->getParam('fc')));
		$css['bordercolor'] = '#'.htmlspecialchars(trim($this->getParam('sc')));
		$this->assign('css', $css);
		//取得title
		$title = htmlspecialchars(trim($this->getParam('tt')));
		$this->assign('title', $title);
		//取得话题
		$topic = trim($this->getParam('nm'));
		//取得推荐数量
		$recommendnumber = intval($this->getParam('tn'));
		$recommendnumber = ($recommendnumber<1 || $recommendnumber>30) ? 1 : $recommendnumber;
		//取得显示图片方式
		$showtype = intval($this->getParam('st'));
		$this->assign('showtype', $showtype);
		//取得签名
		//$sign = trim($this->getParam('si'));
		//取得当前access token
		$hasAccessToken = 0;
		$cAccessToken = $userModel->onGetCurrentAccessToken();
		if(!empty($cAccessToken['access_token']) && !empty($cAccessToken['access_token_secret']) && !empty($cAccessToken['name']))
			$hasAccessToken = 1;
		//从cache取推荐话题
		$openTopicTimeline = Core_Cache::read('_dt_rt_'.md5($topic).'.php');
		if(empty($openTopicTimeline))
		{
			//取得拉数据access token
			if($hasAccessToken)
			{
				$accessToken = $cAccessToken['access_token'];
				$accessTokenSecret = $cAccessToken['access_token_secret'];
			}
			else
			{
				//随机取本地用户access token
				$rUser = $userModel->getUserList(array(array('oauthtoken', '', '!='), array('oauthtokensecret', '', '!=')), 'RAND()', 1);
				$accessToken = $rUser[0]['oauthtoken'];
				$accessTokenSecret = $rUser[0]['oauthtokensecret'];
			}
			//获取安装时候的key
	        $akey = Core_Config::get('appkey', 'basic');
	        $skey = Core_Config::get('appsecret', 'basic');
	        $apiClient = new Core_Open_Client($akey, $skey, $accessToken, $accessTokenSecret);
	        //从平台拉话题时间线
        	$p = array('t' => $topic, 'n' => 30);
        	$openmsg = $apiClient->getTopic($p);
        	if($openmsg['ret']==0)
        		$openTopicTimeline = $openmsg['data']['info'];
	        //生成cache
	        $openTopicTimeline && Core_Cache::write('_dt_rt_'.md5($topic).'.php', $openTopicTimeline, 300);
		}
		$openTopicTimeline = array_slice($openTopicTimeline, 0, $recommendnumber);
        $this->assign('openTopicTimeline', $openTopicTimeline);
		$this->display('datatransfer/recommendtopic.tpl');
	}
	
	public function pendantAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$userModel = new Model_User_Member();
		$css = array();
		$css['width'] = htmlspecialchars(trim($this->getParam('bw')));
		$css['height'] = htmlspecialchars(trim($this->getParam('bh')));
		$css['titlecolor'] = '#'.htmlspecialchars(trim($this->getParam('tc')));
		$css['shadecolor'] = "#FFFFFF";
		$css['bgcolor'] = '#'.htmlspecialchars(trim($this->getParam('bc')));
		$css['fontcolor'] = '#'.htmlspecialchars(trim($this->getParam('fc')));
		$css['bordercolor'] = '#'.htmlspecialchars(trim($this->getParam('sc')));
		$this->assign('css', $css);
		//取得title
		$title = htmlspecialchars(trim($this->getParam('tt')));
		$this->assign('title', $title);
		//取得用户
		$name = trim($this->getParam('nm'));
		//取得数量
		$number = intval($this->getParam('tn'));
		$number = ($number<1 || $number>30) ? 1 : $number;
		//取得显示图片方式
		$showtype = intval($this->getParam('st'));
		$this->assign('showtype', $showtype);
		//取得签名
		//$sign = trim($this->getParam('si'));
		//取得当前access token
		$hasAccessToken = 0;
		$cAccessToken = $userModel->onGetCurrentAccessToken();
		if(!empty($cAccessToken['access_token']) && !empty($cAccessToken['access_token_secret']) && !empty($cAccessToken['name']))
		{
			$hasAccessToken = 1;
			$this->assign('accessToken', $cAccessToken);
		}
		$this->assign('hasAccessToken', $hasAccessToken);
		$hasAccessToken && $cAccessToken['name'] == $name && $isOwn = 1;
		$this->assign('isOwn', $isOwn);
		//从cache取用户信息
		$openUserInfo = Core_Cache::read('_dt_pd_ui_'.md5($name).'.php');
		//从cache取微博，cache key='_pd_nt_'.签名.'.php'
		$openUserTimeline = Core_Cache::read('_dt_pd_ut_'.md5($name).'.php');
		if(empty($openUserTimeline) || empty($openUserInfo))
		{
			//取得拉数据access token
			if($hasAccessToken)
			{
				$accessToken = $cAccessToken['access_token'];
				$accessTokenSecret = $cAccessToken['access_token_secret'];
			}
			else
			{
				//随机取本地用户access token
				$rUser = $userModel->getUserList(array(array('oauthtoken', '', '!='), array('oauthtokensecret', '', '!=')), 'RAND()', 1);
				$accessToken = $rUser[0]['oauthtoken'];
				$accessTokenSecret = $rUser[0]['oauthtokensecret'];
			}
			//获取安装时候的key
	        $akey = Core_Config::get('appkey', 'basic');
	        $skey = Core_Config::get('appsecret', 'basic');
	        $apiClient = new Core_Open_Client($akey, $skey, $accessToken, $accessTokenSecret);
	        //从平台拉用户信息
	        $p = array("n" => $name);
        	$openmsg = $apiClient->getUserInfo($p);
        	if($openmsg['ret']==0)
        		$openUserInfo = $openmsg['data'];
	        //生成cache
	        $openUserInfo && Core_Cache::write('_dt_pd_ui_'.md5($name).'.php', $openUserInfo, 3600*24);
	        //从平台拉用户时间线
        	$p = array('name' => $name, 'n' => 30);
        	$openmsg = $apiClient->getTimeline($p);
        	if($openmsg['ret']==0)
        		$openUserTimeline = $openmsg['data']['info'];
	        //生成cache
	        $openUserTimeline && Core_Cache::write('_dt_pd_ut_'.md5($name).'.php', $openUserTimeline, 300);
		}
		$openUserTimeline = array_slice($openUserTimeline, 0, $number);
        $this->assign('openUserTimeline', $openUserTimeline);
        $this->assign('openUserInfo', $openUserInfo);
		$this->display('datatransfer/pendant.tpl');
	}
	
	public function newtAction()
	{
		$this->assign('pathRoot', Core_Fun::getPathroot());
		$userModel = new Model_User_Member();
		//取得样式数据
		$css = array();
		$css['width'] = htmlspecialchars(trim($this->getParam('bw')));
		$css['height'] = htmlspecialchars(trim($this->getParam('bh')));
		$css['titlecolor'] = '#'.htmlspecialchars(trim($this->getParam('tc')));
		$css['shadecolor'] = "#FFFFFF";
		$css['bgcolor'] = '#'.htmlspecialchars(trim($this->getParam('bc')));
		$css['fontcolor'] = '#'.htmlspecialchars(trim($this->getParam('fc')));
		$css['bordercolor'] = '#'.htmlspecialchars(trim($this->getParam('sc')));
		$this->assign('css', $css);
		//取得title
		$title = htmlspecialchars(trim($this->getParam('tt')));
		$this->assign('title', $title);
		//取得用户（多用户）
		$names = trim($this->getParam('nm'));
		$nameArr = explode(',', $names);
		sort($nameArr);
		$names = implode(',', $nameArr);
		//取得数量
		$number = intval($this->getParam('tn'));
		$number = ($number<1 || $number>30) ? 1 : $number;
		//取得显示图片方式
		$showtype = intval($this->getParam('st'));
		$this->assign('showtype', $showtype);
		//取得签名
		//$sign = trim($this->getParam('si'));
		//取得当前access token
		$hasAccessToken = 0;
		$cAccessToken = $userModel->onGetCurrentAccessToken();
		if(!empty($cAccessToken['access_token']) && !empty($cAccessToken['access_token_secret']) && !empty($cAccessToken['name']))
			$hasAccessToken = 1;
		//从cache取微博
		$openUsersTimeline = Core_Cache::read('_dt_nt_'.md5($names).'.php');
		if(empty($openUsersTimeline))
		{
			//取得拉数据access token
			if($hasAccessToken)
			{
				$accessToken = $cAccessToken['access_token'];
				$accessTokenSecret = $cAccessToken['access_token_secret'];
			}
			else
			{
				//随机取本地用户access token
				$rUser = $userModel->getUserList(array(array('oauthtoken', '', '!='), array('oauthtokensecret', '', '!=')), 'RAND()', 1);
				$accessToken = $rUser[0]['oauthtoken'];
				$accessTokenSecret = $rUser[0]['oauthtokensecret'];
			}
			//获取安装时候的key
	        $akey = Core_Config::get('appkey', 'basic');
	        $skey = Core_Config::get('appsecret', 'basic');
	        $apiClient = new Core_Open_Client($akey, $skey, $accessToken, $accessTokenSecret);
	        //从平台拉多用户时间线
        	$p = array('names' => $names, 'n' => 30);
        	$openmsg = $apiClient->getUsersTimeline($p);
        	if($openmsg['ret']==0)
        		$openUsersTimeline = $openmsg['data']['info'];
	        //生成cache
	        $openUsersTimeline && Core_Cache::write('_dt_nt_'.md5($names).'.php', $openUsersTimeline, 300);
		}
		$openUsersTimeline = array_slice($openUsersTimeline, 0, $number);
        $this->assign('openUsersTimeline', $openUsersTimeline);
		$this->display('datatransfer/newt.tpl');
	}
}
