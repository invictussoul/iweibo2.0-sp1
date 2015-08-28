<?php

/**
 * iweibo2.0
 * 
 * 本地标签操作model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_User_TagLocal.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_User_TagLocal extends Model_Tag
{

    //表名
    protected $_tableName = 'user_tag'; 
    //可修改的字段
    protected $_fields = array('tagname', 'useful', 'color');
    protected $_idkey = 'id'; //数据库主键

    //微博审核状态 0:未审核 2:审核通过 -1:审核未通过' 1:自动通过
    const TAG_USEFUL = 0;
    const TAG_UNUSEFUL = 1;

    protected static $_tagUseful = array(
        self::TAG_USEFUL => '有效',
        self::TAG_UNUSEFUL => '屏蔽'
    );
    protected static $_tagAllowTotal = 10; #本地一个人可以拥有的有效的tag数量

    protected static $_tagProcessCache = array();//缓存本进程请求的用户tag数据

    /**
     * @获取本地某人的有效tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */

    public static function getTag($uname='')
    {
        $tags = array();

        if(!empty(self::$_tagProcessCache) || !empty($uname))//如果本次请求中获取有此用户信息，不再select
        {
                if(isset(self::$_tagProcessCache[$uname]))
                {
                     $returnData = self::$_tagProcessCache[$uname];
                     return $returnData;
                }
        }

        if ($uname)
        {
            $tags = self::singleton()->queryAll(
                            array(array('name', $uname, '='), array('useful', 0, '=')), array('id DESC')
            );
            if ($tags)
            {
                //格式化返回数据 和open格式统一
                foreach ($tags AS $k => $v)
                {
                    $tags[$k]['name'] = $v['tagname'];
                    unset($tags[$k]['tagname'], $tags[$k]['useful']);
                }
            }
        }

        self::$_tagProcessCache[$uname] = array('tag'=>$tags);
        return $tags;
    }

    
    /**
     * 批量获取用户tag
     * @
     */
     public static function getUsersTag ($names,$userCheck=true,$tolowwer=false)
    {
        if($userCheck)//用户账号安全性
        {
            $names = Core_Comm_Util::formatName2Array($names);
            if(empty($names)) return array();
        }


        $uProcessCache = array();//本次请求存在用户的缓存器
        if(!empty(self::$_tagProcessCache) && !empty($names))//如果本次请求中获取有此用户信息，不再read cache
        {
             foreach($names AS $k=> $uname)
            {
                if(isset(self::$_tagProcessCache[$uname]))
                {
                     $uProcessCache[$uname] = self::$_tagProcessCache[$uname];
                     unset($names[$k]);
                }
             }
             if(empty($names)) return $uProcessCache;
        }

        $onceTotal = 200; //每次最多处理200个
        $requests = array_chunk ($names, $onceTotal);
        $result = array ();
        foreach ($requests as $r)
        {
            $_result = self::_getUsersTag ($r,$tolowwer);
            $result = array_merge ($result, $_result);
        }
        $result && self::$_tagProcessCache = array_merge(self::$_tagProcessCache, $result);//合并新数据和进程缓存
        $uProcessCache && $result = array_merge($uProcessCache, $result);//合并本次请求的数据
        return $result;
    }    
    
    /**
     * @获取本地某人的有效tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */

    public static function _getUsersTag($uname='',$tolowwer=false)
    {
        $returnData = $tags = array();
        if(empty($uname)) return array();

        if ($uname)
        {
            $queryName = Core_Comm_Util::array2string ($uname,',',"'",false);
            
            $sql = 'SELECT * FROM `' .self::singleton()->_tableName . '` WHERE `useful` = 0 AND `name` IN(' . $queryName . ')';
            $tags = self::singleton()->getAll ($sql);
            if ($tags)
            {
                //初始化
                if(function_exists('array_combine'))
                {
                    $returnData = array_combine($uname, array_fill(0, count($uname) ,array('tag'=>array()) ) );//创建一个以用户名为key的空数组
                }else{
                    foreach($uname AS $v)
                    {
                        $returnData[$v]['tag'] = array();
                    }
                }
                //格式化返回数据 和open格式统一
                foreach ($tags AS $k => $v)
                {
                    if($tolowwer)
                    {
                        $returnData[strtolower($v['name'])]['tag'][] = array('id'=>$v['id'],'name'=>$v['tagname']);
                    }else{
                        $returnData[$v['name']]['tag'][] = array('id'=>$v['id'],'name'=>$v['tagname']);
                    }
                     unset($tags[$k]);
                }
            }

        }
        return $returnData;
    }    
    
    /**
     * @获取本地某人tag信息
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function getTagname($tid)
    {
        $tagName = '';
        $taginfo = self::singleton()->find($tid);
        if (!empty($taginfo['tagname']))
        {
            $tagName = $taginfo['tagname'];
        }
        return $tagName;
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
        $tags = 0;
        if ($uname)
        {
            $data = array(array('name', $uname), array('useful', 0, '='));
            $tags = self::singleton()->getCount($data);
        }
        return $tags;
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
     * @检查本地tag是够被锁
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/6/13
     */
    public static function checkTagLocked($tagName)
    {
    	$tagModel = new Model_Mb_Tag();
        $tagLock = $tagModel->getTagCount(array(array('visible', 2),array('tagname',$tagName)));//如果存在$tagLock 说明被锁定
        return $tagLock;
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
        //检查添加标签是否饱和
        if (!self::checkTagCount($userName))
        {
            throw new Core_Api_Exception(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_TAG_FULL), Core_Comm_Modret::RET_TAG_FULL);
        }

        //检查是否已有此tag
        if (!self::checkTagAdded($tagName, $userName))
        {
            throw new Core_Api_Exception(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_TAG_ADDED), Core_Comm_Modret::RET_TAG_ADDED);
        }

        //检查是否标签是否被锁定
        if (self::checkTagLocked($tagName, $userName))
        {
            throw new Core_Api_Exception(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_TAG_LOCKED), Core_Comm_Modret::RET_TAG_LOCKED);
        }

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

        //检验是否是以前 锁定现在可以添加的
        $data = array(array('name', $userName), array('tagname',$tagName));
        $tags = self::singleton()->queryOne('*',$data);
        if($tags && !empty($tags['id']))
        {
             $tagid = $tags['id'];
             self::singleton()->updateall('`id`="' . $tagid . '"', array('useful' => 0));
        }else{
            $add = array('name' => $userName, 'tagname' => $tagName);
            $tagid = self::singleton()->add($add);
        }
        $data = array();

        if ($tagid)
        {
            $data = array('data' => array('id' => $tagid, 'tagname' => $tagName)); //本地化标签 会返回 tagname 系统标签数+1
        }
        return $data;
    }

    /**
     * @删除tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function delTag($tagId)
    {
        $tagName = self::getTagname($tagId);
        self::singleton()->remove($tagId);
        return $data = array('data' => array('id' => $tagId, 'tagname' => $tagName)); //本地化标签 会返回 tagname 系统标签数-1 
    }

    /**
     * @删除tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function delTagByName($tagname)
    {
        $tagName = self::getTagname($tagId);
        $sql = 'DELETE FROM `' . self::singleton()->_tableName . '` WHERE `tagname`= \'' . Core_Db::sqlescape($tagname) . '\'';
        return Core_Db::query($sql);
    }

    /**
     * @锁tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function lockTag($tagname)
    {
        return self::singleton()->updateall('`tagname`="' . $tagname . '"', array('useful' => 1));
    }

    /**
     * @解锁tag
     * @param nane
     * @return array
     * @author echoyang
     * @time 2011/5/29
     */
    public static function unLockTag($tagname)
    {
        return self::singleton()->updateall('`tagname`="' . $tagname . '"', array('useful' => 0));
    }

    /**
     * @搜索tag
     * @param nane
     * @return array    
     * @("k" => $searchKey        //搜索关键字
     * @, "n" => $this->pageSize    //每页显示信息的条数
     * @, "p" => $pageNum            //页码,从1开始)
     * @author echoyang
     * @time 2011/5/29
     */
    public static function searchTag($p)
    {
        $userdata = array();
        empty($p['p']) && $p['p'] = 1;
        (empty($p['n']) || $p['n'] > 15) && $p['n'] = 15;

        $start = ($p['p'] - 1) * $p['n'];
        $data = array(array('tagname', $p['k']), array('useful', 0));

        $tagsnum = self::singleton()->getCount($data); //用户数量
        if ($tagsnum)
        {
            $userdata = self::singleton()->queryAll($data, array('id DESC'), array($p['n'], $start));
            $userName = array();
            foreach ($userdata AS $v)
            {
                $v['name'] && $userName[] = $v['name'];
            }
            //$userdata = Core_Open_Api::getClient()->getUserInfos($userName);
            $userdata = Model_User_Util::getFullInfos($userName);
        }

        $user = array(
            'data' => array('info' => $userdata, 'timestamp' => time(), 'totalnum' => $tagsnum),
            'ret' => 0,
            'msg' => 'ok'
        );

        return $user;
    }

}