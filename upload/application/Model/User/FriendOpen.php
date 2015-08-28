<?php

/**
 * iweibo2.0
 * 
 * 本地好友关系链model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_FriendOpen.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_User_FriendOpen extends Model_Friend
{

    /**
     * @检查目标用户是否存在
     * @param nane
     * @return bool
     * @author echoyang
     * @time 2011/5/29
     */
    public static function checkUser($name)
    {
        try
        {
            $user = Core_Open_Api::getClient()->getUserInfo(array('n' => $name));
            if (!empty($user) && $user['data']['name'] == $name)
            {
                return true;
            }
        }
        catch (Exception $e) //帐号不存在api也返回内部错误
        {
            //go on and return false
        }
        return false;
    }

    /**
     * 检测是否我粉丝或偶像或者互为好友
     * @type: 0 检测粉丝，1检测偶像 2,互听
     * @names: 最多30个好友 英文逗号连接
     */
    public static function checkFriend($names, $type=1)
    {
        $onceTotal = 30; //每次最多处理30个

        if (empty($names))
            return array();
        $checkFriends = array();
        if (strpos($names, ',') === false)
        {
            $checkFriends = Core_Open_Api::getClient()->checkFriend(array('n' => $names, 'type' => $type));
            $checkFriends = isset($checkFriends['data']) ? $checkFriends['data'] : array();
        }
        else
        {
            $checkFriendsSorce = explode(',', $names);
            $friendsTotal = count($checkFriendsSorce);
            if ($friendsTotal > $onceTotal)//不止一次api处理
            {
                $checkFriendsTemp = array();
                $friendsArray = array_chunk($checkFriendsSorce, $onceTotal);
                foreach ($friendsArray as $v)
                {
                    $friendsNamesStr = implode(',', $v);
                    $checkFriendsTemp = Core_Open_Api::getClient()->checkFriend(array('n' => $friendsNamesStr, 'type' => $type));
                    $checkFriendsTemp = isset($checkFriendsTemp['data']) ? $checkFriendsTemp['data'] : array();

                    $checkFriends = array_merge($checkFriends, $checkFriendsTemp);
                }
            }
            else
            {
                $checkFriends = Core_Open_Api::getClient()->checkFriend(array('n' => $names, 'type' => $type));
                $checkFriends = isset($checkFriends['data']) ? $checkFriends['data'] : array();
            }
        }

        return $checkFriends;
    }

    /**
     * 批量获取用户信息
     * @param array $names
     * @return array 用户信息
     */
    public static function getUserInfos ($names)
    {

        !is_array($names) && $names = explode(',',$names);
        if(!is_array($names) || !count($names)) return array();
        
        $onceTotal = 1; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            $_result = self::_getUserInfos ($r);
            $result = array_merge ($result, $_result);
        }
        return $result;
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
        //获取本地用户信息
        $localUserInfos = Model_User_Member::getUserInfosByNames ($names);
        
        //获取用户信息
        $client = Core_Open_Api::getClient();
        $openResult = $client->getUserInfos($names);
        $showLocalCer = Core_Config::get('localauth', 'certification.info');//是否启用本地认证
        if ($openResult['ret'] == 0) {
            $openResult = isset ($openResult['data']['info']) ? $openResult['data']['info'] : array ();
        }
        if($showLocalCer && $openResult)
        {
            foreach($openResult as $k=>$val)
            {
                if(empty($val)) continue;
               $val['description'] = Model_User_Member::getCertInfoByName($val['name']);//本地认证消息赋予用户.
               !empty($localUserInfos[$val['name']]['nickname']) && $val['nick'] = $localUserInfos[$val['name']]['nickname'];//本地认证消息赋予用户.
               $openResult[$val['name']] = $val;
               unset($openResult[$k]);
            }
        }
        return $openResult;
    }
    
    /**
     * 收听/取消收听好友
     * @$userInfo 我的uid和name   忽略此字段
     * @$fname 好友name
     * @$type 收听/取消
     */
    public static function followOpenFriend($uname, $fname, $type)
    {
        try
        {
            $openFllowret = false;
            $openFollowRetMag = Core_Open_Api::getClient()->setMyidol(array('type' => $type, 'n' => $fname));
            if (isset($openFollowRetMag['ret']) && $openFollowRetMag['ret'] == 0) //返回不为null,而且ret==0 成功
            {
                $openFllowret = true;
            }
        }
        catch (Exception $e)
        {
            //go on and return false
        }
        return $openFllowret;
    }

    /**
     * 获取好友列表
     * @        param
     * @        $p = array(
     * @        'n'=> $name                //用户名 空表示本人
     * @        , 'num' => $num            //请求个数(1-30)
     * @        , 'start' => $pos        //起始位置
     * @        , 'type' => $type        //0 听众 1 偶像
     * @        );
     */
    public static function getMyfriend($p)
    {
        $userInfo = Core_Open_Api::getClient()->getMyfans($p);
         if (!empty($userInfo['data']['info']) && is_array($userInfo['data']['info']))
         {
            $localAuth = Core_Config::get('localauth', 'certification.info');//本地认证信息开关
            $openAuth = Core_Config::get('platformauth', 'certification.info');//平台认证信息开关

             foreach($userInfo['data']['info'] AS &$t)
             {
                 $uInfo = Model_User_Util::getLocalInfo($t['name']);
                  !empty($uInfo['nick']) && $t['nick'] = $uInfo['nick'];//有本地昵称就覆盖
                   if($localAuth && !empty($uInfo['localauth']) || $openAuth && !empty($t['isvip']))
                  {
                     $t['is_auth'] = true;
                   }

                 if(isset($t['tweet'][0]))
                 {
                     $t['tweet'][0]["timestring"] = Core_Lib_Tutil::tTimeFormat($t['tweet'][0]["timestamp"]);
                     $t['tweet'][0]["text"] = Core_Lib_Tutil::tContentFormat($t['tweet'][0]["text"]);
                 }
             }

         }
        return $userInfo;
    }

    /**
     * 清除新增粉丝数
     * @        param
     */
    public static function cleanFansNum()
    {
        try
        {
            Core_Open_Api::getClient()->getUpdate(array("op" => 1, "type" => 8));
        }
        catch (Exception $e)
        {
            return false;
        }
        return true;
    }

    /*
     * 获取用户消息
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n 每次请求记录的条数（1-20条）
     * @name: 用户名 空表示本人
     * @return array
     * ******************** */

    public function getTimeline($p, $name = '')
    {
        return Core_Open_Api::getClient()->getTimeline($p);
    }

}