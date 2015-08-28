<?php
/**
 * 数据调用
 *
 * @author lvfeng
 */
class Controller_Admin_Datatransfer extends Core_Controller_Action
{
    /**
     * 推荐用户
     */
    public function recommenduserAction ()
    {
        $this->display ('admin/datatransfer_recommenduser.tpl');
    }
    
    /**
     * 推荐用户
     */
    public function recommendtopicAction ()
    {
        $this->display ('admin/datatransfer_recommendtopic.tpl');
    }
    
    /**
     * 微博广播站
     */
    public function pendantAction ()
    {
        $this->display ('admin/datatransfer_pendant.tpl');
    }
    
    /**
     * 最新微博
     */
    public function newtAction ()
    {
        $this->display ('admin/datatransfer_newt.tpl');
    }
    
    public function createcodeAction()
    {
    	$conditions['model'] = trim($this->getParam('model'));
    	$conditions['title'] = Core_Fun::iurlencode(trim($this->getParam('title')));
    	$conditions['recommendtype'] = intval($this->getParam('recommendtype'));
    	$conditions['name'] = Core_Fun::iurlencode(trim($this->getParam('name')));
    	$conditions['number'] = intval($this->getParam('number'));
    	$conditions['width'] = intval($this->getParam('width'));
    	$conditions['height'] = intval($this->getParam('height'));
    	$conditions['titlecolor'] = trim($this->getParam('titlecolor'));
    	$conditions['bgcolor'] = trim($this->getParam('bgcolor'));
    	$conditions['fontcolor'] = trim($this->getParam('fontcolor'));
    	$conditions['bordercolor'] = trim($this->getParam('bordercolor'));
    	$conditions['showtype'] = intval($this->getParam('showtype'));
    	$conditions['autowidth'] = intval($this->getParam('autowidth'));
    	//验证推荐话题是否存在
    	if($conditions['model'] == 'recommendtopic')
    	{
    		try
    		{
	    		$p = array('t' => Core_Fun::iurldecode($conditions['name']), 'n' => 1);
	    		$topicList = Core_Open_Api::getClient()->getTopic($p);
	    		if($topicList['ret'])
	    		{
	    			echo 'error:指定话题不存在';
	    			exit;
	    		}
    		}
    		catch (Core_Api_Exception $e)
    		{
    			echo 'error:'.$e->getMessage();
    			exit;
    		}
    	}
    	//验证推荐用户是否存在
    	if($conditions['model'] == 'pendant' || $conditions['model'] == 'newt')
    	{
    		$userModel = new Model_User_Member();
    		$names = Core_Fun::iurldecode($conditions['name']);
    		$conditions['model'] == 'newt' && $names = explode(',', $names);
    		$errorMsg = 'error:指定用户 ';
    		foreach ((array)$names as $name)
    		{
    			if(!$userModel->getUserInfoByName($name))
    			{
    				$hasError = true;
    				$errorName .= $errorName == '' ? $name : ', '.$name;
    			}
    		}
    		$errorMsg .= $errorName.' 不存在';
    		if($hasError)
    		{
    			echo $errorMsg;
    			exit;
    		}
    	}
    	$http = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
    	$url = Core_Fun::getPathroot().'datatransfer/'.$conditions['model'].
    			'/tt/'.$conditions['title'];
    	if($conditions['model'] == 'recommenduser')
    	{
    		$url .= '/rt/'.$conditions['recommendtype'];
    	}
    	else
    	{
    		$url .= '/nm/'.$conditions['name'];
    		$url .= '/st/'.$conditions['showtype'];
    	}
    	$url .= '/tn/'.$conditions['number'].
    			'/bw/'.$conditions['width'].
    			'/bh/'.$conditions['height'].
    			'/tc/'.$conditions['titlecolor'].
    			'/bc/'.$conditions['bgcolor'].
    			'/fc/'.$conditions['fontcolor'].
    			'/sc/'.$conditions['bordercolor'];
    	
		$iframe = '<iframe frameborder="0" scrolling="no" src="'.$url.'" width="'.($conditions['autowidth']?'100%':$conditions['width']).'" height="'.$conditions['height'].'"></iframe>';
		echo $iframe;
    }
}
