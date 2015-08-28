<?php

/**
 * 微博话题
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Topic extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_topic';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array (
        'tid',
        'title',
        'mblogs',
        'state',
        'wall',
        'wallcensor',
        'wallstarttime',
        'wallendtime',
    );
    /**
     * 状态
     */
    const STATE_NOMAL = 0;
    const STATE_LOCKED = 1;

    public $STATE = array (
        self::STATE_NOMAL => '正常',
        self::STATE_LOCKED => '锁定'
    );
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'tid';
    /**
     * 缓存用到
     * @var type
     */
    public static $_cache_data = null;

    /**
     * 添加话题
     * @param string|array $topic
     * @param bool $updateMblog
     * @param string $opentid 平台消息id 如果存在则在话题微博关系表中增加关系,用户上墙
     * @return bool
     */
    public static function addTopic ($topic, $updateMblog = true, $opentid = null)
    {
        $obj = new self();
        if (is_array ($topic)) {
            if (strlen ($topic['title']) == 0) {
                return;
            }
            $_topic = $obj->getTopicByTitle ($topic['title']);
            if (!$_topic) {
                if ($updateMblog) {
                    $topic['mblogs'] = 1;
                }
                $topic['tid'] = null;
                $r = $obj->add ($topic);
            } else {
                $topic['tid'] = $_topic['tid'];
                if ($updateMblog) {
                    $topic['mblogs'] = intval ($_topic['mblogs'] + 1);
                }
                $r = $obj->update ($topic);
            }
        } else {
            if (strlen ($topic) == 0) {
                return;
            }
            $_topic = $obj->getTopicByTitle ($topic);
            if (!$_topic) {
                $_topic['title'] = $topic;
                if ($updateMblog) {
                    $_topic['mblogs'] = 1;
                }
                $r = $obj->add ($_topic);
            } else {
                $updateMblog && $_topic['mblogs'] = intval ($_topic['mblogs'] + 1);

                //只有在有效期内的上墙话题才在话题微博关系表中增加关系（上墙）
                if (strlen ($opentid) > 0
                        && $_topic['wall']
                        && Core_Fun::time () >= $_topic['wallstarttime']
                        && Core_Fun::time () <= $_topic['wallendtime']) {
                    //需要先审后发的上墙消息不显示
                    $visible = !(bool)$_topic['wallcensor'];
                    //白名单用户不受审核限制
                    if (Model_User_Member::isTrustUser ()) {
                        $visible = true;
                    }

                    self::addTopicBlogRelation ($_topic['tid'], $opentid, $visible);
                }
                $r = $obj->update ($_topic);
            }
        }
        self::updatecache ();
        return $r;
    }

    /**
     * 在话题微博关系表中增加关系
     * @param type $topicid
     * @param type $opentid
     * @param type $visible
     * @return type
     */
    public static function addTopicBlogRelation ($topicid, $opentid, $visible = false)
    {
        if (intval ($topicid) > 0 && strlen ($opentid) > 0) {
            $sql1 = 'REPLACE INTO `##__mb_topicblog` SET `topicid` = ' . intval ($topicid)
                    . ', `opentid` = ' . Core_db::sqlescape ($opentid)
                    . ', `dateline` = ' . Core_Fun::time ()
                    . ', `visible` = ' . intval ((bool)$visible);
            $r1 = Core_Db::query ($sql1);
            if ($visible) {
                $sql2 = 'UPDATE `##__mb_blog` SET `censortime` = \'' .
                        Core_Fun::time () . '\' WHERE `opentid` = \'' . Core_db::sqlescape ($opentid) . '\'';
                $r2 = Core_Db::query ($sql2);
                $sql3 = 'UPDATE `##__mb_topicblog` SET `censortime` = \'' .
                        Core_Fun::time () . '\' WHERE `opentid` = \'' . Core_db::sqlescape ($opentid) . '\'';
                $r3 = Core_Db::query ($sql3);
            }
            return $r1;
        }
        return false;
    }

    /**
     * 修改
     * @param string $topic;
     * @return bool
     */
    public function editTopic ($topic, $updateMblog = false)
    {
        return self::addTopic ($topic, $updateMblog);
    }

    /**
     * 根据话题名称获取话题
     * @param type $topic
     * @return bool
     */
    public function getTopicByTitle ($topic)
    {
        return $this->queryOne ('*', array (array ('title', $topic)));
    }

    /**
     * 根据话题ID获取话题
     * @param int $tid
     * @return bool
     */
    public function getTopicByTid ($tid)
    {
        return $this->queryOne ('*', array (array ('tid', $tid)));
    }

    /**
     * 锁定
     * @param type $tids
     */
    public function lockTopic ($tids)
    {
        $_tidstr = Core_Comm_Util::array2string ($tids);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `state` = ' . self::STATE_LOCKED . ' WHERE `tid` IN (' . $_tidstr . ')';
        $this->query ($sql);
        self::updatecache ();
    }

    /**
     * 开放
     * @param type $tids
     */
    public function unLockTopic ($tids)
    {
        $_tidstr = Core_Comm_Util::array2string ($tids);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `state` = ' . self::STATE_NOMAL . ' WHERE `tid` IN (' . $_tidstr . ')';
        $this->query ($sql);
        self::updatecache ();
    }

    /**
     * 查询话题是否在屏蔽列表中
     * @param array $title
     * @return bool
     */
    public static function isMasked ($title)
    {

        if (!isset (self::$_cache_data)) {
            self::_makeCache ();
        }

        if (array_key_exists ($title, self::$_cache_data)) {
            return true;
        }

        return false;
    }

    /**
     * 建立缓存
     */
    public static function _makeCache ()
    {
        self::updatecache ();
        $_topicObj = new self();
        $_cache_file = self::_getcachefile ();
        $include = Core_Cache::read ($_cache_file);
        if (Core_Cache::isMiss ($include)) {
            $_cacheSize = intval (Core_Config::get ('topic_cache_size', 'basic', 1000)) < 1000 ? 1000 : intval (Core_Config::get ('topic_cache_size', 'basic', 1000));
            $sql = 'SELECT `title` FROM `' . $_topicObj->getTableName () . '` WHERE `state` = ' . self::STATE_LOCKED . ' ORDER BY `tid` DESC LIMIT ' . $_cacheSize;
            $_list = $_topicObj->getAll ($sql);
            $_topic_list = array ();
            foreach ($_list as $key => $m)
            {
                $_topic_list[$m['title']] = 1;
            }
            $_expire = 60 * 60;
            Core_Cache::write ($_cache_file, $_topic_list, $_expire);
            $include = $_topic_list;
        }
        self::$_cache_data = $include;
    }

    /**
     * 更新缓存
     */
    public static function updatecache ()
    {
        Core_Cache::remove (self::_getcachefile ());
        if (isset (self::$_cache_data)) {
            self::$_cache_data = null;
        }
    }

    /**
     * 获取Cache文件
     */
    public static function _getcachefile ()
    {
        return '_mask_topic_list.php';
    }

    /**
     * 是否正在上墙的话题
     * @param type $tid
     */
    public static function isWallTopic ($tid)
    {
        $topicObj = new self();
        $topic = $topicObj->getTopicByTid ($tid);
        if ($topic) {
            if ($topic['state'] || !$topic['wall']) {
                return false;
            }
            $_time = Core_Fun::time ();
            if ($_time >= $topic['wallstarttime'] && $_time <= $topic['wallendtime']) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 根据类型获取上墙话题
     * @param type $type 0:未开始 1:进行中，2：已经结束
     * @param type $start
     * @param type $limit
     * @return array
     */
    public static function getWallByType ($type = 0, $start = null, $limit = null)
    {
        $obj = new self();
        $_time = Core_Fun::time ();
        if (1 == $type) {
            $_and = 'AND `wallstarttime` <= ' . $_time . ' AND `wallendtime` >= ' . $_time;
        }
        if (2 == $type) {
            $_and = 'AND `wallendtime` < ' . $_time;
        }
        if (0 == $type) {
            $_and = 'AND `wallstarttime` > ' . $_time;
        }
        $sql = 'SELECT * FROM `' . $obj->getTableName ()
                . '` WHERE `state` = ' . self::STATE_NOMAL
                . ' AND `wall`=\'1\' ' .
                $_and
                . ' ORDER BY `wallstarttime` ASC';
        if (isset ($start) && isset ($limit)) {
            $_topics = $obj->getAll ($sql, $limit, $start);
        } else {
            $_topics = $obj->getAll ($sql);
        }
        return $_topics;
    }

}