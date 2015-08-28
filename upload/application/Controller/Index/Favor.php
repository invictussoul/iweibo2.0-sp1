<?php
/**
 * iweibo2.0
 * 
 * 我的收藏控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Favor.php 2011-05-20 11:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Favor extends Controller_Index_Timeline
{

    /**
     * 我的收藏
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('favor');
        
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(5, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(5, 'right'));
        
        //我的主页激活样式
        $this->assign('active', 'favor');
        $this->display('user/favor_timeline.tpl');
    }

    /**
     * 我的收藏更多
     * 
     */
    public function moreAction()
    {
        $this->getMore('favor');
    }

    /**
     *收藏微博/取消收藏微博
     * ajax接口
     */
    protected function tAction()
    {
        $tid = $this->getParam('tid'); //微博id
        $type = $this->getParam('type'); //微博id
        if(! Core_Comm_Validator::isTId($tid))
        {
            $this->exitJson(Core_Comm_Modret::RET_ARG_ERR, '收藏id丢失');
        }
        if($type != 1 && $type != 0) //type 1添加收藏 ;0删除
        {
            $this->exitJson(Core_Comm_Modret::RET_ARG_ERR, '收藏类型丢失');
        }
        
        try
        {
            Core_Open_Api::getClient()->postFavMsg(array("type" => $type, "id" => $tid));
        }
        catch(exception $e)
        {
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '收藏失败');
        }
        
        //统计代码
        if($type)
        {
            try
            {
                Model_Stat::addStat('addfav');
            }
            catch(Exception $e)
            {
                //pass
            }
        }
        
        $this->exitJson(Core_Comm_Modret::RET_SUCC);
    }

}
