<?php

/**
 * 安装
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
//强制编码转换
@header ('Content-Type: text/html; charset=utf-8');
error_reporting (E_ERROR | E_WARNING | E_PARSE);
@set_time_limit (1000);
set_magic_quotes_runtime (0);

define ('IN_MB', TRUE);
define ('ROOT_PATH', dirname (__FILE__) . '/../');
$timestamp = time ();

require ROOT_PATH . './install/var.inc.php';
require ROOT_PATH . './install/lang.inc.php';
require ROOT_PATH . './install/db.class.php';
require ROOT_PATH . './install/func.inc.php';

file_exists (ROOT_PATH . './install/extvar.inc.php') && require ROOT_PATH . './install/extvar.inc.php';

//note 是否需要输出界面
$view_off = getgpc ('view_off');
define ('VIEW_OFF', $view_off ? TRUE : FALSE);

//安装执行顺序
$allow_method = array ('show_license', 'env_check', 'check_appkey', 'app_reg', 'db_init', 'ext_info', 'install_check', 'tablepre_check');

$step = intval (getgpc ('step', 'R')) ? intval (getgpc ('step', 'R')) : 0;
$method = getgpc ('method');
if (empty ($method) || !in_array ($method, $allow_method)) {
    $method = isset ($allow_method[$step]) ? $allow_method[$step] : '';
}

if (empty ($method)) {
    show_msg ('method_undefined', $method, 0);
}

//note 基本检测
if (file_exists ($lockfile)) {//note 安装锁定检测
    show_msg ('install_locked', '', 0);
} elseif (!class_exists ('mysqlDb')) {//note 数据库类是否加载检测
    show_msg ('database_nonexistence', '', 0);
}

if ($method == 'show_license') {//note 显示协议
    show_license ();
} elseif ($method == 'env_check') {//note 服务器环境检测
    //note 提前给接口单独检测函数
    VIEW_OFF && function_check ($func_items);

    //note 环境检测
    env_check ($env_items);

    //note 文件目录检测
    dirfile_check ($dirfile_items);

    //note 显示检测结果
    show_env_result ($env_items, $dirfile_items, $func_items);
} elseif ($method == 'app_reg') {
    //UC整合

    @include CONFIG;
    if (!defined ('UC_API')) {
        define ('UC_API', '');
    }
    $submit = true;
    $error_msg = array ();
    if (isset ($form_app_reg_items) && is_array ($form_app_reg_items)) {
        foreach ($form_app_reg_items as $key => $items)
        {
            $$key = getgpc ($key, 'p');
            if (!isset ($$key) || !is_array ($$key)) {
                $submit = false;
                break;
            }
            foreach ($items as $k => $v)
            {
                $tmp = $$key;
                $$k = $tmp[$k];
                if (empty ($$k) || !preg_match ($v['reg'], $$k)) {
                    if (empty ($$k) && !$v['required']) {
                        continue;
                    }
                    $submit = false;
                    VIEW_OFF or $error_msg[$key][$k] = 1;
                }
            }
        }
    } else {
        $submit = false;
    }

    $PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $bbserver = 'http://' . preg_replace ("/\:\d+/", '', $_SERVER['HTTP_HOST']) . ($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '');
    $default_ucapi = $bbserver . '/ucenter';
    $default_appurl = $bbserver . substr ($PHP_SELF, 0, strpos ($PHP_SELF, 'install/') - 1);
    $ucapi = defined ('UC_API') && UC_API ? UC_API : $default_ucapi;

    if ($submit) {

        $app_type = 'OTHER';

        $app_name = $sitename ? $sitename : SOFT_NAME;
        $app_url = $siteurl ? $siteurl : $default_appurl;
        $app_url .= '/index.php';
        $ucapi = $ucurl ? $ucurl : (defined ('UC_API') && UC_API ? UC_API : $default_ucapi);
        $ucip = isset ($ucip) ? $ucip : '';
        $ucfounderpw = $ucpw;
        $app_tagtemplates = 'apptagtemplates[template]=' . urlencode ('<a href="{url}" target="_blank">{subject}</a>') . '&' .
                'apptagtemplates[fields][subject]=' . urlencode ($lang['tagtemplates_subject']) . '&' .
                'apptagtemplates[fields][uid]=' . urlencode ($lang['tagtemplates_uid']) . '&' .
                'apptagtemplates[fields][username]=' . urlencode ($lang['tagtemplates_username']) . '&' .
                'apptagtemplates[fields][dateline]=' . urlencode ($lang['tagtemplates_dateline']) . '&' .
                'apptagtemplates[fields][url]=' . urlencode ($lang['tagtemplates_url']);

        $ucapi = preg_replace ("/\/$/", '', trim ($ucapi));
        if (empty ($ucapi) || !preg_match ("/^(http:\/\/)/i", $ucapi)) {
            show_msg ('uc_url_invalid', $ucapi, 0);
        } else {
            if (!$ucip) {
                $temp = @parse_url ($ucapi);
                $ucip = gethostbyname ($temp['host']);
                if (ip2long ($ucip) == -1 || ip2long ($ucip) === FALSE) {
                    show_msg ('uc_dns_error', $ucapi, 0);
                }
            }
        }
        include_once UC_PATH;

        $ucinfo = dfopen ($ucapi . '/index.php?m=app&a=ucinfo&release=' . UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
        list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode ('|', $ucinfo);
        if ($status != 'UC_STATUS_OK') {
            show_msg ('uc_url_unreachable', $ucapi, 0);
        } else {
            $dbcharset = strtolower (DBCHARSET ? str_replace ('-', '', DBCHARSET) : DBCHARSET);
            $ucdbcharset = strtolower ($ucdbcharset ? str_replace ('-', '', $ucdbcharset) : $ucdbcharset);
            if (UC_CLIENT_VERSION > $ucversion) {
                show_msg ('uc_version_incorrect', $ucversion, 0);
            }
            /*不检测UC数据库字符集
             *  elseif ($dbcharset && $ucdbcharset != $dbcharset) {
              show_msg ('uc_dbcharset_incorrect', '', 0);
              }
             */
            $postdata = "m=app&a=add&ucfounder=&ucfounderpw=" . urlencode ($ucpw) . "&apptype=" . urlencode ($app_type) . "&appname=" . urlencode ($app_name) . "&appurl=" . urlencode ($app_url) . "&appip=&appcharset=" . CHARSET . '&appdbcharset=' . DBCHARSET . '&' . $app_tagtemplates . '&release=' . UC_CLIENT_RELEASE;
            $ucconfig = dfopen ($ucapi . '/index.php', 500, $postdata, '', 1, $ucip);
            if (empty ($ucconfig)) {
                show_msg ('uc_api_add_app_error', $ucapi, 0);
            } elseif ($ucconfig == '-1') {
                show_msg ('uc_admin_invalid', '', 0);
            } else {
                list($appauthkey, $appid) = explode ('|', $ucconfig);
                if (empty ($appauthkey) || empty ($appid)) {
                    show_msg ('uc_data_invalid', '', 0);
                } elseif ($succeed = save_uc_config ($ucconfig . "|$ucapi|$ucip", UC_CONFIG)) {
                    if (VIEW_OFF) {
                        show_msg ('app_reg_success');
                    } else {
                        $step = $step + 1;
                        header ("Location: index.php?step=$step");
                        exit;
                    }
                } else {
                    show_msg ('config_unwriteable', '', 0);
                }
            }
        }
    }
    if (VIEW_OFF) {

        show_msg ('missing_parameter', '', 0);
    } else {

        show_form ($form_app_reg_items, $error_msg);
    }
} elseif ($method == 'check_appkey') {
    //note 检查微博 APP key
    @include CONFIG;
    $submit = true;
    $error_msg = array ();

    if (isset ($form_app_key_items) && is_array ($form_app_key_items)) {
        foreach ($form_app_key_items as $key => $items)
        {
            $$key = getgpc ($key, 'p');
            if (!isset ($$key) || !is_array ($$key)) {
                $submit = false;
                break;
            }
            foreach ($items as $k => $v)
            {
                $tmp = $$key;
                $$k = $tmp[$k];
                if (empty ($$k) || !preg_match ($v['reg'], $$k)) {
                    if (empty ($$k) && !$v['required']) {
                        continue;
                    }
                    $submit = false;
                    VIEW_OFF or $error_msg[$key][$k] = 1;
                }
            }
        }
    } else {
        $submit = false;
    }

    if ($submit) {//note 处理提交上来的数据
        setcookie ('app_key', $app_key);
        setcookie ('app_secret', $app_secret);
        setcookie ('site_name', $site_name);
        setcookie ('site_description', $site_description);

        if (VIEW_OFF) {
            show_msg ('app_reg_success');
        } else {
            if ($use_memcache) {
                $memcache_cfg = array ('server' => $memcache_server, 'port' => $memcache_port);
                $r = check_memcache ($memcache_cfg);
                if (!$r) {
                    show_msg ('memcache_invalid', 'memcache_info', 0);
                } else {
                    edit_memcache_config ($memcache_cfg);
                }
            }
            if ($use_uc) {
                $use_uc = setcookie ('use_uc', true);
                $step = $step + 1;
                header ("Location: index.php?step=$step");
                exit;
            } else {
                $use_uc = setcookie ('use_uc', false);
                $step = $step + 2;
                header ("Location: index.php?step=$step");
                exit;
            }
        }
    }
    if (VIEW_OFF) {
        show_msg ('missing_parameter', '', 0);
    } else {
        show_form ($form_app_key_items, $error_msg);
    }
} elseif ($method == 'db_init') {//note 安装数据库
    @include CONFIG;

    $submit = true;
    $error_msg = array ();

    if (isset ($form_db_init_items) && is_array ($form_db_init_items)) {
        foreach ($form_db_init_items as $key => $items)
        {
            $$key = getgpc ($key, 'p');
            if (!isset ($$key) || !is_array ($$key)) {
                $submit = false;
                break;
            }
            foreach ($items as $k => $v)
            {
                $tmp = $$key;
                $$k = $tmp[$k];
                if (empty ($$k) || !preg_match ($v['reg'], $$k)) {
                    if (empty ($$k) && !$v['required']) {
                        continue;
                    }
                    $submit = false;
                    VIEW_OFF or $error_msg[$key][$k] = 1;
                }
            }
        }
    } else {
        $submit = false;
    }


    if (!VIEW_OFF && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($adminpw != $adminpw2) {
            $error_msg['siteinfo']['adminpw2'] = 1;
            $submit = false;
        }

        //是否强制安装
        $forceinstall = isset ($_POST['dbinfo']['forceinstall']) ? $_POST['dbinfo']['forceinstall'] : '';
        $dbname_not_exists = true;

        if (!empty ($dbhost) && empty ($forceinstall)) {
            $dbname_not_exists = check_db ($dbhost, $dbuser, $dbpw, $dbname, $tablepre);
            if (!$dbname_not_exists) {
                $form_db_init_items['dbinfo']['forceinstall'] = array ('type' => 'checkbox', 'required' => 0, 'reg' => '/^.*+/');
                $error_msg['dbinfo']['forceinstall'] = 1;
                $submit = false;
                $dbname_not_exists = false;
            }
        }
    }

    if ($submit) {//note 处理提交上来的数据
        if (get_magic_quotes_gpc ()) {
            $_COOKIE = _stripslashes ($_COOKIE);
        }
        $use_uc = $_COOKIE['use_uc'];
        if ($use_uc) {
            //uc检查
            $adminuser = check_adminuser ($adminusername, $adminpw, $adminemail);
            if ($adminuser['uid'] < 1) {
                show_msg ($adminuser['error'], '', 0);
            } else {
                $adminuseruid = $adminuser['uid'];
            }
        }

        $step = $step + 1;
        if (empty ($dbname)) {
            show_msg ('dbname_invalid', $dbname, 0);
        } else {
            if (!@mysql_connect ($dbhost, $dbuser, $dbpw)) {
                $errno = mysql_errno ();
                $error = mysql_error ();
                if ($errno == 1045) {
                    show_msg ('database_errno_1045', $error, 0);
                } elseif ($errno == 2003) {
                    show_msg ('database_errno_2003', $error, 0);
                } else {
                    show_msg ('database_connect_error', $error, 0);
                }
            }
            if (mysql_get_server_info () > '4.1') {
                mysql_query ("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET " . DBCHARSET);
            } else {
                mysql_query ("CREATE DATABASE IF NOT EXISTS `$dbname`");
            }

            if (mysql_errno ()) {
                show_msg ('database_errno_1044', mysql_error (), 0);
            }
            mysql_close ();
        }

        if (strpos ($tablepre, '.') !== false || intval ($tablepre[0])) {
            show_msg ('tablepre_invalid', $tablepre, 0);
        }

        $_config = array (
            'dbhost' => $dbhost, 'dbuser' => $dbuser, 'dbpw' => $dbpw, 'dbname' => $dbname, 'tablepre' => $tablepre
        );

        config_edit ($_config);

        $db = new mysqlDb;
        $db->connect ($dbhost, $dbuser, $dbpw, $dbname, 1, DBCHARSET);

        $sql = file_get_contents ($sqlfile);
        $sql = str_replace ("\r\n", "\n", $sql);

        if (!VIEW_OFF) {
            show_header ();
            show_install ();
        }
        $pwd = md5 (random (10));


        //建数据库
        runquery ($sql);
        global $adminuseruid, $adminusername;
        //添加管理员
        $adminuseruid = $adminuseruid > 0 ? $adminuseruid : 1;
        $_salt = getSalt ();
        $_password = formatPassword ($adminpw, $_salt);
        $adminuseruid = mysql_real_escape_string ($adminuseruid);
        $adminusername = mysql_real_escape_string ($adminusername);
        $adminemail = mysql_real_escape_string ($adminemail);
        $adminsql = "INSERT INTO {$tablepre}user_member SET `gid` = '1'
        , `uid` = '$adminuseruid'
        , `username` = '$adminusername'
        , `email` = '$adminemail'
        , `salt` = '$_salt'
        , `password` = '$_password'
        ";
        $db->query ($adminsql);
        //初始化配置
        $setting_basic = include SETTING_BASIC;
        @$app_url = strtolower (($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . substr ($_SERVER['PHP_SELF'], 0, strrpos ($_SERVER['PHP_SELF'], '/')));
        $setting_basic['resource_path'] = substr ($_SERVER['SCRIPT_NAME'], 0, -17);
        $setting_basic['site_url'] = substr ($app_url, 0, -7);
        $setting_basic['site_name'] = mysql_real_escape_string ($_COOKIE['site_name']);
        $setting_basic['site_email'] = $adminemail;
        $setting_basic['seo_description'] = mysql_real_escape_string ($_COOKIE['site_description']);
        $setting_basic['appkey'] = mysql_real_escape_string ($_COOKIE['app_key']);
        $setting_basic['appsecret'] = mysql_real_escape_string ($_COOKIE['app_secret']);
        $setting_basic['rundebug'] = false;
        $setting_basic['how_words'] = 'iWeibo 腾讯微博';
        $setting_basic['login_user_inherit'] = true;
        $use_uc = mysql_real_escape_string ($_COOKIE['use_uc']);
        if ($use_uc) {
            $setting_basic['useuc'] = 1;
            //写UC配置
            $_setting_uc = $_COOKIE['uc_config_serialize'];
            $db->query ("Replace Into {$tablepre}base_config Set `config`='$_setting_uc' , `group`='uc' ");
        }
        $_setting_basic = serialize ($setting_basic);
        $db->query ("Replace Into {$tablepre}base_config Set `config`='$_setting_basic' , `group`='basic' ");
        //初始化权限
        $access = serialize (array ('a001' => 1));
        $db->query ("Replace Into {$tablepre}base_config Set `config`='$access' , `group`='access.1' ");
        //初始化认证设置
        $certInfo['localauth'] = true;
        $certInfo['platformauth'] = true;
        $certInfo['localauthtext'] = 'iWeibo认证资料';
        $certInfo = serialize ($certInfo);
        $db->query ("Replace Into {$tablepre}base_config Set `config`='$certInfo' , `group`='certification.info' ");
        //初始化菜单
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '我的主页', 'index', '/', '1', '0', '1', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '广播大厅', 'public', '/public', '1', '0', '2', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '名人墙', 'people', '/people', '1', '0', '3', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '话题墙', 'topic', '/topic', '1', '0', '4', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '微直播', 'tlive', '/tlive', '1', '0', '5', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '微访谈', 'tiview', '/tiview', '1', '0', '6', '0', '1')");
        //$db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '手机', 'wap', '/wap', '1', '1', '1', '1', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '上墙', 'wall', '/wall', '1', '1', '2', '0', '1')");
        $db->query ("INSERT INTO {$tablepre}base_nav VALUES (NULL, '0', '活动', 'event', '/event', '1', '1', '3', '0', '1')");
        //初始化管理组
        $db->query ("INSERT INTO {$tablepre}user_group VALUES (NULL, '0', '管理员')");
        $db->query ("INSERT INTO {$tablepre}user_group VALUES (NULL, '0', '普通会员')");
        $db->query ("INSERT INTO {$tablepre}user_group VALUES (NULL, '0', '屏蔽用户')");
        //初始化皮肤
        $db->query ("INSERT INTO {$tablepre}mb_skin VALUES (NULL, '', 'default', '" . $setting_basic['resource_path'] . "view/default/images/thumb.jpg', 0, 1)");
        $db->query ("INSERT INTO {$tablepre}mb_skin VALUES (NULL, '天空之蓝', 'style', '" . $setting_basic['resource_path'] . "view/style/images/thumb.jpg', 1, 1)");
        $db->query ("INSERT INTO {$tablepre}mb_skin VALUES (NULL, '春天之美', 'green', '" . $setting_basic['resource_path'] . "view/green/images/thumb.jpg', 2, 1)");
        //初始化其它数据
        $sql_init = file_get_contents ($sqlfile_init);
        $sql_init = str_replace ("\r\n", "\n", $sql_init);
        init_iweibo_data ($sql_init);



        VIEW_OFF && show_msg ('initdbresult_succ');
        if (!VIEW_OFF) {
            echo '<script type="text/javascript">document.getElementById("laststep").disabled=false;document.getElementById("laststep").value = \'' . lang ('install_succeed') . '\';</script>' . "\r\n";
            show_footer ();
        }
    }
    if (VIEW_OFF) {

        show_msg ('missing_parameter', '', 0);
    } else {

        show_form ($form_db_init_items, $error_msg);
    }
} elseif ($method == 'ext_info') {//note 配置附加信息
    //note 执行自动登录
    //auto_login();
    //LOCK防止重复安装
    @write_verson ($lockfile);
    //清除缓存
    @cleanCache();
    if (VIEW_OFF) {
        //note 接口用
        show_msg ('ext_info_succ');
    } else {
        show_header ();
        echo '<div class="desc">';
        echo '<a href="../">' . lang ('install_complete') . '</a><br>';
        //登录完跳转
        echo '<script>setTimeout(function(){window.location=\'../\'}, 2000);</script>' . lang ('redirect') . '';
        echo '</div><iframe src="../index.php" width="0" height="0" style="display: none;"></iframe>';
        show_footer ();
        echo '</div>';
    }
} elseif ($method == 'install_check') {//note 检测是否安装完成
    if (file_exists ($lockfile)) {
        show_msg ('installstate_succ');
    } else {
        show_msg ('lock_file_not_touch', $lockfile, 0);
    }
} elseif ($method == 'tablepre_check') {//note 检测表前缀
    $dbinfo = getgpc ('dbinfo');
    extract ($dbinfo);
    if (check_db ($dbhost, $dbuser, $dbpw, $dbname, $tablepre)) {
        show_msg ('tablepre_not_exists', 0);
    } else {
        show_msg ('tablepre_exists', $tablepre, 0);
    }
}