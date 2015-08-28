<?php
/**
 * 令牌操作类
 * 
 * @author lvfeng
 */
class Model_Base_Token
{
	//用户表对象
	protected $tokenTableObj;
	//用户表可操作字段
	protected $tokenTableSafeColumu = array('tid', 'uid', 'sign', 'created');
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->tokenTableObj = Table_Base_Token::getInstance();
	}
	
	/**
	 * 添加(替换)令牌
	 *
	 * @param array $tokenInfo
	 * @return int $tid
	 */
	public function addToken($tokenInfo) 
	{
		return $this->tokenTableObj->add($tokenInfo, $this->tokenTableSafeColumu, true);
	}
	
	/**
	 * 根据令牌编号删除令牌
	 *
	 * @param int|array $tid
	 * @return boolean
	 */
	public function deleteTokenByTid($tid) 
	{
		return $this->tokenTableObj->remove($tid);
	}
	
	/**
	 * 根据用户编号删除令牌
	 *
	 * @param int $uid
	 * @return boolean
	 */
	public function deleteTokenByUid($uid) 
	{
		if(!$tokenInfo = $this->getTokenInfoByUid($uid))
			return true;
		return $this->tokenTableObj->remove($tokenInfo['tid']);
	}
	
	/**
	 * 根据用户编号取得令牌
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getTokenInfoByUid($uid){
		return $this->tokenTableObj->queryOne('*', array(array('uid', $uid)));
	}
	
	/**
	 * 根据用户编号和签名取得令牌
	 *
	 * @param int $uid
	 * @param string $sign
	 * @return array
	 */
	public function getTokenInfoByUidAndSign($uid, $sign){
		return $this->tokenTableObj->queryOne('*', array(array('uid', $uid), array('sign', $sign)));
	}
}
