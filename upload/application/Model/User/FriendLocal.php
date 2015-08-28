<?php

/**
 * iweibo2.0
 *
 * 本地收听关系
 *
 * @author gavin,echoyang
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_FriendLocal.php 2011-06-09 15:43:00Z gionouyang $
 * @package Model
 * @since 2.0
 */
class Model_User_FriendLocal extends Model_Friend
{

    public static $_updateOpen = true; //修改本地收听/取消收听是否同步到open平台
    public static $_getTag = false; //true时候 getMyfriend（）返回好友会带tag
    
    protected static $_data = array ();//数据容器
    protected static $_followProcessCache = array();//缓存本进程请求的用户收听数据，目前只缓存本地偶像关系


    /**
     * 获取好友列表 todo 替换成本地数据
     * @param
     * @$p = array(
     * @'n'=> $name                //用户名 空表示本人
     * @, 'num' => $num            //请求个数(1-30)
     * @, 'start' => $pos        //起始位置
     * @, 'type' => $type        //0 听众 1 偶像
     * @);
     * $getTag true获取好友本地化标签，false 不获取。
     */
    public static function getMyfriend ($p, $getTag=false)
    {
        $result = array ();
        self::$_getTag = $getTag;
        $userObj = new Model_User_Member();
        if (!empty ($p['n'])) {
            $uname = $p['n'];
        } else {
            $uname = $_SESSION['name'];
        }

        if ($uname) {
            $user = Model_User_Util::getLocalInfo ($uname);
            $uid = isset ($user['uid']) ? $user['uid'] : 0;
            $u['fansnum'] = isset ($user['fansnum']) ? $user['fansnum'] : 0;
            $u['idolnum'] = isset ($user['idolnum']) ? $user['idolnum'] : 0;

            if ($p['type'] == 0) {
                //粉丝
                $result['data']['hasnext'] = $u['fansnum'] > ($p['start'] + $p['num']) ? 0 : 1;
                $users = self::getFollowerInfos ($uname, $p['start'], $p['num'], null);
                $result['data']['info'] = $users;
            } elseif ($p['type'] == 1) {
                //偶像
                $result['data']['hasnext'] = $u['idolnum'] > ($p['start'] + $p['num']) ? 0 : 1;
                $users = self::getFolloweeInfos ($uname, $p['start'], $p['num']);
                $result['data']['info'] = $users;
            }

            $result['data']['timestamp'] = Core_Fun::time ();
            $result['msg'] = 'ok';
            $result['ret'] = 0;
            $result['uname'] = $uname;
        } else {
            $result['msg'] = '获取平台用户名失败';
            $result['ret'] = 1;
        }
        return $result;
    }

