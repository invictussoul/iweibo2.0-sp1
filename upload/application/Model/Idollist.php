<?php

/**
 * 我收听的好友的操作类
 * 
 * @author echoyang
 */
class Model_Idollist
{

    protected static $_expiresTime = 86400; #好友缓存文件有效秒数
    protected static $_maxPage = 10; #最多可以查询maxpage次api ,获取maxPage*pageNum的好友数 目前2000人
    protected static $_pageNum = 200; #一次最多拉取好友数，api限定小于等于200
    protected static $_idollistDir = '_data/_idollist/'; #缓存我收听好友的根目录
    protected static $_getFriendSrc = 1; //1云端数据，0本地数据
 
    /**
     * @获取我的好友地址
     * @param 
     * @return 相对地址
     * @author echoyang
     * @time 2011/5/13
     */

    public static function getIdollistCacheFile($uname='')
    {
        if (empty($uname))
        {
            throw new Core_Exception('没有用户错误');
        }

        $idollistDir = self::getIdollistCacheDir(); //好友缓存的根目录

        $md5NameStr = md5($uname);
        $idollistDirMd5 = substr($md5NameStr, -2); //加name的md5值倒数第1~2位做1级文件夹
        $idollistDir = $idollistDir . $idollistDirMd5 . '/';

        $idollistDirMd5 = substr($md5NameStr, -4, 2); //加name的md5值倒数第3~4位做2级文件夹
        $idollistDir = $idollistDir . $idollistDirMd5 . '/';

        $idollistDirMd5 = substr($md5NameStr, -6, 2); //加name的md5值倒数第5~6位做3级文件夹
        $idollistDir = $idollistDir . $idollistDirMd5 . '/';

        is_dir(CACHEDIR . $idollistDir) || Core_Fun_File::makeDir(CACHEDIR . $idollistDir);

        $myIdollistFile = $idollistDir . $uname . '.php';  //我收听好友缓存文件

        return $myIdollistFile;
    }

    /**
     * @获取好友缓存主目录
     * @param 
     * @return 相对地址
     * @author echoyang
     * @time 2011/5/13
     */
    public static function getIdollistCacheDir()
    {
        $idollistDir = self::$_idollistDir;
        self::$_getFriendSrc = Model_Friend::getFriendSrc(); //数据源
        $idollistDir .= self::$_getFriendSrc ? 'open/' : 'local/';
        is_dir(CACHEDIR . $idollistDir) || Core_Fun_File::makeDir(CACHEDIR . $idollistDir);
        return $idollistDir;
    }

    /**
     * 更新我的好友缓存文件,并返回数据
     * @return json
     */
    public static function setIdollistFileCache($myIdollistFile)
    {

        if (empty($myIdollistFile))
        {
            throw new Core_Exception('没有缓存文件错误');
        }

        $backData = self::$_getFriendSrc ? self::getOpenIdollist() : self::getLocalIdollist(); //判斷啟用雲端還是本地
        if ($backData)
        {
            Core_Cache::write($myIdollistFile, $backData, self::$_expiresTime);
        }
        return $backData;
    }

    /**
     * @获取好友缓存
     * @param 
     * @return 相对地址
     * @author echoyang
     * @time 2011/5/13
     */
    public static function getIdollists()
    {
        if (empty($_SESSION['name']))
            return array();
        $myIdollistFile = self::getIdollistCacheFile($_SESSION['name']);
        //判断是否有效 如果$backData == null 则更新缓存
        $backData = Core_Cache::read($myIdollistFile);

        if ($backData == null)
        {
            $backData = self::setIdollistFileCache($myIdollistFile); #更新我的好友缓存文件,并返回数据
        }
        return $backData;
    }

    /**
     * 获取我的好友列表api取数据
     * @return json
     */
    public static function getOpenIdollist()
    {
        try
        {
            static $idollists = array();
            static $page = 1;
            $start = ($page - 1) * self::$_pageNum;
            $idollist = Core_Open_Api::getClient()->getIdolShortList(array("type" => 1, "reqnum" => self::$_pageNum, "startindex" => $start));

            $info = empty($idollist['data']['info']) ? array() : $idollist['data']['info'];
            $info && $idollists = array_merge($idollists, $info);
            if (isset($idollist['data']['hasnext']) && $idollist['data']['hasnext'] == 0 && $page < self::$_maxPage)
            {
                $page++;
                return self::getOpenIdollist();
            }
            else
            {
                $backData = $idollists; // Core_Comm_Modret::getRetJson(Core_Comm_Modret::RET_SUCC, "", $idollists);
                return $backData;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    /**
     * 获取我的好友列表api取数据
     * @return json
     */
    public static function getLocalIdollist()
    {
        $myIdollist = Model_User_FriendLocal::getMyfriend(array('name' => $_SESSION['name'], 'num' => self::$_maxPage * self::$_pageNum, 'pos' => 0, 'type' => 1));
        return empty($myIdollist['data']['info']) ? array() : $myIdollist['data']['info'];
    }

}