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
 * @version $Id: Controller_Index_U.php 479 2011-05-20 11:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Gd extends Core_Controller_Action
{

	public function indexAction()
	{
		$gdcheck = new Core_Lib_Gdcheck();
		$gdcheck->createImg();
	}

}