    /**
     * 清除新增粉丝数
     * @param
     */
    public static function cleanFansNum ()
    {
        try
        {
            //用户model 本地用户数情空
            $member = new Model_User_Member();
            $member->onSetNewFollowers ($_SESSION["name"], 0);
            //Core_Open_Api::getClient()->getUpdate(array("op" => 1, "type" => 8)); //清空云端 新增粉丝数
        } catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * uid2是否收听了uid1
     * @param type $name1
     * @param type $name2
     * @return type
     */
    public static function isFollower ($name1, $name2)
    {
        if (strlen ($name1) > 0 && strlen ($name2) > 0) {
            if ($name1 == $name2) {
                return false;
            }
            if (!isset (self::$_data['isFollower_' . $name1 . '_' . $name2])) {
                $obj = new self();
                self::$_data['isFollower_' . $name1 . '_' . $name2] = $obj->getCount (array (array ('followername', $name1, '='), array ('followeename', $name2, '=')));
            }
            return self::$_data['isFollower_' . $name1 . '_' . $name2];
        } else {
            return false;
        }
    }

    /**
     * uid1是否收听了uid2
     * @param type $name1
     * @param type $name2
     * @return type
     */
    public static function isFollowee ($name1, $name2)
    {
        if (strlen ($name1) > 0 && strlen ($name2) > 0) {
            if ($name1 == $name2) {
                return false;
            }
            if (!isset (self::$_data['isFollowee_' . $name1 . '_' . $name2])) {
                $obj = new self();
                self::$_data['isFollowee_' . $name1 . '_' . $name2] = $obj->getCount (array (array ('followeename', $name1, '='), array ('followername', $name2, '=')));
            }
            return self::$_data['isFollowee_' . $name1 . '_' . $name2];
        } else {
            return false;
        }
    }

    /**
     * 获取$name的听众数
     * @param type $name
     * @param force bool:  默认 false 读取用户表 | true 强制count
     * @return type
     */
    public static function getFollowerCountByName ($name, $force=false)
    {
        if (strlen ($name) > 0) {
            if (!$force) {
                $user = Model_User_Util::getLocalInfo ($name);
                if (isset ($user['fansnum'])) {
                    return $user['fansnum'];
                }
            }
            $obj = new self();
            return $obj->getCount (array (array ('followername', $name, '=')));
        } else {
            return 0;
        }
    }

    /**
     * 获取$name的收听数
     * @param type $name
     * @param force bool:  默认 false 读取用户表 | true 强制count
     * @return type
     */
    public static function getFolloweeCountByName ($name, $force=false)
    {
        if (strlen ($name) > 0) {
            if (!$force) {
                $user = Model_User_Util::getLocalInfo ($name);
                if (isset ($user['idolnum'])) {
                    return $user['idolnum'];
                }
            }
            $obj = new self();
            return $obj->getCount (array (array ('followeename', $name, '=')));
        } else {
            return 0;
        }
    }

    /**
     * 是否双向收听
     * @param int $followeename 收听人的uid
     * @param int $followername 被收听人的uid
     */
    public static function isTowDirectionFollow ($followeename, $followername)
    {
        if (isset ($followeename) && isset ($followername)) {
            $obj = new self();
            $r1 = $obj->getCount (
                            array (array ('followeename', $followeename, '='), array ('followername', $followername, '=')));
            $r2 = $obj->getCount (
                            array (array ('followeename', $followername, '='), array ('followername', $followeename, '=')));
            return ($r1 + $r2 == 2);
        } else {
            return false;
        }
    }

    /**
     * 取$name收听的人
     *
     * @param int $name
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return array
     */
    public static function getFollowees ($name, $start, $limit)
    {
        $obj = new self();
        $sql = 'SELECT * FROM `' . $obj->_tableName . '` WHERE `followeename`=\'' . $name . '\' ORDER BY `time` DESC';
        if (isset ($start) && isset ($limit)) {
            $fs = $obj->getAll ($sql, $limit, $start);
        } else {
            $fs = $obj->getAll ($sql);
        }
        $r = array ();
        foreach ($fs as $f)
        {
            $r[] = $f['followername'];
        }
        return $r;
    }

    /**
     * 取$name收听的人
     *
     * @param string $name
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return formatU后的用户信息
     */
    public static function getFolloweeInfos ($name, $start = null, $limit = null)
    {
        $obj = new self();
        $names = self::getFollowees ($name, $start, $limit);
        return $obj->getUserInfos ($names);
    }

    /**
     * 取$name收听的人的用户名
     *
     * @param string $name
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return array 偶像用户名列表
     */
    public static function getFolloweeNames ($name, $start = null, $limit = null)
    {
        $obj = new self();
        return $obj->getFollowees ($name, $start, $limit);
    }

    /**
     * 取收听$name的人
     *
     * @param string $name
     * @param string $direction 1 双向的（'我'已收听的）   0 单向的（'我'未收听的）
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return array 用户ID列表
     */
    private function getFollowers ($name, $start, $limit, $direction = '')
    {
        $sqladd = in_array ($direction, array ('0', '1')) ? " AND direction='$direction'" : '';
        $obj = new self();
        $sql = 'SELECT * FROM `' . $obj->_tableName . '` WHERE `followername` = \'' . $name . '\'' . $sqladd .
                ' ORDER BY time DESC';
        if (isset ($start) && isset ($limit)) {
            $fs = $obj->getAll ($sql, $limit, $start);
        } else {
            $fs = $obj->getAll ($sql);
        }
        $r = array ();
        foreach ($fs as $f)
        {
            $r[] = $f['followeename'];
        }
        return $r;
    }

    /**
     * 取收听$name的人
     *
     * @param string $name
     * @param string $direction 1 双向的（'我'已收听的）   0 单向的（'我'未收听的）
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return array 用户名信息列表
     */
    public static function getFollowerInfos ($name, $start, $limit, $direction = '')
    {
        $obj = new self();
        $names = $obj->getFollowers ($name, $start, $limit, $direction);
        return $obj->getUserInfos ($names);
    }

    /**
     * 批量获取用户信息
     * @param array $names
     * @return array 本地化后的用户信息
     */
    public static function getUserInfos ($names)
    {
        !is_array ($names) && $names = explode (',', $names);
        if (!is_array ($names) || !count ($names))
            return array ();

        $onceTotal = 200; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            if (self::$_getTag)//self::$_getTag true时候 返回用户信息会携带用户标签。默认不开启。
            {
                $_result = Model_User_Util::getFullInfos ($r);
            } else {
                $_result = Model_User_Util::getInfos ($r);
            }

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
        $localUserInfos = Model_User_Util::getLocalInfos ($names);

        //获取平台用户信息
        $openResult = Core_Open_Api::getClient ()->getUserInfos ($names);
        if ($openResult['ret'] == 0) {
            $openUserInfos = isset ($openResult['data']['info']) ? $openResult['data']['info'] : array ();
        }
        //添加好友关系

        $friends = self::checkFriend ($names, 1);

        $showLocalCer = Core_Config::get ('localauth', 'certification.info');//是否启用本地认证
        //把某些属性替换成本地内容
        foreach ((array)$openUserInfos as $val)
        {
            if (empty ($val))
                continue;
            $result[$val['name']] = $val;
            $result[$val['name']]['idolnum'] = isset ($localUserInfos[$val['name']]['idolnum']) ? $localUserInfos[$val['name']]['idolnum'] : 0;
            $result[$val['name']]['fansnum'] = isset ($localUserInfos[$val['name']]['fansnum']) ? $localUserInfos[$val['name']]['fansnum'] : 0;
            $result[$val['name']]['uid'] = isset ($localUserInfos[$val['name']]['uid']) ? $localUserInfos[$val['name']]['uid'] : 0;
            $result[$val['name']]['isidol'] = isset ($friends[$val['name']]) ? $friends[$val['name']] : false;
            !empty ($localUserInfos[$val['name']]['nickname']) && $result[$val['name']]['nick'] = $localUserInfos[$val['name']]['nickname'];
            $result[$val['name']]['head'] = $val['head'];

            $result[$val['name']]['description'] = $showLocalCer && !empty ($localUserInfos[$val['name']]['localauth']) && isset ($localUserInfos[$val['name']]['localauthtext']) ? $localUserInfos[$val['name']]['localauthtext'] : '';//系统启用本地认证 切用户被本地认证
        }
        return $result;
    }

    /**
     * 取收听我/他/她的人
     * 取$uid的粉丝
     *
     * @param int $uid
     * @param string $direction 1 双向的（'我'已收听的）   0 单向的（'我'未收听的）
     * @param int $start 用于分页
     * @param int $limit 用于分页
     * return array 粉丝用户名列表
     */
    public static function getFollowerNames ($name, $start, $limit, $direction = '')
    {
        $obj = new self();
        return $obj->getFollowers ($name, $start, $limit);
    }

    /**
     * 本地格式化用户信息
     * @param type $uid
     * @return type
     */
    public static function localFormatU ($name, $headSize = 50)
    {
        if (strlen ($name) > 0) {

            $uInfo = Core_Open_Api::getClient ()->getUserInfo (array ('n' => $name));
            $userInfo = Core_Lib_Base::formatU ($uInfo['data'], $headSize);
            $user = Model_User_Util::getLocalInfo ($name);
            //把某些属性替换成本地内容
            $userInfo['isvip'] = $user['localauth'] ? $user['localauth'] : 0;
            $userInfo['idolnum'] = $user['idolnum'] ? $user['idolnum'] : 0;
            $userInfo['fansnum'] = $user['fansnum'] ? $user['fansnum'] : 0;
            $userInfo['uid'] = $user['uid'];

            return $userInfo;
        }
    }

    /*
     * 获取多用户消息
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n 每次请求记录的条数（1-20条）
     * @name: 用户名 空表示本人
     * @return array
     * ******************** */

    public function getTimeline ($p, $name = '')
    {
        if (strlen ($name) > 0) {
            $uname = $name;
        } else {
            $userObj = new Model_User_Member();
            $u = $userObj->onGetCurrentAccessToken ();
            $uname = $u['name'];
        }

        $namelist = self::getFolloweeNames ($uname);
        if (count ($namelist) > 0) {
            $p['names'] = implode (',', $namelist) . ',' . $uname;
            return Core_Open_Api::getClient ()->getUsersTimeline ($p);
        } else {
            $p['names'] = $uname;
        }
        return Core_Open_Api::getClient ()->getUsersTimeline ($p);
    }

    /**
     * 检测是否我粉丝或偶像或者互为好友
     * @
     */
    public static function checkFriend ($names, $type = 1, $userCheck=true, $tolowwer=false)
    {
        if ($userCheck) {//用户账号安全性
            $names = Core_Comm_Util::formatName2Array ($names);
            if (empty ($names))
                return array ();
        }

        $uProcessCache = array();//本次请求存在用户的缓存器，目前只缓存本地偶像关系
        if($type==1)
        {
            if(!empty(self::$_followProcessCache) && !empty($names))//如果本次请求中获取有此用户信息，不再read cache
            {
                 foreach($names AS $k=> $uname)
                {
                    if(isset(self::$_followProcessCache[$uname]))
                    {
                         $uProcessCache[$uname] = self::$_followProcessCache[$uname];
                         unset($names[$k]);
                    }
                 }
                 if(empty($names)) return $uProcessCache;
            }
        }

        $onceTotal = 200; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            $_result = self::_checkFriend ($r, $type, $tolowwer);
            $result = array_merge ($result, $_result);
        }

        if($type==1)
        {
            $result && self::$_followProcessCache = array_merge(self::$_followProcessCache, $result);//合并新数据和进程缓存
            $uProcessCache && $result = array_merge($uProcessCache, $result);//合并本次请求的数据
        }

        return $result;
    }

