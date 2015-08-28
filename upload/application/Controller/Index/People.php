<?php

/**
 * iweibo2.0
 * 
 * 找人模块控制器
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Search.php 2011/5/25
 * @package Controller
 * @since 2.0
 */
class Controller_Index_People extends Core_Controller_TAction
{

    //构造函数
    public function preDispatch()
    {
        parent::preDispatch();
        $this->assign('active', 'people');
    }

    /**
     * 找人
     *
     */
    public function indexAction()
    {
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(9, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(9, 'right'));

        $this->assign('data', array('title'=>'名人墙'));
        $this->display('people/find.tpl');
    }

}