<?php
/**
 * 用户操作类
 *
 * @author lvfeng
 */
class Model_User_Member
{
    //用户表对象
    protected $memberTableObj;
    //用户表可操作字段
    protected $memberTableSafeColumu = array ('uid', 'gid', 'username', 'nickname',
        'password', 'salt', 'secques',
        'email', 'gender', 'localauth', 'localauthtext',
        'style', 'oauthtoken', 'oauthtokensecret', 'name',
        'province', 'city', 'mobile',
        'regtime', 'regip', 'lastvisit', 'lastip',
        'homenation', 'homeprovince', 'homecity',
        'birthyear', 'birthmonth', 'birthday',
        'realname', 'privbirth', 'nation', 'newfollowers',
        'occupation', 'homepage', 'summary', 'fansnum', 'idolnum', 'trust'
    );
    //用户表可操作表达式字段
    protected $memberTableUnsetColumu = array ('newfollowers', 'fansnum', 'idolnum');
    //数据容器
    protected static $_data = array();

    /**
     * 构造函数
     */
    public function __construct ()
    {
        $this->memberTableObj = Table_User_Member::getInstance();
    }

    /**
     * 获得加密随机码
     *
     * @return string
     */
    public function getSalt ()
    {
        return substr (uniqid (rand ()), -6);
    }

    /**
     * 加密密码
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function formatPassword ($password, $salt)
    {
        return md5 (md5 ($password) . $salt);
    }

    /**
     * 加密安全提问（未用到）
     *
     * @param int $questionId
     * @param string $answer
     * @return string
     */
    public function formatSecques ($questionId, $answer)
    {
        return substr (md5 ($answer . md5 ($questionId)), 16, 8);
    }

    /**
     * 添加用户
     *
     * @param array $userInfo
     * @return int uid
     */
    public function addUser ($userInfo)
    {
        return $this->memberTableObj->add ($userInfo, $this->memberTableSafeColumu);
    }

    /**
     * 修改用户信息
     *
     * @param array $userInfo
     * @return boolean
     */
    public function editUserInfo ($userInfo)
    {
        $update = $this->memberTableObj->update ($userInfo, $this->memberTableSafeColumu, $this->memberTableUnsetColumu);
        $update && $name = $this->getNameByUid ($userInfo['uid']);
        !empty ($name) && Model_User_Local::delCache ($name);
        return $update;
    }

    /**
     * 根据用户编号删除用户信息（未用到，未清缓存）
     *
     * @param int|array $uid
     * @return boolean
     */
    public function deleteUserByUid ($uid)
    {
        return $this->memberTableObj->remove ($uid);
    }

    /**
     * 验证用户名是否已注册
     *
     * @param string $username
     * @param int $uid
     * @return boolean
     */
    public function checkUsernameExists ($username, $uid=0)
    {
        $whereArr = array (array ('username', $username));
        !empty ($uid) && $whereArr[] = array ('uid', $uid, '<>');
        return $this->memberTableObj->queryCount ($whereArr);
    }

    /**
     * 验证邮箱是否已注册
     *
     * @param string $email
     * @param int $uid
     * @return boolean
     */
    public function checkEmailExists ($email, $uid=0)
    {
        $whereArr = array (array ('email', $email));
        !empty ($uid) && $whereArr[] = array ('uid', $uid, '<>');
        return $this->memberTableObj->queryCount ($whereArr);
    }

    /**
     * 验证access_token是否已注册
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @param int $uid
     * @return boolean
     */
    public function checkAccessTokenExists ($oauthToken, $oauthTokenSecret, $uid=0)
    {
        $whereArr = array (array ('oauthtoken', $oauthToken), array ('oauthtokensecret', $oauthTokenSecret));
        !empty ($uid) && $whereArr[] = array ('uid', $uid, '<>');
        return $this->memberTableObj->queryCount ($whereArr);
    }

