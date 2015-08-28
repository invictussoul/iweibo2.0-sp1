<?php
/**
 * iweibo2.0
 * 
 * 我的主页控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_U.php 2011-06-07 15:55:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_U extends Controller_Index_Timeline
{

    /**
     * 显示我的主页
     * 
     * @param string $name 微博帐号
     */
    private function index($name)
    {
        $msgCode = intval($this->getParam('msg'));
        $msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
        
        if(Core_Config::get('useuc', 'basic', false) && isset($_SESSION['ucsynlogin']) && $_SESSION['ucsynlogin'])
        {
            //生成同步登录的代码
            $ucsynlogin = Core_Outapi_Uc::call('user_synlogin', $_SESSION['uid']);
            $this->assign('ucsynlogin', $ucsynlogin);
            unset($_SESSION['ucsynlogin']);
        }
        //个人认证设置
        $local = Core_Config::get('localauth', 'certification.info');
        $platform = Core_Config::get('platformauth', 'certification.info');
        $localtext = Core_Config::get('localauthtext', 'certification.info');
         $authInfo = array('local' => $local, 'platform' => $platform, 'localtext' => $localtext);
        $this->assign('auth', $authInfo);

        if(strtolower($name) == strtolower($this->userInfo["name"])) //跳我的主页
        {
            //主栏组件
            $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(1, 'main'));
            //右栏组件
            $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(1, 'right'));
            
            //如需拉取多个类型请|上(1|2) 得到3，type=3即可,填零表示拉取所有类型
            $utype = Core_Comm_Validator::getNumArg($this->getParam("utype"), 0, 64, 0);
            //Contenttype:内容过滤 填零表示所有类型 1-带文本 2-带链接 4图片 8-带视频 0x10-带音频
            $ctype = Core_Comm_Validator::getNumArg($this->getParam("ctype"), 0, 16, 0);
            $this->assign('utype', $utype);
            $this->assign('ctype', $ctype);
            
            Core_Lib_Base::clearNewMsgInfo(5);

            //获取填充tbody
            $this->getBody('index');
            //激活样式
            $this->assign('active', 'index');
            $this->display('user/index_timeline.tpl');
        }
        else //他人页
        {
            //获取显示用户资料

            $guest = Model_User_Util::getFullInfo($name);
            $guest['head'] = Core_Lib_Base::formatHead($guest['head'],120);

            //拉取偶像列表, 只拉13个
            $p = array("n" => $name, "num" => 12, "start" => 0, "type" => 1);
            
            //获取偶像
            $friendObj = Model_Friend::singleton();
            $idols = $friendObj->getMyfriend($p);
            $idollist = $idols["data"]["info"];
            foreach($idollist as &$u)
            {
                $u['head'] = Core_Lib_Base::formatHead($u['head'], 50);
            }
            //获取填充tbody
            $this->getBody('guest', $name);
            
            $utype = Core_Comm_Validator::getTidArg($this->getParam('utype'), '0');
            $ctype = Core_Comm_Validator::getTidArg($this->getParam('ctype'), '0');
            $url = '/u/' . $name;
            //时间线类型
            $filterlist = array(array('url' => $url, 'name' => '全部', 'utype' => '0'), 
            array('url' => $url . '/utype/1', 'name' => '原创', 'utype' => '1'), 
            array('url' => $url . '/utype/2', 'name' => '转播', 'utype' => '2'), 
            array('url' => $url . '/ctype/4', 'name' => '图片', 'ctype' => '4'), 
            array('url' => $url . '/ctype/8', 'name' => '视频', 'ctype' => '8'), 
            array('url' => $url . '/ctype/16', 'name' => '音乐', 'ctype' => '16'));
            
            $this->assign('idollist', $idollist);
            $this->assign('guest', $guest);
            $this->assign('utype', $utype);
            $this->assign('ctype', $ctype);
            $this->assign('filterlist', $filterlist);

            //激活样式
            $this->assign('active', 'guest');
            $this->display('index/guest_timeline.tpl');
        }
    }

    /**
     * 魔术方法，查找对应action
     * 
     * @param string $methodName
     * @param array $args
     */
    public function __call($methodName, $args)
    {
        if('Action' == substr($methodName, - 6))
        {
            $action = substr($methodName, 0, strlen($methodName) - 6);
            //如果是合法用户
            $this->index($action);
            return;
        }
        parent::__call($methodName, $args);
    }

    /**
     * 查找某用户个人详情
     */
    public function guestinfoAction()
    {
        $name = $this->getParam('name'); //客人微博帐号
        

        if(! Core_Comm_Validator::isUserAccount($name))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG);
        }
        try
        {
            $userInfo = Model_User_Util::getInfo($name);
            //$userInfo = Core_Open_Api::getClient()->getUserInfo(array("n" => $name));
            if(empty($userInfo))
            {
                echo Core_Comm_Modret::getRetJson(Core_Comm_Modret::RET_API_ARG_ERR, "用户信息不存在");
                exit();
            }
            else
            {
                $userInfo['head'] = Core_Lib_Base::formatHead($userInfo['head'], 100);
                $this->exitJson(Core_Comm_Modret::RET_SUCC, "", $userInfo);
            }
        }
        catch(exception $e)
        {
            $this->exitJson(Core_Comm_Modret::RET_API_ARG_ERR, "用户信息不存在");
        }
    }
	
     /**
     *  下载自己自动注册的登录帐号和密码
	 */		
	public function  dAction()
	{		
			$userModel = new Model_User_Member();
			$cUser = $userModel->onGetCurrentUser();
	        $tokenArr = $userModel->onGetCurrentAccessToken();
			
	        if(!empty($cUser['uid']) && !empty($tokenArr['name'])) //若用户未登录，则不允许下载
			{
				$name = $this->getParam("n");
				$pwd  = $this->getParam("p");	
				
	            $str = "\r\n 网站地址为:".Core_Config::get('site_url', 'basic', false)."\r\n 你的帐号为：" . $name	. "\r\n 你的密码为：" . $pwd;			
				Header("Content-type: application/octet-stream"); 
				Header("Accept-Ranges: bytes"); 
				Header("Accept-Length: ".strlen($str)); 
				Header("Content-Disposition: attachment; filename=" . Core_Config::get('site_name', 'basic', 'iweibo2.0') );    
				echo $str;
				exit();
		}
	}		
	
	

}
