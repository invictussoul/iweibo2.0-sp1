<?php
/**
 * 禁止IP操作类
 * 
 * @author lvfeng
 */
class Model_User_Banned
{
	//禁止IP表对象
    protected $bannedTableObj;
    //禁止IP表可操作字段
    protected $bannedTableSafeColumu = array('id', 'ip', 'username');
    //数据容器
    protected static $_data = array();
    
    /**
     * 构造函数
     */
    public function __construct ()
    {
        $this->bannedTableObj = Table_User_Banned::getInstance();
    }
	
	/**
	 * 添加一条被禁止IP
	 *
	 * @param string $ip
	 * @return int
	 */
	public function addBanned($ip)
	{
		$bannedInfo = array('ip'=>$ip, 'username'=>$_SESSION['adminname']);
		return $this->bannedTableObj->add($bannedInfo, $this->bannedTableSafeColumu);
	}
	
	/**
	 * 清除部分被禁止IP
	 *
	 * @param array $id
	 * @return boolean
	 */
	public function delBanned($id)
	{
		return $this->bannedTableObj->remove($id);
	}
	
	/**
     * 取得被禁止的IP数量
     *
     * @param array $whereArr
     * @return int
     */
    public function getBannedCount ($whereArr=array ())
    {
        return $this->bannedTableObj->queryCount ($whereArr);
    }
	
	/**
	 * 取得被禁止的IP列表
	 * 
	 * @param array $whereArr
     * @param array $orderByArr
     * @param array $limitArr
	 * @return array
	 */
	public function getBannedList($whereArr=array (), $orderByArr=array (), $limitArr=array ())
    {
        return $this->bannedTableObj->queryAll('*', $whereArr, $orderByArr, $limitArr);
	}
	
	/**
	 * 验证IP是否属于被禁止
	 *
	 * @param string $ip
	 * @param array $ipBannedList
	 * @return boolean
	 */
	public function checkBanned($ip, $bannedList=null)
	{
		if($bannedList==null)
		{
			if(!isset(self::$_data['bannedlist']))
				self::$_data['bannedlist'] = $this->getBannedList();
			$bannedList = self::$_data['bannedlist'];
		}
		$ipArr = explode('.', $ip);
		foreach ((array)$bannedList as $banned)
		{
			$bannedArr = explode('.', $banned['ip']);
			if($ipArr[0] == $bannedArr[0] || $bannedArr[0] == '*')
				if($ipArr[1] == $bannedArr[1] || $bannedArr[1] == '*')
					if($ipArr[2] == $bannedArr[2] || $bannedArr[2] == '*')
						if($ipArr[3] == $bannedArr[3] || $bannedArr[3] == '*')
							return true;
		}
		return false;
	}
	
	/**
	 * 验证IP是否已存在
	 *
	 * @param string $ip
	 * @return boolean
	 */
	public function checkIpExists($ip)
	{
		return $this->bannedTableObj->queryCount (array(array('ip', $ip)));
	}
	
	/**
	 * 验证IP
	 *
	 * @param string $ip
	 * @return array
	 */
	public function checkIp($ip)
	{
		$ipArr = explode('.', $ip);
		foreach ($ipArr as $value)
		{
			if($value != '*' && (!is_numeric($value) || (intval($value) < 0 || intval($value) > 255)))
			{
				return array('errorcode'=>Core_Comm_Modret::RET_IP_FORMATERROR, 'message'=>Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_IP_FORMATERROR).'<br>');
			}
		}
		if($this->checkIpExists($ip)) 
		{
			return array('errorcode'=>Core_Comm_Modret::RET_IP_BANNED, 'message'=>Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_IP_BANNED).'<br>');
		}
		if($this->checkBanned(Core_Comm_Util::getClientIp(), array(array('ip'=>$ip))))
		{
			return array('errorcode'=>Core_Comm_Modret::RET_IP_CANNOTBANNEDSELF, 'message'=>Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_IP_CANNOTBANNEDSELF).'<br>');
		}
		return 0;
	}
}
