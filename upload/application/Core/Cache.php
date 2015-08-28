<?php

/**
 * 统一Cache方法
 *
 * @author Icehu
 */
class Core_Cache
{

	/**
	 *
	 * @var stirng
	 */
	public static $_method = 'php'; // php or memcache!

	/**
	 * 读取一条Cache
	 * @param string $file	缓存的key 可以是数组
	 * @param string $method	缓存方法Memcache或者文件Cache，如果使用Memache，在系统不支持是，自动降级到文件Cache
	 * @return mix
	 * @author Icehu
	 */
	public static function read($file, $method=null)
	{
		$method = self::_getmethod($method);
		$file = self::_getkey($file,$method);
		switch ($method)
		{
			case 'memcache':
				$rt = Core_Cache_Memcached::get($file);
				break;
			case 'php':
			default :
				$rt = Core_Cache_File::get($file);
				break;
		}
		if($method == 'php' && is_array($file))
		{
			$len = strlen(CACHEDIR);
			foreach($rt as $rk => $rv)
			{
				$rt[substr($rk, $len)] = $rv;
				unset($rt[$rk]);
			}
		}
		return $rt;
	}

	/**
	 * alias to read
	 * @param string|array $key 缓存的Key 可以接受数组，返回一批Cache
	 * @param string $method 缓存方法 memcache | php
	 * @return mix
	 * @author Icehu
	 */
	public static function get($key, $method=null)
	{
		return self::read($key, $method);
	}

	/**
	 * 写入一条Cache
	 *
	 * @param string $key 缓存的Key
	 * @param mix $data 缓存的数据
	 * @param number $_expire 超时时间，单位秒
	 * @param string $method	缓存方法Memcache或者文件Cache，如果使用Memache，在系统不支持是，自动降级到文件Cache
	 * @author Icehu
	 */
	public static function write($key, $data, $_expire = 0, $method=null)
	{
		$method = self::_getmethod($method);
		$key = self::_getwritekey($key,$method);
		switch ($method)
		{
			case 'memcache':
				Core_Cache_Memcached::set($key, $data, $_expire);
				break;
			case 'php':
			default :
				Core_Cache_File::set($key, $data, $_expire);
		}
	}

	/**
	 * alias to self::write
	 * @param string|array $key
	 * @param mix $data
	 * @param number $_expire
	 * @param string $method
	 */
	public static function set($key, $data, $_expire = 0, $method=null)
	{
		self::write($key, $data, $_expire, $method);
	}

	protected static $memcacheuse = null;

	/**
	 * 是否支持memcache，暂时屏蔽
	 * @return bool
	 * @author Icehu
	 */
	public static function memcacheuse()
	{
		if(null === self::$memcacheuse)
		{
			if(file_exists(CONFIG_PATH . 'memcached.php') && (class_exists('Memcache', false) || class_exists('Memcached', false)) )
			{
				self::$memcacheuse = true;
			}
			else
			{
				self::$memcacheuse = false;
			}
		}
		return self::$memcacheuse;
	}

	/**
	 * 
	 * 删除一条Cache
	 *
	 * @param string $file 缓存Key
	 * @param string $method	缓存方法Memcache或者文件Cache，如果使用Memache，在系统不支持是，自动降级到文件Cache
	 * @author Icehu
	 */
	public static function delete($file, $method=null)
	{
		self::remove($file, $method);
	}

	/**
	 * alias to self::delete
	 */
	public static function del($key, $method=null)
	{
		self::remove($key, $method);
	}

	/**
	 *
	 * 删除一条Cache
	 *
	 * @param string $file 缓存Key 传入数组删除一批
	 * @param string $method	缓存方法Memcache或者文件Cache，如果使用Memache，在系统不支持是，自动降级到文件Cache
	 * @author Icehu
	 */
	public static function remove($key, $method=null)
	{
		$method = self::_getmethod($method);
		$key = self::_getkey($key,$method);
		switch ($method)
		{
			case 'memcache':
				Core_Cache_Memcached::del($key);
				break;

			case 'php' :
			default:
				Core_Cache_File::del($key);
				break;
		}
	}

	/**
	 * 清理整个Cache目录里的文件Cache
	 * 考虑加入Memcache的 flush
	 * @param string $dir 
	 */
	public static function updateAllCache($dir='')
	{
		!$dir && $dir = CACHEDIR;
		$handle = opendir($dir);
		while ($file = readdir($handle))
		{
			if (preg_match('/^\./', $file))
			{
				continue;
			}
			$realFile = rtrim($dir, '/') . '/' . $file;
			if (is_dir($realFile))
			{
				self::updateAllCache($realFile);
			}
			else
			{
				if (!preg_match('/\.php$/', $file))
				{
					continue;
				}
				self::remove(str_replace(CACHEDIR, '', $realFile), 'php');
			}
		}
	}

	public static function _getmethod($method=null)
	{
		!$method && $method = self::$_method;
		if(self::memcacheuse() && 'memcache' == $method )
		{
			return 'memcache';
		}
		return 'php';
	}

	protected static function _getkey($key,$method='php')
	{
		if($method == 'php')
		{
			if(is_array($key))
			{
				foreach($key as $ck => $cv)
				{
					$key[$ck] = CACHEDIR . $cv;
				}
				return $key;
			}
			else
			{
				return CACHEDIR . $key;
			}
		}
		return $key;
	}

	protected static function _getwritekey($key,$method='php')
	{
		if($method == 'php')
		{
			if(is_array($key))
			{
				foreach($key as $ck => $cv)
				{
					$key[CACHEDIR . $ck] = $cv;
					unset($key[$ck]);
				}
				return $key;
			}
			else
			{
				return CACHEDIR . $key;
			}
		}
		return $key;
	}

	/**
     * 判断Cache请求是否Miss
     * 统一接口，Memcached扩展在2.0将Miss时的返回值改为了Null
     *
     * @param mix $val Cache返回的值
     * @return bool
	 * @author Icehu
     */
    public static function isMiss($val)
    {
        return $val === null || $val === false;
    }

}