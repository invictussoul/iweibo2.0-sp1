<?php
/**
 * 文件缓存接口
 *
 * @author icehu
 */
class Core_Cache_File
{

	/**
	 * Set 方法
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
		if(is_array($key))
		{
			foreach($key as $ck => $cv)
			{
				self::set($ck, $cv, $expire);
			}
		}
		else
		{
			$data = "<?php\r\n /**\r\n Cachefile auto created by IWeibo , created on GMT+8 " . strftime("%Y-%m-%d %H:%M:%S", time()) . " , do not modify it!\r\n*/ \r\nreturn " . var_export($data, true) . ";\r\n";
			!$expire && $expire = mt_rand(24, 48) * 3600;
			Core_Fun_File::write($key, $data);
			@touch($key, time() + $expire);
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
		if(is_array($key))
		{
			$rt = array();
			foreach($key as $ck)
			{
				$cv = self::get($ck);
				if(null !== $cv)
				{
					$rt[$ck] = $cv;
				}
			}
			return $rt;
		}
		else
		{
			if (intval(@filemtime($key)) < time())
			{
				return null;
			}
			return include($key);
		}
	}

	/**
	 * 删除一个或者一组Cache
	 *
	 * @param string|array $key
	 * @author Icehu
	 */
	public static function del($key)
	{
		if (is_array($key))
		{
			foreach($key as $ck)
			{
				self::del($ck);
			}
		}
		else
		{
			Core_Fun_File::remove($key);
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
}