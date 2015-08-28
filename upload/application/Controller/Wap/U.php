<?php
/**
 * iweibo2.0
 * 
 * 我的主页，客人页wap控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_U.php 2011-05-30 17:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_U extends Controller_Wap_Timeline
{

    /**
     * 显示我的主页
     * 
     * @param string $name 微博帐号
     */
    private function index($name)
    {
        if(strtolower($name) == strtolower($this->userInfo["name"])) //跳我的主页
        {
            //获取填充tbody
            $this->getBody('index');
            
            //每次请求记录的条数（1-20条）
            $num = Core_Comm_Validator::getNumArg($this->getParam("num"), 1, 30, 10);
            //时间线类型
            $filterlist = array(5, 10, 20, 30);
            
            $this->assign('num', $num);
            $this->assign('len', $num - 1);
            $this->assign('filterlist', $filterlist);
            
            $this->assign('requrl', '/wap/u/' . $name);
            $this->display('wap/index.tpl');
        }
        else //他人页
        {
            //获取显示用户资料
            $guest = Model_User_Util::getFullInfo($name);
            $guest['head'] = Core_Lib_Base::formatHead($guest['head'], 120);
            
            //获取填充tbody
            $this->getBody('guest', $name);
            
            $url = '/u/' . $name;
            
            $this->assign('guest', $guest);
            $this->assign('len', 9);
            $this->assign('requrl', '/wap/u/' . $name);
            $this->display('wap/guest.tpl');
        }
    }

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
}