<?php
/**
 * 皮肤管理
 *
 * @author lvfeng
 */
class Controller_Admin_Skin extends Core_Controller_Action
{
	/**
	 * 编辑皮肤
	 */
	public function editAction()
	{
		//queryString加密码
		$securecode = 'adminskinedit';
		$skinModel = new Model_Mb_Skin();
		$conditions['id'] = intval($this->getParam('id'));
		if(trim($this->getParam('action'))=='edit')
		{
			$conditions['name'] = trim($this->getParam('name'));
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$conditions['orderkey'] = intval($this->getParam('orderkey'));
			$conditions['useable'] = intval($this->getParam('useable'));
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			$errorBackUrl = '/admin/skin/edit/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			//验证表单取得的数据
			$errorMessage = '';
			$checkResult = array();
			$checkResult[] = $skinModel->checkName($conditions['name'], $conditions['id']);
			$checkResult[] = $skinModel->checkFolderName($conditions['foldername'], $conditions['id']);
			foreach ($checkResult as $result)
			{
				!empty($result) && $errorMessage .= $result['message'];
			}
			if(empty($errorMessage))
			{
				$skinInfo = array(	'id' => $conditions['id']
									,'name' => $conditions['name']
									,'foldername' => $conditions['foldername']
									,'orderkey' => $conditions['orderkey']
									,'useable' => $conditions['useable']
									);
				//缩略图
				$thumb = Core_Util_Upload::upload('thumb');
				if($thumb['code']==0)
				{
					$skinInfo['thumb'] = $thumb['link'];
				}
				
				if($skinModel->editSkinInfo($skinInfo))
				{
					//清除缓存
					$skinModel->deleteSkinCache();
					$this->showmsg('皮肤信息更新成功', 'admin/skin/installed');
				}
				else 
				{
					$this->showmsg('皮肤信息更新失败', $errorBackUrl);
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
			$conditions['orderkey'] = intval($tmpconditions[3]);
			$conditions['useable'] = intval($tmpconditions[4]);
		}
		else 
		{
			$conditions = $skinModel->getSkinInfoFromDbById($conditions['id']);
		}
		$this->assign('conditions', $conditions);
		
		$this->display('admin/skin_edit.tpl');
	}
	
	/**
	 * 安装皮肤
	 */
	public function installAction()
	{
		//queryString加密码
		$securecode = 'adminskininstall';
		$skinModel = new Model_Mb_Skin();
		if(trim($this->getParam('action'))=='install')
		{
			$conditions['name'] = trim($this->getParam('name'));
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$thumb = Core_Util_Upload::upload('thumb');
			$conditions['orderkey'] = intval($this->getParam('orderkey'));
			$conditions['useable'] = intval($this->getParam('useable'));
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			$errorBackUrl = '/admin/skin/install/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			//验证表单取得的数据
			$errorMessage = '';
			$checkResult = array();
			$checkResult[] = $skinModel->checkName($conditions['name']);
			$checkResult[] = $skinModel->checkFolderName($conditions['foldername']);
			foreach ($checkResult as $result)
			{
				!empty($result) && $errorMessage .= $result['message'];
			}
			if(empty($errorMessage))
			{
				$skinInfo = array(	'name' => $conditions['name']
									,'foldername' => $conditions['foldername']
									,'thumb' => $thumb['link']
									,'orderkey' => $conditions['orderkey']
									,'useable' => $conditions['useable']
									);
				if($skinModel->addSkin($skinInfo))
				{
					//清除缓存
					$skinModel->deleteSkinCache();
					$this->showmsg('皮肤安装成功', 'admin/skin/installed');
				}
				else 
				{
					$this->showmsg('皮肤安装失败', $errorBackUrl);
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
			$conditions['orderkey'] = intval($tmpconditions[2]);
			$conditions['useable'] = intval($tmpconditions[3]);
		}
		else
		{
			$conditions['foldername'] = trim($this->getParam('foldername'));
			$conditions['orderkey'] = 0;
		}
		$this->assign('conditions', $conditions);
		
		$this->display('admin/skin_install.tpl');
	}
	
	/**
	 * 已安装皮肤
	 */
	public function installedAction()
	{
		$skinModel = new Model_Mb_Skin();
		if(trim($this->getParam('action'))=='installed')
		{
			$deleteids = is_array($this->getParam('style_delete')) ? $this->getParam('style_delete') : array();
			foreach ($deleteids as $id)
			{
				$skinModel->deleteSkinById(intval($id));
			}
			//清除缓存
			$skinModel->deleteSkinCache();
			$this->showmsg('皮肤列表更新成功');
		}
		//皮肤数量
		$skinCount = $skinModel->getSkinCount();
		$this->assign('skinCount', $skinCount);
		//分页
		$perpage = 10;
		$curpage = $this->getParam('page') ? intval($this->getParam('page')) : 1;
		$mpurl = '/admin/skin/installed/';
		$multipage = $this->multipage($skinCount, $perpage, $curpage, $mpurl);
		$this->assign('multipage', $multipage);
		//皮肤列表
		$skinList = $skinModel->getSkinListFromDb(null, 'orderkey desc', array($perpage, $perpage*($curpage-1)));
		//皮肤文件夹列表
		$folderList = Core_Comm_Util::getFolderList(ROOT.Model_Mb_Skin::VIEW_DIR);
		foreach ($skinList as $key=>$skin)
		{
			!in_array($skin['foldername'], $folderList) && $skinList[$key]['error'] = 1;
		}
		$this->assign('styles', $skinList);
		
		$this->display('admin/skin_installed.tpl');
	}
	
	/**
	 * 未安装皮肤
	 */
	public function notinstalledAction()
	{
		$skinModel = new Model_Mb_Skin();
		//皮肤列表
		$skinList = $skinModel->getSkinListFromDb(null, 'orderkey desc');
		$skinArr = array();
		foreach ($skinList as $skin)
		{
			$skinArr[] = $skin['foldername'];
		}
		//皮肤文件夹列表
		$folderList = Core_Comm_Util::getFolderList(ROOT.Model_Mb_Skin::VIEW_DIR);
		$folderArr = array();
		foreach ($folderList as $folder)
		{
			!in_array($folder, $skinArr) && $folderArr[] = $folder;
		}
		$this->assign('folderArr', $folderArr);
		
		$this->display('admin/skin_notinstalled.tpl');
	}
}