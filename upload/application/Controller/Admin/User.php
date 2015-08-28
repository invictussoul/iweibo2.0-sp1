<?php
/**
 * 用户设置
 *
 * @author lvfeng
 */
class Controller_Admin_User extends Core_Controller_Action
{
	/**
	 * 添加用户
	 */
	public function addAction()
	{
		//queryString加密码
		$securecode = 'adminuseradd';
		$memberModel = new Model_User_Member();
		if (trim($this->getParam('action'))=='add')
		{
			$conditions['username'] = trim($this->getParam('username'));
			$conditions['nickname'] = trim($this->getParam('nickname'));
			$password = trim($this->getParam('password'));
			$conditions['email'] = trim($this->getParam('email'));
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			$errorBackUrl = '/admin/user/add/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			//验证表单取得的数据
			$errorMessage = '';
			$checkResult = array();
			$checkResult[] = $this->checkUsername($memberModel, $conditions['username']);
			$checkResult[] = $this->checkNickName($conditions['nickname']);
			$checkResult[] = $this->checkPassword($password);
			$checkResult[] = $this->checkEmail($memberModel, $conditions['email']);
			foreach ($checkResult as $result)
			{
				!empty($result) && $errorMessage .= $result['message'];
			}
			if(empty($errorMessage))
			{
				//如果使用UC安装
		        if(Core_Config::get('useuc','basic',false))
		        {
		        	//验证UC邮箱
		        	if(Core_Outapi_Uc::call('user_checkemail', $conditions['email']) > 0)
		        	{
		        		//UC注册
		        		$uid = Core_Outapi_Uc::call('user_register', $conditions['username'], $password, $conditions['email']);
		        		//UC注册成功
						if($uid > 0) 
						{
							//自动注册到本地
							if($memberModel->onAutoRegister($uid, $conditions['username'], $conditions['email']))
							{
								$memberModel->editUserInfo(array('uid'=>$uid, 'nickname'=>$conditions['nickname']));
								$this->showmsg('添加用户成功', 'admin/user/search');
							}
							else
							{
								$this->showmsg('添加本地用户失败', $errorBackUrl);
							}
						}
						elseif($uid == -3) 
						{
				            $this->showmsg('用户名已被注册！', $errorBackUrl);
						} 
						else
						{
							 $this->showmsg('添加用户失败', $errorBackUrl);
						}
		        	}
		        	else 
		        	{
		        		$this->showmsg('邮箱已被注册！', $errorBackUrl);
		        	}
		        }
		        else
		        {
					//加密密码
					$salt = $memberModel->getSalt();
					$password = $memberModel->formatPassword($password, $salt);
					$userInfo = array(	'username' => $conditions['username']
										,'nickname' => $conditions['nickname']
										,'password' => $password
										,'salt' => $salt
										,'email' => $conditions['email']
										,'regtime' => time()
										,'regip' => Core_Comm_Util::getClientIp()
										,'lastvisit' => time()
										,'lastip' => Core_Comm_Util::getClientIp()
										);
					if($memberModel->addUser($userInfo))
					{
						$this->showmsg('添加用户成功', 'admin/user/search');
					}
					else 
					{
						$this->showmsg('添加用户失败', $errorBackUrl);
					}
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
			$conditions['username'] = trim($tmpconditions[0]);
			$conditions['nickname'] = trim($tmpconditions[1]);
			$conditions['email'] = trim($tmpconditions[2]);
		}
		$this->assign('conditions', $conditions);
		
		$this->display('admin/user_add.tpl');
	}
	
	/**
	 * 编辑用户
	 */
	public function editAction()
	{
		//queryString加密码
		$securecode = 'adminuseredit';
		$memberModel = new Model_User_Member();
		$groupModel = new Model_User_Group();
		$conditions['uid'] = intval($this->getParam('uid'));
		if (trim($this->getParam('action'))=='edit')
		{
			$conditions['username'] = trim($this->getParam('username'));
			$password = trim($this->getParam('password'));
			$conditions['nickname'] = trim($this->getParam('nickname'));
			$conditions['gid'] = intval($this->getParam('gid'));
			$conditions['gender'] = intval($this->getParam('gender'));
			$conditions['birthyear'] = intval($this->getParam('birthyear'));
			$conditions['birthmonth'] = intval($this->getParam('birthmonth'));
			$conditions['birthday'] = intval($this->getParam('birthday'));
			$conditions['homenation'] = trim($this->getParam('homenation'));
			$conditions['homeprovince'] = trim($this->getParam('homeprovince'));
			$conditions['homecity'] = trim($this->getParam('homecity'));
			$conditions['nation'] = trim($this->getParam('nation'));
			$conditions['province'] = trim($this->getParam('province'));
			$conditions['city'] = trim($this->getParam('city'));
			$conditions['occupation'] = intval($this->getParam('occupation'));
			$conditions['email'] = trim($this->getParam('email'));
			$conditions['mobile'] = trim($this->getParam('mobile'));
			$conditions['homepage'] = trim($this->getParam('homepage'));
			$conditions['summary'] = trim($this->getParam('summary'));
			$conditions['regip'] = trim($this->getParam('regip'));
			$conditions['regtime'] = strtotime(trim($this->getParam('regtime')));
			$conditions['lastip'] = trim($this->getParam('lastip'));
			$conditions['lastvisit'] = strtotime(trim($this->getParam('lastvisit')));
			$userInfo = $conditions;
			//将从表单取得的数据拼接到queryString
			$conditionstr = implode('||', $conditions);
			$md5str = md5($conditionstr.$securecode);
			//个人首页转码后将从表单取得的数据拼接到queryString
			$conditions['homepage'] = Core_fun::iurlencode(trim($this->getParam('homepage')));
			$conditionstr = implode('||', $conditions);
			$errorBackUrl = '/admin/user/edit/conditions/'.$conditionstr.'/code/'.$md5str.'/';
			
			if(strlen($password) > 0)
			{
				$userInfo['salt'] = $memberModel->getSalt();
				$userInfo['password'] = $memberModel->formatPassword($password, $userInfo['salt']);
				//如果使用UC安装
				if(Core_Config::get('useuc','basic',false)){
					if(Core_Outapi_Uc::call('user_edit', $userInfo['username'], '', $password, '', 1) < 0)
						$this->showmsg('密码修改失败', $errorBackUrl);
				}
			}
			//如果使用UC安装
	        if(Core_Config::get('useuc','basic',false))
	        {
	        	$ucrt = Core_Outapi_Uc::call('user_edit', $userInfo['username'], '', '', $userInfo['email'], 1);
	        	if($ucrt < 0)
	        	{
	        		if($ucrt == -8)
	        			$this->showmsg('该用户受保护无权限更改！', $errorBackUrl);
	        		$this->showmsg('邮箱已被注册！', $errorBackUrl);
	        	}
	        }
	        if($this->checkEmail($memberModel, $userInfo['email'], $userInfo['uid']))
		        $this->showmsg('邮箱已被注册！', $errorBackUrl);
		    
			if($memberModel->editUserInfo($userInfo))
				$this->showmsg('编辑用户成功', 'admin/user/search');
			else 
				$this->showmsg('编辑用户失败', $errorBackUrl);
		}
		//取得用户组信息列表
		$groupList = $groupModel->getGroupList(null, 'gid');
		foreach ($groupList as $value)
			$usergroups[$value['gid']] = array('type'=>$value['type'], 'title'=>$value['title']);
		$this->assign('usergroups', $usergroups);
		//从queryString取得数据
		$conditionstr = trim($this->getParam('conditions'));
		$code = trim($this->getParam('code'));
		if(!empty($code) && strlen($conditionstr) > 0 && md5($conditionstr.$securecode)==$code) 
		{
			$tmpconditions = explode('||', $conditionstr);
			$conditions['uid'] = intval($tmpconditions[0]);
			$conditions['username'] = trim($tmpconditions[1]);
			$conditions['nickname'] = trim($tmpconditions[2]);
			$conditions['gid'] = intval($tmpconditions[3]);
			$conditions['gender'] = intval($tmpconditions[4]);
			$conditions['birthyear'] = intval($tmpconditions[5]);
			$conditions['birthmonth'] = intval($tmpconditions[6]);
			$conditions['birthday'] = intval($tmpconditions[7]);
			$conditions['homenation'] = trim($tmpconditions[8]);
			$conditions['homeprovince'] = trim($tmpconditions[9]);
			$conditions['homecity'] = trim($tmpconditions[10]);
			$conditions['nation'] = trim($tmpconditions[11]);
			$conditions['province'] = trim($tmpconditions[12]);
			$conditions['city'] = trim($tmpconditions[13]);
			$conditions['occupation'] = intval($tmpconditions[14]);
			$conditions['email'] = trim($tmpconditions[15]);
			$conditions['mobile'] = trim($tmpconditions[16]);
			$conditions['homepage'] = trim($tmpconditions[17]);
			$conditions['summary'] = trim($tmpconditions[18]);
			$conditions['regip'] = trim($tmpconditions[19]);
			$conditions['regtime'] = trim($tmpconditions[20]);
			$conditions['lastip'] = trim($tmpconditions[21]);
			$conditions['lastvisit'] = trim($tmpconditions[22]);
		}
		else 
		{
			$conditions = $memberModel->getUserInfoByUid($conditions['uid']);
			$conditions['birthyear'] = empty($conditions['birthyear']) ? 1900 : $conditions['birthyear'];
			$conditions['birthmonth'] = empty($conditions['birthmonth']) ? 1 : $conditions['birthmonth'];
			$conditions['birthday'] = empty($conditions['birthday']) ? 1 : $conditions['birthday'];
		}
		$this->assign('conditions', $conditions);
		
		//取得个人设置配置信息
    	$setting = include CONFIG_PATH . 'setting.php';
    	$this->assign('setting', $setting);
		
		$this->display('admin/user_edit.tpl');
	}
	
	/**
	 * 搜索用户
	 */
	public function searchAction()
	{
		//queryString加密码
		$securecode = 'adminusersearch';
		$memberModel = new Model_User_Member();
		$groupModel = new Model_User_Group();
		//取得数据
		$conditionstr = trim($this->getParam('conditions'));
		$code = trim($this->getParam('code'));
		if(!empty($code) && strlen($conditionstr) > 0 && md5($conditionstr.$securecode)==$code) 
		{
			$tmpconditions = explode('||', $conditionstr);
			$conditions['type'] = trim($tmpconditions[0]);
			$conditions['keyword'] = trim($tmpconditions[1]);
			$conditions['gid'] = intval($tmpconditions[2]);
			$conditions['gender'] = intval($tmpconditions[3]);
			$conditions['regdate'] = intval($tmpconditions[4]);
			$conditions['lastvisit'] = intval($tmpconditions[5]);
		}
		else
		{
			$conditions['type'] = trim($this->getParam('type'));
			$conditions['keyword'] = trim($this->getParam('keyword'));
			$conditions['gid'] = intval($this->getParam('gid'));
			$conditions['gender'] = intval($this->getParam('gender'));
			$conditions['regdate'] = intval($this->getParam('regdate'));
			$conditions['lastvisit'] = intval($this->getParam('lastvisit'));
		}
		$whereArr = array();
		!empty($conditions['keyword']) && $whereArr[] = array($conditions['type'], $conditions['keyword'], 'LIKE');
		!empty($conditions['gid']) && $whereArr[] = array('gid', $conditions['gid']);
		!empty($conditions['gender']) && $whereArr[] = array('gender', $conditions['gender']);
		switch ($conditions['regdate'])
		{
			case 1:
				$whereArr[] = array('regtime', strtotime('-7 day'), '>');
				break;
			case 2:
				$whereArr[] = array('regtime', strtotime('-14 day'), '>');
				break;
			case 3:
				$whereArr[] = array('regtime', strtotime('-30 day'), '>');
				break;
			case 4:
				$whereArr[] = array('regtime', strtotime('-180 day'), '>');
				break;
			case 5:
				$whereArr[] = array('regtime', strtotime('-365 day'), '>');
				break;
			case 6:
				$whereArr[] = array('regtime', strtotime('-365 day'), '<=');
				break;
			default :
				break;
		}
		switch ($conditions['lastvisit'])
		{
			case 1:
				$whereArr[] = array('lastvisit', strtotime('-7 day'), '>');
				break;
			case 2:
				$whereArr[] = array('lastvisit', strtotime('-14 day'), '>');
				break;
			case 3:
				$whereArr[] = array('lastvisit', strtotime('-30 day'), '>');
				break;
			case 4:
				$whereArr[] = array('lastvisit', strtotime('-180 day'), '>');
				break;
			case 5:
				$whereArr[] = array('lastvisit', strtotime('-365 day'), '>');
				break;
			case 6:
				$whereArr[] = array('lastvisit', strtotime('-365 day'), '<=');
				break;
			default :
				break;
		}
		//用户数量
		$userCount = $memberModel->getUserCount($whereArr);
		$this->assign('userscount', $userCount);
		//分页
		$perpage = 20;
		$curpage = $this->getParam('page') ? intval($this->getParam('page')) : 1;
		$conditionstr = implode('||', $conditions);
		$md5str = md5($conditionstr.$securecode);
		$mpurl = '/admin/user/search/conditions/'.$conditionstr.'/code/'.$md5str.'/';
		$multipage = $this->multipage($userCount, $perpage, $curpage, $mpurl);
		$this->assign('multipage', $multipage);
		//用户列表
		$userList = $memberModel->getUserList($whereArr, 'uid desc', array($perpage, $perpage*($curpage-1)));
		$this->assign('users', $userList);
		//用户组列表
		$groupList = $groupModel->getGroupList(null, 'gid');
		foreach ($groupList as $value)
		{
			$usergroups[$value['gid']] = array('type'=>$value['type'], 'title'=>$value['title']);
		}
		$this->assign('usergroups', $usergroups);
		//查询条件
		$this->assign('conditions', $conditions);
		
		$this->display('admin/user_search.tpl');
	}
	
	/**
	 * 屏蔽用户
	 */
	public function shieldAction()
	{
		$memberModel = new Model_User_Member();
		$gid = intval($this->getParam('gid'));
		$uid = intval($this->getParam('uid'));
		if($memberModel->editUserInfo(array('uid'=>$uid, 'gid'=>$gid)))
			$this->showmsg('操作成功');
		else
			$this->showmsg('操作失败');
	}
	
	/**
	 * 删除用户
	 */
//	public function deleteAction()
//	{
//		$memberModel = new Model_User_Member();
//		$deleteids = is_array($this->getParam('deleteids')) ? $this->getParam('deleteids') : array();
//		$ucrt=1;
//		//如果使用UC安装
//        if(Core_Config::get('useuc','basic',false))
//        {
//        	$ucrt = Core_Outapi_Uc::call('user_delete', $deleteids);
//        }
//        if($ucrt){
//			foreach ($deleteids as $id)
//			{
//				$memberModel->deleteUserByUid(intval($id));
//			}
//        }
//		$ucrt && $this->showmsg('用户列表更新成功');
//	}

	/**
     * 输出国家JSON列表
     */
	public function getnationAction($citys)
    {
    	$citys = Core_Comm_City::$cityConfig;
    	foreach ($citys as $key => $value)
    	{
    		$list[$key] = $value['name'];
    	}
    	echo json_encode($list);
    }
    
    /**
     * 输出省份JSON列表
     */
    public function getprovinceAction()
    {
    	$citys = Core_Comm_City::$cityConfig;
    	$nationIndex = trim($this->getParam('nation'));
    	if(!empty($nationIndex))
    	{
			foreach ($citys[$nationIndex]['province'] as $key => $value)
	    	{
	    		$list[$key] = $value['name'];
	    	}
	    	echo json_encode($list);
    	}
    }
    
    /**
     * 输出城市JSON列表
     */
    public function getcityAction()
    {
    	$citys = Core_Comm_City::$cityConfig;
    	$nationIndex = trim($this->getParam('nation'));
    	$provinceIndex = trim($this->getParam('province'));
    	if(!empty($nationIndex)) 
    	{
			foreach ($citys[$nationIndex]['province'][$provinceIndex]['city'] as $key => $value)
	    	{
	    		$list[$key] = $value;
	    	}
	    	echo json_encode($list);
    	}
    }
    
	/**
	 * 验证字符串长度
	 *
	 * @param string $str
	 * @param int $minlen
	 * @param int $maxlen
	 * @return boolean
	 */
	public function checkLength($str, $minlen, $maxlen) 
	{
		$len = strlen($str);
		if($len > $maxlen || $len < $minlen) 
		{
			return 1;
		}
		return 0;
	}
	
	/**
	 * 验证用户名
	 *
	 * @param string $username
	 * @return array
	 */
	public function checkUsername($memberModel, $username) 
	{
		$minlen = 1;
		$maxlen = 32;
		if($this->checkLength($username, $minlen, $maxlen)) 
		{
			return array('errorcode'=>'1', 'message'=>'用户名长度不符合字符数'.$minlen.'-'.$maxlen.'的要求<br>');
		}
		if($memberModel->checkUsernameExists($username)) 
		{
			return array('errorcode'=>'2', 'message'=>'用户名已存在<br>');
		}
		return 0;
	}
	
	/**
	 * 验证昵称
	 *
	 * @param string $nickname
	 * @return array
	 */
	public function checkNickName($nickname) 
	{
		$minlen = 1;
		$maxlen = 32;
		if($this->checkLength($nickname, $minlen, $maxlen)) 
		{
			return array('errorcode'=>'3', 'message'=>'昵称长度不符合字符数'.$minlen.'-'.$maxlen.'的要求<br>');
		}
		return 0;
	}
	
	/**
	 * 验证密码
	 *
	 * @param string $password
	 * @return array
	 */
	public function checkPassword($password) 
	{
		if(strlen($password) < 1)
		{
			return array('errorcode'=>'4', 'message'=>'密码不能为空<br>');
		}
		return 0;
	}
	
	/**
	 * 验证邮箱
	 *
	 * @param string $email
	 * @return array
	 */
	public function checkEmail($memberModel, $email, $uid=0) 
	{
		if($memberModel->checkEmailExists($email, $uid)) 
		{
			return array('errorcode'=>'2', 'message'=>'邮箱已被注册<br>');
		}
		return 0;
	}
}
