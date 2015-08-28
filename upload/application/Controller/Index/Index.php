<?php
/**
 * iweibo2.0
 *
 * 我的主页控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Index.php 2011-06-07 11:31:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Index extends Controller_Index_Timeline
{

    /**
     * 显示我的主页
     *
     */
    public function indexAction()
    {
        $this->showmsg('', 'u/' . $this->userInfo['name'], 0);
    }

    /**
     * 我的主页更多
     *
     */
    public function moreAction()
    {
        Core_Lib_Base::clearNewMsgInfo(5);
        $this->getMore('index');
    }
}