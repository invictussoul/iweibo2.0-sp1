<?php

/**
 * 常用函数库
 *
 * @author Icehu
 */
require_once 'PEAR.php';
class Core_Fun
{

	/**
	 * 对变量进行反转义到原始数据
	 *
	 * @param string|array $param 需要反转义的原始数据
	 * @return string|array
	 * @author Icehu
	 */
	public static function stripslashes($param)
	{
		if (is_array($param))
		{
			foreach ($param as $k => $v)
			{
				$param[$k] = self::stripslashes($v);
			}
			return $param;
		}
		else
		{
			return stripslashes($param);
		}
	}

	/**
	 * 字符转码方法
	 * @param string $in_charst 输入字符集
	 * @param string $out_charset 输出字符集
	 * @param string|array $param 转换数据
	 * @return string|array
	 * @author Icehu
	 */
	public static function iconv($in_charst, $out_charset, $param)
	{
		if (is_array($param))
		{
			foreach ($param as $_key => $_var)
			{
				$param[$_key] = self::iconv($in_charst, $out_charset, $_var);
			}
			return $param;
		}
		else
		{
			if (function_exists('iconv'))
			{
				return @iconv($in_charst, $out_charset, $param);
			}
			elseif (function_exists('mb_convert_encoding'))
			{
				return @mb_convert_encoding($param, $out_charset, $in_charst);
			}
			else
			{
				return $param;
			}
		}
	}

	/**
	 * 获取上一次访问的地址
	 *
	 * @return string
	 * @author Icehu
	 * @todo 考虑登陆、退出地址、SEO转换等问题
	 */
	public static function getreffer()
	{
		return $_SERVER['HTTP_REFERER'];
	}

