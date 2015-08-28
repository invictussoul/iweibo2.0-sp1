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
class Model_User_Open
{
    protected static $_userProcessCache = array();//缓存本进程请求的单个用户完整数据

    protected static $_usersProcessCache = array();//缓存本进程请求的多用户简单用户数据

    /**
     * 获取单个用户的信息
     * @param array $name|是一个一维数组
     * @return array 用户信息
     */ 
    public static function getUserInfo ($name,$userCheck=true)
    {  					 
        $name = isset($name[0])?$name[0]:$_SESSION['name'];
        if(empty($name)) return array();

        $returnData = array();//本次请求存在用户的缓存器

        if(!empty(self::$_userProcessCache) || !empty($name))//如果本次请求中获取有此用户信息，不再读取
        {
                if(isset(self::$_userProcessCache[$name]))
                {
                     $returnData = self::$_userProcessCache[$name];
                     return $returnData;
                }
        }

        //获取用户信息
        $client = Core_Open_Api::getClient();
       
	    
		try{
            $openResult = $client->getUserInfo(array('n'=>$name));
        
		}catch(Core_Api_Exception $e){
          
		    $openResult = array('ret'=>1);
        }
		

	   if($openResult['ret'] == 0 && !empty( $openResult['data']) )
        {
            if(isset($openResult['data']['Ismyblack']))
            {
                $openResult['data']['isblack'] = $openResult['data']['Ismyblack'];
                unset($openResult['data']['Ismyblack']);
            }
            if(isset($openResult['data']['Ismyfans']))
            {
                $openResult['data']['isfans'] = $openResult['data']['Ismyfans'];
                unset($openResult['data']['Ismyfans']);
            }
            if(isset($openResult['data']['Ismyidol']))
            {
                $openResult['data']['isidol'] = $openResult['data']['Ismyidol'];
                unset($openResult['data']['Ismyidol']);
            }

			if (isset ($openResult['data']['introduction']))
			{
			   $openResult['data']['introduction'] = htmlspecialchars( $openResult['data']['introduction']);
			}
			
	
			
            $returnData[$name] = $openResult['data'];
        }else{
             $returnData[$name] = array();
        }

        self::$_userProcessCache[$name] = $returnData;

	    return  $returnData;
    }
    /**
     * 获取一批用户的信息
     * @param array $names
     * @return array 用户信息
     */
    public static function getUsersInfo ($names,$userCheck=true)
    {
        if($userCheck)//用户账号安全性
        {
            $names = Core_Comm_Util::formatName2Array($names);
            if(empty($names)) return array();
        }


        $uProcessCache = array();//本次请求存在用户的缓存器
        if(!empty(self::$_usersProcessCache) && !empty($names))//如果本次请求中获取有此用户信息，不再read cache
        {
             foreach($names AS $k=> $uname)
            {
                if(isset(self::$_usersProcessCache[$uname]))
                {
                     $uProcessCache[$uname] = self::$_usersProcessCache[$uname];
                     unset($names[$k]);
                }
             }
             
             if(empty($names)|| count($names)<1) return $uProcessCache;
        }

        $onceTotal = 200; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            $_result = self::_getUserInfos ($r);
            $resultUser = array_merge ($result, $_result);
        }

        $resultUser && self::$_usersProcessCache = array_merge(self::$_usersProcessCache, $resultUser);//合并新数据和进程缓存
        $uProcessCache && $resultUser = array_merge($uProcessCache, $resultUser);//合并本次请求的数据


		
        return $resultUser;
    }

    /**
     * 批量获取用户信息
     * @param array $names
     * @return array 本地化后的用户信息
     */
    private static function _getUserInfos ($names)
    {
        if (empty ($names)) {
            return array ();
        }  
        //获取用户信息
        $client = Core_Open_Api::getClient();
        try{
            $openResult = Core_Open_Api::getClient()->getUserInfos($names);
        }catch(Core_Api_Exception $e){
            $openResult = array('ret'=>1);
        }
        if ($openResult['ret'] == 0)
        {
            $openResult = isset ($openResult['data']['info']) ? $openResult['data']['info'] : array ();
            foreach ($openResult AS $k =>$v)
            {
                $openResult[strtolower($v['name'])] = $v;
                unset($openResult[$k]);
            }
        }else{
            $openResult = array();
        }
        
        //api未返回的用户是错误用户，制空，以便进程缓存
        $retUsers = array_keys($openResult);
        if($retUsers)
        {
            $retUsers = array_map('strtolower', $retUsers);
            $errorNames = array_diff($names,$retUsers);
            if($errorNames)
            {
                foreach($errorNames AS $v)
                {
                    $openResult[$v] = array();
                }
            }
        }
 
        return $openResult;
    }


}