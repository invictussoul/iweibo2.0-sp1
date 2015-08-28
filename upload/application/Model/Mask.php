<?php

/**
 * 微博屏蔽列表
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Mask extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_mask';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array (
        'opentid',
        'time'
    );
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'opentid';
    /**
     * 缓存用到
     * @var type
     */
    public static $_cache_data = null;

    /**
     * 添加
     * @param int $opentids;
     * @return bool
     */
    public static function addMask ($opentids)
    {
        $_maskObj = new self();
        foreach ((array)$opentids as $opentid)
        {
            $sql = "Replace Into `" . $_maskObj->_tableName . "` Set `opentid`='" . Core_Db::sqlescape ($opentid) . "' , `time`='" . Core_Fun::time () . "' ";
            Core_Db::query ($sql, true);
        }
        self::updatecache ();
    }

    /**
     * 删除
     * @param array $opentids
     * @return bool
     */
    public static function deleteMask ($opentids)
    {
        $obj = new self();
        self::updatecache ();
        return $obj->remove ($opentids);
    }

    /**
     * 查询一条微博是否在屏蔽列表中
     * @param array $opentid
     * @return bool
     */
    public static function isMasked ($opentid)
    {
        if (!isset (self::$_cache_data)) {
            self::_makeCache ();
        }

        if (array_key_exists ($opentid, self::$_cache_data)) {
            return true;
        }

        return false;
    }

    /**
     * 查询一组微博是否在屏蔽列表中
     * @param array $opentids
     * @return array 在屏蔽列表中的微博id
     */
    public static function isMaskeds ($opentids)
    {
        if (!isset (self::$_cache_data)) {
            self::_makeCache ();
        }
        $_opentids = (array)$opentids;
        $_returns = array ();
        foreach ($_opentids as $_opentid)
        {
            if (array_key_exists ($_opentid, self::$_cache_data)) {
                $_returns[$_opentid] = 1;
            }
        }
        return $_returns;
    }

    private static function _makeCache ()
    {
        $obj = new self();
        $_cache_file = self::_getcachefile ();
        $include = Core_Cache::read ($_cache_file);
        if (Core_Cache::isMiss ($include)) {
            $_cacheSize = intval (Core_Config::get ('mask_cache_size', 'basic', 1000)) < 1000 ? 1000 : intval (Core_Config::get ('mask_cache_size', 'basic', 1000));
            $sql = 'SELECT `opentid` FROM `' . $obj->getTableName () . '` ORDER BY `time` DESC LIMIT ' . $_cacheSize;
            ;
            $_list = $obj->getAll ($sql);
            $_mask_list = array ();
            foreach ($_list as $key => $m)
            {
                $_mask_list[$m['opentid']] = 1;
            }
            $_expire = 60 * 60;
            Core_Cache::write ($_cache_file, $_mask_list, $_expire);
            $include = $_mask_list;
        }
        self::$_cache_data = $include;
    }

    /**
     * 更新缓存
     */
    private static function updatecache ()
    {
        Core_Cache::remove (self::_getcachefile ());
        if (isset (self::$_cache_data)) {
            self::$_cache_data = null;
        }
    }

    /**
     * 获取Cache文件
     */
    private static function _getcachefile ()
    {
        return '_mask_list.php';
    }

}