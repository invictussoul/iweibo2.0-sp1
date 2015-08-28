<?php
/**
 * iweibo2.0
 * 
 * wap提到我的控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_At.php 2011-06-01 16:12:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_At extends Controller_Wap_Timeline
{

    /**
     * 显示提到我的
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('at');        
        $this->assign('len', 9);
        
        $this->assign('requrl', Core_Fun::getPathroot().'wap/at/index');
        $this->display('wap/at.tpl');
    }
}