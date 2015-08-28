<?php

/**
 * 单表Table操作类
 *
 * @author Icehu
 */
class Core_Db_Table
{

    /**
     * 表名
     * @var string
     */
    protected $_table;
    /**
     * 数据字段列表
     * @var array
     */
    protected $_fields = null;
    /**
     * 全局Cache数组
     * @var array
     */
    protected static $_data;
    /**
     * 是否使用Cache
     * @var bool
     */
    protected $_usecache = true;
    /**
     * 主键的值
     * @var number|privkey
     */
    public $_id = 0;
    /**
     * 主键字段名
     * @var string
     */
    public $_idkey = 'id';

    /**
     * 构造函数
     * @param string $table 表名
     * @param array $fields 字段列表
     * @author Icehu
     */
    public function __construct ( $table , $fields=null )
    {
        $this->_table = $table;
        $this->_fields = $fields;
        if (!isset (self::$_data[$this->_table])) {
            self::$_data[$this->_table] = array ();
        }
    }

    /**
     * 设置是否使用Cache
     * @param bool $tf 是否使用Cache
     * @author Icehu
     */
    public function usecache ( $tf )
    {
        $this->_usecache = $tf;
    }

    /**
     * 查找一个或者一组元素
     * 查找组时,保持原有顺序返回
     * 查找一个时，返回单行，否则返回多行的列表
     *
     * @param number|array $id
     * @param array $fields
     * @return array
     * @author Icehu
     */
    public function find ( $id , $fields=null )
    {
        $id = (array) $id;
        $tf = array ();
        $rt = array ();
        $n = count ($id);
        if ($n == 1) {  //返回一项
            $_i = array_shift ($id);
            if (@self::$_data[$this->_table][$_i]) {
                return self::$_data[$this->_table][$_i];
            } else {
                $tf = array ($_i);
            }
        } else {   //返回一组
            foreach ($id as $v)
            {
                if (@self::$_data[$this->_table][$v]) {
                    $rt[$v] = self::$_data[$this->_table][$v];
                } else {
                    $rt[$v] = array ();
                    $tf[$v] = $v;
                }
            }
        }
        if ($fields) {
            $_field = array ();
            foreach ((array) $fields as $_key => $_value)
            {
                if ($this->_fields && !in_array ($_key , $this->_fields)) {
                    continue;
                } else {
                    $_field[$_key] = "$_value";
                }
            }
            if ($_field) {
                $_field = implode (',' , $_field);
            } else {
                $_field = '*';
            }
        } else {
            $_field = '*';
        }
        $tn = count ($tf);
        if ($tn == 1) {
            $k = array_shift ($tf);
            $where = "`{$this->_idkey}`='" . $k . "'";
            $rt[$k] = self::$_data[$this->_table][$k] = Core_Db::fetchOne ("Select $_field From {$this->_table} where $where");
        } elseif ($tn > 1) {
            $where = "`{$this->_idkey}` in ('" . implode ("','" , $tf) . "')";
            $row = Core_Db::fetchAll ("Select $_field From {$this->_table} where $where " , null , 0 , $this->_idkey , null);
            foreach ((array) $row as $k => $v)
            {
                $rt[$k] = self::$_data[$this->_table][$k] = $v;
            }
        } else {
            return $n == 1 ? array_shift ($rt) : $rt;
        }
        return $n == 1 ? array_shift ($rt) : $rt;
    }

    /**
     * 载入一组数据到单表操作类中
     * 使用场景有限，需要评估，请暂时不要使用
     *
     * @param array $array
     * @author Icehu
     */
    public function load ( $array )
    {
        if ($array[$this->_idkey]) {
            $this->_id = $array[$this->_idkey];
            @self::$_data[$this->_table][$this->_id] = $array;
        }
    }

