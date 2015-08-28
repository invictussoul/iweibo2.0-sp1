<?php
/**
 * 组设置
 *
 * @author lvfeng
 */
class Controller_Admin_Group extends Core_Controller_Action
{
	/**
	 * 管理组权限
	 */
	public function accessAction()
	{
		$groupModel = new Model_User_Group();
		$gid = intval($this->getParam('gid'));
		if (trim($this->getParam('action'))=='access')
		{
			$accessnew = is_array($this->getParam('accessnew')) ? $this->getParam('accessnew') : array();
			$accessArr = array();
			foreach ($accessnew as $value)
			{
				$accessArr[trim($value)] = 1;
			}
			Model_User_Access::delAccess($gid);
			Model_User_Access::setAccess($accessArr, $gid);
			$this->showmsg('编辑组权限成功', 'admin/group/access/gid/'.$gid);
		}
		//取得组信息
		$group = $groupModel->getGroupInfoByGid($gid);
		$this->assign('group', $group);
		//取得组权限
		$accessArr = Model_User_Access::getAccess($gid);
		$accessList = array();
		foreach ((array)$accessArr as $key => $value)
		{
			$value && $accessList[] = $key;
		}
		//取得权限列表
		$authTree = include CONFIG_PATH . 'authtree.php';
		$this->assign('user_checkboxes', $authTree['user']);
		$this->assign('user_checked', $accessList);
		$this->assign('admin_checkboxes', $authTree['admin']);
		$this->assign('admin_checked', $accessList);
		
		$this->display('admin/group_access.tpl');
	}
	
	/**
	 * 管理组
	 */
	public function manageAction()
	{
		$groupModel = new Model_User_Group();
		if(trim($this->getParam('action'))=='manage')
		{
			$errorMessage = '';
			$deleteids = is_array($this->getParam('deleteids')) ? $this->getParam('deleteids') : array();
			foreach ($deleteids as $id)
			{
				$groupModel->deleteGroupByGid(intval($id));
			}
			
			$titlenew = is_array($this->getParam('titlenew')) ? $this->getParam('titlenew') : array();
			foreach($titlenew as $gid => $title) 
			{
				$gid = intval($gid);
				$title = trim($title);
				//验证表单取得的数据
				$editErrorMessage = '';
				$checkResult = array();
				$checkResult[] = $this->checkGroupTitle($title, $gid, $groupModel);
				foreach ($checkResult as $result)
				{
					!empty($result) && $editErrorMessage .= '编辑'.$title.'组失败，'.$result['message'];
				}
				if(empty($editErrorMessage))
				{
					$groupModel->editGroupInfo(array('gid'=>$gid, 'title'=>$title));
				}
				$errorMessage .= $editErrorMessage;
			}
			
			if($title = trim($this->getParam('newtitle')))
			{
				//验证表单取得的数据
				$addErrorMessage = '';
				$checkResult = array();
				$checkResult[] = $this->checkGroupTitle($title, null, $groupModel);
				foreach ($checkResult as $result)
				{
					!empty($result) && $addErrorMessage .= '添加'.$title.'组失败，'.$result['message'];
				}
				if(empty($addErrorMessage))
				{
					$groupModel->addGroup(array('title'=>$title));
				}
				$errorMessage .= $addErrorMessage;
			}
			
			$this->showmsg(!empty($errorMessage) ? $errorMessage : '更新组列表成功');
		}
		//取得组列表
		$groupList = $groupModel->getGroupList(null, 'gid');
		foreach ($groupList as $value)
		{
			$usergroups[$value['gid']] = array('type'=>$value['type'], 'title'=>$value['title']);
		}
		
		$userModel = new Model_User_Member();
		foreach ($usergroups as $key=>$value)
		{
			$usergroups[$key]['usernum'] = $userModel->getUserCount(array(array('gid', $key)));
			$conditionstr = 'nickname||||'.$key.'||0||0||0';
			$code = md5($conditionstr.'adminusersearch');
			$usergroups[$key]['url'] = '/admin/user/search/conditions/'.$conditionstr.'/code/'.$code;
		}
		$this->assign('usergroups', $usergroups);
		
		$this->display('admin/group_manage.tpl');
	}
	
	/**
	 * 验证标题
	 *
	 * @param string $title
	 * @param int $gid
	 * @return array
	 */
	public function checkGroupTitle($title, $gid=0, $groupModel)
	{
//		if(strlen($title) < 2) 
//		{
//			return array('errorcode'=>'1', 'message'=>'组名长度字符数不能小于2<br>');
//		}
		if($groupModel->checkTitleExists($title, $gid)) 
		{
			return array('errorcode'=>'2', 'message'=>'组名已存在<br>');
		}
		return 0;
	}
}