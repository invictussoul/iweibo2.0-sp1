<?php
/**
 * 用户组操作类
 *
 * @author lvfeng
 */
class Model_User_Group
{
	//用户组表对象
	protected $groupTableObj;
	//用户组表可操作字段
	protected $groupTableSafeColumu = array('title');
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->groupTableObj = Table_User_Group::getInstance();
	}
	
	/**
	 * 添加组
	 *
	 * @param array $groupInfo
	 * @return int $gid
	 */
	public function addGroup($groupInfo) 
	{
		return $this->groupTableObj->add($groupInfo, $this->groupTableSafeColumu);
	}
	
	/**
	 * 根据组编号编辑组
	 *
	 * @param array $groupInfo
	 * @return boolean
	 */
	public function editGroupInfo($groupInfo)
	{
		return $this->groupTableObj->update($groupInfo, $this->groupTableSafeColumu);
	}
	
	/**
	 * 根据组编号删除组
	 *
	 * @param int|array $gid
	 * @return int boolean
	 */
	public function deleteGroupByGid($gid)
	{
		return $this->groupTableObj->remove($gid);
	}
	
	/**
	 * 根据组编号取得组信息
	 *
	 * @param int $gid
	 * @return array
	 */
	public function getGroupInfoByGid($gid)
	{
		return $this->groupTableObj->queryOne('*', array(array('gid', $gid)));
	}
	
	/**
	 * 取得组信息列表
	 *
	 * @param array $whereArr
	 * @param array $orderByArr
	 * @param array $limitArr
	 * @return array
	 */
	public function getGroupList($whereArr=array(), $orderByArr=array(), $limitArr=array())
	{
		return $this->groupTableObj->queryAll('*', $whereArr, $orderByArr, $limitArr);
	}
	
	/**
	 * 验证用户组名是否已存在
	 *
	 * @param string $title
	 * @param int $gid
	 * @return boolean
	 */
	public function checkTitleExists($title, $gid=0) 
	{
		$whereArr = array(array('title', $title));
		!empty($gid) && $whereArr[] = array('gid', $gid, '<>');
		return $this->groupTableObj->queryCount($whereArr);
	}
}
