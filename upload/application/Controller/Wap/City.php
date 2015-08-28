<?php
/**
 * iweibo2.0
 * 
 * wap同城广播控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_City.php 2011-06-01 18:25:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_City extends Controller_Index_Timeline
{

    /**
     * 同城广播
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('city');        
        $this->assign('len', 9);
        
        //获取话题推荐
        $model = new Model_Hottopic;
        $hotlist = $model->__toData();
        
        $this->assign('hotlist', $hotlist);
        
        $this->assign('requrl', Core_Fun::getPathroot().'wap/city/index');
        $this->display('wap/public.tpl');
        
    }
}