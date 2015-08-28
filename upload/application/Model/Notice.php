<?php

/**
 * 公告管理
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_Notice extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_notice';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array ('id' , 'mid' , 'title' , 'content' , 'displayorder' , 'endtime');
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';

    /**
     * 添加
     * @param array $notice
     * @return bool
     */
    public function addNotice ( $add , $safe=array () , $replace=false )
    {
        //@TODO 是否同步发一条微博,待确定
        return $this->add ($add , $safe = array () , $replace = false);
    }

    /**
     * 修改
     * @param array $notice
     * @return bool
     */
    public function editNotice ( $notice )
    {
        return $this->update ($notice);
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function deleteNotice ( $ids )
    {
        return $this->remove ($ids);
    }

}