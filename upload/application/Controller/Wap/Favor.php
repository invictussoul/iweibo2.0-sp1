<?php
/**
 * iweibo2.0
 * 
 * wap我的收藏控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Favor 2011-05-30 21:29:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Favor extends Controller_Wap_Timeline
{

    /**
     * 显示我的收藏
     * 
     */
    public function indexAction()
    {
        //获取填充tbody
        $this->getBody('favor');
        
        //如需拉取多个类型请|上(1|2) 得到3，type=3即可,填零表示拉取所有类型
        $utype = Core_Comm_Validator::getNumArg($this->getParam("utype"), 0, 64, 0);
        
        $this->assign('len', 9);
        
        $this->assign('requrl', Core_Fun::getPathroot() . 'wap/favor/index/utype/' . $utype);
        $this->display('wap/favor.tpl');
    }

    /**
     *收藏微博/取消收藏微博
     * ajax接口
     */
	protected function tAction()
	{
		$tid =  $this->getParam('tid');//微博id
		$type =  $this->getParam('type');//微博id
		if(!Core_Comm_Validator::isTId($tid))
		{
			Core_Fun::showmsg('收藏id丢失',-1);//返回上一页;
		}
		if($type!=1 && $type!=0)//type 1添加收藏 ;0删除
		{
			Core_Fun::showmsg('收藏类型丢失',-1);//返回上一页;
		}

		try
		{
			Core_Open_Api::getClient()->postFavMsg(array("type"=>$type, "id"=>$tid));
		}
		catch(exception $e)
		{
			Core_Fun::showmsg('收藏失败',-1);//返回上一页;
		}
        $backurl = $this->getParam('backurl');
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?Core_Fun::getUrlroot().'wap':$_SERVER['HTTP_REFERER'];     
		Core_Fun::showmsg('',$backurl,0);//返回上一页
	}

}