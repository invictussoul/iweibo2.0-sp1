<?php
/**
 * Session 重写类
 * @author icehu
 */
class Core_Lib_Session
{

	protected static $table = null;
	protected static $sessions = array();
	protected static $sessparams = array();
	protected static $changed = null;

	const GCRATE = 1000;

	public static function open()
	{
		//do nothing
	}

	public static function close()
	{
		//gc
		self::gc(ini_get('session.gc_maxlifetime'));
	}

	public static function write($id, $data)
	{
		$sdata = self::getsession($id);
		if ($data == $sdata && !isset(self::$changed[$id]))
		{
			return;
		}
		$stime = session_cache_expire();
		$stime = $stime > 0 ? $stime : 30;
		$replace = array(
			'skey' => Core_Db::sqlescape($id),
			'expire' => time() + $stime * 60,
			'lastupdate' => time(),
			'uid' => isset($_SESSION['uid']) ? $_SESSION['uid'] : 0,
			'sdata' => $data,
			'ip' => $_SERVER['REMOTE_ADDR'] ? ip2long($_SERVER['REMOTE_ADDR']) : 0,
		);
		$set = array();
		foreach($replace as $k => $v)
		{
			if(in_array($k,array('sdata','skey')))
			{
				$set[] = "`$k`='" . Core_Db::sqlescape($v) . "'";
			}
			else
			{
				$set[] = "`$k`=$v";
			}
		}
		$set = implode(' , ', $set);
		try
		{
			Core_Db::query("Replace Into ##__base_session set {$set}", true);
		}
		catch (Exception $e)
		{
		}
		self::$sessions[$id] = $data;
		Core_Cache::write(self::getcacheid($id), $replace , session_cache_expire() * 60 + 1, 'memcache');
	}

	public static function read($id)
	{
		return self::getsession($id);
	}

	public static function changeed($id=null)
	{
		if(null === $id)
		{
			$id = session_id ();
		}
		self::$changed[$id] = true;
	}

	public static function destroy($id)
	{
		$skey = Core_Db::sqlescape($id);
		try
		{
			Core_Db::query("Delete from ##__base_session where `skey`='{$skey}'", true);
		}
		catch (Exception $e)
		{

		}
		self::clearcache($id);
	}

	public static function gc($max)
	{
		if (mt_rand(0, self::GCRATE) == 0)
		{
			$time = time() - $max;
			try
			{
				Core_Db::query("Delete from `##__base_session` where `expire` < {$time}", true);
				Core_Db::query("OPTIMIZE TABLE `##__base_session`", true);
			}
			catch (Exception $e)
			{
				
			}
		}
	}

	/**
	 * 获取Session数据
	 * 并对Session id进行合法性检查
	 * 
	 * @param string $id
	 * @return string
	 */
	public static function getsession(&$id)
	{
		//Sessionid 合法性检查
		if($id && !preg_match('/[0-9a-z]{10,32}/i', $id))
		{
			$id = md5(microtime() . mt_rand(0 , 100000));
			session_id($id);
		}
		self::loadsession($id);
		return isset(self::$sessions[$id]) ? self::$sessions[$id] : '';
	}

	public static function sessparams($p=null,$value=null,$id=null)
	{
		if(null === $id)
			$id = session_id ();
		if(strlen($p)>0 && null !== $value)
		{
			if(@self::$sessparams[$id][$p] != $value)
				self::$changed[$id] = true;
			self::$sessparams[$id][$p] = $value;
			return;
		}
		$param = array();
		self::loadsession($id);
		$param = isset(self::$sessparams[$id]) ? self::$sessparams[$id] : array();
		if(null === $p)
		{
			return $param;
		}
		else
		{
			if(isset($param[$p]))
				return $param[$p];
			else
				return '';
		}
	}

	private static $onlinenum = null;

	public static function onlinenum($sec=300)
	{
		if(null === self::$onlinenum)
		{
			$num = Core_Cache::read('_cache/onlineuser.php', 'memcache');
			if($num)
				self::$onlinenum = $num;
			else
			{
				$expire = time() - $sec;
				self::$onlinenum = Core_Db::getOne("Select count(*) From ##__base_session where `lastupdate`>$expire");
				Core_Cache::write('_cache/onlineuser.php',self::$onlinenum,60, 'memcache');
			}
		}
		return self::$onlinenum;
	}

	protected static function loadsession($id=null)
	{
		if(null === $id)
		{
			$id = session_id();
		}
		if (isset(self::$sessions[$id]))
		{
			return;
		}
		$tmp = Core_Cache::read(self::getcacheid($id), 'memcache');
		if (false !== $tmp && isset($tmp['sdata']))
		{
			self::$sessions[$id] = $tmp['sdata'];
			self::$sessparams[$id] = $tmp;
		}
		if (!isset(self::$sessions[$id]))
		{
			$time = time();
			$skey = Core_Db::sqlescape($id);
			$tmp = Core_Db::fetchOne("Select `uid`,`lastupdate`,`ip`,`expire`,`sdata` From ##__base_session where `skey`='{$skey}' and `expire`>{$time}");
			if (!$tmp)
			{
				$tmp = array();
			}
			Core_Cache::write(self::getcacheid($id), $tmp, session_cache_expire() * 60 + 1, 'memcache');

			self::$sessions[$id] = isset($tmp['sdata']) && $tmp['sdata'] ? $tmp['sdata'] : '';
			self::$sessparams[$id] = $tmp;
		}
	}

	public static function clearcache($id)
	{
		Core_Cache::remove(self::getcacheid($id), 'memcache');
		unset(self::$sessions[$id]);
	}

	public static function getcacheid($id)
	{
		return '_sessions/' . substr($id, 0, 2) . '/' . $id . '.php';
	}

}