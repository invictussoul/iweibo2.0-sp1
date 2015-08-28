<?php
/**
 * iweibo2.0
 * 
 * wap封面页控制器
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Index.php 2011-05-16 15:02:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Index extends Controller_Wap_Timeline
{   
    /**
     * 我的主页
     * 
     */
    public function indexAction()
    {
        $this->showmsg('','wap/u/' . $this->userInfo['name'], 0);
    }
    
    /**
     * 显示封面页
     * 
     */
    public function homeAction()
    {
        //获取填充tbody
        $this->getBody('public');        
        $this->assign('len', 9);
        
        $this->assign('requrl', Core_Fun::getPathroot().'wap/index/index');
        $this->display('wap/home.tpl');
    }   
     
    /**
     * 显示图片
     * 
     */
    public function imgAction()
    {
        $rurl = $this->getParam('rurl'); 
        //xss 暂时解决方案
        $url = $this->getParam('url');        
        $i = $this->getParam('i') ? $this->getParam('i') : '240';
        $temp = '/wap/index/img/url/'.Core_Fun::iurlencode($url).'/rurl/'.Core_Fun::iurlencode($rurl);
        //时间线类型
        $filterlist = array(
                		array('url'=>$temp.'/i/120', 'i'=>'120', 'name'=>'120*240'),
                		array('url'=>$temp.'/i/240', 'i'=>'240', 'name'=>'240*320'),
                		array('url'=>$temp.'/i/320', 'i'=>'320', 'name'=>'320*480'),
                		array('url'=>$temp.'/i/2000', 'i'=>'2000', 'name'=>'原图')
            		);
        
        $this->assign('i', $i);
        $this->assign('filterlist', $filterlist);
        
        $this->assign('rurl', $rurl);
        $this->assign('url', $url);
        $this->display('wap/image.tpl');
    }    
}