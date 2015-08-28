<?php
/**
 * iweibo2.0
 * 
 * 表基类
 *
 * @author lvfeng
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Table_Base.php 2011-06-09 16:27:00Z gionouyang $
 * @package Model
 * @since 2.0
 */
class Table_Base extends Core_Db_Table
{
	/**
	 * 获取记录数
	 *
	 * @param array $whereArr=array(array('字段', '值', '操作符'),...)
	 * @return int
	 */
	public function queryCount($whereArr)
	{
		$results = $this->queryOne('COUNT(*) row_count', $whereArr);
		return $results['row_count'];
	}
	
	/**
	 * 获取一条记录
	 * 
	 * @param array $whereArr=array(array('字段', '值', '操作符'),...)
	 * @return array
	 */
	public function queryOne($fieldArr, $whereArr)
	{
		$sql = 'SELECT '.$this->formatFields($fieldArr).' FROM '.$this->_table
					.$this->formatWhere($whereArr);
		return parent::fetchOne($sql);
	}
	
	/**
	 * 获取多条记录
	 *
	 * @param array $fieldArr
	 * @param array $whereArr=array(array('字段', '值', '操作符'),...)
	 * @param array $orderByArr
	 * @param array $limitArr=array($row_count, $offset)
	 * @return array
	 */
	public function queryAll($fieldArr, $whereArr, $orderByArr, $limitArr)
	{
		$row_count = 0;
		$offset = 0;
		$sql = 'SELECT '.$this->formatFields($fieldArr).' FROM '.$this->_table
					.$this->formatWhere($whereArr)
					.$this->formatOrderBy($orderByArr);
		!empty($limitArr) && 2==count($limitArr) && list($row_count, $offset) = (array)$limitArr;
		return parent::fetchAll($sql, $row_count, $offset);
	}
	
	/**
	 * 格式化字段
	 *
	 * @param array $fieldArr
	 * @return string
	 */
	public function formatFields($fieldArr)
	{
		return !empty($fieldArr) ? implode(', ', (array)$fieldArr) : '*';
	}
	
	/**
	 * 格式化查询条件
	 *
	 * @param array $whereArr=array(array('字段', '值', '操作符'),...)
	 * @return string
	 */
	public static function formatWhere($whereArr)
	{
		$where = '';
		if(!empty($whereArr))
		{
			foreach ((array)$whereArr as $value)
			{
				list($prefix, $suffix) = (!empty($value[2]) && strtoupper($value[2])=='LIKE') ? array('%', '%') : array('', '');
				$where .= (empty($where)?' WHERE ':' AND ').$value[0].' '.(empty($value[2])?'=':$value[2])." '".$prefix.Core_Db::sqlescape($value[1]).$suffix."' ";
			}
		}
		return $where;
	}
	
	/**
	 * 格式化排序字段
	 *
	 * @param array $orderByArr
	 * @return string
	 */
	public function formatOrderBy($orderByArr)
	{
		return !empty($orderByArr) ? ' ORDER BY '.implode(', ', (array)$orderByArr) : '';
	}
}
