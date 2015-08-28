<?php

/**
 * iweibo2.0
 * 
 * 本地用户信息
 *
 * @author echoyang
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_MemberLocal.php echo出品必属精品 2011-06-09
 * @package Model
 * @since 2.0
 */
class Model_User_Util
{
	/**
	 * 单个用户较完整信息（平台完整信息,结合本地化信息，本地关系链）
	 * @param string $uname 
     * @param bool $simple true强制获取open关系链(此开关也回影响本地粉丝数和好友数)
	 * @return array
	 */

	public static function getInfo($uname,$simple=false)
	{
        if(empty($uname))
        {
            if(isset($_SESSION['name']))
            {
                $uname = $_SESSION['name'];
            }else{
                return array();
            }
        }
        $userInfosArray = array();
        try{
            //以open数据为基础 本地化数据覆盖
            if(Model_Friend::getFriendSrc()||$simple)
            {
                $userInfosArray = Model_User::userInfo($uname,12);//open关系链
            }else{
                $userInfosArray = Model_User::userInfo($uname,13);//本地化关系链
            }
            unset($userInfosArray['tag']);//去掉平台tag
        }
        catch(Core_Exception $e)
        {
        }
        return $userInfosArray;
    }
    
	/**
	 * 单个用户较完整信息（平台完整信息,结合本地化信息，本地关系链，本地化标签）
	 * @param array $accounts 
	 * @return array
	 */
	public static function getFullInfo($uname)
	{
        if(empty($uname) && isset($_SESSION['name']))
        {
            $uname = $_SESSION['name'];
        }
        $userInfosArray = array();
        try{
            //以open数据为基础 本地化数据覆盖
            if(Model_Friend::getFriendSrc())
            {
                $userInfosArray = Model_User::userInfo($uname,14);//open关系链
            }else{
                $userInfosArray = Model_User::userInfo($uname,15);//本地化关系链
            }
        }
        catch(Core_Exception $e)
        {
        }
        return $userInfosArray;
    }    

    
	/**
	 * 批量用户较完整信息（平台简单信息,结合本地化信息，本地关系链）
	 * @param array $accounts 注意：$accounts 键值返回都是小写
	 * @return array
	 */
	public static function getInfos($accounts=array())
	{
        if(empty($accounts)) return array();
        $userInfosArray = array();
        try{
            //以open数据为基础 本地化数据覆盖
            if(Model_Friend::getFriendSrc())
            {
                $userInfosArray = Model_User::usersInfo($accounts,12);//open关系链
            }else{
                $userInfosArray = Model_User::usersInfo($accounts,13);//本地化关系链
            }
        }
        catch(Core_Exception $e)
        {
        }
        return $userInfosArray;
	}

	/**
	 * 批量用户完整信息（平台简单信息,结合本地化信息，本地关系链，本地化标签）
	 * @param array $accounts 注意：键值返回都是小写
	 * @return array
	 */
	public static function getFullInfos($accounts=array())
	{
        if(empty($accounts)) return array();
        $userInfosArray = array();
        try{
            //以open数据为基础 本地化数据覆盖
            if(Model_Friend::getFriendSrc())
            {
                $userInfosArray = Model_User::usersInfo($accounts,14);//open关系链
            }else{
                $userInfosArray = Model_User::usersInfo($accounts,15);//本地化关系链
            }
        }
        catch(Core_Exception $e)
        {
        }

        return $userInfosArray;
	}

	/**
	 * 本地单个用户信息
	 * @param array $accounts 注意：$accounts对大小写敏感，请务必输入正确的账号
     * @return 账户不存在 会有账户id的空数组
	 * @return array
	 */
	public static function getLocalInfo($accounts='')
	{
        if(empty($accounts) && isset($_SESSION['name']))
        {
            $accounts = $_SESSION['name'];
        }
        $localUser = array();
        try{
            $localUser = Model_User::userInfo($accounts,4);//open关系链
        }
        catch(Core_Exception $e)
        {
        }

        return $localUser;
    }    
    
    
	/**
	 * 批量本地用户信息
	 * @param array $accounts 注意：$accounts对大小写敏感，请务必输入正确的账号
     * @return 账户不存在 会有账户id的空数组
	 * @return array
	 */
	public static function getLocalInfos($accounts=array())
	{
        if(empty($accounts)) return array();
        $localUser = array();
        try{
            $localUser = Model_User::usersInfo($accounts,4);//open关系链
        }
        catch(Core_Exception $e)
        {
        }

        return $localUser;
    }
    
	/**
	 * 批量open用户信息
	 * @param array $accounts 注意：$accounts对大小写不敏感
     * @return 账户不存在 会过滤 键值返回都是小写
	 * @return array
	 */
	public static function getOpenInfos($accounts=array())
	{
        if(empty($accounts)) return array();
        $openUser = array();
        try{
            $openUser = Model_User::usersInfo($accounts,8);//open关系链
        }
        catch(Core_Exception $e)
        {
        }

        return $openUser;
    }
    
    /**
	 * 批量建议open的用户name可用性
	 * @param array $accounts
	 * @return array 返回user的键值返回都是小写
	 */
	public static function filterOpenUsers($accounts=array())
	{
        if(empty($accounts)) return array();
        $openUser = self::getOpenInfos($accounts);
        $openUser = array_keys($openUser);
        return $openUser;
    }

}

?>