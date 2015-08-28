<?php
/**
 * UCenter API接口
 *
 * @author Icehu
 */

define('UC_CLIENT_VERSION', '1.6');
define('UC_CLIENT_RELEASE', '20100501');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 0);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 0);
define('API_UPDATEHOSTS', 0);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_UPDATECREDITSETTINGS', 1);
define('API_ADDFEED', 1);

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '1');

Core_Fun::loadClass('Core_Outapi_Uc');
define('UC_CLIENT_ROOT', INCLUDE_PATH . 'uc_client/' );

error_reporting(0);
class Controller_Api_Uc extends Core_Controller_Action
{
	public function preDispatch() {
		if(!Core_Config::get('useuc', 'basic', false))
			exit();
	}
	
	public function indexAction()
	{
		//Uc API接口
		$code = $this->getParam('code', '');
		parse_str(authcode($code, 'DECODE', UC_KEY), $get);
		$post = uc_unserialize(file_get_contents('php://input'));
		
		if (time() - @$get['time'] > 3600)
		{
			exit('Authracation has expiried');
		}
		if (empty($get))
		{
			exit('Invalid Request');
		}
		
		/*
		$somecontent = $post;
		
		$filename = INCLUDE_PATH . "logs/log.txt";
		if (is_writable($filename)) {
		    if (!$handle = fopen($filename, 'a+')) {
		         echo "不能打开文件 $filename";
		         exit;
		    }
		    if (fwrite($handle, var_export($somecontent, true)) === FALSE) {
		        echo "不能写入到文件 $filename";
		        exit;
		    }
		    fclose($handle);
		} else {
		    echo "文件 $filename 不可写";
		}
		*/

		if (in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed')))
		{
			$charset = strtolower(UC_CHARSET);
			if($charset == 'gbk')
			{
				$get && $get = Core_Fun::iconv('gbk//ignore', 'utf-8//ignore', $get);
				$post && $post = Core_Fun::iconv('gbk//ignore', 'utf-8//ignore', $post);
			}
			$uc_note = new uc_note();
			echo $rt = @$uc_note->$get['action']($get,$post);
			exit();
		}
		else
		{
			exit(API_RETURN_FAILED);
		}
	}

}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{

	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; $i++)
	{
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++)
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++)
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ($operation == 'DECODE')
	{
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))
		{
			return substr($result, 26);
		}
		else
		{
			return '';
		}
	}
	else
	{
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

function dstripslashes($string)
{
	if (is_array($string))
	{
		foreach ($string as $key => $val)
		{
			$string[$key] = dstripslashes($val);
		}
	}
	else
	{
		$string = stripslashes($string);
	}
	return $string;
}

class uc_note
{

	function _serialize($arr, $htmlon = 0)
	{
		if (!function_exists('xml_serialize'))
		{
			include_once UC_CLIENT_ROOT . 'lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function test($get)
	{
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get)
	{
		//删除用户
		if (!API_DELETEUSER)
		{
			return API_RETURN_FORBIDDEN;
		}
		$uids = str_replace("'", '', stripcslashes($get['ids']));
		return API_RETURN_SUCCEED;
	}

	function renameuser($get)
	{
		//重命名用户
		if (!API_RENAMEUSER)
		{
			return API_RETURN_FORBIDDEN;
		}
		$uid = $get['uid'];
		$usernamenew = $get['newusername'];
		return API_RETURN_SUCCEED;
	}

	function gettag($get)
	{
		if (!API_GETTAG)
		{
			return API_RETURN_FORBIDDEN;
		}
		return $this->_serialize(array($get['id'], array()), 1);
	}

	function synlogin($get)
	{
		if (!API_SYNLOGIN)
		{
			return API_RETURN_FORBIDDEN;
		}
		
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		
		$uid = intval($get['uid']);
		$username = trim($get['username']);
		
		$userModel = new Model_User_Member();
		$userModel->onLogout();
		if($userInfo = $userModel->getUserInfoByUid($uid))
		{
			$userModel->onSetCurrentUser($userInfo['uid'], $userInfo['nickname']);
			if($userInfo['oauthtoken'] && $userInfo['oauthtokensecret'] && $userInfo['name'])
			{
				$userModel->onSetCurrentAccessToken($userInfo['oauthtoken'], $userInfo['oauthtokensecret'], $userInfo['name']);
			}
		}
		else 
		{
			list($uid, $username, $email) = Core_Outapi_Uc::call('get_user', $username);
			//本地用户不存在，则自动注册本地用户
			$userModel->onAutoRegister($uid, $username, $email);
			$userModel->onSetCurrentUser($uid, null);
		}
	}

	function synlogout($get)
	{
		if (!API_SYNLOGOUT)
		{
			return API_RETURN_FORBIDDEN;
		}
		
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		
		$userModel = new Model_User_Member();
		$userModel->onLogout();
	}
	
	function updatepw($get)
	{
		//修改密码 
		if (!API_UPDATEPW)
		{
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get)
	{
		if (!API_UPDATEBADWORDS)
		{
			return API_RETURN_FORBIDDEN;
		}
		//do nothing!
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post='')
	{
		if (!API_UPDATEHOSTS)
		{
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post='')
	{
		if (!API_UPDATEAPPS)
		{
			return API_RETURN_FORBIDDEN;
		}
		
		$UC_API = $post['UC_API'];
		if(empty($post) || empty($UC_API)) {
			return API_RETURN_SUCCEED;
		}
		
		$UC_API = '';
		if($post['UC_API']) {
			$UC_API = $post['UC_API'];
			unset($post['UC_API']);
		}
		
		$cachefile = INCLUDE_PATH . 'uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		
		/**
		 * todo 修改iWeibo uc配置
 		if($UC_API && is_writeable(S_ROOT.'./config.php')) {
			$configfile = trim(file_get_contents(S_ROOT.'./config.php'));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen(S_ROOT.'./config.php', 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}
		*/
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post='')
	{
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		
		$cachefile = INCLUDE_PATH . './uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get)
	{
		//积分兑换
		if (!API_UPDATECREDIT)
		{
			return API_RETURN_FORBIDDEN;
		}

		return API_RETURN_SUCCEED;
	}

	function getcredit($get)
	{
		//获取某用户的积分
		if (!API_GETCREDIT)
		{
			return API_RETURN_FORBIDDEN;
		}

	}

	function getcreditsettings($get)
	{
		//获取积分设置
		if (!API_GETCREDITSETTINGS)
		{
			return API_RETURN_FORBIDDEN;
		}
		$credits = array();
	}

	function updatecreditsettings($get)
	{
		//更新积分设置
		if (!API_UPDATECREDITSETTINGS)
		{
			return API_RETURN_FORBIDDEN;
		}
		$outextcredits = array();
		return API_RETURN_SUCCEED;
	}

	function addfeed($get, $post='')
	{
		//
		if (!API_ADDFEED)
		{
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}

}