<?php

/**
 * 数据库对外接口方法
 * @author Icehu
 */
class Core_Db
{

	/**
	 * 快速获取数据表中的一个字段
	 *
	 * @param string $sql 需要转义！
	 * @param bool $master 是否读取主库
	 * @return string
	 * @author Icehu
	 */
	public static function getOne($sql, $master=false)
	{
		return self::Db()->getOne($sql, $master);
	}

	/**
	 * 快速从数据表中读取一行
	 * @param string $sql 需要转义！
	 * @param bool $master 是否读取主库
	 * @return array 
	 * @author Icehu
	 */
	public static function fetchOne($sql, $master=false)
	{
		return self::Db()->fetchOne($sql, $master);
	}

	/**
	 *
	 * 从数据库获得多条记录，返回以主键值为下标的数组
	 *
	 * @param sting $sql
	 * @param number $num 返回数量
	 * @param number $start 起始行
	 * @param string $rid 返回的主键ID
	 * @param callback $callback
	 * @param string $dbname
	 * @return array|null
	 * @author Icehu
	 */
	public static function fetchAll($sql, $num=null, $start=0, $rid=null, $callback=null)
	{
		if (!$rid)
		{
			$rid = 'id';
		}
		return self::Db()->fetchAll($sql, $num, $start, $rid, $callback);
	}

	/**
	 * 直接执行一个SQL
	 * 在sql语句中用 ##__ 代表表前缀，接口会自动替换为正确的前缀
	 * 
	 * @param string $sql 主义需要转移
	 * @param bool $master 是否读取主库
	 * @return resource
	 */
	public static function query($sql, $master=false)
	{
		return self::Db()->query($sql, $master);
	}

	/**
	 * <pre>
	 * 指定一个关联数组 比如
	 * array('field1'=>1,'field2'=>2)
	 * key是字段名称，val是字段值
	 * 向一个Table中插入一行
	 * 只有在$safe中允许的key，才会被插入。
	 * </pre>
	 * @param string $table 数据表名称
	 * @param array $array	插入的数据，看例子	不要转义数据，系统会自动转义，否则会被转义两次
	 * @param array $safe 安全限制数组，留空不限制
	 * @return insertid|bool
	 * @author Icehu
	 */
	public static function insert($table, $array, $safe=array())
	{
		return self::Db()->insert($table, $array, $safe);
	}

	/**
	 * 同Insert，区别是使用Replace
	 * 
	 * @param string $table 数据表名称
	 * @param array $array	插入的数据，看例子	不要转义数据，系统会自动转义，否则会被转义两次
	 * @param array $safe 安全限制数组，留空不限制
	 * @return insertid|bool
	 * @author Icehu
	 */
	public static function replace($table, $array, $safe=array())
	{
		return self::Db()->replace($table, $array, $safe);
	}

	/**
	 * Update操作的快捷封装
	 *
	 * @param string $table	数据表名称
	 * @param string $where	where条件
	 * @param array $array	更新的关联数组	不要转义数据，系统会自动转义，否则会被转义两次
	 * @param array $safe 安全限制数组
	 * @param array $unset 不加“ ' ”的字段，可以用来写表达式 比如 num = num+1
	 * @return bool
	 * @author Icehu
	 */
	public static function update($table, $where, $array, $safe=array(), $unset=array())
	{
		return self::Db()->update($table, $where, $array, $safe, $unset);
	}

	/**
	 * 获取 insert 时产生的 auto_incret ID
	 * @return number
	 * @author Icehu
	 */
	public static function insertId()
	{
		return self::Db()->insertId();
	}

	/**
	 * 对字符串进行转移，保证入库安全
	 * @param string $str
	 * @return mix
	 */
	public static function sqlescape($str)
	{
		if (is_array($str))
		{
			foreach ($str as $_key => $_var)
			{
				$str[$_key] = self::sqlescape($_var);
			}
			return $str;
		}
		else
		{
			return self::Db()->escape($str);
		}
	}

	/**
	 * 获取MySQL Driver对象
	 * @return Core_Db_Driver_Mysql
	 * @author Icehu
	 */
	public static function Db()
	{
		//todo mysqli driver
//		if ($cfg['mysqli'] ) {
//			$return = Core_Db_Driver_Mysqli::getInstance(self::getConfig());
//		}else {
		$return = Core_Db_Driver_Mysql::getInstance(self::getConfig());
//		}
		return $return;
	}
	
	/**
	 * 获取数组
	 *
	 * @param resource $query
	 * @param sting $return_num
	 * @return array
	 */
	public static function fetchArray($query,$return_num=MYSQL_ASSOC)
	{
		return self::Db()->fetchArray($query,$return_num);
	}
	/**
	 * 获取记录数
	 *
	 * @param resource $query
	 * @return Integer
	 */
	public static function num_rows($query)
	{
		return self::Db()->num_rows($query);
	}
	
	/**
	 * 获取数据库服务器信息
	 *
	 * @return mixed 
	 */
	public static function server_info()
	{
		return self::Db()->server_info();	
	}
	

	protected static $config = null;

	/**
	 * 初始化数据库配置
	 * @return array
	 */
	protected static function getConfig()
	{
		if(null === self::$config)
		{
			self::$config = include ROOT . 'config/db.php';
		}
		return self::$config;
	}

}