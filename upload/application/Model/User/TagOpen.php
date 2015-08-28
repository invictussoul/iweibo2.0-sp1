<?php

/**
 * iweibo2.0
 * 
 * 平台标签操作model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_TagOpen.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_User_TagOpen extends Model_Tag
{

    protected static $_tagAllowTotal = 10; #open一个人可以拥有的有效的tag数量

    /**
     * @获取Open某人的有效tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */

    public static function getTag($uname='')
    {
        $userInfo = Core_Open_Api::getClient()->getUserInfo(array('n' => $uname));
        return $userInfo['data']['tag'];
    }

    /**
     * @获取tag允许最大数
     * @param 
     * @return  num
     * @author echoyang
     * @time 2011/5/29
     */
    public static function getTagMaxCount()
    {
        return self::$_tagAllowTotal;
    }

    /**
     * @检查Open某人是否可以再添加tag
     * @param nane
     * @return bool
     * @author echoyang
     * @time 2011/5/29
     */
    public static function checkTagCount($uname='')
    {
        $tagCount = self::getTagCount($uname);
        return $tagCount < self::getTagMaxCount();
    }

    /**
     * @检查本地某人有效tag个数
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function getTagCount($uname='')
    {
        $tagnum = 0;
        $tags = self::getTag($userName);
        if ($tags && is_array($tags))
        {
            $tagnum = count($tags);
        }
        return $tagnum;
    }

    /**
     * @检查本地某人有效tag个数
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function checkTagAdded($tagName, $userName)
    {
        $tags = self::getTag($userName);
        if ($tags)
        {
            foreach ($tags AS $v)
            {
                if ($v['name'] == $tagName)
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @检查tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function checkTag($tagName, $userName)
    {

        $tags = self::getTag($userName);
        //检查添加标签是否饱和
        $tagCount = 0;
        if ($tags && is_array($tags))
        {
            $tagCount = count($tags);
            if ($tagCount >= self::getTagMaxCount())
            {
                throw new Core_Api_Exception(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_TAG_FULL), Core_Comm_Modret::RET_TAG_FULL);
            }

            //检查是否已有此tag
            foreach ($tags AS $v)
            {
                if ($v['name'] == $tagName)
                {
                    throw new Core_Api_Exception(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_TAG_ADDED), Core_Comm_Modret::RET_TAG_ADDED);
                }
            }
        }
        return;
    }

    /**
     * @添加tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function addTag($tagName, $userName)
    {
        self::checkTag($tagName, $userName);
        return Core_Open_Api::getClient()->addTag(array('n' => $tagName));
    }

    /**
     * @添加tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function delTag($tagId)
    {
        return Core_Open_Api::getClient()->delTag(array('id' => $tagId));
    }

    /**
     * @搜索tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function searchTag($p)
    {
        $tag =  Core_Open_Api::getClient()->getSearch($p);
        if(!empty($tag['data']['info']))
        {
            $localAuth = Core_Config::get('localauth', 'certification.info');//本地认证信息开关
            $openAuth = Core_Config::get('platformauth', 'certification.info');//平台认证信息开关
            foreach($tag['data']['info'] AS &$v)
            {
                  $uInfo = Model_User_Util::getLocalInfo($v['name']);
                  !empty($uInfo['nick']) && $v['nick'] = $uInfo['nick'];//有本地昵称就覆盖
                   if($localAuth && !empty($uInfo['localauth']) || $openAuth && !empty($v['isvip']))
                  {
                     $v['is_auth'] = true;
                   }
            }
        }
        return $tag;
        
    }


}