    /**
     * 修改密码时验证旧密码是否正确
     *
     * @param string $username
     * @param string $oldPassword
     * @return boolean
     */
    public function checkUserOldPassword ($username, $oldPassword)
    {
        $oldPassword = $this->formatPassword ($oldPassword, $this->getSaltByUsername ($username));
        return $this->memberTableObj->queryCount (array (array ('username', $username), array ('password', $oldPassword)));
    }

    /**
     * 根据用户名获得salt
     *
     * @param string $username
     * @return string
     */
    public function getSaltByUsername ($username)
    {
    	$uinfo = $this->getUserInfoByUsername($username);
        return empty ($uinfo['salt']) ? '' : $uinfo['salt'];
    }

    /**
     * 根据用户编号获得用户信息
     *
     * @param int $uid
     * @return array
     */
    public function getUserInfoByUid ($uid)
    {
    	if(!isset(self::$_data['uid.'.$uid]))
    		self::$_data['uid.'.$uid] = $this->memberTableObj->queryOne ('*', array (array ('uid', $uid)));
    	return self::$_data['uid.'.$uid];
    }

    /**
     * 根据用户编号获得用户微博账号
     *
     * @param int $uid
     * @return string
     */
    public function getNameByUid ($uid)
    {
    	$uinfo = $this->getUserInfoByUid($uid);
        return empty ($uinfo['name']) ? '' : $uinfo['name'];
    }

    /**
     * 根据用户微博账号获得用户编号
     *
     * @param string $name
     * @return  int
     */
    public function getUidByName ($name)
    {
    	$uinfo = $this->getUserInfoByName($name);
        return empty ($uinfo['uid']) ? 0 : $uinfo['uid'];
    }

    /**
     * 根据用户名获得用户信息
     *
     * @param string $username
     * @return array
     */
    public function getUserInfoByUsername ($username)
    {
    	if(!isset(self::$_data['username.'.$username]))
    		self::$_data['username.'.$username] = $this->memberTableObj->queryOne ('*', array (array ('username', $username)));
    	return self::$_data['username.'.$username];
    }

    /**
     * 根据用户名取得认证信息
     * 
     * @param int $uid
     * @return 不是认证用户 返回 false | 是认证用户，返回 认证文字说明
     * Model_User_Member::getCertInfoByUid($uid);
     */
    public static function getCertInfoByUid ($uid)
    {
        $obj = new self();
        $user = $obj->getUserInfoByUid ($uid);
        if ($user['localauth']) 
            return $user['localauthtext'];
        return false;
    }

    /**
     * 根据用户名取得认证信息
     * 
     * @param string $username
     * @return 不是认证用户 返回 false | 是认证用户，返回 认证文字说明
     * Model_User_Member::getCertInfoByUsername($username);
     */
    public static function getCertInfoByUsername ($username)
    {
        $obj = new self();
        $user = $obj->getUserInfoByUsername ($username);
        if ($user['localauth']) 
            return $user['localauthtext'];
        return false;
    }

    /**
     * 根据用户名取得认证信息
     * 
     * @param string $name
     * @return 不是认证用户 返回 false | 是认证用户，返回 认证文字说明
     * Model_User_Member::getCertInfoByUsername($name);
     */
    public static function getCertInfoByName ($name)
    {
        $obj = new self();
        $user = $obj->getUserInfoByName ($name);
        if ($user['localauth']) 
            return $user['localauthtext'];
        return false;
    }

    /**
     * 通过access_token获得用户信息
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return array
     */
    public function getUserInfoByAccessToken ($oauthToken, $oauthTokenSecret)
    {
        return $this->memberTableObj->queryOne ('*', array (array ('oauthtoken', $oauthToken), array ('oauthtokensecret', $oauthTokenSecret)));
    }

    /**
     * 通过平台用户名获得用户信息
     *
     * @param string $name
     * @return array
     */
    public function getUserInfoByName ($name)
    {
    	if(!isset(self::$_data['name.'.$name]))
    		self::$_data['name.'.$name] = $this->memberTableObj->queryOne ('*', array (array ('name', $name)));
    	return self::$_data['name.'.$name];
    }

