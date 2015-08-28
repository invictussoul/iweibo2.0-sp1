<?php
/**
 * 令牌表类
 * 
 * @author lvfeng
 */
class Table_Base_Token extends Table_Base
{
	/**
	 * 表名
	 * 
	 * @var string
	 */
	public $_table = '##__base_token';
	
	/**
	 * 字段列表
	 * 
	 * @var array
	 */
	public $_fields = array('tid', 'uid', 'sign', 'created');
	
	/**
	 * 主键字段名
	 * 
	 * @var string
	 */
	public $_idkey = 'tid';
	
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
