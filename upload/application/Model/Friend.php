<?php

/**
 * iweibo2.0
 * 
 * 好友关系链model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_Friend.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_Friend extends Core_Model
{
    const FRIEND_FAN = 0; //听众
    const FRIEND_IDO = 1; //收听

    const FRIEND_OPEN = 1; //微博好友数据源 1平台数据 0本地数据
    const FRIEND_LOCAL = 0;

    protected static $_friendModel = array(
        self::FRIEND_LOCAL => 'Model_User_FriendLocal',
        self::FRIEND_OPEN => 'Model_User_FriendOpen'
    );
    private static $instance; # 保存类实例在此属性中
    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'user_follow';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array('followeename', 'followername', 'direction', 'time');
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'followeename';

    // singleton 方法
    public static function singleton()
    {
        if (!isset(self::$instance))
        {
            if (self::getFriendSrc() == self::FRIEND_LOCAL)
            {
                self::$instance = new self::$_friendModel[self::FRIEND_LOCAL];
            }
            else
            {
                self::$instance = new self::$_friendModel[self::FRIEND_OPEN];
            }
        }
        return self::$instance;
    }

    /**
     * @获取friend数据源类型
     * @param 
     * @return #0 云端friend ;1本地标签
     * @author echoyang
     * @time 2011/5/29
     */
    public static function getFriendSrc()
    {
        return Core_Config::get('login_user_inherit', 'basic', self::FRIEND_OPEN);
    }

    /**
     * 收听/取消收听好友
     * @$userInfo 我的uid和name 
     * @$fname 好友name
     * @$type 1/0  收听/取消
     */
    public static function followFriend($userInfo, $fname, $type)
    {
        if (empty($userInfo['name']) || empty($fname) || (is_string($fname) && $fname==$userInfo['name']) )
            return false;
        
        $fname = Core_Comm_Util::formatName2Array($fname);
        if(empty($fname)) return false;

        if(is_array( $fname) &&in_array($userInfo['name'], $fname))
        {
            $k = array_search($userInfo['name'],$fname);
            unset($fname[$k]);
        }

        if(count($fname)==1)
        {
            self::_followFriend($userInfo, $fname[0], $type);
        }else{
            $onceTotal = 30; //每次最多处理30个
            $requests = array_chunk ($fname, $onceTotal);
            foreach ($requests as $v)
            {
                $_fname = implode(',',$v);
                self::_followFriend($userInfo, $_fname, $type);
            }
        }

        return true;
    }    
    
    /**
     * 收听/取消收听好友
     * @$userInfo 我的uid和name 
     * @$fname 好友name
     * @$type 1/0  收听/取消
     */
    public static function _followFriend($userInfo, $fname, $type)
    {
        if (empty($userInfo['name']) || empty($fname) || $userInfo['name'] == $fname)
            return false;

        try
        {

            $uname = $userInfo['name'];

            $openFllowret = $localFllowret = false;

            //同步收听/取消收听 到open平台
            $openFllowret = Model_User_FriendOpen::followOpenFriend($uname, $fname, $type);

            //同步收听/取消收听 到本地平台
           $localFllowret = Model_User_FriendLocal::followLocalFriend($uname, $fname, $type);


            if (self::getFriendSrc())
            {//open
                return $openFllowret;
            }
            else
            {//local

                //删除我和被收听者的本地缓存
                if(is_array($fname))
                {
                   $fname[] = $userInfo['name'];
                    $users = $fname;
                }else{
                    $users = array($userInfo['name'],$fname );
                }
                Model_User_Local::delCache($users);

                return $localFllowret;
            }
        }
        catch (Exception $e)
        {
            return false;
        }
    }

}