    /**
     * 通过平台用户名数组获得用户信息
     *
     * @param array $names|$tolowwer 是否把键值变为小写
     * @return array
     */
    public static function getUserInfosByNames ($names, $tolowwer=false)
    {
        if (is_array ($names) && count ($names) > 0) {
            $sql = 'SELECT * FROM `##__user_member` WHERE `name` IN(' . Core_Comm_Util::array2string ($names) . ')';
            $sqlResult = Core_Db::fetchAll ($sql);
            $result = array ();
            foreach ($sqlResult as $value)
            {
                if($tolowwer)
                    $result[$value['name']] = $value;
                else
                    $result[strtolower($value['name'])] = $value;
            }
            return $result;
        } 
        return array ();
    }

    /**
     * 获得用户列表
     *
     * @param array $whereArr
     * @param array $orderByArr
     * @param array $limitArr
     * @return array
     */
    public function getUserList ($whereArr=array (), $orderByArr=array (), $limitArr=array ())
    {
        return $this->memberTableObj->queryAll ('*', $whereArr, $orderByArr, $limitArr);
    }

    /**
     * 获得用户数量
     *
     * @param array $whereArr
     * @return int
     */
    public function getUserCount ($whereArr=array ())
    {
        return $this->memberTableObj->queryCount ($whereArr);
    }

    /**
     * 用户注册
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @return int uid
     */
    public function onRegister ($username, $password, $email)
    {
        $salt = $this->getSalt ();
        $password = $this->formatPassword ($password, $salt);
        $userInfo = array ('username' => $username
            , 'password' => $password
            , 'salt' => $salt
            , 'email' => $email
            , 'regtime' => time ()
            , 'regip' => Core_Comm_Util::getClientIp ()
            , 'lastvisit' => time ()
            , 'lastip' => Core_Comm_Util::getClientIp ()
        );
        return $this->addUser ($userInfo);
    }

    /**
     * 自动注册
     *
     * @param int $uid
     * @param string $username
     * @param string $email
     * @return int uid
     */
    public function onAutoRegister ($uid, $username, $email)
    {
        $userInfo = array ('uid' => $uid
            , 'username' => $username
            , 'password' => md5 (rand ())
            , 'email' => $email
            , 'regtime' => time ()
            , 'regip' => Core_Comm_Util::getClientIp ()
            , 'lastvisit' => time ()
            , 'lastip' => Core_Comm_Util::getClientIp ()
        );
        return $this->addUser ($userInfo);
    }

    /**
     * 更新密码
     *
     * @param int $uid
     * @param string $password
     * @return boolean
     */
    public function onUpdatePassword ($uid, $password)
    {
        $salt = $this->getSalt ();
        $password = $this->formatPassword ($password, $salt);
        $userInfo = array ('uid' => $uid
            , 'password' => $password
            , 'salt' => $salt
        );
        return $this->editUserInfo ($userInfo);
    }

    /**
     * 绑定微博账号
     *
     * @param int $uid
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @param string $name
     * @return boolean
     */
    public function onBindAccessToken ($uid, $oauthToken, $oauthTokenSecret, $name)
    {
        $userInfo = array ('uid' => $uid
            , 'oauthtoken' => $oauthToken
            , 'oauthtokensecret' => $oauthTokenSecret
            , 'name' => $name
        );
        return $this->editUserInfo ($userInfo);
    }

    /**
     * 验证用户是否已绑定微博账号
     *
     * @param int $uid
     * @return boolean
     */
    public function onCheckBindAccessToken ($uid)
    {
        $userInfo = $this->getUserInfoByUid ($uid);
        return empty ($userInfo['oauthToken']) || empty ($userInfo['oauthTokenSecret']) ? false : true;
    }

