<?php
/**
 * UC配置文件，在安装时初始化。
 * if( 使用UC模式安装 )
 * {
 *	 //设置使用UC的开关 常读配置写入basic组
 *   Core_Config::get('useuc','basic') == 1
 *
 *   //设置UC具体参数
 *   Core_Config::get( null ,'uc',array()) == array(
 *		'connect' => ''	,	// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
							// mysql 是直接连接的数据库, 为了效率, 建议采用 mysql
 *		'dbhost' => '', 	// UCenter 数据库主机
 *		'dbuser' => '', 	// UCenter 数据库用户名
 *		'dbpw'] => '', 		// UCenter 数据库密码
 *		'dbname' => '', 	// UCenter 数据库名称
 *		'dbcharset'=> ''	// UCenter 数据库字符集
 *		'dbtablepre'] => '' // UCenter 数据库表前缀
 *
 *		'key' => ''			// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
 *		'api' => ''			// UCenter 的 URL 地址, 在调用头像时依赖此常量
 *		'charset' =>''		// UCenter 的字符集
 *		'ip' => '',			// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
 *		'appid' => ''		// 当前应用的 ID
 *
 *   );
 * }
 */
$uccfg = Core_Config::get(null,'uc',array());

define('UC_CONNECT', $uccfg['connect']);				// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
											// mysql 是直接连接的数据库, 为了效率, 建议采用 mysql

//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
//UCenter 配置参数
define('UC_DBHOST', $uccfg['dbhost']); 			// UCenter 数据库主机
define('UC_DBUSER', $uccfg['dbuser']); 			// UCenter 数据库用户名
define('UC_DBPW', $uccfg['dbpw']); 				// UCenter 数据库密码
define('UC_DBNAME', $uccfg['dbname']); 			// UCenter 数据库名称
define('UC_DBCHARSET', $uccfg['dbcharset']); 	// UCenter 数据库字符集
define('UC_DBTABLEPRE', $uccfg['dbtablepre']); 	// UCenter 数据库表前缀
define('UC_DBCONNECT', $uccfg['dbconnect']); 	// UCenter 数据库持久连接 0=关闭, 1=打开

//通信相关
define('UC_KEY', $uccfg['key']);				// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', $uccfg['api']);				// UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', $uccfg['charset']);		// UCenter 的字符集
define('UC_IP', $uccfg['ip']);					// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', $uccfg['appid']);			// 当前应用的 ID

require_once INCLUDE_PATH . 'uc_client/client.php';

class Core_Outapi_Uc
{
	public static function call( $command )
	{
		$func = 'uc_' . $command;
		if(!function_exists($func))
		{
			return false;
		}
		//自动适配GBK版本UCenter
		$charset = strtolower(UC_CHARSET);
		if(func_num_args () > 1)
		{
			$params = func_get_args();
			array_shift($params);
			if($charset == 'gbk')
			{
				$params = Core_Fun::iconv('utf-8//ignore', 'gbk//ignore', $params);
			}
			$rt = call_user_func_array($func, $params);
			if($charset == 'gbk')
			{
				return Core_Fun::iconv('gbk//ignore', 'utf-8//ignore', $rt);
			}
			return $rt;
		}
		else
		{
			$rt = call_user_func($func);
			if($charset == 'gbk')
			{
				return Core_Fun::iconv('gbk//ignore', 'utf-8//ignore', $rt);
			}
			return $rt;
		}
	}
}