    private static function _checkFriend ($names, $type = 1, $tolowwer=false)
    {
        $obj = new self();
        //type: 0 听众，1收听 2,互听
        $userObj = new Model_User_Member();
        $cu = $userObj->onGetCurrentAccessToken ();
        $cuname = $cu['name'];

        $name = Core_Comm_Util::array2string ($names);

        if(in_array($cuname,$names))//有自己的话，不去查收听关系
        {
            $cuname = strtolower ($cuname);
            $userKey = array_search($cuname, $names);
            unset($names[$userKey]);
            if(empty($names)) return array($cuname=>false);
        }

        $fansResult = array ();
        $idolResult = array ();
        $result = array ();

        foreach ($names as $_n)
        {
            $tolowwer && $_n = strtolower ($_n);
            $fansResult[$_n] = false;
            $idolResult[$_n] = false;
        }

        if ($type == 0 || $type == 2) {
            $sql = 'SELECT `followeename` FROM `' . $obj->_tableName . '` WHERE `followername` = \'' . Core_Db::sqlescape ($cuname) .
                    '\' AND `followeename` IN(' . $name . ')';
            $sqlResult = $obj->getAll ($sql);
            foreach ($sqlResult as $_r)
            {
                $tolowwer && $_r['followeename'] = strtolower ($_r['followeename']);
                $fansResult[$_r['followeename']] = true;
            }
        }
        if ($type == 1 || $type == 2) {
            $sql = 'SELECT `followername` FROM `' . $obj->_tableName . '` WHERE `followeename` = \'' . $cuname .
                    '\' AND `followername` IN(' . $name . ')';
            $sqlResult = $obj->getAll ($sql);
            foreach ($sqlResult as $_r)
            {
                $tolowwer && $_r['followername'] = strtolower ($_r['followername']);
                $idolResult[$_r['followername']] = true;
            }
        }
        if ($type == 0) {
            return $fansResult;
        }
        if ($type == 1) {
            return $idolResult;
        }
        if ($type == 2) {
            foreach ($names as $_n)
            {
                $result[$_n] = array ('isidol' => $idolResult[$_n], 'isfans' => $fansResult[$_n]);
            }
            return $result;
        }
    }

