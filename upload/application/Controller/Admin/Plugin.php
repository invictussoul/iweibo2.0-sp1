<?php
/**
 * 插件管理
 *
 * @author lvfeng
 */
class Controller_Admin_Plugin extends Core_Controller_Action
{
	/**
	 * 安装插件
	 */
	public function installAction()
	{
		//queryString加密码
		$securecode = 'adminpluginstall';
		$pluginModel = new Model_Mb_Plugin();
		if(trim($this->getParam('action'))=='install')
		{
			$conditions['name'] = trim($this->getParam('name'));
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$conditions['useable'] = intval($this->getParam('useable'));
			$conditions['visible'] = intval($this->getParam('visible'));
			$conditions['usehack'] = intval($this->getParam('usehack'));
			$conditions['orderkey'] = intval($this->getParam('orderkey'));
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			$errorBackUrl = '/admin/plugin/install/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			//验证表单取得的数据
			$errorMessage = '';
			$checkResult = array();
			$checkResult[] = $pluginModel->checkName($conditions['name'], $conditions['id']);
			$checkResult[] = $pluginModel->checkFolderName($conditions['foldername'], $conditions['id']);
			foreach ($checkResult as $result)
			{
				!empty($result) && $errorMessage .= $result['message'];
			}
			//验证文件夹名是否存在
			if(in_array($conditions['foldername'], Core_Comm_Util::getFolderList(ROOT.Model_Mb_Plugin::PLUGIN_DIR)))
			{
				//运行用户自定义初始化函数
				$call = array('Plugin_'.ucfirst($conditions['foldername']).'_Index', 'setup');
            	if (class_exists('Plugin_'.ucfirst($conditions['foldername']).'_Index'))
            		if(is_callable($call))
            			if(!call_user_func($call))
            				$errorMessage .= '自定义初始化失败<br>';
			}
			else
			{
				$errorMessage .= '非法文件夹名<br>';
			}
			if(empty($errorMessage))
			{
				$pluginInfo = array('name' => $conditions['name']
									,'foldername' => $conditions['foldername']
									,'useable' => $conditions['useable']
									,'visible' => $conditions['visible']
									,'usehack' => $conditions['usehack']
									,'orderkey' => $conditions['orderkey']
									);
				if($pluginModel->addPlugin($pluginInfo))
				{
					//清除缓存
					$pluginModel->deletePluginCache();
					$this->showmsg('插件安装成功', 'admin/plugin/installed');
				}
				else 
				{
					$this->showmsg('插件安装失败', $errorBackUrl);
				}
			}
			else 
			{
				$this->showmsg($errorMessage, $errorBackUrl);
			}
		}
		//从queryString取得数据
		$conditionstr = trim($this->getParam('conditions'));
		$code = trim($this->getParam('code'));
		if(!empty($code) && strlen($conditionstr) > 0 && md5($conditionstr.$securecode)==$code) 
		{
			$tmpconditions = explode('||', $conditionstr);
			$conditions['name'] = trim($tmpconditions[0]);
			$conditions['foldername'] = trim($tmpconditions[1]);
			$conditions['useable'] = intval($tmpconditions[2]);
			$conditions['visible'] = intval($tmpconditions[3]);
			$conditions['usehack'] = intval($tmpconditions[4]);
			$conditions['orderkey'] = intval($tmpconditions[5]);
		}
		else
		{
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$conditions['orderkey'] = 0;
		}
		$this->assign('conditions', $conditions);
		
		$this->display('admin/plugin_install.tpl');
	}
	
