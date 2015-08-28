<?php
/**
 * iweibo2.0
 * 
 * 个人设置
 *
 * @author lvfeng
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Setting.php 2011/05/26
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Setting extends Core_Controller_TAction
{
    
    private $userModel;

    public function preDispatch()
    {
        parent::preDispatch();
        $this->userModel = new Model_User_Member();
    }

    /**
     * 修改头像页
     */
    public function faceAction()
    {
        $action = 'face';
        $this->assign('action', $action);
        
        $uploadType = intval($this->getParam('ut'));
        $uploadType && $this->assign('uploadType', $uploadType);
        
        $msgCode = intval($this->getParam('msg'));
        $msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
        
        $userhead_src = array_key_exists('head', $this->userInfo) && ! empty($this->userInfo['head']) ? $this->userInfo['head'] : '/resource/images/default_head_100.jpg';
        $this->assign('userhead_src', $userhead_src);
        
        $this->assign('time', time());
        
        $tabArr = array(array('url' => '/setting', 'title' => '个人资料'), array('url' => '/tag', 'title' => '个人标签'));
        $tabbar = Core_Lib_Base::formatTab($tabArr, 0);
        $this->assign('tabbar', $tabbar);
        
        $this->display('user/setting.tpl');
    }

	/**
     * 取消授权
     */
    public function accreditAction() 
    {
        $errorBackUrl = 'setting/accredit/msg/';

        $uid = $this->userInfo['uid'];
        if (!empty($uid) && trim($this->getParam('change')) == 'do') {
            //取得当前用户信息
            $cUser = $this->userModel->onGetCurrentUser();
            //删除原有的绑定token信息
            $this->userModel->onBindAccessToken($cUser['uid'], '', '', '');
            //绑定后清除本地缓存
            $name && Model_User_Local::delCache($name);
            //退出登录
            $this->userModel->onLogout();
            //如果使用UC安装
            if (Core_Config::get('useuc', 'basic', false)) {
                //生成同步登出的代码
                $_SESSION['ucsynlogout'] = 1;
            }
            $this->showmsg('', 'login', 0);
        }
        $userhead_src = array_key_exists('head', $this->userInfo) && !empty($this->userInfo['head']) ? $this->userInfo['head'] : '/resource/images/default_head_100.jpg';
        $this->assign('action', 'accredit');
        $this->assign('userhead_src', $userhead_src);
        $this->assign('username', $this->userInfo['name']);
        $this->display('user/accredit.tpl');
    }

    /**
     * 修改头像
     */
    public function changefaceAction()
    {
        if(! empty($GLOBALS['HTTP_RAW_POST_DATA']))
        {
            $fileContent = $GLOBALS['HTTP_RAW_POST_DATA'];
            $picType = Core_Comm_Util::getFileType(substr($fileContent, 0, 2));
            $len = strlen($fileContent);
            $name = time() . '.jpg';
            
            $p = array('pic' => array($picType, $name, $fileContent));
            Core_Open_Api::getClient()->updateUserHead($p);
        }
        usleep(100000);
    }

    /**
     * 修改头像
     */
    public function generalchangefaceAction()
    {
        $errorBackUrl = 'setting/face/ut/1/msg/';
        if(Core_Comm_Validator::isUploadFile($_FILES['pic']))
        {
            $fileContent = file_get_contents($_FILES['pic']['tmp_name']);
            $picType = Core_Comm_Util::getFileType(substr($fileContent, 0, 2));
            $len = intval($_FILES['pic']['size']);
            $name = $_FILES['pic']['name'];
            
            if($picType != 'jpg' && $picType != 'gif' && $picType != 'png')
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PIC_TYPE, 0);
            
            if($len < 2 || $len > 2 * 1024 * 1024)
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PIC_SIZE, 0);
            
            $p = array('pic' => array($picType, $name, $fileContent));
            $rt = Core_Open_Api::getClient()->updateUserHead($p);
            if($rt['ret'] == 0)
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_FACE_UPLOADSUCCEED, 0);
        }
        $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_FACE_UPLOADFAILED, 0);
    }

    /**
     * 修改密码
     */
    public function changepwdAction()
    {
        $errorBackUrl = 'setting/changepwd/msg/';
        
        $msgCode = intval($this->getParam('msg'));
        $msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
        
        $uid = $this->userInfo['uid'];
        
        $action = 'changepwd';
        $this->assign('action', $action);
        
        if(! empty($uid) && trim($this->getParam('action')) == $action)
        {
            $user = $this->userModel->getUserInfoByUid(intval($uid));
            
            $oldpwd = trim($this->getParam('oldpwd'));
            $pwd = trim($this->getParam('pwd'));
            $pwdconfirm = trim($this->getParam('pwdconfirm'));
            
            if(! Model_User_Validator::checkPassword($pwd))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_FORMATERROR, 0);
            if($pwd != $pwdconfirm)
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_DOESNOTMATCH, 0);
            
     //如果使用UC安装
            if(Core_Config::get('useuc', 'basic', false))
            {
                $ucrt = Core_Outapi_Uc::call('user_edit', $user['username'], $oldpwd, $pwd);
                if($ucrt >= 0)
                    $this->showmsg('', 'setting/index/msg/' . Core_Comm_Modret::RET_PASSWORD_CHANGESUCCEED, 0);
                elseif($ucrt == - 1)
                    $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_OLDPASSWORDDOESNOTMATCH, 0);
                else
                    $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_CHANGEFAILED, 0);
            }
            else
            {
                if(! $this->userModel->checkUserOldPassword($user['username'], $oldpwd))
                    $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_OLDPASSWORDDOESNOTMATCH, 0);
                $conditions['uid'] = intval($uid);
                $conditions['salt'] = $this->userModel->getSalt();
                $conditions['password'] = $this->userModel->formatPassword($pwd, $conditions['salt']);
                //将密码更新到数据库
                if($this->userModel->editUserInfo($conditions))
                    $this->showmsg('', 'setting/index/msg/' . Core_Comm_Modret::RET_PASSWORD_CHANGESUCCEED, 0);
                else
                    $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_PASSWORD_CHANGEFAILED, 0);
            }
        }
        
        $userhead_src = array_key_exists('head', $this->userInfo) && ! empty($this->userInfo['head']) ? $this->userInfo['head'] : '/resource/images/default_head_100.jpg';
        $this->assign('userhead_src', $userhead_src);
        
        $tabArr = array(array('url' => '/setting', 'title' => '个人资料'), array('url' => '/tag', 'title' => '个人标签'));
        $tabbar = Core_Lib_Base::formatTab($tabArr, 0);
        $this->assign('tabbar', $tabbar);
        
        $this->display('user/setting.tpl');
    }

    /**
     * 修改基本资料
     */
    public function indexAction()
    {
        $errorBackUrl = 'setting/index/msg/';
        
        $msgCode = intval($this->getParam('msg'));
        $msgCode && $this->assign('showmsg', Core_Comm_Modret::getMsg($msgCode));
        
        $uid = $this->userInfo['uid'];
        
        $action = 'update';
        $this->assign('action', $action);
        
        $userhead_src = array_key_exists('head', $this->userInfo) && ! empty($this->userInfo['head']) ? $this->userInfo['head'] : '/resource/images/default_head_100.jpg';
        $this->assign('userhead_src', $userhead_src);
        
        //从数据库取得用户信息
        $userInfo = $this->userModel->getUserInfoByUid($uid);
        //如果本地用户昵称为空则将平台用户信息赋给本地
        if(empty($userInfo['nickname']))
        {
        	//从平台取得用户信息
            $user = Core_Open_Api::getClient()->getUserInfo();
            $userInfo['nickname'] = $user['data']['nick'];
            $userInfo['gender'] = $user['data']['sex'];
            $userInfo['birthyear'] = $user['data']['birth_year'];
            $userInfo['birthmonth'] = $user['data']['birth_month'];
            $userInfo['birthday'] = $user['data']['birth_day'];
            $userInfo['nation'] = $user['data']['country_code'];
            $userInfo['province'] = $user['data']['province_code'];
            $userInfo['city'] = $user['data']['city_code'];
            $userInfo['summary'] = $user['data']['introduction'];
        }
        $userInfo['birthyear'] = empty($userInfo['birthyear']) ? 1900 : $userInfo['birthyear'];
		$userInfo['birthmonth'] = empty($userInfo['birthmonth']) ? 1 : $userInfo['birthmonth'];
		$userInfo['birthday'] = empty($userInfo['birthday']) ? 1 : $userInfo['birthday'];
        $this->assign('userInfo', $userInfo);
        
        if(! empty($uid) && trim($this->getParam('action')) == $action)
        {
            $conditions['uid'] = intval($uid);
            $conditions['nickname'] = trim($this->getParam('nickname'));
            $conditions['gender'] = intval($this->getParam('gender'));
            $conditions['birthyear'] = intval($this->getParam('birthyear'));
            $conditions['birthmonth'] = intval($this->getParam('birthmonth'));
            $conditions['birthday'] = intval($this->getParam('birthday'));
            $conditions['privbirth'] = intval($this->getParam('privbirth'));
            $conditions['homenation'] = trim($this->getParam('homenation'));
            $conditions['homeprovince'] = trim($this->getParam('homeprovince'));
            $conditions['homecity'] = trim($this->getParam('homecity'));
            $conditions['nation'] = trim($this->getParam('nation'));
            $conditions['province'] = trim($this->getParam('province'));
            $conditions['city'] = trim($this->getParam('city'));
            $conditions['occupation'] = intval($this->getParam('occupation'));
            $conditions['email'] = trim($this->getParam('email'));
            $conditions['homepage'] = trim($this->getParam('homepage'));
            $conditions['summary'] = trim($this->getParam('summary'));
            
            //数据验证
            if(! Model_User_Validator::checkNickname($conditions['nickname']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_NICKNAMEFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['gender'], 0, 2))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_GENDERFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['birthyear'], 1900, intval(date('Y'))))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_BIRTHYEARFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['birthmonth'], 1, 12))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_BIRTHMONTHFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['birthday'], 1, 31))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_BIRTHDAYFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['privbirth'], 0, 4))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_PRIVBIRTHFORMATERROR, 0);
            if(! Model_User_Validator::checkHomeNation($conditions['homenation']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_HOMENATIONFORMATERROR, 0);
            if(! Model_User_Validator::checkHomeProvince($conditions['homenation'], $conditions['homeprovince']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_HOMEPROVINCEFORMATERROR, 0);
            if(! Model_User_Validator::checkHomeCity($conditions['homenation'], $conditions['homeprovince'], 
            $conditions['homecity']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_HOMECITYFORMATERROR, 0);
            if(! Model_User_Validator::checkNation($conditions['nation']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_NATIONFORMATERROR, 0);
            if(! Model_User_Validator::checkProvince($conditions['nation'], $conditions['province']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_PROVINCEFORMATERROR, 0);
            if(! Model_User_Validator::checkCity($conditions['nation'], $conditions['province'], $conditions['city']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_CITYFORMATERROR, 0);
            if(! Model_User_Validator::checkNumRange($conditions['occupation'], 0, 9999))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_OCCUPATIONFORMATERROR, 0);
            if(! Model_User_Validator::checkEmail($conditions['email']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_EMAIL_FORMATERROR, 0);
            if(! Model_User_Validator::checkHomepage($conditions['homepage']))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_HOMEPAGEFORMATERROR, 0);
            if(! Model_User_Validator::checkMbStrLength($conditions['summary'], 0, 140))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_SUMMARYFORMATERROR, 0);
            
            //如果使用UC安装，将email更新到UC
            if(Core_Config::get('useuc', 'basic', false))
            {
            	$ucrt = Core_Outapi_Uc::call('user_edit', $userInfo['username'], '', '', $conditions['email'], 1);
	            if($ucrt < 0)
	            {
	            	if($ucrt == -8)
	            		$this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_UC_USERPROTECTED, 0);
	                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_EMAIL_USED, 0);
	            }
            }
            //将数据更新到数据库
            if($this->userModel->editUserInfo($conditions))
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_UPDATESUCCEED, 0);
            else
                $this->showmsg('', $errorBackUrl . Core_Comm_Modret::RET_USERINFO_UPDATEFAILED, 0);
        }
        
        $this->assign('pathRoot', Core_Fun::getPathroot());
        
        //取得个人设置配置信息
        $setting = include CONFIG_PATH . 'setting.php';
        $this->assign('setting', $setting);
        
        $tabArr = array(array('url' => '/setting', 'title' => '个人资料'), array('url' => '/tag', 'title' => '个人标签'));
        $tabbar = Core_Lib_Base::formatTab($tabArr, 0);
        $this->assign('tabbar', $tabbar);
        
        $this->display('user/setting.tpl');
    }

    /**
     * 输出国家JSON列表
     */
    public function getnationAction()
    {
        echo json_encode(Core_Comm_Util::getNationList());
    }

    /**
     * 输出省份JSON列表
     */
    public function getprovinceAction()
    {
        $nationIndex = trim($this->getParam('nation'));
        if(! empty($nationIndex))
            echo json_encode(Core_Comm_Util::getProvinceList($nationIndex));
    }

    /**
     * 输出城市JSON列表
     */
    public function getcityAction()
    {
        $nationIndex = trim($this->getParam('nation'));
        $provinceIndex = trim($this->getParam('province'));
        if(! empty($nationIndex))
            echo json_encode(Core_Comm_Util::getCityList($nationIndex, $provinceIndex));
    }
}