    /**
     * 收听/取消收听好友
     * @$userInfo 我的uid和name   忽略此字段
     * @$fnames 好友names 用,分割
     * @$type 收听/取消
     */
    public static function followLocalFriend ($uname, $fnames, $type)
    {
        $names = Core_Comm_Util::formatName2Array ($fnames);
        foreach ($names as $name)
        {
            self::_followLocalFriend ($uname, $name, $type);
        }
        return true;
    }

    /**
     * 收听/取消收听好友
     * @param type $uname
     * @param type $fname
     * @param type $type
     * @return type
     */
    private static function _followLocalFriend ($uname, $fname, $type)
    {
        if ($type == 1) {
            $localFllowret = self::addLocalFollow ($uname, $fname);
        } else {
            $localFllowret = self::deleteLocalFollow ($uname, $fname);
        }
        return $localFllowret;
    }

    /**
     * 添加收听关系
     * $followeename 收听 $followername
     * @param string $followeename 收听人
     * @param string $followername 被收听
     */
    private static function addLocalFollow ($followeename, $followername)
    {
        if (strlen ($followeename) > 0 && strlen ($followername) > 0) {
            $obj = self::singleton ();
            if (!self::isFollowee ($followeename, $followername)) {
                //收听人的收听数+1
                Model_User_Member::onSetFollowees ($followeename, '+1');
                //被收听人的听众数+1
                Model_User_Member::onSetFollowers ($followername, '+1');
                //收听时候 给被收听人数 加1
                $memberObj = new Model_User_Member();
                $memberObj->onSetNewFollowers ($followername, 1);
            }
            $_direction = 0;
            if (self::isFollowee ($followername, $followeename)) {
                $_direction = 1;
                $sql1 = "Replace Into `" . $obj->_tableName . "` Set `followeename`='" . Core_Db::sqlescape ($followername) .
                        "' , `followername`='" . Core_Db::sqlescape ($followeename) . "', `direction`=1 ";
                Core_Db::query ($sql1, true);
            }
            $sql = "Replace Into `" . $obj->_tableName . "` Set `followeename`='" . Core_Db::sqlescape ($followeename) .
                    "' , `followername`='" . Core_Db::sqlescape ($followername) . "', `direction`=" . $_direction . " , `time`='" .
                    Core_Fun::time () . "' ";
            return Core_Db::query ($sql, true);
        } else {
            return false;
        }
    }

