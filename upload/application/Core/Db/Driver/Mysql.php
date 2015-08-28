<?php

/**
 * 使用mysql_函数的Mysql Driver
 * 建议封装一层对此类的调用，不建议直接调用该接口
 *
 * @author Icehu
 */
class Core_Db_Driver_Mysql
{

	/**
	 * self
	 * @var Core_Db_Driver_Mysql
	 */
	private static $_instance;
	private static $_cfg;
	private $dbLink = array();
	private $clink = null;
	private $c_dbname = null;
	public static $query_num;

	/**
	 * 获取单例对象
	 * @return Core_Db_Driver_Mysql
	 * @author Icehu
	 */
	public static function getInstance($cfg)
	{
		if (self::$_instance)
		{
			return self::$_instance;
		}
		self::$_cfg = $cfg;
		self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * 创建数据库链接
	 * 只有在需要创建时才创建，最小化调用
	 *
	 * @param bool $read 是否只读链接
	 * @return resource 返回link_source
	 * @author Icehu
	 */
	private function getdbLink($read=false)
	{
		$dbname = self::$_cfg['dbName'];
		if ($read && isset(self::$_cfg['extConfig']['read']['dbhost']))
		{
			$config = self::$_cfg['extConfig']['read'];
		}
		else if (isset(self::$_cfg['extConfig']['write']['dbhost']))
		{
			$config = self::$_cfg['extConfig']['write'];
		}
		else
		{
			$config = array(
				'dbhost' => self::$_cfg['dbHost'],
				'dbuser' => self::$_cfg['dbUser'],
				'dbpwd' => self::$_cfg['dbPwd'],
				'dbpconnect' => self::$_cfg['dbPconnect'],
			);
		}
		$key = implode('_', $config);
		if (!isset($this->dbLink[$key]) || !is_resource($this->dbLink[$key]))
		{
			if ($config['dbpconnect'])
			{
				$this->dbLink[$key] = mysql_pconnect($config['dbhost'], $config['dbuser'], $config['dbpwd'], true) or die('Connect Db Error');
			}
			else
			{
				$this->dbLink[$key] = mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpwd'], true) or die('Connect Db Error');
			}
			if(mysql_get_server_info($this->dbLink[$key]) >= '4.1')
			{
				mysql_query('SET character_set_connection=utf8,character_set_results=utf8,character_set_client=binary,sql_mode=\'\'', $this->dbLink[$key]);
			}
		}
		if ($this->c_dbname[$key] != $dbname)
		{
			mysql_select_db($dbname, $this->dbLink[$key]) or die('Can\'t use ' . $dbname);
			$this->c_dbname[$key] = $dbname;
		}
		return $this->clink = $this->dbLink[$key];
	}

	/**
	 * 执行一条SQL 查询
	 * @param string $sql sql语句，需要做安全转义
	 * @param bool $master 是否查询主库
	 * @return resource 返回link_source
	 * @author Icehu
	 */
	public function query($sql, $master=false)
	{
		$sql = $this->changequery($sql);
		if (!$master && preg_match('/^select/i', ltrim($sql)))
		{
			$link = $this->getdbLink(true);
		}
		else
		{
			$link = $this->getdbLink();
		}
		if (!$source = mysql_query($sql, $link))
			$this->halt($sql, $link);
		++self::$query_num;
		return $source;
	}

	/**
	 * 对查询进行表前缀替换
	 * 将 ##__替换为配置中的前缀
	 *
	 * @param string $sql 查询的sql
	 * @return string
	 * @author Icehu
	 */
	public function changequery($sql)
	{
		return str_replace('##__', self::$_cfg['dbPrefix'], $sql);
	}

	/**
	 * 对查询的字符串进行安全转义
	 * @param string $str
	 * @return string
	 * @author Icehu
	 */
	public function escape($str)
	{
		return addslashes($str);
	}

	/**
	 * 释放结果集
	 *
	 * @param resource $resource 结果集
	 * @author Icehu
	 */
	public function free($resource)
	{
		if (is_resource($resource))
		{
			mysql_free_result($resource);
		}
	}

	/**
	 * 快捷的获得一个字段信息
	 *
	 * @param string $sql sql语句，需要做安全转义
	 * @return mix|null
	 * @author Icehu
	 */
	public function getOne($sql, $master=false)
	{
		$resource = $this->query($sql, $master);
		if ($resource)
		{
			$row = mysql_fetch_array($resource, MYSQL_NUM);
			$this->free($resource);
			return $row[0];
		}
		else
		{
			return null;
		}
	}

	/**
	 * 获取服务器信息
	 * @return string
	 * @author Icehu
	 */
	public function server_info()
	{
		return mysql_get_server_info($this->getdbLink(true));
	}

	/**
	 * 快捷的获得一条记录
	 *
	 * @param string $sql sql语句，需要做安全转义
	 * @return array|null
	 * @author Icehu
	 */
	public function fetchOne($sql, $master=false)
	{
		$resource = $this->query($sql, $master);
		if ($resource)
		{
			$row = array();
			$row = mysql_fetch_array($resource, MYSQL_ASSOC);
			$this->free($resource);
			return $row;
		}
		else
		{
			return array();
		}
	}

	/**
	 * 获得多条记录
	 *
	 * @param sting $sql sql语句，需要做安全转义
	 * @param number $start
	 * @param number $num
	 * @param callback $callback 对单行结果执行的callbak函数
	 * @return array|null
	 * @author Icehu
	 */
	public function fetchAll($sql, $num=null, $start=0, $rid=null, $callback=null)
	{
		if ($num)
		{
			$sql = $sql . ' LIMIT ' . intval($start) . ' , ' . $num;
		}
		if (null === $rid)
		{
			$rid = 'id';
		}
		$resource = $this->query($sql, false);
		if ($resource)
		{
			$return = array();
			while ($row = mysql_fetch_array($resource, MYSQL_ASSOC))
			{
				if (is_callable($callback))
				{
					if ($rid && $row[$rid])
					{
						$return[$row[$rid]] = call_user_func($callback, $row);
					}
					else
					{
						$return[] = call_user_func($callback, $row);
					}
				}
				else
				{
					if ($rid && isset($row[$rid]) && $row[$rid])
					{
						$return[$row[$rid]] = $row;
					}
					else
					{
						$return[] = $row;
					}
				}
			}
			$this->free($resource);
			return $return;
		}
		else
		{
			return array();
		}
	}

	/**
	 * 对结果集执行fetchArray查询
	 *
	 * @param resource $resource 结果集
	 * @param number $return_num 返回的索引方式
	 * @return array
	 * @author Icehu
	 */
	public function fetchArray($resource, $return_num=MYSQL_ASSOC)
	{
		return mysql_fetch_array($resource, $return_num);
	}

	/**
	 * 获取自增的id
	 * @param bool $bigint 是否bigint 列
	 * @return number
	 * @author Icehu
	 */
	public function insertId($bigint = false)
	{
		if ($bigint)
		{
			$r = mysql_query('Select LAST_INSERT_ID()', $this->clink);
			$row = mysql_fetch_array($r, MYSQL_NUM);
			return $row[0]; //bigint 列
		}
		else
		{
			return mysql_insert_id($this->clink);
		}
	}

	/**
	 * update 快捷操作
	 *
	 * @param string $table 操作的表名
	 * @param string $where where子句
	 * @param array $array 操作的数据关联数组 无需转移，使用原始数据
	 * @param array $safe 安全限制数组
	 * @param array $unset 不做单引号环绕的数组
	 * @return bool
	 * @author Icehu
	 */
	public function update($table, $where, $array, $safe=array(), $unset=array())
	{
		$set = $this->createset($array, $safe, $unset);
		$sql = "Update $table Set $set Where $where";
		return $this->query($sql, true);
	}

	/**
	 * replace 快捷操作
	 *
	 * @param string $table 操作的表名
	 * @param array $array 操作的数据关联数组 无需转移，使用原始数据
	 * @param array $safe 安全限制数组
	 * @return bool
	 * @author Icehu
	 */
	public function replace($table, $array, $safe=array())
	{
		$set = $this->createset($array, $safe);
		$sql = "Replace Into $table Set $set";
		if ($resource = $this->query($sql, true))
		{
			return ($id = $this->insertId()) ? $id : true;
		}
		return false;
	}

	/**
	 * insert 快捷操作
	 * @param string $table 操作的表名
	 * @param array $array 操作的数据关联数组 无需转移，使用原始数据
	 * @param array $safe 安全限制数组
	 * @return number
	 * @author Icehu
	 */
	public function insert($table, $array, $safe=array())
	{
		$set = $this->createset($array, $safe);
		$sql = "Insert Into $table Set $set";
		if ($resource = $this->query($sql, true))
		{
			return ($id = $this->insertId()) ? $id : true;
		}
		return false;
	}

	/**
	 * 创建安全的set子句
	 * @param array $array 需要创建set子句的关联数组
	 * @param array $safe 安全限制数组，字段列表
	 * @param array $unset 不用单引号环绕的字段列表
	 * @return string
	 * @author Icehu
	 */
	public function createset($array, $safe=array(), $unset=array())
	{
		$_res = array();
		foreach ((array) $array as $_key => $_val)
		{
			if ($safe && !in_array($_key, $safe))
			{
				continue;
			}
			else
			{
				if ($unset && in_array($_key, $unset))
				{
					$_res[$_key] = "`$_key`=$_val";
				}
				else
				{
					$_val = $this->escape($_val);
					$_res[$_key] = "`$_key`='$_val'";
				}
			}
		}
		return implode(',', $_res);
	}

	/**
	 * 获取记录条数
	 *
	 * @param resource $query
	 * @return Integer
	 */
	function num_rows($query){
		return mysql_num_rows($query);
	}

	/**
	 * 数据库报错抛出的异常
	 *
	 * @param string $sql 查询的sql语句
	 * @param resource $link 数据库链接资源
	 * @author Icehu
	 */
	protected function halt($sql, $link)
	{
		throw new Core_Db_Exception('MySQL Query Error : ' . mysql_error($link) . '<br /> SQL:' . $sql, mysql_errno($link));
	}

}