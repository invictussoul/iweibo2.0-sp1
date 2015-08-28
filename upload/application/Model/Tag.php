<?php

/**
 * iweibo2.0
 * 
 * 标签操作model
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Model_Tag.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Model_Tag extends Core_Model
{
    const TAG_OPEN = 1; //微博标签数据源1平台数据 0 本地数据 
    const TAG_LOCAL = 0;
    //表名
    protected $_tableName = 'user_tag'; 
    
    protected static $_tagModel = array(
        self::TAG_LOCAL => 'Model_User_TagLocal',
        self::TAG_OPEN => 'Model_User_TagOpen'
    );
    private static $instance; # 保存类实例在此属性中

    // singleton 方法

    public static function singleton()
    {
        if (!isset(self::$instance))
        {
            if (self::getTagSrc() == self::TAG_LOCAL)
            {
                self::$instance = new self::$_tagModel[self::TAG_LOCAL];
            }
            else
            {
                self::$instance = new self::$_tagModel[self::TAG_OPEN];
            }
        }
        return self::$instance;
    }

    /**
     * @获取tag数据源类型
     * @param 
     * @return #0 云端tag ;1本地标签
     * @author echoyang
     * @time 2011/5/29
     */
    public static function getTagSrc()
    {
        return self::TAG_LOCAL;
    }

}