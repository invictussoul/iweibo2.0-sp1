<?php

/**
 * 安装
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
if (!defined ('IN_MB')) {
    exit ('Access Denied');
}
define ('SOFT_NAME', 'iWeibo');
define ('SOFT_VERSION', '2.0');
define ('SOFT_RELEASE', '20110620');
define ('INSTALL_LANG', 'SC_UTF8');
define ('UC_NAME', 'UCenter');

//note 定义配置文件路径
define ('CONFIG', ROOT_PATH . './config/db.php');
define ('CONFIG_MEMCACHE', ROOT_PATH . './config/memcached.php');
define ('UC_PATH', ROOT_PATH . './application/uc_client/client.php');
define ('UC_CONFIG', ROOT_PATH . './config/uc_config.inc.php');
define ('SETTING_BASIC', ROOT_PATH . './install/setting_basic.php');
define ('SETTING_DB', ROOT_PATH . './install/setting_db.php');
define ('SETTING_UC', ROOT_PATH . './install/setting_uc.php');
define ('CACHEDIR', ROOT_PATH . 'cache/');

//note 定义安装数据库文件位置
$sqlfile = ROOT_PATH . './install/db.sql';
$sqlfile_init = ROOT_PATH . './install/db_init.sql';

//note 定义安装完成后的锁定安装文件的位置
$lockfile = ROOT_PATH . './config/version.php';

//note 定义网站语言
define ('CHARSET', 'utf-8');
define ('DBCHARSET', 'utf8');

//note 定义原始表前缀
define ('ORIG_TABLEPRE', 'iweibo2_');

//note 一下常量为错误代码定义
define ('METHOD_UNDEFINED', 255);
define ('ENV_CHECK_RIGHT', 0);
define ('ERROR_CONFIG_VARS', 1);
define ('SHORT_OPEN_TAG_INVALID', 2);
define ('INSTALL_LOCKED', 3);
define ('DATABASE_NONEXISTENCE', 4);
define ('PHP_VERSION_TOO_LOW', 5);
define ('MYSQL_VERSION_TOO_LOW', 6);
define ('UC_URL_INVALID', 7);
define ('UC_DNS_ERROR', 8);
define ('UC_URL_UNREACHABLE', 9);
define ('UC_VERSION_INCORRECT', 10);
define ('UC_DBCHARSET_INCORRECT', 11);
define ('UC_API_ADD_APP_ERROR', 12);
define ('UC_ADMIN_INVALID', 13);
define ('UC_DATA_INVALID', 14);
define ('DBNAME_INVALID', 15);
define ('DATABASE_ERRNO_2003', 16);
define ('DATABASE_ERRNO_1044', 17);
define ('DATABASE_ERRNO_1045', 18);
define ('DATABASE_CONNECT_ERROR', 19);
define ('TABLEPRE_INVALID', 20);
define ('CONFIG_UNWRITEABLE', 21);
define ('ADMIN_USERNAME_INVALID', 22);
define ('ADMIN_EMAIL_INVALID', 25);
define ('ADMIN_EXIST_PASSWORD_ERROR', 26);
define ('ADMININFO_INVALID', 27);
define ('LOCKFILE_NO_EXISTS', 28);
define ('TABLEPRE_EXISTS', 29);
define ('ERROR_UNKNOW_TYPE', 30);
define ('ENV_CHECK_ERROR', 31);
define ('UNDEFINE_FUNC', 32);
define ('MISSING_PARAMETER', 33);
define ('LOCK_FILE_NOT_TOUCH', 34);

//note 定义需要检测的函数
$func_items = array ('mysql_connect', 'fsockopen', 'gethostbyname', 'file_get_contents', 'xml_parser_create');

//note 环境要求定义
$env_items = array
    (
    'os' => array ('c' => 'PHP_OS', 'r' => 'notset', 'b' => 'unix'),
    'php' => array ('c' => 'PHP_VERSION', 'r' => '5.0', 'b' => '5.0'),
    'attachmentupload' => array ('r' => 'notset', 'b' => '2M'),
    'gdversion' => array ('r' => '1.0', 'b' => '2.0'),
    'diskspace' => array ('r' => '10M', 'b' => 'notset'),
);

//note 可写目录和文件和设置
$dirfile_items = array
    (
    'data' => array ('type' => 'dir', 'path' => './data'),
    'config' => array ('type' => 'dir', 'path' => './config'),
    'cache' => array ('type' => 'dir', 'path' => './cache'),
    'upload' => array ('type' => 'dir', 'path' => './uploadfile'),
    'application/uc_client/data' => array ('type' => 'dir', 'path' => './application/uc_client/data')	
);

//note 表单配置
$form_db_init_items = array
    (
    'dbinfo' => array
        (
        'dbhost' => array ('type' => 'text', 'required' => 1, 'reg' => '/^.*$/', 'value' => array ('type' => 'string', 'var' => 'localhost')),
        'dbname' => array ('type' => 'text', 'required' => 1, 'reg' => '/^.*$/', 'value' => array ('type' => 'string', 'var' => 'iweibo2')),
        'dbuser' => array ('type' => 'text', 'required' => 0, 'reg' => '/^.*$/', 'value' => array ('type' => 'string', 'var' => 'root')),
        'dbpw' => array ('type' => 'password', 'required' => 0, 'reg' => '/^.*$/', 'value' => array ('type' => 'string', 'var' => '')),
        'tablepre' => array ('type' => 'text', 'required' => 1, 'reg' => '/^.*$/', 'value' => array ('type' => 'string', 'var' => 'iweibo_')),
    ),
    'siteinfo' => array
        (
        'adminusername' => array ('type' => 'text', 'required' => 1, 'reg' => '/^\w{3,15}$/'),
        'adminemail' => array ('type' => 'text', 'required' => 1, 'reg' => '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/'),
        'adminpw' => array ('type' => 'password', 'required' => 1, 'reg' => '/^.*$/'),
        'adminpw2' => array ('type' => 'password', 'required' => 1, 'reg' => '/^.*$/'),
    ),
);

//note APP key
$form_app_key_items = array
    (
    'appkey' => array
        (
        'site_name' => array ('type' => 'text', 'required' => 1, 'reg' => '/^.*$/', 'value' => array ('type' => 'constant', 'var' => 'SOFT_NAME')),
        'site_description' => array ('type' => 'textarea', 'required' => 0, 'reg' => '/^.*$/'),
        'app_key' => array ('type' => 'text', 'required' => 1, 'reg' => '/^[0-9a-z]{32}$/', 'value' => array ('type' => 'string', 'var' => '')),
        'app_secret' => array ('type' => 'text', 'required' => 1, 'reg' => '/^[0-9a-z]{32}$/', 'value' => array ('type' => 'string', 'var' => '')),
        'use_uc' => array ('type' => 'checkbox', 'required' => 0, 'reg' => '/^[01]$/')
    ),
    'memcache' => array (
        'use_memcache' => array ('type' => 'checkbox', 'required' => 0, 'reg' => '/^[01]$/'),
        'memcache_server' => array ('type' => 'text', 'required' => 0, 'reg' => '/^.*+/', 'value' => array ('type' => 'string', 'var' => '127.0.0.1')),
        'memcache_port' => array ('type' => 'text', 'required' => 0, 'reg' => '/^.*+/', 'value' => array ('type' => 'string', 'var' => '11211')),
    )
);//note UC应用
$form_app_reg_items = array
    (
    'ucenter' => array
        (
        'ucurl' => array ('type' => 'text', 'required' => 1, 'reg' => '/^https?:\/\//', 'value' => array ('type' => 'var', 'var' => 'ucapi')),
        'ucip' => array ('type' => 'text', 'required' => 0, 'reg' => '/^\d+\.\d+\.\d+\.\d+$/'),
        'ucpw' => array ('type' => 'password', 'required' => 1, 'reg' => '/^.*$/')
    )
);