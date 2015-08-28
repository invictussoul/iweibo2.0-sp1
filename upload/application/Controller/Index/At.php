<?php
/**
 * iweibo2.0
 * 
 * 提到我的控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_At.php 2011-05-16 14:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_At extends Controller_Index_Timeline
{

    /**
     * 提到我的
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('at');
        
        $utype = Core_Comm_Validator::getTidArg($this->getParam('utype'), '0');
        //时间线类型
        $filterlist = array(array('url' => '/at', 'name' => '全部', 'utype' => '0'), 
        array('url' => '/at/index/utype/1', 'name' => '原创', 'utype' => '1'), 
        array('url' => '/at/index/utype/2', 'name' => '转播', 'utype' => '2'), 
        array('url' => '/at/index/utype/8', 'name' => '对话', 'utype' => '8'), 
        array('url' => '/at/index/utype/64', 'name' => '评论', 'utype' => '64'));
        
        $this->assign('utype', $utype);
        $this->assign('filterlist', $filterlist);
        
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(4, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(4, 'right'));
        
        //我的主页激活样式
        $this->assign('active', 'at');
        $this->display('user/at_timeline.tpl');
    }

    /**
     * 提到我的更多
     * 
     */
    public function moreAction()
    {
        Core_Lib_Base::clearNewMsgInfo(6);
        $this->getMore('at');
    }
}