    /**
     * $followeename 取消收听 $followername
     * @param int $followeename 收听人的uid
     * @param int $followername 被取消收听人的uid
     */
    private static function deleteLocalFollow ($followeename, $followername)
    {
        if (strlen ($followeename) > 0 && strlen ($followername) > 0) {
            $obj = self::singleton ();
            if (self::isFollowee ($followeename, $followername)) {
                //收听人的收听数-1
                Model_User_Member::onSetFollowees ($followeename, '-1');
                //被收听人的听众数-1
                Model_User_Member::onSetFollowers ($followername, '-1');
            }
            if (self::isFollowee ($followername, $followeename)) {
                //取消双向收听关系
                $sql0 = "Replace Into `" . $obj->_tableName . "` Set `followername`='" .
                        Core_Db::sqlescape ($followeename) . "' , `followeename`='" . Core_Db::sqlescape ($followername) . "', `direction`=0 , `time`='" .
                        Core_Fun::time () . "' ";
                Core_Db::query ($sql0, true);
            }
            $sql = "DELETE FROM `" . $obj->_tableName . "` WHERE `followeename`='" . Core_Db::sqlescape ($followeename) .
                    "' AND `followername`='" . Core_Db::sqlescape ($followername) . "'";
            return Core_Db::query ($sql, true);
        } else {
            return false;
        }
    }

}