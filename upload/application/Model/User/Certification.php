<?php
/**
 * 认证操作类
 *
 * @author lvfeng
 */
class Model_User_Certification extends Core_Config 
{
	//配置名
	const CERTIFICATION_CONFIG_GROUP = 'certification.info';
	
	/**
	 * 保存认证设置
	 *
	 * @param array $certInfo
	 */
	public static function setCertInfo($certInfo)
	{
		self::getCertInfo();
		self::add($certInfo, self::CERTIFICATION_CONFIG_GROUP);
		self::update(self::CERTIFICATION_CONFIG_GROUP);
	}
	
	/**
	 * 取得认证设置
	 *
	 * @return array
	 */
	public static function getCertInfo()
	{
		return self::get(null, self::CERTIFICATION_CONFIG_GROUP, false);
	}
}
