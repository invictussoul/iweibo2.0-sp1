<?php
/**
 * iweibo2.0
 * 
 * 我的广播控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Mine.php 2011-05-20 11:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Mine extends Controller_Index_Timeline
{

    /**
     * 显示我的广播
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('mine');
        
        $utype = Core_Comm_Validator::getTidArg($this->getParam('utype'), '0');
        $ctype = Core_Comm_Validator::getTidArg($this->getParam('ctype'), '0');
        //时间线类型
        $filterlist = array(array('url' => '/mine', 'name' => '全部', 'utype' => '0'), 
        array('url' => '/mine/index/utype/1', 'name' => '原创', 'utype' => '1'), 
        array('url' => '/mine/index/utype/2', 'name' => '转播', 'utype' => '2'), 
        array('url' => '/mine/index/utype/8', 'name' => '对话', 'utype' => '8'), 
        array('url' => '/mine/index/utype/64', 'name' => '评论', 'utype' => '64'), 
        array('url' => '/mine/index/ctype/4', 'name' => '图片', 'ctype' => '4'), 
        array('url' => '/mine/index/ctype/8', 'name' => '视频', 'ctype' => '8'), 
        array('url' => '/mine/index/ctype/16', 'name' => '音乐', 'ctype' => '16'));
        
        $this->assign('utype', $utype);
        $this->assign('ctype', $ctype);
        $this->assign('filterlist', $filterlist);
        
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(3, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(3, 'right'));
        
        //激活样式
        $this->assign('active', 'mine');
        $this->display('user/mine_timeline.tpl');
    }

    /**
     * 我的广播更多
     * 
     */
    public function moreAction()
    {
        $this->getMore('mine');
    }
}