    /**
     * 对单表进行Update操作
     *
     * @param array $update 需要update的字段，必须包括主键字段
     * @param array $limit 限制用的安全字段列表，只有在这个列表中的字段才允许被更新
     * @param array $unset 不被单引号环绕的字段，可以用来做运算 比如 num = num + 1
     * @return bool
     * @author Icehu
     */
    public function update ( $update , $limit=array () , $unset = array () )
    {
        if (isset ($update[$this->_idkey]) && intval ($update[$this->_idkey])) {
            $id = intval ($update[$this->_idkey]);
        } else {
            $id = 0;
        }
        if (!$id && !$id = $this->_id) {
            return false;
        }
        unset ($update[$this->_idkey]);
        @self::$_data[$this->_table][$id] = null;
        $r = $this->updateall ("`$this->_idkey`=$id" , $update , $limit , $unset);
        if ($r) {
            //update cache!
            return true;
        }
        return false;
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

        $_set = array ();
        foreach ((array) $update as $_key => $_value)
        {
            if ($this->_fields && !in_array ($_key , $this->_fields)) {
                continue;
            }
            if ($limit && !in_array ($_key , $limit)) {
                continue;
            }
            if ($_key == $this->_idkey) {
                continue;
            }
            if ($unset && in_array ($_key , $unset)) {
                $_set[] = "`$_key`=$_value";
            } else {
                $_value = Core_Db::sqlescape ($_value);
                $_set[] = "`$_key`='$_value'";
            }
        }
        if (!$_set) {
            return false;
        } else {
            $_set = implode (',' , $_set);
            Core_Db::query ("Update {$this->_table} set {$_set} where $where" , true);
            return true;
        }
    }

    /**
     * 向数据表插入一条记录
     * @param array $add	插入的数据关联数组
     * @param array $safe	safe数组，不在这个数组的key将忽略，安全用
     * @param bool $replace	是否使用replace
     * @return insertid|true|false
     * @author Icehu
     */
    public function add ( $add , $safe=array () , $replace=false )
    {
        if ($replace) {
            $rt = Core_Db::replace ($this->_table , $add , $safe);
        } else {
            $rt = Core_Db::insert ($this->_table , $add , $safe);
        }
        //返回insertid or add 里的主键 否则返回 true or false
        if (true === $rt && $add[$this->_idkey]) {
            $rt = $add[$this->_idkey];
        }
        return $rt;
    }

    /**
     *
     * 向数据表插入一条记录
     * @param array $add	插入的数据关联数组
     * @param array $safe	safe数组，不在这个数组的key将忽略，安全用
     * @param bool $replace	是否使用replace
     * @return insertid|true|false
     * @author Icehu
     */
    public function insert ( $add , $safe=array () , $replace=false )
    {
        return $this->add ($add , $safe = array () , $replace);
    }

    /**
     * 删除一条记录
     * @param string|number $id 主键的值
     * @return bool
     * @author Icehu
     */
    public function remove ( $id )
    {
        if (!is_array ($id)) {
            $_where = "`$this->_idkey`='$id'";
            @self::$_data[$this->_table][$id] = null;
        } else {
            $_where = "`$this->_idkey` in ('" . implode ("','" , (array) $id) . "')";
            foreach ((array) $id as $v)
            {
                @self::$_data[$this->_table][$v] = null;
            }
        }
        return $this->removeall ($_where);
    }

    /**
     * 通用的删除方法
     * @param string $where where 字句
     * @return bool
     * @author Icehu
     */
    public function removeall ( $where )
    {
        Core_Db::query ("Delete From `{$this->_table}` where {$where}" , true);
        return true;
    }

    /**
     * 优化当前表
     *
     * @author Icehu
     */
    public function optimize ()
    {
        Core_Db::query ("OPTIMIZE TABLE `{$this->_table}`" , true);
    }

    public function fetch ()
    {
        
    }

    /**
     * 快速从数据表中读取一行
     * @param string $sql 需要转义！
     * @param bool $master 是否读取主库
     * @return array
     * @author Icehu
     */
    public static function fetchOne ( $sql , $master=false )
    {
        return Core_Db::fetchOne ($sql , $master);
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
    public function fetchAll ( $sql='' , $num=null , $start=0 , $rid=null , $callback=null )
    {
        if (strlen ($sql) == 0) {
            $sql = "SELECT * FROM `{$this->_table}`";
        }
        return Core_Db::fetchAll ($sql , $num , $start , $rid , $callback);
    }

    public function __get ( $_key )
    {
        if (isset (self::$_data[$this->_table][$_key])) {
            return self::$_data[$this->_table][$_key];
        }
        return null;
    }

}