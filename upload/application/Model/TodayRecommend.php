<?php

/**
 * 今日推荐
 * @author Gavin <yaojungang@comsenz.com>
 */
class Model_TodayRecommend extends Core_Model
{

    /**
     * 数据库表名
     * @var type string
     */
    protected $_tableName = 'mb_today_recommend';
    /**
     * 数据库字段名
     * @var type array
     */
    protected $_fields = array ('id' , 'content' ,'displayorder');
    /**
     * 数据库主键
     * @var type string
     */
    protected $_idkey = 'id';

    /**
     * 添加
     * @param array $add
     * @return bool
     */
    public function addTodayRecommend ( $add , $safe=array () , $replace=false )
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        return $this->add ($add , $safe = array () , $replace = false);
    }

    /**
     * 修改
     * @param array $data
     * @return bool
     */
    public function editTodayRecommend ( $data )
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        return $this->update ($data);
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function deleteTodayRecommend ( $ids )
    {
        Model_Componentprocessunit::cleanupAllComponentCache();  //触发清除组件缓存
        return $this->remove ($ids);
    }

    /**
     * 给前台提供数据
     * @param type $amount
     */
    public function __toData($amount)
    {
        $whereArr = array ();
        $whereArr[] = array ('1', 1, '=');
        $orderByArr = 'displayorder ASC';
        $limitArr = array ($amount, 0);
        $ts = $this->queryAll ($whereArr, $orderByArr, $limitArr);
        $r = array();
        foreach ($ts as $t)
        {
            $r[] = $t['content'];
        }
        return $r;
    }
}