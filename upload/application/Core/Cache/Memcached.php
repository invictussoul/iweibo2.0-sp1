<?php
/**
 * Memcached 接口
 *
 * @author icehu
 */
class Core_Cache_Memcached
{

	protected static $memType = null;

	protected static $mem = null;

	protected static $cfg = null;

	/**
	 * 载入配置文件
	 * @author Icehu
	 */
	protected static function loadcfg()
	{
		if(null === self::$cfg)
		{
			self::$cfg = include CONFIG_PATH . 'memcached.php';
		}
	}

	/**
	 * 获取Memcached Client
	 * 支持Memcached扩展的情况下使用Memcached
	 * 否则使用memcache扩展
	 * 如果memcache扩展也不支持，则放弃
	 *
	 * @return Memcache|Memcached
	 * @author Icehu
	 */
	protected static function getMemcached()
	{
		if(null === self::$memType)
		{
			if(class_exists('Memcached',false))
			{
				//优先使用Memcached扩展
				self::loadcfg();
				if(self::$cfg)
				{
					self::$mem = new Memcached();
					self::$memType = 'memcached';
					self::$mem->addServers(self::$cfg);
					return self::$mem;
				}
				else
				{
					self::$memType = 'NoConfigInit';
				}
			}
			elseif(class_exists('Memcache',false))
			{
				self::loadcfg();
				if(self::$cfg)
				{
					self::$mem = new Memcache();
					self::$memType = 'memcache';
					foreach(self::$cfg as $server)
					{
						self::$mem->addServer($server[0],$server[1],false,$server[2]);
					}
					return self::$mem;
				}
				else
				{
					self::$memType = 'NoConfigInit';
				}
			}
			else
			{
				self::$memType = 'NotSupport';
			}
		}
		return self::$mem;
	}

	/**
	 * Memcached Set 方法
	 * 可以设置单个或者多个Cache
	 * 当Key传入一个关联数组时，一次设置多个Cache。此时$data参数无用，随便传一个
	 *
	 *
	 * @param string|array $key
	 * @param mix|number $data
	 * @param number $expire
	 * @author Icehu
	 */
	public static function set($key,$data,$expire=600)
	{
		$mem = self::getMemcached();
		if($mem)
		{
			switch (self::$memType)
			{
				case 'memcached':
					if(is_array($key))
					{
						//批量设置
						$mem->setMulti($key , time() + $expire);
					}
					else
					{
						//单个
						$mem->set($key , $data , time() + $expire);
					}
					break;
				case 'memcache':
					if(is_array($key))
					{
						//批量设置
						foreach($key as $mk => $mv)
						{
							$mem->set($mk,$mv,false,$expire);
						}
					}
					else
					{
						//单个
						$mem->set($key,$data,false,$expire);
					}
					break;
			}
		}
	}

	/**
	 * Memcached get操作
	 *
	 *
	 * @param string|mix $key string 或者 关联数组获取多个cache
	 * @return mix
	 * @author Icehu
	 */
	public static function get($key)
	{
		$mem = self::getMemcached();
		if($mem)
		{
			switch(self::$memType)
			{
				//使用Memcached扩展
				case 'memcached':
					if(is_array($key))
					{
						$cas = array();
						return $mem->getMulti($key,$cas,Memcached::GET_PRESERVE_ORDER);
					}
					else
					{
						return $mem->get($key);
					}
					break;
				case 'memcache':
					return $mem->get($key);
					break;
			}
		}
		return null;
	}

	/**
	 * 删除一个或者一组Cache
	 *
	 * @param string|array $key
	 * @author Icehu
	 */
	public static function del($key)
	{
		$mem = self::getMemcached();
		if($mem)
		{
			switch (self::$memType)
			{
				case 'memcached':
					if(is_array($key))
					{
						//Memcached 扩展2.0新增 deleteMulti
						if(method_exists($mem, 'deleteMulti'))
						{
							$mem->deleteMulti($key);
						}
						else
						{
							foreach($key as $mk)
							{
								$mem->delete($mk);
							}
						}
					}
					else
					{
						$mem->delete($key);
					}
					break;
				case 'memcache':
					if(is_array($key))
					{
						foreach($key as $mk)
						{
							$mem->delete($mk);
						}
					}
					else
					{
						$mem->delete($key);
					}
					break;
			}
		}
	}

	/**
     * 判断Memcache请求是否Miss
     * 统一接口，Memcached扩展在2.0将Miss时的返回值改为了Null
     *
     * @param mix $val Memcached返回的值
     * @return bool
	 * @author Icehu
     */
    public static function isMiss($val)
    {
        return $val === null || $val === false;
    }

	public static function debug()
	{
		var_dump(self::$memType);
		var_dump(self::$mem);
	}
}