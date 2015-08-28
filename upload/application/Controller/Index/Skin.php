<?php
/**
 * iweibo2.0
 * 
 * 我的皮肤控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Skin.php 2011-06-02 17:50:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Skin extends Core_Controller_TAction
{

    /**
     * 显示皮肤列表
     * 
     */
    public function indexAction()
    {
        $model = new Model_Mb_Skin();
        $data = $model->getSkinList();
        echo Core_Comm_Modret::getRetJson(Core_Comm_Modret::RET_SUCC, "", $data);
    }

    /**
     * 设置默认皮肤
     * 
     */
    public function setAction()
    {
        $model = new Model_User_Member();
        $flag = $model->onSetStyle($this->userInfo['uid'], $this->getParam('s'));
        if($flag)
            echo Core_Comm_Modret::getRetJson(Core_Comm_Modret::RET_SUCC, "");
        else
            echo Core_Comm_Modret::getRetJson(Core_Comm_Modret::RET_SKIN_ERR, "");
    }
}