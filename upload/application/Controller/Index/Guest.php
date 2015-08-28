<?php
/**
 * iweibo2.0
 * 
 * 客人页控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Guest.php 2011-05-22 11:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Guest extends Controller_Index_Timeline
{

    /**
     * 客人页更多
     * 
     */
    public function moreAction()
    {
        $name = $this->getParam("u"); //客人微博帐号
        

        if(Core_Comm_Validator::isUserAccount($name))
        {
            $this->getMore('guest', $name);
        }
    }
}