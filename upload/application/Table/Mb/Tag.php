<?php
/**
 * 标签表类
 * 
 * @author lvfeng
 */
class Table_Mb_Tag extends Table_Base
{
	/**
	 * 表名
	 * 
	 * @var string
	 */
	public $_table = '##__mb_tag';
	
	/**
	 * 字段列表
	 * 
	 * @var array
	 */
	public $_fields = array('id', 'tagname', 'usenum', 'visible','color');
	
	/**
	 * 主键字段名
	 * 
	 * @var string
	 */
	public $_idkey = 'id';
    
	public static $tableObject;
	
	/**
	 * 获得表实例
	 *
	 * @return object
	 */
	public static function getInstance()
    {
        if(isset(self::$tableObject))
            return self::$tableObject;
        self::$tableObject = new self();
        return self::$tableObject;
    }
    
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		if(!isset(self::$_data[$this->_table])) 
			self::$_data[$this->_table] = array();
	}
}
