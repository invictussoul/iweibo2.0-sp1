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
 * @version $Id: Model_User_MemberLocal.php 2011-06-09
 * @package Model
 * @since 2.0
 */
class Model_User_Local
{
    protected static $_userLocalDir = '_data/_userlocal/'; #缓存本地化用户信息
    protected static $_expiresTime = 604800; #个人数据缓存7天

    protected static $_userProcessCache = array();//缓存本进程请求的本地用户数据

    protected static $_userName2Key = array();//缓存用户名对应用户key
    protected static $_userKey2Name = array();//缓存用户key对应用户名称
    
    /**
     * 批量获取用户信息
     * @param array $names|$userCheck false 不用检查输入用户安全性
     * @return array 本地化后的用户信息
     */
    public static function getUsersInfo ($names,$userCheck=true)
    {
        $names = array_map('strtolower', $names);
        self::$_userName2Key = self::$_userKey2Name = array();//清空用户缓存

        if($userCheck)//用户账号安全性
        {
            $names = Core_Comm_Util::formatName2Array($names);
            if(empty($names)) return array();
        }

        
        $uProcessCache = array();//本次请求存在用户的缓存器
        if(!empty(self::$_userProcessCache) && !empty($names))//如果本次请求中获取有此用户信息，不再read cache
        {
             foreach($names AS $k=> $uname)
            {
                if(isset(self::$_userProcessCache[$uname]))
                {
                     $uProcessCache[$uname] = self::$_userProcessCache[$uname];
                     unset($names[$k]);
                }
             }
             if(empty($names)) return $uProcessCache;
        }


        $cacheUserData = array();//架构缓存（文件or memcache）
        $allUserKey = self::getCacheKey($names);//获取用户缓存地址
        $cacheUserData = Core_Cache::read($allUserKey);//获取缓存数据
		
		
        $cacheUserKey = array_keys($cacheUserData);//获已缓存的用户的key
        $unCacheUserName = self::getMissName($cacheUserKey);//获取未被缓存的用户名称

        if(is_array($unCacheUserName) && $unCacheUserName)
        {
            $newFetchUser = self::fetchUsersInfo($unCacheUserName);
            Core_Cache::write($newFetchUser,'',self::$_expiresTime);
            $cacheUserData = array_merge($cacheUserData,$newFetchUser);
            
        }


        //转换key的键值为用户名
        foreach($cacheUserData AS $k => $v)
        {

            $name = self::$_userKey2Name[$k];
            $cacheUserData[$name] = $v;
            unset($cacheUserData[$k]);
        }

        
        $cacheUserData && self::$_userProcessCache = array_merge(self::$_userProcessCache, $cacheUserData);//合并新数据和进程缓存
        $uProcessCache && $cacheUserData = array_merge($uProcessCache, $cacheUserData);//合并本次请求的数据
      
	    return $cacheUserData;
        
    }

    /**
     * @单个或者批量删除用户缓存
     * @param $users: array|string(單個用户名或者英文逗号相连接的用户名)
     * @return array|string
     * @author echoyang
     * @time 2011/6/16
     */
    public static function delCache($users)
    {
        //格式化
        $users = Core_Comm_Util::formatName2Array($users);
        if(empty($users)) return false;
        $users = array_map('strtolower', $users);
        $allUserKey = self::getCacheKey($users,false);//获取用户缓存地址
        Core_Cache::del($allUserKey);
        return true;
    }
    
    
    /**
     * @获取用户名对应的缓存key
     * @param $users: array|string
     * @return array|string
     * @author echoyang
     * @time 2011/6/16
     */
    public static function getCacheKey($users,$cacheKey=true)
    {
        if(empty($users) ) return array();
        if(is_array($users))
        {
            $cachePath = array();
            foreach($users AS $v)
            {
                $userPath = Core_Comm_Util::getUserCachePath($v, self:: $_userLocalDir);
                $cachePath[] =  $userPath;
                if($cacheKey)
                {
                    self::$_userName2Key[$v] = $userPath;
                    self::$_userKey2Name[$userPath] = $v;
                }
            }
        }else{
            $cachePath = Core_Comm_Util::getUserCachePath($users, self:: $_userLocalDir);
        }
        return $cachePath;
    }
    