	/**
	 * 编辑插件
	 */
	public function editAction()
	{
		//queryString加密码
		$securecode = 'adminpluginedit';
		$pluginModel = new Model_Mb_Plugin();
		$conditions['id'] = intval($this->getParam('id'));
		if(trim($this->getParam('action'))=='edit')
		{
			$conditions['name'] = trim($this->getParam('name'));
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$conditions['useable'] = intval($this->getParam('useable'));
			$conditions['visible'] = intval($this->getParam('visible'));
			$conditions['usehack'] = intval($this->getParam('usehack'));
			$conditions['orderkey'] = intval($this->getParam('orderkey'));
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			$errorBackUrl = '/admin/plugin/edit/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			//验证表单取得的数据
			$errorMessage = '';
			$checkResult = array();
			$checkResult[] = $pluginModel->checkName($conditions['name'], $conditions['id']);
			$checkResult[] = $pluginModel->checkFolderName($conditions['foldername'], $conditions['id']);
			foreach ($checkResult as $result)
			{
				!empty($result) && $errorMessage .= $result['message'];
			}
			if(empty($errorMessage))
			{
				$pluginInfo = array('id' => $conditions['id']
									,'name' => $conditions['name']
									,'foldername' => $conditions['foldername']
									,'useable' => $conditions['useable']
									,'visible' => $conditions['visible']
									,'usehack' => $conditions['usehack']
									,'orderkey' => $conditions['orderkey']
									);
				if($pluginModel->editPluginInfo($pluginInfo))
				{
					//清除缓存
					$pluginModel->deletePluginCache();
					$this->showmsg('插件信息更新成功', 'admin/plugin/installed');
				}
				else 
				{
					$this->showmsg('插件信息更新失败', $errorBackUrl);
				}
			}
			else 
			{
				$this->showmsg($errorMessage, $errorBackUrl);
			}
		}
		//从queryString取得数据
		$conditionstr = trim($this->getParam('conditions'));
		$code = trim($this->getParam('code'));
		if(!empty($code) && strlen($conditionstr) > 0 && md5($conditionstr.$securecode)==$code) 
		{
			$tmpconditions = explode('||', $conditionstr);
			$conditions['id'] = intval($tmpconditions[0]);
			$conditions['name'] = trim($tmpconditions[1]);
			$conditions['foldername'] = trim($tmpconditions[2]);
			$conditions['useable'] = intval($tmpconditions[3]);
			$conditions['visible'] = intval($tmpconditions[4]);
			$conditions['usehack'] = intval($tmpconditions[5]);
			$conditions['orderkey'] = intval($tmpconditions[6]);
		}
		else 
		{
			$conditions = $pluginModel->getPluginInfoFromDbById($conditions['id']);
		}
		$this->assign('conditions', $conditions);
		
		$this->display('admin/plugin_edit.tpl');
	}
	
	/**
	 * 已安装插件
	 */
	public function installedAction()
	{
		$pluginModel = new Model_Mb_Plugin();
		if(trim($this->getParam('action'))=='installed')
		{
			$deleteids = is_array($this->getParam('style_delete')) ? $this->getParam('style_delete') : array();
			foreach ($deleteids as $id)
			{
				$pluginModel->deletePluginById(intval($id));
			}
			//清除缓存
			$pluginModel->deletePluginCache();
			$this->showmsg('插件更新成功');
		}
		//插件数量
		$pluginCount = $pluginModel->getPluginCount();
		$this->assign('pluginCount', $pluginCount);
		//分页
		$perpage = 10;
		$curpage = $this->getParam('page') ? intval($this->getParam('page')) : 1;
		$mpurl = '/admin/plugin/installed/';
		$multipage = $this->multipage($pluginCount, $perpage, $curpage, $mpurl);
		$this->assign('multipage', $multipage);
		//插件列表
		$pluginList = $pluginModel->getPluginListFromDb(null, 'orderkey', array($perpage, $perpage*($curpage-1)));
		//插件文件夹列表
		$folderList = Core_Comm_Util::getFolderList(ROOT.Model_Mb_Plugin::PLUGIN_DIR);
		foreach ($pluginList as $key=>$plugin)
		{
			!in_array($plugin['foldername'], $folderList) && $pluginList[$key]['error'] = 1;
		}
		$this->assign('styles', $pluginList);
		
		$this->display('admin/plugin_installed.tpl');
	}
	
	/**
	 * 未安装插件
	 */
	public function notinstalledAction()
	{
		$pluginModel = new Model_Mb_Plugin();
		//插件列表
		$pluginList = $pluginModel->getPluginListFromDb(null, 'orderkey');
		$pluginArr = array();
		foreach ($pluginList as $plugin)
		{
			$pluginArr[] = $plugin['foldername'];
		}
		//插件文件夹列表
		$folderList = Core_Comm_Util::getFolderList(ROOT.Model_Mb_Plugin::PLUGIN_DIR);
		$folderArr = array();
		foreach ($folderList as $folder)
		{
			!in_array($folder, $pluginArr) && $folderArr[] = $folder;
		}
		$this->assign('folderArr', $folderArr);
		
		$this->display('admin/plugin_notinstalled.tpl');
	}
}