<?php
/**
 * 组权限操作类
 *
 * @author lvfeng
 */
class Model_User_Access extends Core_Config 
{
	//配置前缀
	const ACCESS_PREFIX = 'access.';
    
	/**
	 * 设置组权限
	 *
	 * @param array $accessList
	 * @param int $gid
	 */
	public static function setAccess($accessList, $gid)
	{
		self::getAccess($gid);
		self::add($accessList, self::ACCESS_PREFIX.$gid);
		self::update(self::ACCESS_PREFIX.$gid);
	}
	
	/**
	 * 清除组权限
	 *
	 * @param int $gid
	 */
	public static function delAccess($gid)
	{
		$accessList = self::getAccess($gid);
		$accessList = is_array($accessList) ? $accessList : array();
		self::delAccessByModelList(array_keys($accessList), $gid);
	}
	
	/**
	 * 清除部分模块组权限
	 *
	 * @param array $modelList
	 * @param int $gid
	 */
	public static function delAccessByModelList($modelList, $gid)
	{
		$accessList = array();
		foreach ((array)$modelList as $model)
		{
			$accessList[$model] = null;
		}
		self::setAccess($accessList, $gid);
	}
	
	/**
	 * 取得组权限
	 *
	 * @param int $gid
	 * @return array
	 */
	public static function getAccess($gid)
	{
		return self::getAccessByModel(null, $gid);
	}
	
	/**
	 * 取得某模块的组权限
	 *
	 * @param string $model
	 * @param int $gid
	 * @return boolean
	 */
	public static function getAccessByModel($model, $gid)
	{
		return self::get($model, self::ACCESS_PREFIX.$gid, false);
	}
	
	/**
	 * 验证一个模块组权限
	 *
	 * @param int $gid
	 * @param string $model
	 * @return boolean
	 */
	public static function checkAccessByGidAndModel($gid, $model)
	{
		if(!isset(self::$_data[$gid.'.'.$model]))
    		self::$_data[$gid.'.'.$model] = self::getAccessByModel($model, $gid);
    	return self::$_data[$gid.'.'.$model];
	}
	
	/**
	 * 验证多个模块组权限，需要全部通过
	 *
	 * @param int $gid
	 * @param array $modelList
	 * @return boolean
	 */
	public static function checkAccessByGidAndModelListAll($gid, $modelList)
	{
		$accessList = self::getAccess($gid);
		foreach ($modelList as $model)
		{
			if(!$accessList[$model])
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 验证多个模块组权限，只需通过一个
	 *
	 * @param int $gid
	 * @param array $modelList
	 * @return boolean
	 */
	public static function checkAccessByGidAndModelListOne($gid, $modelList)
	{
		$accessList = self::getAccess($gid);
		foreach ($modelList as $model)
		{
			if($accessList[$model])
			{
				return true;
			}
		}
		return false;
	}
}