	/**
	 * 设置Cookie
	 *
	 * @param string $name Cookie名称
	 * @param string $value Cookie值
	 * @param number $kptime Cookie有效期
	 * @param bool $httponly 是否httponly
	 * @author Icehu
	 */
	public static function setcookie($name, $value=null, $kptime=0, $httponly=false)
	{
		$cookie_pre = Core_Config::get('cookiepre', 'basic', 't_');
		$domain = Core_Config::get('cookiedomain', 'basic', null);
		if (null !== $value)
		{
			if ($kptime)
			{
				setcookie($cookie_pre . $name, $value, self::time() + $kptime, '/', $domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0, $httponly);
			}
			else
			{
				setcookie($cookie_pre . $name, $value, null, '/', $domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0, $httponly);
			}
		}
		else
		{
			//drop
			setcookie($cookie_pre . $name, '', self::time() - 3600, '/', $domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
		}
	}

	/**
	 * 获取Cookie的值
	 * @param string $name
	 * @return string
	 * @author Icehu
	 */
	public static function getcookie($name)
	{
		$cookie_pre = Core_Config::get('cookiepre', 'basic', 't_');
		return @$_COOKIE[$cookie_pre . $name];
	}

	/**
	 * 获取脚本运行时间
	 *
	 * @return number
	 * @author Icehu
	 */
	public static function time()
	{
		if (!isset($_SERVER['REQUEST_TIME']))
		{
			$_SERVER['REQUEST_TIME'] = time();
		}
		$timemodify = Core_Config::get('timemodify', 'basic',0);
		return $_SERVER['REQUEST_TIME'] + intval($timemodify);
	}

	/**
	 * 获取当前脚本执行的微秒时间
	 * @return float
	 * @author Icehu
	 */
	public static function microtime()
	{
		return microtime(true);
	}

	/**
	 * 获取客户端IP
	 * 目前是取带来IP，是否取真实IP？如果取真实IP，可能被伪造
	 * @return string
	 * @author Icehu
	 * @todo 取真实IP？
	 */
	public static function ip()
	{
		if (isset($_SERVER['REMOTE_ADDR']))
		{
			return $_SERVER['REMOTE_ADDR'];
		}
		else if ($_tmp = getenv('REMOTE_ADDR'))
		{
			return $_tmp;
		}
		return 'unknow';
	}

	/**
	 * 考虑系统配置时区的时间格式化方法
	 * 默认 +8 时区
	 *
	 * @param string $format 格式化时间的字符串，同date方法
	 * @param number $timestamp 时间戳
	 * @return string
	 * @author Icehu
	 */
	public static function date($format, $timestamp=null)
	{
		$timestamp = (null == $timestamp) ? self::time() : $timestamp;
		$timezone = Core_Config::get('timezone', 'basic', 8);
		return gmdate($format, $timestamp + $timezone * 3600);
	}

	/**
	 * 考虑了系统设置时区的时间戳生成方法
	 *
	 * @param number $hour 小时
	 * @param number $min 分钟
	 * @param number $sec 秒钟
	 * @param number $mon 月份
	 * @param number $year 年份
	 * @param number $day 日期
	 * @return timestamp
	 * @author Icehu
	 */
	public static function mktime($hour, $min, $sec, $mon, $year, $day)
	{
		$timezone = Core_Config::get('timezone', 'basic', 8);
		$t = gmmktime($hour, $min, $sec, $mon, $day, $year);
		return $t ? $t - $timezone * 3600 : 0;
	}

	/**
	 * 将 yyyy-mm-dd H:i:s 格式的时间格式化成时间戳
	 * @param string $str
	 * @return timestamp
	 * @author Icehu
	 */
	public static function strtotime($str)
	{
		$str = str_replace(array(' ', ':'), '-', trim($str));
		$a = explode('-', $str);
		@list($year, $mon, $day, $hour, $min, $sec) = $a;
		return self::mktime($hour, $min, $sec, $mon, $year, $day);
	}

	/**
	 * 将数组转换为JSON格式
	 * 所有格式的数组都将转换为json对象，而不会转换为js array
	 *
	 * @param array $array
	 * @param bool $_s
	 * @return string
	 * @author Icehu
	 */
	public static function array2json($array=array(), $_s = false)
	{
		$r = array();
		foreach ((array) $array as $key => $val)
		{
			if (is_array($val))
			{
				$r[$key] = "\"$key\": " . self::array2json($val, $_s);
			}
			else
			{
				if ($_s && $key == '_s')
				{
					$r[$key] = "\"$key\": " . $val;
				}
				else
				{
					if (is_numeric($val))
					{
						$r[$key] = "\"$key\": " . $val;
					}
					else if (is_bool($val))
					{
						$r[$key] = "\"$key\": " . ($val ? 'true' : 'false');
					}
					else if (is_null($val))
					{
						$r[$key] = "\"$key\": null";
					}
					else
					{
						$r[$key] = "\"$key\": \"" . str_replace(array("\r\n", "\n", "\""), array("\\n", "\\n", "\\\""), $val) . '"';
					}
				}
			}
		}
		return '{' . implode(',', $r) . '}';
	}

	public static function error($msg, $code=-1, $params=array())
	{
		throw new Core_Exception($msg, $code, $params);
	}

	/**
     * 返回json对象
     */
	public static function returnJson( $code , $msg='' , $params=array(), $callback=NULL)
	{
		$msg = empty($msg)? Core_Comm_Modret::getMsg($code) :$msg;
		return Core_Comm_Modret::getRetJson($code, $msg, $params,$callback);
	}

	/**
     * 输出json对象 并exit
     */
	public static function exitJson( $code , $msg='' , $params=array(), $callback=NULL)
	{
		exit( self::returnJson($code, $msg, $params,$callback));
	}

	/**
     * iframe的ajax方式输出json对象 主要是有script标签
     */
	public static function iFrameExitJson( $code , $msg='' , $params=array(), $callback=NULL)
	{
		if(Core_Comm_Validator::checkCallback($callback))		//有回调函数才需要<script>标签
			{
				exit('<script>'.self::returnJson($code,$msg , $params, $callback).'</script>');
			}
			else
			{
					exit( self::returnJson($code, $msg, $params));
			}
	}

	public static function showmsg ( $msg , $gourl=-1 , $time = null , $button=false )
    {
        if ($time === null)
		{
            $time = Core_Config::get ('showtime' , 'basic' , 3);
		}
        $time > 1000 && $time = intval ($time / 1000);

		$seogourl = $gourl;
        if ($gourl == -1)
		{
            $seogourl = $gourl = empty($_SERVER['HTTP_REFERER'])?self::getUrlroot():$_SERVER['HTTP_REFERER'];
//            $seogourl = $gourl = 'javascript:history.go(-1)';
        }
		elseif (!preg_match ('#^(https?|ftp)://#' , $gourl) && !preg_match ('/^javascript/' , $gourl))
		{
            $seogourl = Core_Template::seoChange ($gourl);
			if(!preg_match('/^\//', $gourl))
			{
				$gourl = '/' . $gourl;
			}
        }

        if (!$time)
		{ #立即跳转
            if (!preg_match ('/^javascript/' , $gourl))
			{
                header ('Location: ' . $seogourl);
            }
            echo $meta = "<meta http-equiv=\"refresh\" content=\"{$time};url={$seogourl}\" />";
        } else {
            #延迟跳转
			Core_Template::assignvar('gourl' , $gourl);
			Core_Template::assignvar('seogourl' , $seogourl);
			Core_Template::assignvar('__msg' , $msg);
			Core_Template::assignvar('msg' , $msg);
			Core_Template::assignvar('time' , $time);
			Core_Template::assignvar('button' , $button);
            if (Core_Controller_Front::getInstance()->getModelName () == 'admin')
			{
				Core_Template::render ('admin/showmsg.tpl');
            }
			else
			{
				Core_Template::render ('index/showmsg.tpl');
            }
        }
        exit;
    }

	public static $data = null;

	/**
	 * 获得webroot
	 * 以 / 结尾
	 * @return string
	 * @author Icehu
	 */
	public static function getWebroot()
	{
		return Core_Controller_Front::getWebRoot();
	}

	/**
	 * 获取前端用的Url根目录
	 * 开启SEO 返回 /
	 * 否则 返回 /index.php/
	 *
	 * @return string
	 * @author Icehu
	 */
	public static function getPathroot()
	{
		if(Core_Config::get('seo', 'basic', 0))
		{
			return self::getUrlroot();
		}
		else
		{
			return self::getUrlroot() . 'index.php' . self::getPathinfoPre();
		}
	}

	/**
	 * 获得Urlroot
	 * @return string
	 * @author Icehu
	 */
	public static function getUrlroot()
	{
		$webroot = self::getWebroot();
		$http = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
		return $http . '://' . $_SERVER['HTTP_HOST'] . $webroot;
	}

	/**
	 * 获得用户地址
	 * @return string
	 * @author echoyang
	 */
	public static function getUserurl($uname='')
	{
		 $usertUrl= self::getUrlroot();
		if($uname && Core_Comm_Validator::isUserAccount($uname))
		{
			$http = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
			$port = ($_SERVER["SERVER_PORT"] == 80)? '':':80';
			$usertUrl = $http . '://' . $_SERVER['HTTP_HOST'] .$port. self::seoChange('u/'.$uname);
		}
		return $usertUrl;
	}

	/**
	 * 获得 参数是$para的模块$preUrl下的 url，用于前台分页pathinfo数据生成
	 *@$preUrl = /model/controllor/action
	 *@$para = array('key1'=>$value1,'key2'=>$value2)
	 * @return string
	 * @author echoyang
	 */
	public static function getParaUrl($preUrl,$para = array())
	{
		$returnUrl = '';
		if(!empty($preUrl))
		{
			if(is_array($para) && $para)
			{
				$returnUrl = $preUrl;
				foreach($para AS $k=>$v)
				{
					if($k &&$v)
					{
						if(strpos($k,'__')===0)//去掉__开头的私有变量
						{
							continue;
						}else{
							$returnUrl .= '/'.$k.'/'.$v;
						}
					}
				}
			}
		}
		return $returnUrl;
	}

	/**
	 * 获得用户地址以及简短url
	 * @return array('origin','short')
	 * @author echoyang
	 */
	public static function getUserShorturl($uname='', $formatLength=0,$endStr='' )
	{
		empty($endStr) && $endStr = '...'; //末尾添加str
		$formatLength = intval($formatLength);
		if(empty($formatLength))
		{
			$formatLength = 26;  //长度默认取26
		}
		$userShortUrl = $usertUrl = self::getUserurl($uname);
		if( strlen($usertUrl) > $formatLength )
		{
			$userShortUrl =  substr($usertUrl, 0 ,$formatLength);
			$userShortUrl .= $endStr;
		}
		return array('origin'=>$usertUrl,'short'=>$userShortUrl);
	}



	/**
	 * seo转换方法
	 * @param string $str
	 * @return string
	 * @author Icehu
	 */
	public static function seoChange($str)
	{
		return Core_Template::seoChange($str);
	}

	public static function escape_special_chars($string)
	{
		if (!is_array($string))
		{
			$string = preg_replace('!&(#?\w+);!', '%%%TTT_START%%%\\1%%%TTT_END%%%', $string);
			$string = htmlspecialchars($string);
			$string = str_replace(array('%%%TTT_START%%%', '%%%TTT_END%%%'), array('&', ';'), $string);
		}
		return $string;
	}

	/**
	 * 检查文件是否可读
	 * @param string $filename
	 * @return bool
	 * @author Icehu
	 */
	public static function isReadable($filename)
	{
		if (!$fh = @fopen($filename, 'r', true))
		{
			return false;
		}
		@fclose($fh);
		return true;
	}

    /**
	 * 获取pathinfo前缀,当nginx无配置时,使用'?',否则使用默认值'/'
	 * @return string
	 * @author maynardliu
	 */
	public static function getPathinfoPre()
	{
		static $pathinfopre;
		if(isset($pathinfopre))
		{
			return $pathinfopre;
		}
		else
		{
			$pathinfopre = "/";
			if(false!==strpos($_SERVER['SERVER_SOFTWARE'],"nginx"))
			{
				if(!isset($_SERVER['ORIG_PATH_INFO']))
				{
					$pathinfopre = "?";
				}
			}
            if(false!==strpos($_SERVER['SERVER_SOFTWARE'],"Microsoft-IIS"))
            {
                $pathinfopre = "?";
            }
		}
		return $pathinfopre;
	}

	/**
	 * 经过重写的SESSION_START
	 *
	 * @author Icehu
	 */
	public static function session_start()
	{
		if (!defined('SESSION_START'))
		{
			$cookie_pre = Core_Config::get('cookiepre', 'basic', 't_');
			$domain = Core_Config::get('cookiedomain', 'basic', null);
			ini_set('session.name', $cookie_pre . 'skey');
			session_set_cookie_params(0, '/', $domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0, true);
			session_set_save_handler(
					array('Core_Lib_Session', 'open'),
					array('Core_Lib_Session', 'close'),
					array('Core_Lib_Session', 'read'),
					array('Core_Lib_Session', 'write'),
					array('Core_Lib_Session', 'destroy'),
					array('Core_Lib_Session', 'gc')
			);
			$cookieTime = Core_Config::get('cookietime', 'basic', 30);
			session_cache_expire($cookieTime > 0 ? $cookieTime : 30);
			session_start();
			define('SESSION_START', true);
		}
	}

	/**
	 * UTF-8数据的中文截字
	 *
	 * @param string $content 需要截字的原文
	 * @param number $length 截取的长度
	 * @param string $add 末尾添加的字符串
	 * @return string
	 * @author Icehu
	 */
	public static function cn_substr($content, $length, $add='')
	{
		if ($length && strlen($content) > $length)
		{
			$str = substr($content, 0, $length);
			$len = strlen($str);
			for ($i = strlen($str) - 1; $i >= 0; $i-=1)
			{
				$hex .= ' ' . ord($str[$i]);
				$ch = ord($str[$i]);
				if (($ch & 128) == 0)
					return substr($str, 0, $i) . $add;
				if (($ch & 192) == 192)
					return substr($str, 0, $i) . $add;
			}
			return($str . $hex . $add);
		}
		return $content;
	}

	/**
	 * 按照命名规则载入类
	 *
	 * @param string $class
	 * @return Class
	 * @author
	 */
	public static function loadClass($class)
	{
		if (class_exists($class, false))
		{
			return;
		}
		$file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
		//include_once($file);
		require_once(INCLUDE_PATH . $file);
	}

	/**
	 * 特殊加密url,防止地址被解析不完全
	 *
	 * @param string $class
	 * @return string
	 * @author
	 */
	public static function iurlencode($key)
	{
		if(preg_match('/^apache/i', $_SERVER['SERVER_SOFTWARE']))
		{
			//Apache在重写情况下 “/” 需要加密两次，否则Url rewrite出错
			return rawurlencode(str_replace(array('/', '?', '&', '#'), array( Core_Config::get('seo', 'basic', 0) ? '%252F' : '%2F', '%3F', '%26', '%23'), $key));
		}
		else
		{
			return rawurlencode(str_replace(array('/', '?', '&', '#'), array('%2F', '%3F', '%26', '%23'), $key));
		}
	}

	/**
	 * 特殊url解密,防止地址不是原地址
	 *
	 * @param string $class
	 * @return string
	 * @author icehu
	 */
	public static function iurldecode($key)
	{
		$key = rawurldecode($key);
		if(preg_match('/^apache/i', $_SERVER['SERVER_SOFTWARE']))
		{
			//Apache 会自动解码一次
			return str_replace(array('%2F') , array('/') , $key);
		}
		else
		{
			return str_replace(array('%2F', '%3F', '%26', '%23'), array('/', '?', '&', '#'), $key);
		}
	}

	/**
	 * 格式化字节
	 * @param $size - 大小(字节)
	 * @return 返回格式化后的文本
	 * @author Icehu
	*/
   public static function formatBytes($size)
	{
		if ($size >= 1073741824)
		{
			$size = round($size / 1073741824 * 100) / 100 . ' GB';
		}
		elseif ($size >= 1048576)
		{
			$size = round($size / 1048576 * 100) / 100 . ' MB';
		}
		elseif ($size >= 1024)
		{
			$size = round($size / 1024 * 100) / 100 . ' KB';
		}
		else
		{
			$size = $size . ' Bytes';
		}
		return $size;
	}

	/**
	 * 插件钩子调用方法
	 * @param string $hackName
	 * @return string
	 */
	public static function pluginHack($hackName)
	{
		$returnContents = '';
		$pluginModel = new Model_Mb_Plugin();
		$pluginList = $pluginModel->getPluginList(3);
		foreach($pluginList as $plugin)
		{
            $call = array('Plugin_'.ucfirst($plugin['foldername']).'_Index', $hackName.'Hack');
            if (class_exists('Plugin_'.ucfirst($plugin['foldername']).'_Index'))
            	if(is_callable($call))
            		$returnContents .= call_user_func($call);
		}
		return $returnContents;
	}
}
/**
 * Core_Excetpion 异常重写
 * 这两个异常放到Fun 文件中，不符合命名规范。
 * 但是Fun全局载入，这是为了减少文件数量，提高性能。
 *
 * @author Icehu
 */
class Core_Exception extends Exception
{

	public $params = array();

	public function __construct($msg, $code, $params=array())
	{
		parent::__construct($msg, $code);
		$this->params = (array) $params;
	}

	public function getParams()
	{
		return $this->params;
	}

}

/**
 * Core_Excetpion 异常重写
 * open_client异常
 * @author echoyang
 */
class Core_Api_Exception extends Core_Exception
{

	public $params = array();

	public function __construct($msg, $code, $params=array())
	{
		parent::__construct($msg, $code, $params);

	}
}

class Core_Db_Exception extends Core_Exception
{
}