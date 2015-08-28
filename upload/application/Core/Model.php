<?php

/**
 * Model 基类
 * @author Gavin <yaojungang@comsenz.com>
 */
class Core_Model
{

    /**
     * 数据库表名
     * @var string
     */
    protected $_tableName;
    /**
     * model所对应的数据表名的前缀
     *
     * @var string
     */
    protected $_prefix = "##__";
    /**
     * SQL语句容器，用于存放SQL语句，为SQL语句组装函数提供SQL语句片段的存放空间。
     *
     * @var array
     */
    protected $_parts;
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields;
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';
    /**
     * 数据库操作对象
     * @var type
     */
    protected $_tableObj;

    /**
     * 获取数据库操作对象
     * @return Core_Db_Table  数据库操作对象
     */
    public function getTable ()
    {
        return $this->_tableObj;
    }

    /**
     * 获取数据表名称
     * @return string 数据表名称
     */
    public function getTableName ()
    {
        return $this->_tableName;
    }

    public function __construct ()
    {
        $this->_tableName = $this->_prefix . $this->_tableName;
        $_dbTable = new Table_Base ($this->_tableName, $this->_fields);
        $_dbTable->_idkey = $this->_idkey;
        $this->_tableObj = $_dbTable;
    }

    /**
     * 添加
     * @param array $add	插入的数据关联数组
     * @param array $safe	safe数组，不在这个数组的key将忽略，安全用
     * @param bool $replace	是否使用replace
     * @return insertid|true|false
     */
    public function add ($add, $safe=array (), $replace=false)
    {
        return $this->_tableObj->add ($add, $safe = array (), $replace = false);
    }

    /**
     * 删除
     * @param int|array $ids 待删除的IDS
     * @return bool true|false
     */
    public function remove ($ids)
    {
        return $this->_tableObj->remove ($ids);
    }

    /**
     * 修改
     * @param array $update
     * @param array $limit
     * @param array $unset
     * @return type
     */
    public function update ($update, $limit=array (), $unset = array ())
    {
        return $this->_tableObj->update ($update, $limit, $unset);
    }

    /**
     * 通用的Update操作接口
     *
     * @param string $where where字句条件
     * @param array $update 更新的字段列表
     * @param array $limit 安全限制用数组，只有在这个数组中的字段才会蹦更新
     * @param array $unset 不被单引号环绕的字段列表，可以用来做运算 比如 num = num + 1
     * @return bool
     * @author Icehu
     */
    public function updateall ( $where , $update , $limit=array () , $unset = array () )
    {
		return $this->_tableObj->updateall ($where , $update , $limit , $unset);
	}

    /**
     * 查找一个或者一组元素
     * 查找组时,保持原有顺序
     * @param number|array $id
     * @param array $fields
     *  @return array
     */
    public function find ($id, $fields=null)
    {
        return $this->_tableObj->find ($id, $fields = null);
    }

    /**
     * 快捷的获得一条记录
     *
     * @param string $sql sql语句，需要做安全转义
     * @return array|null
     */
    public function fetchOne ($sql)
    {
        return $this->_tableObj->fetchOne ($sql);
    }

    /**
     * 获取一条记录
     *
     * @param array $whereArr=array(array('字段', '值', '操作符'),...)
     * @return array
     */
    public function queryOne ($fieldArr, $whereArr)
    {
        return $this->_tableObj->queryOne ($fieldArr, $whereArr);
    }

    /**
     * 直接执行一个SQL
     * 在sql语句中用 ##__ 代表表前缀，接口会自动替换为正确的前缀
     *
     * @param string $sql 需要转移
     * @param bool $master 是否读取主库
     * @return resource
     */
    public function query ($sql, $master=false)
    {
        return Core_Db::query ($sql, $master);
    }

    /**
     * 获得数量
     *
     * @param array $whereArr
     * @return int
     */
    public function getCount ($whereArr=array ())
    {
        return $this->_tableObj->queryCount ($whereArr);
    }

    /**
     * 获得列表
     *
     * @param array $whereArr
     * @param array $orderByArr
     * @param array $limitArr
     * @return array
     */
    public function queryAll ($whereArr=array (), $orderByArr=array (), $limitArr=array ())
    {
        return $this->_tableObj->queryAll ("*", $whereArr, $orderByArr, $limitArr);
    }

    /**
     * 获取列表
     */
    public function getAll ($sql='', $num=null, $start=0, $rid=null, $callback=null)
    {
        if (strlen ($sql) == 0) {
            $sql = "SELECT * FROM `" . $this->getTableName () . "` ";
        }
        return $this->_tableObj->fetchAll ($sql, $num, $start, $rid, $callback);
    }

    /**
     * 简单获取列表
     * @param string $orderby 排序参数 如 "id DESC"
     * @param string $fields 要获取的字段 如"id,name,emal"
     * @return array
     */
    public function getList ($orderby='', $fields = '')
    {
        if (strlen ($fields) == 0) {
            $sql = "SELECT * FROM `" . $this->getTableName () . "` ";
        } else {
            $sql = "SELECT " . $fields . " FROM `{$this->_tableName}` ";
        }
        if (strlen ($orderby) > 0) {
            $sql .= "ORDER BY " . $orderby;
        }
        return $this->getAll ($sql);
    }

}