    /**
     * 用户登录
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function onLogin ($username, $password)
    {
        $password = $this->formatPassword ($password, $this->getSaltByUsername ($username));
        return $this->memberTableObj->queryOne ('*', array (array ('username', $username), array ('password', $password)));
    }

    /**
     * 管理员登录（未用到）
     *
     * @param string $username
     * @param string $password
     * @param string $questionId
     * @param string $answer
     * @return array
     */
    public function onAdminLogin ($username, $password, $questionId, $answer)
    {
        $password = $this->formatPassword ($password, $this->getSaltByUsername ($username));
        $secques = $this->formatSecques ($questionId, $answer);
        return $this->memberTableObj->queryOne ('*', array (array ('username', $username), array ('password', $password), array ('secques', $secques)));
    }

    /**
     * 保存当前用户信息
     *
     * @param int $uid
     * @param string $nick
     */
    public function onSetCurrentUser ($uid, $nick)
    {
        $_SESSION['uid'] = $uid;
        $_SESSION['nick'] = $nick;
        $_SESSION['lastupdate'] = Core_Fun::time ();
    }

    /**
     * 获得当前用户信息
     *
     * @return array
     */
    public function onGetCurrentUser ()
    {
        $cUser = array ();
        $cUser['uid'] = isset ($_SESSION['uid']) ? $_SESSION['uid'] : null;
        $cUser['nick'] = isset ($_SESSION['nick']) ? $_SESSION['nick'] : null;
        //为用户延长会话保持时间
        if (empty ($_SESSION['lastupdate']) || ($cUser && Core_Fun::time () - $_SESSION['lastupdate'] > 300)) 
            $_SESSION['lastupdate'] = Core_Fun::time ();
        return $cUser;
    }

    /**
     * 保存当前用户access token
     *
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @param string $name
     */
    public function onSetCurrentAccessToken ($oauthToken, $oauthTokenSecret, $name)
    {
        $_SESSION['access_token'] = $oauthToken;
        $_SESSION['access_token_secret'] = $oauthTokenSecret;
        $_SESSION['name'] = $name;
    }

    /**
     * 获得当前用户access token
     *
     * @return array
     */
    public function onGetCurrentAccessToken ()
    {
    	$tokenArr = array ();
        $tokenArr['access_token'] = isset ($_SESSION['access_token']) ? $_SESSION['access_token'] : null;
        $tokenArr['access_token_secret'] = isset ($_SESSION['access_token_secret']) ? $_SESSION['access_token_secret'] : null;
        $tokenArr['name'] = isset ($_SESSION['name']) ? $_SESSION['name'] : null;
        return $tokenArr;
    }

    /**
     * 用户登出
     */
    public function onLogout ()
    {
        $tokenModel = new Model_Base_Token();
        if (isset($_SESSION['uid']) && $_SESSION['uid'])
            $tokenModel->deleteTokenByUid ($_SESSION['uid']);
            
        Core_Fun::setcookie ('iwb_token', null);
        
        if(isset ($_SESSION['uid']))
        	unset ($_SESSION['uid']);
        if(isset ($_SESSION['nick']))
        	unset ($_SESSION['nick']);
        
        if(isset ($_SESSION['access_token']))
	        unset ($_SESSION['access_token']);
	    if(isset ($_SESSION['access_token_secret']))
	        unset ($_SESSION['access_token_secret']);
	    if(isset ($_SESSION['name']))
	        unset ($_SESSION['name']);
	        
	    if(isset ($_SESSION['finduser']))
	        unset ($_SESSION['finduser']);
	    if(isset ($_SESSION['changeuser']))
	        unset ($_SESSION['changeuser']);
	        
	    if(isset ($_SESSION['ucsynlogin']))
	        unset ($_SESSION['ucsynlogin']);
	    if(isset ($_SESSION['ucsynlogout']))
	        unset ($_SESSION['ucsynlogout']);
    }

