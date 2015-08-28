<?php
/**
 * iweibo2.0
 * 
 * wap广播大厅控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Public.php 2011-06-01 20:50:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Public extends Controller_Wap_Timeline
{

    /**
     * 显示广播大厅
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('public');        
        $this->assign('len', 9);
        
        //获取话题推荐
        $model = new Model_Hottopic;
        $hotlist = $model->__toData();
        
        $this->assign('hotlist', $hotlist);
        $this->assign('requrl', Core_Fun::getPathroot().'wap/public/index');
        $this->display('wap/public.tpl');
    }
}