    /**
     * @返回未缓存的用户name
     * @param $cacheUserKey 已缓存的所有用户的key 
     * @return 未缓存用户的name
     * @author echoyang
     * @time 2011/6/16
     */
    public static function getMissName($cacheUserKey)
    {
        $allUserKey = self::$_userName2Key;//所有用户的key
        if(is_array($cacheUserKey) && $cacheUserKey )//有缓存的计算差集
        {
               $unCacheUserKey = array_diff($allUserKey, $cacheUserKey);
               $unCacheUserName = self::getCacheNameFromKey($unCacheUserKey);
        }else{//否则获取全部内容
                $unCacheUserName = self::$_userKey2Name;;
        }
        return $unCacheUserName;
    }


     /**
     * @根据缓存key得到缓存name
     * @param array $keys 缓存地址
     * @return array name 根据用户缓存地址 反算用户名
     * @author echoyang
     * @time 2011/6/16
     */
    public static function getCacheNameFromKey($unCacheUserKey)
    {
        $unCacheUserName = array();
        foreach($unCacheUserKey AS $unKey)
        {
           !empty(self::$_userKey2Name[$unKey]) && $unCacheUserName[] = self::$_userKey2Name[$unKey];
        }
        return $unCacheUserName;
    }    
    
    /**
     * 批量获取用户信息
     * @param array $names
     * @return array 本地化后的用户信息
     */
    public static function fetchUsersInfo ($names)
    {
        !is_array($names) && $names = explode(',',$names);
        if(!is_array($names) || !count($names)) return array();
        
        $onceTotal = 200; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            $_result = self::_fetUsersInfo ($r);
            $result = array_merge ($result, $_result);
        }
        return $result;
    }

    /**
     * 批量获取用户信息
     * @param array $names
     * @return array 本地化后的用户信息
     */
    private static function _fetUsersInfo ($names)
    {
		

		
        if (empty ($names)) {
            return array ();
        }

        
        //获取本地用户信息
        $userLocal = Model_User_Member::getUserInfosByNames ($names);

        $returnUser = array();
		

        //把某些属性替换成本地内容
        foreach ($names as $val)
        {
            $key = empty(self::$_userName2Key[$val])?Core_Comm_Util::getUserCachePath($val, self:: $_userLocalDir):self::$_userName2Key[$val];//此用户要缓存的key
            
            if(empty($val)) continue;
            if(empty($userLocal[$val]))
            {
                $returnUser[$key] = array();
                continue;
            }
            $user = array();
            $user['name'] = $userLocal[$val]['name'];
            $user['uid'] = $userLocal[$val]['uid'];//uid
            $user['gid'] = $userLocal[$val]['gid'];//组
            $user['mobile'] = $userLocal[$val]['mobile'];
            $user['email'] = $userLocal[$val]['email'];
            $user['occupation'] = $userLocal[$val]['occupation'];//从事行业
            $user['homepage'] = $userLocal[$val]['homepage'];//个人主页

            $user['fansnum'] = $userLocal[$val]['fansnum'];//粉丝数
            $user['idolnum'] = $userLocal[$val]['idolnum'];//偶像数

            $user['username'] = $userLocal[$val]['username'];//登录账号

            $user['nick'] = empty($userLocal[$val]['nickname'])? '': trim($userLocal[$val]['nickname']);//昵称
            $user['sex'] = $userLocal[$val]['gender'];//昵称
           
  		    $user['introduction'] = htmlspecialchars($userLocal[$val]['summary']);//个人介绍
           
		    $user['country_code'] = $userLocal[$val]['nation'];// 所在国
            $user['province_code'] = $userLocal[$val]['province'];//所在省
            $user['city_code'] = $userLocal[$val]['city'];// 所在市
             $user['location'] = Core_Comm_Util::getLocation($userLocal[$val]['nation'], $userLocal[$val]['province'], $userLocal[$val]['city']);//所在地

            $user['birth_year'] = $userLocal[$val]['birthyear'];//出生年
            $user['birth_month'] = $userLocal[$val]['birthmonth'];//出生月
            $user['birth_day'] = $userLocal[$val]['birthday'];//出生日

            $user['localauthtext'] = $userLocal[$val]['localauthtext'];//本地认证文字说明
            $user['localauth'] = $userLocal[$val]['localauth'];//本地认证
            $user['trust'] = $userLocal[$val]['trust'];//白名单

            $user['style'] = $userLocal[$val]['style'];//白名单
            unset($user['isidol']);

            $returnUser[$key] = $user;
        }

		
        return $returnUser;
    }
    
}