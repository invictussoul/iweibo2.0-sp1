<?php

/**
 * 微博
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Blog extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_blog';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array (
        'id',
        'uid',
        'opentid',
        'username',
        'nickname',
        'isauth',
        'account',
        'origintid',
        'originopentid',
        'content',
        'comefrom',
        'dateline',
        'lastcomment',
        'ip',
        'comments',
        'distributions',
        'picture',
        'state'
    );
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';

    //微博审核状态 0:未审核 2:审核通过 -1:审核未通过' 1:自动通过
    const STATE_NOMAL = 0;
    const STATE_MASKED = -1;
    const STATE_PASS = 1;
    const STATE_AUTO_PASS = 2;

    public $STATE = array (
        self::STATE_NOMAL => '待审核',
        self::STATE_PASS => '已通过',
        self::STATE_MASKED => '已屏蔽',
        self::STATE_AUTO_PASS => '自动通过'
    );
    //微博类型  //1广播 2 转播 3 私信 4 对话
    const TYPE_CREATE = 1;  //原创广播
    const TYPE_RETWEET = 2;  //转播
    const TYPE_BOX = 3;   //私信
    const TYPE_DIALOG = 4;  //对话
    const TYPE_EMPTYT = 5;  //空回
    const TYPE_MENTION = 6;  //提到
    const TYPE_DIANPING = 7;  //评论

    public $TYPE = array (
        self::TYPE_CREATE => '原创',
        self::TYPE_RETWEET => '转播',
        self::TYPE_DIALOG => '对话',
        self::TYPE_DIANPING => '评论'
    );

    /**
     * 添加本地微博
     * @param array $blog
     * @return insertid|true|false 返回insertid or add 里的主键 否则返回 true or false
     */
    public static function addBlog ($blog)
    {
        $obj = new self();
        $opentid = $blog['id'];
        if (isset ($txt)) {
            $txt = $blog['txt'];
        } else {
            $txt = $blog['text'];
        }
        $content = $blog['text'];
        //白名单用户不审核
        if (Model_User_Member::isTrustUser ($blog['name'])) {
            $visible = true;
            $state = self::STATE_AUTO_PASS;
        } else {
            //词语过滤
            $_filter = Model_Filter::checkContent ($txt);

            if ($_filter > 0) {
                if ($_filter == 1) {
                    //含敏感词直接进审核箱
                    $visible = false;
                    $state = self::STATE_NOMAL;
                    Model_Mask::addMask ($opentid);
                } elseif ($_filter == 2) {
                    //含禁止内容，禁止发布
                    $visible = false;
                    $state = self::STATE_MASKED;
                    Model_Mask::addMask ($opentid);
                }
            } else {
                $visible = true;
                $state = self::STATE_AUTO_PASS;
            }
            //先审后发
            if (Core_Config::get ('censor', 'basic', 0)) {
                Model_Mask::addMask ($opentid);
                $visible = false;
                $state = self::STATE_NOMAL;
            }
        }
        $_type = (int)$blog['type'];
        //1广播 2 转播 3 对话 4 评论
        if ($_type > 1) {
            $originopentid = $blog['originopentid'];
            $_origin_blog = $obj->getBlogByOriginopentid ($originopentid);
            $origintid = $_origin_blog['id'];
        } else {
            $originopentid = 0;
            $_origin_blog = 0;
            $origintid = 0;
        }

        $user = Model_User_Util::getInfo ($blog['name']);

        if (isset ($user['uid'])) {
            $uid = $user['uid'];
            $username = $user['username'];
            $nickname = $user['nick'];
            $isauth = $user['is_auth'];
        }

        $account = $blog['name'];

        $comefrom = $blog['from'];
        $dateline = $blog['timestamp'];
        $picture = $blog['image'];
        $audio = $blog['music'];

        if (isset ($blog['video']['realurl'])) {
            $video = $blog['video']['realurl'];
        } else {
            $video = '';
        }
        if (isset ($blog['ip'])) {
            $ip = $blog['ip'];
        } else {
            $ip = Core_Comm_Util::getClientIp ();
        }

        $_blog = array (
            'uid' => $uid,
            'username' => $username,
            'nickname' => $nickname,
            'isauth' => $isauth,
            'type' => $_type,
            'opentid' => $opentid,
            'account' => $account,
            'origintid' => $origintid,
            'originopentid' => $originopentid,
            'txt' => $txt,
            'content' => $content,
            'comefrom' => $comefrom,
            'dateline' => $dateline,
            'ip' => $ip,
            'picture' => $picture,
            'audio' => $audio,
            'video' => $video,
            'state' => $state,
            'visible' => $visible
        );
        $r = $obj->add ($_blog);
        //提取话题
        $_topics = self::getTopicByContent ($txt);
        if (!empty ($_topics)) {
            foreach ($_topics as $topic)
            {
                Model_Topic::addTopic ($topic, true, $opentid);
            }
        }
        return $r;
    }

    /**
     * 根据源平台id查找本地消息,转发时用到
     * @param type $opentid
     * @return type
     */
    public static function getBlogByOpentid ($opentid)
    {
        if (strlen ($opentid) > 0) {
            $obj = new self();
            $sql = 'SELECT * FROM `' . $obj->getTableName () . '` WHERE `opentid` = \'' . Core_Db::sqlescape ($opentid) . '\'';
            return $obj->fetchOne ($sql);
        }
    }

    /**
     * 根据源平台id查找本地消息,转发时用到
     * @param type $getBlogByOriginopentid
     * @return type
     */
    public static function getBlogByOriginopentid ($originopentid)
    {
        if (strlen ($originopentid) > 0) {
            $obj = new self();
            $sql = 'SELECT * FROM `' . $obj->getTableName () . '` WHERE `originopentid` = \'' . Core_Db::sqlescape ($originopentid) . '\'';
            return $obj->fetchOne ($sql);
        }
    }

    /**
     * 根据平台ID查找本地微博
     * @param type $opentid
     * @return type
     */
    public static function findByOpentid ($opentid)
    {
        $obj = new self();
        if (isset ($opentid)) {
            $sql = 'SELECT * FROM `' . $obj->getTableName () . '` WHERE `opentid` = \'' . Core_Db::sqlescape ($opentid) . '\'';
            return $obj->fetchOne ($sql);
        } else {
            return false;
        }
    }

    /**
     * 根据平台ID删除本地微博
     * @param int|array $opentid  待删除的微博ID
     * @return bool 是否删除成功
     */
    public static function deleteBlogByOpentid ($opentid)
    {
        $obj = new self();
        $_blog = $obj->findByOpentid ($opentid);
        $obj->deleteWallBlogByOpentid ($opentid);
        if ($_blog) {
            return $obj->deleteBlog ($_blog['id']);
        }
        return null;
    }

    /**
     * 删除本地微博
     * @param int|array $ids  待删除的微博ID
     * @return bool 是否删除成功
     */
    private function deleteBlog ($ids)
    {
        $obj = new self();
        return $obj->remove ($ids);
    }

    /**
     * 获得列表
     *
     * @param array $whereArr
     * @param array $orderByArr
     * @param array $limitArr
     * @return array
     */
    public function getBlogList ($whereArr=array (), $orderByArr=array (), $limitArr=array ())
    {
        return $this->queryAll ("*", $whereArr, $orderByArr, $limitArr);
    }

    /**
     * 屏蔽
     * @param type $opentids
     */
    public function maskBlog ($opentids)
    {
        $_opentidstr = Core_Comm_Util::array2string ($opentids);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `visible` = 0 WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `state` = ' . self::STATE_MASKED . ' WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
    }

    /**
     * 取消屏蔽
     * @param type $opentids
     */
    public function unmaskBlog ($opentids)
    {
        $_opentidstr = Core_Comm_Util::array2string ($opentids);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `visible` = 1 WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `state` = ' . self::STATE_PASS . ' WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
    }

    /**
     * 解析##
     * @param string $content
     * @return array
     */
    public static function getTopicByContent ($content)
    {
        //(#\s*[^#\s]{1}[^#]{0,59}?\s*#) 官网的

        if (strpos ($content, '#') !== FALSE && preg_match_all ("/.*#([^#]+?)#.*|/iU", $content, $matches)) {
            // if (strpos ($content, '#') !== FALSE && preg_match_all ('/(#\s*[^#\s]{1}[^#]{0,59}?\s*#)/', $content, $matches)) {
            $_topics = array ();
            foreach ($matches[1] as $key => $match)
            {
                if (self::checkTopic ($match)) {
                    $_topics[] = $match;
                }
            }
            $topics = array_filter (array_unique ($_topics));
            return $topics;
        } else {
            return null;
        }
    }

    /**
     * 检查topic是否合法
     * @param type $topic
     */
    public static function checkTopic ($topic)
    {
        $_topic = trim ($topic);
        if (strlen ($_topic) == 0 || strlen ($_topic) > 60) {
            return false;
        }
        if ('.' == $_topic) {
            return false;
        }
        if ('输入话题标题' == $_topic) {
            return false;
        }
        return true;
    }

    /**
     * 根据话题墙id获取消息
     * @param int $wallTopicId 本地话题ID
     * @param int $start
     * @param int $limit
     * @return array
     */
    public static function getBlogByWallTopicId ($wallTopicId, $start = null, $limit = null, $userHead = 50)
    {
        $obj = new self();
        if (intval ($start) == 0 || $start == 0) {
            $_orderby = 'DESC';
        } else {
            $_orderby = 'ASC';
        }
        $sql = 'SELECT `opentid` FROM `##__mb_topicblog` WHERE `visible` = 1 AND `topicid`=' . intval ($wallTopicId) . ' ORDER BY `censortime`,`id` ' . $_orderby;
        if (isset ($start) && isset ($limit)) {
            $_blogIds = $obj->getAll ($sql, $limit, $start);
        } else {
            $_blogIds = $obj->getAll ($sql);
        }
        if (count ($_blogIds) > 0) {
            $blogIds = array ();
            foreach ($_blogIds as $blogId)
            {
                $blogIds[] = $blogId['opentid'];
            }
            $sql1 = 'SELECT * FROM  `' . $obj->_tableName . '` WHERE `opentid` IN(' . implode (',', $blogIds) . ') ORDER BY `censortime`,`id` ' . $_orderby;
            $blogs = $obj->getAll ($sql1);
            foreach ($blogs as &$blog)
            {
                $user = Model_User_FriendLocal::localFormatU ($blog['account'], $userHead);
                $blog['user_head'] = $user['head'];
            }
            return $blogs;
        }
    }

    public static function getBlogByWallTopicIdMore ($wallTopicId, $start = null, $limit = null)
    {
        $obj = new self();
        if (intval ($start) == 0 || $start == 0) {
            $_orderby = 'DESC';
        } else {
            $_orderby = 'ASC';
        }
        $sql = 'SELECT `opentid` FROM `##__mb_topicblog` WHERE `visible` = 1 AND `topicid`=' . intval ($wallTopicId) . ' ORDER BY `censortime` ' . $_orderby;
        if (isset ($start) && isset ($limit)) {
            $_blogIds = $obj->getAll ($sql, $limit, $start);
        } else {
            $_blogIds = $obj->getAll ($sql);
        }
        if (count ($_blogIds) > 0) {
            $blogIds = array ();
            foreach ($_blogIds as $blogId)
            {
                $blogIds[] = $blogId['opentid'];
            }
            $sql1 = 'SELECT * FROM  `' . $obj->_tableName . '` WHERE `opentid` IN(' . implode (',', $blogIds) . ') ORDER BY `censortime` ' . $_orderby;
            $blogs = $obj->getAll ($sql1);
            foreach ($blogs as &$blog)
            {
                $user = Model_User_FriendLocal::localFormatU ($blog['account'], 120);
                $blog['user_head'] = $user['head'];
            }
            return $blogs;
        }
    }

    /**
     * 根据话题墙id获取消息总数
     * @param int $wallTopicId 本地话题ID
     */
    public static function getBlogByWallTopicIdCount ($wallTopicId)
    {
        $obj = new self();
        $sql = 'SELECT COUNT(*) AS C FROM `##__mb_topicblog` WHERE `visible` = 1 AND `topicid`=' . intval ($wallTopicId) . ' ORDER BY `censortime` DESC';
        $_c = $obj->fetchOne ($sql);
        return intval ($_c['C']);
    }

    /**
     * 根据话题名获取blog，
     * 设计多表查询，仅用于后台上墙话题消息审核
     * @param string $whereTopicTitle 话题名称查询条件 如 LIKE '$test$' 或 = 'test'
     * @param type $num
     * @param type $start
     * @return type
     */
    public function getBlogByTopicTitle ($where, $num, $start)
    {
        $sql = 'SELECT
blog.id AS id,
r.opentid AS opentid,
r.topicid AS topicid,
blog.censortime AS censortime,
blog.txt AS txt,
blog.content AS content,
topic.title AS topicname,
blog.account AS account,
blog.dateline AS dateline,
r.visible AS visible
FROM
`##__mb_topicblog` AS r
INNER JOIN `##__mb_blog` AS blog ON r.opentid = blog.opentid
INNER JOIN `##__mb_topic` AS topic ON r.topicid = topic.tid ' . $where;
        $r = Core_Db::fetchAll ($sql, $num, $start);
        return $r;
    }

    public function getBlogByTopicTitleCount ($where)
    {
        $sql = 'SELECT
count(*) AS row_count
FROM
`##__mb_topicblog` AS r
INNER JOIN `##__mb_blog` AS blog ON r.opentid = blog.opentid
INNER JOIN `##__mb_topic` AS topic ON r.topicid = topic.tid ' . $where;
        $r = Core_Db::fetchAll ($sql);
        return intval ($r[0]['row_count']);
    }

    /**
     * 屏蔽上墙消息
     * @param type $opentids
     */
    public function lockWallBlog ($opentids)
    {
        $_opentidstr = Core_Comm_Util::array2string ($opentids);
        $sql = 'UPDATE `##__mb_topicblog` SET `visible` = 0 WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
    }

    /**
     * 开放上墙消息
     * @param type $opentids
     */
    public function unLockWallBlog ($opentids)
    {
        $_opentidstr = Core_Comm_Util::array2string ($opentids);
        $sql = 'UPDATE `##__mb_topicblog` SET `visible` = 1 , `censortime` =' . Core_Fun::time () . '  WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
        $sql = 'UPDATE `' . $this->_tableName . '` SET `censortime` =' . Core_Fun::time () . '  WHERE `opentid` IN (' . $_opentidstr . ')';
        $this->query ($sql);
    }

    /**
     * 删除上墙关系表中的微博
     * @param array $opentids
     */
    public static function deleteWallBlogByOpentid ($opentid)
    {
        $sql = 'DELETE FROM `##__mb_topicblog` WHERE `opentid` = \'' . Core_Db::sqlescape ($opentid) . '\'';
        self::query ($sql);
    }

}