<?php
/**
 * 皮肤操作类
 *
 * @author lvfeng
 */
class Model_Mb_Skin
{
	//皮肤表对象
	protected $skinTableObj;
	//皮肤表可操作字段
	protected $skinTableSafeColumu = array('name', 'foldername', 'thumb', 'orderkey', 'useable');
	//皮肤缓存标识
	const SKIN_CACHE_KEY = '_skin_list.php';
	//皮肤目录
	const VIEW_DIR = 'view/';
	//数据容器
    protected static $_data = array();
    
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->skinTableObj = Table_Mb_Skin::getInstance();
	}
	
	/**
	 * 添加皮肤
	 *
	 * @param array $skinInfo
	 * @return int $id
	 */
	public function addSkin($skinInfo) 
	{
		return $this->skinTableObj->add($skinInfo, $this->skinTableSafeColumu);
	}
	
	/**
	 * 根据编号编辑皮肤
	 *
	 * @param array $skinInfo
	 * @return boolean
	 */
	public function editSkinInfo($skinInfo)
	{
		return $this->skinTableObj->update($skinInfo, $this->skinTableSafeColumu);
	}
	
	/**
	 * 根据编号删除皮肤
	 *
	 * @param int|array $id
	 * @return int boolean
	 */
	public function deleteSkinById($id)
	{
		return $this->skinTableObj->remove($id);
	}
	
	/**
	 * 获得皮肤数量
	 *
	 * @param array $whereArr
	 * @return int
	 */
	public function getSkinCount($whereArr=array())
	{
		return $this->skinTableObj->queryCount($whereArr);
	}
	
	/**
	 * 根据编号取得皮肤信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getSkinInfoFromDbById($id)
	{
		return $this->skinTableObj->queryOne('*', array(array('id', $id)));
	}
	
	/**
	 * 取得皮肤信息列表
	 *
	 * @param array $whereArr
	 * @param array $orderByArr
	 * @param array $limitArr
	 * @return array
	 */
	public function getSkinListFromDb($whereArr=array(), $orderByArr=array(), $limitArr=array())
	{
		return $this->skinTableObj->queryAll('*', $whereArr, $orderByArr, $limitArr);
	}
	
	/**
	 * 根据编号从缓存取得(已启用)皮肤信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getSkinInfoFromCacheById($id)
	{
		$skinList = $this->getSkinListFromCache();
		return isset($skinList[$id]) ? $skinList[$id] : null;
	}
	
	/**
	 * 从缓存取得(已启用)皮肤信息列表
	 *
	 * @return array
	 */
	public function getSkinListFromCache()
	{
		return Core_Cache::read(self::SKIN_CACHE_KEY);
	}
	
	/**
	 * 根据编号取得皮肤信息
	 * 只取得已启用的皮肤信息，先从缓存中取皮肤信息，为空则再从数据库中取
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getSkinInfoById($id)
	{
		$skinInfo = $this->getSkinInfoFromCacheById($id);
		if(!empty($skinInfo))
		{
			return $skinInfo;
		}
		$skinList = $this->getSkinListFromDb(array(array('id', $id), array('useable', 1)));
		if(isset($skinList[0]))
		{
			$this->updateSkinCache();
			return $skinList[0];
		}
		return null;
	}
	
	/**
	 * 取得插件信息列表
	 * 只取得已启用的皮肤信息，先从缓存中取插件信息，为空则再从数据库中取
	 *
	 * @return array
	 */
	public function getSkinList()
	{
		if(isset(self::$_data['skin']))
    		return self::$_data['skin'];
    		
		$skinList = $this->getSkinListFromCache();
		if(!empty($skinList))
		{
			self::$_data['skin'] = $this->formatSkinList($skinList);
			return self::$_data['skin'];
		}
		
		$skinList = $this->getSkinListFromDb(array(array('useable', 1)), 'orderkey');
		if(!empty($skinList))
		{
			$this->updateSkinCache();
			self::$_data['skin'] = $this->formatSkinList($skinList);
			return self::$_data['skin'];
		}
		return array();
	}
	
	/**
	 * 格式化皮肤列表
	 *
	 * @param array $skinList
	 * @return array
	 */
	public function formatSkinList($skinList)
	{
		$newSkinList = array();
		foreach ((array)$skinList as $skin)
		{
			unset($skin['orderkey']);
			$skin['id'] = intval($skin['id']);
			$skin['useable'] = intval($skin['useable']);
			$newSkinList[] = $skin;
		}
		$newSkinList['length'] = intval(count($newSkinList));
		return $newSkinList;
	}
	
	/**
	 * 将皮肤信息列表写入缓存
	 */
	public function updateSkinCache()
	{
		$skinList = $this->getSkinListFromDb(array(array('useable', 1)), 'orderkey');
		Core_Cache::write(self::SKIN_CACHE_KEY, $skinList);
	}
	
	/**
	 * 清除缓存
	 */
	public function deleteSkinCache()
	{
		Core_Cache::delete(self::SKIN_CACHE_KEY);
	}
	
	/**
	 * 验证皮肤名是否已存在
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function checkNameExists($name, $id=0) 
	{
		$whereArr = array(array('name', $name));
		!empty($id) && $whereArr[] = array('id', $id, '<>');
		return $this->skinTableObj->queryCount($whereArr);
	}
	
	/**
	 * 验证皮肤文件夹是否已存在
	 *
	 * @param string $folderName
	 * @param int $id
	 * @return boolean
	 */
	public function checkFolderNameExists($folderName, $id=0) 
	{
		$whereArr = array(array('foldername', $folderName));
		!empty($id) && $whereArr[] = array('id', $id, '<>');
		return $this->skinTableObj->queryCount($whereArr);
	}
	
	/**
	 * 验证皮肤名
	 *
	 * @param string $name
	 * @param int $id
	 * @return array
	 */
	public function checkName($name, $id=0) 
	{
		if(strlen($name) < 2) 
		{
			return array('errorcode'=>'1', 'message'=>'皮肤名长度字符数不能小于2<br>');
		}
		if($this->checkNameExists($name, $id)) 
		{
			return array('errorcode'=>'2', 'message'=>'皮肤名已存在<br>');
		}
		return 0;
	}
	
	/**
	 * 验证皮肤文件夹
	 *
	 * @param string $folderName
	 * @param int $id
	 * @return array
	 */
	public function checkFolderName($folderName, $id=0) 
	{
		if(strlen($folderName) < 1) 
		{
			return array('errorcode'=>'3', 'message'=>'皮肤文件夹长度字符数不能小于1<br>');
		}
		if($this->checkFolderNameExists($folderName, $id)) 
		{
			return array('errorcode'=>'4', 'message'=>'皮肤文件夹已存在<br>');
		}
		return 0;
	}
}
