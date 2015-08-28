<?php
/**
 * iweibo2.0
 * 
 * wap我的广播控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Mine.php 2011-05-31 16:16:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Mine extends Controller_Wap_Timeline
{

    /**
     * 显示我的主页
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('mine');
        
        //如需拉取多个类型请|上(1|2) 得到3，type=3即可,填零表示拉取所有类型
        $utype = Core_Comm_Validator::getNumArg($this->getParam("utype"), 0, 64, 0);
        
        $this->assign('len', 9);
        
        $this->assign('requrl', Core_Fun::getPathroot().'wap/mine/index/utype/' . $utype);
        $this->display('wap/mine.tpl');
    }
}