    /**
     * 更新用户新增听众数
     *
     * @param string $name
     * @param int $newFollowers
     * @return boolean
     */
    public function onSetNewFollowers ($name, $newFollowers)
    {
        $userObj = new Model_User_Member();
        $uid = $userObj->getUidByName ($name);
        $newFollowers = empty ($newFollowers) ? 0 : 'newfollowers + ' . intval ($newFollowers);
        $userInfo = array ('uid' => $uid
            , 'newfollowers' => $newFollowers
        );
        return $this->editUserInfo ($userInfo);
    }

    /**
     * 更新用户听众数
     *
     * @param string $name
     * @param int $addFansnum( +1 or -1)
     * @return boolean
     */
    public static function onSetFollowers ($name, $addFansnum)
    {
        $obj = new self();
        $user = $obj->getUserInfoByName ($name);
        $uid = $user['uid'];
        if ($uid > 0) 
        {
            //听众数已经为0了就不能再减了
            if ($user['fansnum'] == 0 && $addFansnum[0] == '-') 
                return;
            $obj = new self();
            $addFansnum = empty ($addFansnum) ? 0 : 'fansnum + ' . intval ($addFansnum);
            $userInfo = array ('uid' => $uid
                , 'fansnum' => $addFansnum
            );
            return $obj->editUserInfo ($userInfo);
        }
    }

    /**
     * 更新用户偶像数
     *
     * @param string $name
     * @param int $addIdolnum( +1 or -1)
     * @return boolean
     */
    public static function onSetFollowees ($name, $addIdolnum)
    {
        $obj = new self();
        $user = $obj->getUserInfoByName ($name);
        $uid = $user['uid'];
        if ($uid > 0) 
        {
            //偶像数已经为0了就不能再减了
            if ($user['idolnum'] == 0 && $addIdolnum[0] == '-') 
                return;
            $obj = new self();
            $addIdolnum = empty ($addIdolnum) ? 0 : 'idolnum + ' . intval ($addIdolnum);
            $userInfo = array ('uid' => $uid
                , 'idolnum' => $addIdolnum
            );
            return $obj->editUserInfo ($userInfo);
        }
    }

    /**
     * 更新用户皮肤
     *
     * @param int $uid
     * @param string $style
     * @return boolean
     */
    public function onSetStyle ($uid, $style)
    {
        if (trim ($style) == '')
            return false;
        $userInfo = array ('uid' => $uid
            , 'style' => $style
        );
        return $this->editUserInfo ($userInfo);
    }

    /**
     * 更新用户最后访问时间和IP
     *
     * @param int $uid
     * @return boolean
     */
    public function onSetLastVisit ($uid)
    {
        $userInfo = array ('uid' => $uid
            , 'lastvisit' => time ()
            , 'lastip' => Core_Comm_Util::getClientIp ()
        );
        return $this->editUserInfo ($userInfo);
    }

    /**
     * 检测用户是否登录
     *
     * @return boolean
     */
    public function checkLogged ()
    {
        $cUser = $this->onGetCurrentUser ();
        $cToken = $this->onGetCurrentAccessToken ();
        if (!empty ($cUser['uid']) && !empty ($cToken['name']))
            return true;
        return false;
    }

    /**
     * 取被屏蔽用户微博账号列表
     *
     * @return array
     */
    public function onGetBlacklist ()
    {
    	if(!isset(self::$_data['blacklist']))
    	{
            self::$_data['blacklist'] = array();
	        $result = $this->getUserList (array (array ('gid', 4)));
 	        foreach ($result as $user)
	            $user['name'] && self::$_data['blacklist'][] = $user['name'];
    	}
    	return self::$_data['blacklist'];
    }

    /**
     * 判断用户是否是可信（白名单）用户
     * 
     * @param type $name 平台用户名，为空时取当前用户
     */
    public static function isTrustUser ($name = null)
    {
        //$username 为空时判断当前用户
        if (!isset ($name) && isset ($_SESSION['name'])) 
            $name = $_SESSION['name'];
        $user = Model_User_Util::getLocalInfo ($name);
        return isset ($user['trust']) ? $user['trust'] : false;
    }
}
