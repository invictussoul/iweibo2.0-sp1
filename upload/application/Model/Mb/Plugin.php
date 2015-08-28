<?php
/**
 * 插件操作类
 *
 * @author lvfeng
 */
class Model_Mb_Plugin
{
	//插件表对象
	protected $pluginTableObj;
	//插件表可操作字段
	protected $pluginTableSafeColumu = array('name', 'foldername', 'useable', 'visible', 'usehack', 'orderkey');
	//插件缓存标识
	const PLUGIN_CACHE_KEY = '_plugin_list.php';
	//插件目录
	const PLUGIN_DIR = 'application/Plugin/';
	//数据容器
    protected static $_data = array();
    
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->pluginTableObj = Table_Mb_Plugin::getInstance();
	}
	
	/**
	 * 添加插件
	 *
	 * @param array $pluginInfo
	 * @return int $id
	 */
	public function addPlugin($pluginInfo) 
	{
		return $this->pluginTableObj->add($pluginInfo, $this->pluginTableSafeColumu);
	}
	
	/**
	 * 根据编号编辑插件
	 *
	 * @param array $pluginInfo
	 * @return boolean
	 */
	public function editPluginInfo($pluginInfo)
	{
		return $this->pluginTableObj->update($pluginInfo, $this->pluginTableSafeColumu);
	}
	
	/**
	 * 根据编号删除插件
	 *
	 * @param int|array $id
	 * @return int boolean
	 */
	public function deletePluginById($id)
	{
		return $this->pluginTableObj->remove($id);
	}
	
	/**
	 * 获得插件数量
	 *
	 * @param array $whereArr
	 * @return int
	 */
	public function getPluginCount($whereArr=array())
	{
		return $this->pluginTableObj->queryCount($whereArr);
	}
	
	/**
	 * 根据编号从数据库取得插件信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getPluginInfoFromDbById($id)
	{
		return $this->pluginTableObj->queryOne('*', array(array('id', $id)));
	}
	
	/**
	 * 从数据库取得插件信息列表
	 *
	 * @param array $whereArr
	 * @param array $orderByArr
	 * @param array $limitArr
	 * @return array
	 */
	public function getPluginListFromDb($whereArr=array(), $orderByArr=array(), $limitArr=array())
	{
		return $this->pluginTableObj->queryAll('*', $whereArr, $orderByArr, $limitArr);
	}
	
	/**
	 * 根据编号从缓存取得(已启用)插件信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getPluginInfoFromCacheById($id)
	{
		$pluginList = $this->getPluginListFromCache();
		return isset($pluginList[1][$id]) ? $pluginList[1][$id] : null;
	}
	
	/**
	 * 从缓存取得(已启用)插件信息列表
	 *
	 * @return array（'1'=>全部Arr, '2'=>显示导航Arr, '3'=>允许调用钩子Arr）
	 */
	public function getPluginListFromCache()
	{
		return Core_Cache::read(self::PLUGIN_CACHE_KEY);
	}
	
	/**
	 * 根据编号取得插件信息
	 * 只取得已启用的插件信息，先从缓存中取插件信息，为空则再从数据库中取
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getPluginInfoById($id)
	{
		$pluginInfo = $this->getPluginInfoFromCacheById($id);
		if(!empty($pluginInfo))
		{
			return $pluginInfo;
		}
		$pluginInfo = $this->getPluginListFromDb(array(array('id', $id), array('useable', 1)));
		if(isset($pluginInfo[0]))
		{
			$this->updatePluginCache();
			return $pluginInfo[0];
		}
		return null;
	}
	
	/**
	 * 取得插件信息列表
	 * 只取得已启用的插件信息，先从缓存中取插件信息，为空则再从数据库中取
	 *
	 * @param array $type - 1.全部 2.显示导航 3.允许调用钩子
	 * @return array（'1'=>全部Arr, '2'=>显示导航Arr, '3'=>允许调用钩子Arr）
	 */
	public function getPluginList($type)
	{
		if(isset(self::$_data[$type]))
    		return self::$_data[$type];
    		
		$pluginList = $this->getPluginListFromCache();
		if(!empty($pluginList[$type]))
		{
			self::$_data[$type] = $pluginList[$type];
			return self::$_data[$type];
		}
		
		$whereArr = array(array('useable', 1));
		switch ($type)
		{
			case 2:
				$whereArr[]=array('visible', 1);
			break;
			case 3:
				$whereArr[]=array('usehack', 1);
			break;
			case 1:
			default :
				break;
		}
		$pluginList = $this->getPluginListFromDb($whereArr, 'orderkey');
		if(!empty($pluginList))
		{
			$this->updatePluginCache();
			self::$_data[$type] = $pluginList;
			return self::$_data[$type];
		}
		return array();
	}
	
	/**
	 * 将插件信息列表写入缓存
	 */
	public function updatePluginCache()
	{
		$pluginList = array();
		$pluginArr = $this->getPluginListFromDb(array(array('useable', 1)), 'orderkey');
		foreach ($pluginArr as $pluginInfo)
		{
			$pluginList[1][] = $pluginInfo;
			$pluginInfo['visible']==1 && $pluginList[2][] = $pluginInfo;
			$pluginInfo['usehack']==1 && $pluginList[3][] = $pluginInfo;
		}
		Core_Cache::write(self::PLUGIN_CACHE_KEY, $pluginList);
	}
	
	/**
	 * 清除缓存
	 */
	public function deletePluginCache()
	{
		Core_Cache::delete(self::PLUGIN_CACHE_KEY);
	}
	
	/**
	 * 验证插件名是否已存在
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function checkNameExists($name, $id=0) 
	{
		$whereArr = array(array('name', $name));
		!empty($id) && $whereArr[] = array('id', $id, '<>');
		return $this->pluginTableObj->queryCount($whereArr);
	}
	
	/**
	 * 验证插件是否启用显示导航
	 *
	 * @param string $folderName
	 * @return boolean
	 */
	public function checkPluginUseable($folderName)
	{
		$pluginList = $this->getPluginList(2);
		foreach ($pluginList as $plugin){
			if(ucfirst($folderName) == $plugin['foldername'])
				return true;
		}
		return false;
	}
	
	/**
	 * 验证插件文件夹是否已存在
	 *
	 * @param string $folderName
	 * @param int $id
	 * @return boolean
	 */
	public function checkFolderNameExists($folderName, $id=0) 
	{
		$whereArr = array(array('foldername', $folderName));
		!empty($id) && $whereArr[] = array('id', $id, '<>');
		return $this->pluginTableObj->queryCount($whereArr);
	}
	
	/**
	 * 验证插件名
	 *
	 * @param string $name
	 * @param int $id
	 * @return array
	 */
	public function checkName($name, $id=0) 
	{
		if(strlen($name) < 2) 
		{
			return array('errorcode'=>'1', 'message'=>'插件名长度字符数不能小于2<br>');
		}
		if($this->checkNameExists($name, $id)) 
		{
			return array('errorcode'=>'2', 'message'=>'插件名已存在<br>');
		}
		return 0;
	}
	
	/**
	 * 验证插件文件夹
	 *
	 * @param string $folderName
	 * @param int $id
	 * @return array
	 */
	public function checkFolderName($folderName, $id=0) 
	{
		if(strlen($folderName) < 1) 
		{
			return array('errorcode'=>'3', 'message'=>'插件文件夹长度字符数不能小于1<br>');
		}
		if($this->checkFolderNameExists($folderName, $id)) 
		{
			return array('errorcode'=>'4', 'message'=>'插件文件夹已存在<br>');
		}
		return 0;
	}
}
