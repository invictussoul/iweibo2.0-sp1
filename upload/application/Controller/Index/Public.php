<?php

/**
 * iweibo2.0
 * 
 * 广播大厅控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Public.php 2011-05-21 15:00:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Public extends Controller_Index_Timeline {

    /**
     * 显示广播大厅
     * 
     */
    public function indexAction() {
        //获取填充tbody
        $this->getBody('public');
        //tab
        $tabArr = array(array('url' => '/public', 'title' => '大家在说'), array('url' => '/city', 'title' => '同城广播'));
        $tabbar = Core_Lib_Base::formatTab($tabArr, 0);
        $this->assign('tabbar', $tabbar);

        $p = Core_Comm_Validator::getNumArg($this->getParam("p"), 0, PHP_INT_MAX, 0);

        //激活样式
        $this->assign('p', $p);

        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(2, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(2, 'right'));

        //激活样式
        $this->assign('active', 'public');
        $this->assign('action', 'public');
        $this->display('index/public_timeline.tpl');
    }

}
