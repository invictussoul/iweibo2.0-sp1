<?php

/**
 * 词语过滤
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Filter extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_filter';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array ('id', 'word', 'replacement');
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';
    /**
     * 缓存文件
     * @var type
     */
    public static $_cached_filter = null;

    /**
     * 添加
     * @param array $filter
     * @return bool
     */
    public function addFilter ($filter)
    {
        $sql = "Replace Into `" . $this->_tableName . "` Set `word`='" . Core_Db::sqlescape ($filter['word']) . "' , `replacement`='" . Core_Db::sqlescape ($filter['replacement']) . "' ";
        Core_Db::query ($sql, true);
        self::updatecache ();
    }

    /**
     * 验证关键词是否已存在
     * 添加时时使用
     * @param string $word
     * @return boolean
     */
    public static function checkWorkExists ($word)
    {
        $obj = new self();
        return $obj->getCount (array (array ('word', $word)));
    }

    /**
     * 查询一组微博是否在屏蔽列表中
     * @param array $opentids
     * @return array 在屏蔽列表中的微博id
     */
    public static function isMasked ($opentids)
    {
        if (!isset (self::$_cached_filter)) {
            self::_makeCache ();
        }
        $_opentids = (array)$opentids;
        $_returns = array ();
        foreach ($_opentids as $_opentid)
        {
            if (array_key_exists ($_opentid, self::$_cached_filter)) {
                $_returns[$_opentid] = 1;
            }
        }
        return $_returns;
    }

    /**
     * 写缓存
     */
    private static function _makeCache ()
    {
        $obj = new self();
        $_cache_file = self::_getcachefile ();
        if (null === ($include = Core_Cache::read ($_cache_file))) {
            $sql = 'SELECT `word`,`replacement` FROM `' . $obj->getTableName () . '`';
            ;
            $_list = $obj->getAll ($sql);
            $banned = $censor = array ();
            $data = array ('filter' => array (), 'banned' => '', 'censor' => '');
            foreach ($_list as $key => $filter)
            {
                $filter['word'] = preg_replace ("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote ($filter['word'], '/'));
                switch ($filter['replacement'])
                {
                    case '2':
                        $banned[] = $filter['word'];
                        break;
                    case '1':
                        $censor[] = $filter['word'];
                        break;
                    default:
                        $data['filter']['word'][] = '/' . $filter['word'] . '/i';
                        $data['filter']['replace'][] = $filter['replacement'];
                        break;
                }
            }
            if ($banned) {
                $data['banned'] = '/(' . implode ('|', $banned) . ')/i';
            }
            if ($censor) {
                $data['censor'] = '/(' . implode ('|', $censor) . ')/i';
            }
            $_expire = 60 * 60;
            Core_Cache::write ($_cache_file, $data, $_expire);
            $include = Core_Cache::read ($_cache_file);
        }
        self::$_cached_filter = $include;
    }

    /**
     * 更新缓存
     */
    private static function updatecache ()
    {
        Core_Cache::remove (self::_getcachefile ());
        if (isset (self::$_cached_filter)) {
            unset (self::$_cached_filter);
        }
    }

    /**
     * 获取Cache文件
     */
    private static function _getcachefile ()
    {
        return '_filter_list.php';
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function deleteFilter ($ids)
    {
        $obj = new self();
        self::updatecache ();
        return $this->remove ($ids);
    }

    /**
     * 修改
     * @param type $update
     * @return type
     */
    public function updateFilter ($update)
    {
        self::updatecache ();
        return $this->update ($update);
    }

    //note 检查微博内容合法性 长度 词语过滤
    public static function checkContent ($content)
    {
        //如果当前用户是白名单用户，直接放行
        if (Model_User_Member::isTrustUser ()) {
            return 0;
        }

        $obj = new self();
        if ($obj->_checkCensor ($content)) {
            return 1;
        } elseif ($obj->_checkBanned ($content)) {
            return 2;
        }elseif($obj->_checkTopic ($content)){
            return 2;
        }

        return 0;
    }

    /**
     * 检查内容中的话题是否包含被禁止的话题
     * @param type $content
     * @return int
     */
    private function _checkTopic ($content)
    {
        $wellArray = Model_Blog::getTopicByContent ($content);
        //包含被锁定话题，禁止发送
        if ($wellArray) {
            foreach ($wellArray as $topic)
            {
                return Model_Topic::isMasked ($topic);
            }
        }
    }

    //检查内容是否包含禁止发表的词汇
    private function _checkBanned ($content)
    {
        if (!isset (self::$_cached_filter)) {
            self::_makeCache ();
        }
        return self::$_cached_filter['banned'] && @preg_match (self::$_cached_filter['banned'], $content);
    }

    //检查内容是否包含需要审核的词汇
    private function _checkCensor ($content)
    {
        if (!isset (self::$_cached_filter)) {
            self::_makeCache ();
        }
        return self::$_cached_filter['censor'] && @preg_match (self::$_cached_filter['censor'], $content);
    }

    //词语过滤 - 替换敏感词 暂时未使用
    private function filter ($content)
    {
        if (!isset (self::$_cached_filter)) {
            self::_makeCache ();
        }
        return empty (self::$_cached_filter['filter']['word']) ? $content :
                @preg_replace (self::$_cached_filter['filter']['word'], self::$_cached_filter['filter']['replace'], $content);
    }

}