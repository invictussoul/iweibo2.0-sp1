<?php
/**
 * run文件
 * @author Icehu
 */
!defined('ROOT') && exit('Forbidden');
define('CACHEDIR',ROOT . 'cache/');
if( !file_exists(ROOT . 'config/db.php') )
{
	//go to install!
	header('Location: install/index.php');
	exit;
}

define('APPLICATION_PATH',INCLUDE_PATH . 'Controller/');
define('PLUGIN_PATH',INCLUDE_PATH . 'Plugin/');
define('TEMPLATE_PATH',ROOT . 'view/');
define('CONFIG_PATH',ROOT . 'config/');

date_default_timezone_set('Asia/Shanghai');
set_include_path(INCLUDE_PATH . PATH_SEPARATOR . get_include_path());
ini_set('magic_quotes_runtime', 0);
$rundebug = Core_Config::get('rundebug','basic',false);
if ($rundebug)
{
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	//error_reporting(2047);
}
else
{
	error_reporting(0);
}
//other env set

//使用原始数据，入库需要转义！
if (get_magic_quotes_gpc()) {
	//trips
	$_GET = Core_Fun::stripslashes($_GET);
	$_POST = Core_Fun::stripslashes($_POST);
	$_COOKIE = Core_Fun::stripslashes($_COOKIE);
	$_REQUEST = Core_Fun::stripslashes($_REQUEST);
}

//全局session_start
Core_Fun::session_start();
//bug fix Ea or Apc session_write
register_shutdown_function('session_write_close');
//兼容 json_encode / json_decode
if( !function_exists('json_encode') )
{
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}

if( !function_exists('json_decode') )
{
	function json_decode($data , $assoc = false) {
		$use = 0;
		if($assoc)
		{
			//SERVICES_JSON_LOOSE_TYPE	返回关联数组
			$use = 0x10;
		}
        $json = new Services_JSON($use);
        return( $json->decode($data) );
    }
}

$front = Core_Controller_Front::getInstance();
$front->setApplicationPath(APPLICATION_PATH);
$front->setPluginPath(PLUGIN_PATH);
//注册运行的Model
$front->registerModels(array('admin','example','index','api','plugin','wap'));
try{
	$front->dispatch();
}
catch (Core_Db_Exception $e)
{
	$inajax = $front->inAjax();
	if($rundebug)
	{
		if($inajax)
		{
			Core_Fun::exitJson($e->getCode(), $e->getMessage(), $e->getParams());
		}
		else
		{
			header('Content-Type: text/html; charset=utf-8');
			echo '<pre>';
			var_dump($e);
		}
	}
	else
	{
		if($inajax)
		{
			Core_Fun::exitJson($e->getCode(), '系统繁忙' , $e->getParams());
		}
		else
		{
			Core_Fun::showmsg('系统繁忙', -1 );
		}
	}
}
catch (Core_Exception $e)
{
	$inajax = $front->inAjax();
	if($rundebug)
	{
		if($inajax)
		{
			Core_Fun::exitJson($e->getCode(), $e->getMessage(), $e->getParams());
		}
		else
		{
			header('Content-Type: text/html; charset=utf-8');
			echo '<pre>';
			var_dump($e);
		}
	}
	else
	{
		$code = $e->getCode();
		$msg = $e->getMessage();
		if($code > 0)
		{
			if($code == 404)
			{
				header('HTTP/1.1 404 Not Found');
				exit;
			}
			else
			{
				if($inajax)
				{
					Core_Fun::exitJson($code, '系统繁忙' , $msg);
				}
				else
				{
					Core_Fun::showmsg('系统繁忙', -1 );
				}
			}
		}
		else if($code < 0)
		{
			if($inajax)
			{
				Core_Fun::exitJson($code, '系统繁忙' , $msg);
			}
			else
			{
				Core_Fun::showmsg($msg, -1 );
			}
		}
	}
}
catch (Exception $e){
	//todo! error_report!
	$inajax = $front->inAjax();
	if($inajax)
	{
		Core_Fun::exitJson($e->getCode(), $e->getMessage());
	}
	else
	{
		header('Content-Type: text/html; charset=utf-8');
		echo '<pre>';
		var_dump($e);
	}
}

function __autoload($class)
{
	if (class_exists($class, false))
	{
		return;
	}
	$file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
	//include_once($file);
	require_once(INCLUDE_PATH . $file);
}
