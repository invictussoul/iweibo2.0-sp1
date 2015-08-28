<?php
/**
 * iweibo2.0
 *
 * 开放平台鉴权类
 *
 * @author hordeliu
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Open_Opent.php 626 2011-05-23 115:42:00Z gionouyang $
 * @package Core
 * @since 2.0
 */
require_once (INCLUDE_PATH . '/Core/Open/Oauth.php');
class Core_Open_Opent
{
    public $host = 'open.t.qq.com';
    public $timeout = 30;
    public $connectTimeout = 30;
    public $sslVerifypeer = FALSE;
    public $format = 'json';
    public $decodeJson = TRUE;
    public $httpInfo;
    public $userAgent = '';
    public $decode_json = FALSE;

    function accessTokenURL()
    {
        return 'http://open.t.qq.com/cgi-bin/access_token';
    }

    function authenticateURL()
    {
        return 'http://open.t.qq.com/cgi-bin/authenticate';
    }

    function authorizeURL()
    {
        return 'http://open.t.qq.com/cgi-bin/authorize';
    }

    function requestTokenURL()
    {
        return 'http://open.t.qq.com/cgi-bin/request_token';
    }

    function lastStatusCode()
    {
        return $this->http_status;
    }

    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL)
    {
        $this->sha1_method = new Core_Open_OAuthSignatureMethodHmacSha1();
        $this->consumer = new Core_Open_OAuth($consumer_key, $consumer_secret);
        if(! empty($oauth_token) && ! empty($oauth_token_secret))
        {
            $this->token = new Core_Open_OAuth($oauth_token, $oauth_token_secret);
        }
        else
        {
            $this->token = NULL;
        }
    }

    /**
     * Get a request_token from Weibo
     * oauth授权之后的回调页面
     * 返回包含 oauth_token 和oauth_token_secret的key/value数组
     */
    function getRequestToken($oauth_callback = NULL)
    {
        $parameters = array();
        if(! empty($oauth_callback))
        {
            $parameters['oauth_callback'] = $oauth_callback;
        }

        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
        $token = Core_Open_OAuthUtil::parse_parameters($request);
        $this->token = new Core_Open_OAuth($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    /**
     * 获取授权url
     *
     * @return string
     */
    function getAuthorizeURL($token, $signInWithWeibo = TRUE, $url = '')
    {
        if(is_array($token))
        {
            $token = $token['oauth_token'];
        }
        if(empty($signInWithWeibo))
        {
            return $this->authorizeURL() . '?oauth_token=' . $token . '&mini=1';
        }
        else
        {
            return $this->authenticateURL() . '?oauth_token=' . $token ;
        }
    }

    /**
     * 交换授权
     * Exchange the request token and secret for an access token and
     * secret, to sign API calls.
     *
     * @return array array("oauth_token" => the access token,
     * "oauth_token_secret" => the access secret)
     */
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false)
    {
        $parameters = array();
        if(! empty($oauth_verifier))
        {
            $parameters['oauth_verifier'] = $oauth_verifier;
        }
        $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
        $token = Core_Open_OAuthUtil::parse_parameters($request);
        $this->token = new Core_Open_OAuth($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    function jsonDecode($response, $assoc = true)
    {
        $response = preg_replace('/[^\x20-\xff]*/', "", $response); //清除不可见字符
        $jsonArr = json_decode($response, $assoc);
        if(! is_array($jsonArr))
        {
			throw new Core_Api_Exception('操作失败', Core_Comm_Modret::RET_API_ARG_ERR);
        }
        $ret = $jsonArr["ret"];
        $msg = $jsonArr["msg"];
        /**
         *Ret=0 成功返回
         *Ret=1 参数错误//todu errcode不够精细
         *Ret=2 频率受限
         *Ret=3 鉴权失败
         *Ret=4 服务器内部错误
         */
        switch($ret)
        {
            case 0:
                return $jsonArr;
                break;
            case 1:
                    empty($msg) && $msg = Core_Comm_Apiret::getMsg(Core_Comm_Apiret::RET_ARG_ERR);//todu errcode不够精细
                    throw new Core_Api_Exception($msg, Core_Comm_Apiret::RET_ARG_ERR);
                break;
            case 2:
                    $msg = Core_Comm_Apiret::getMsg(Core_Comm_Apiret::RET_FREQ_LIMIT);
                    throw new Core_Api_Exception($msg, Core_Comm_Apiret::RET_FREQ_LIMIT);
                break;
            case 3:
            	$errcode = $jsonArr["errcode"];
            	$errcode = ($errcode >= 12 && $errcode <=15 || $errcode<0) ? 8 : $errcode;
                $errcode = 3000 + $errcode;
            	if($errcode == 3001 || $errcode == 3003)
            	{
            		//无效token或token被吊销
            		$userModel = new Model_User_Member();
            		$ui = array ('uid' => $cUser['uid']
								, 'oauthtoken' => ''
								, 'oauthtokensecret' => ''
								, 'name' => ''
								);
		        	$userModel->editUserInfo($ui);
		        	$userModel->onSetCurrentAccessToken(null, null, null);
		        	$backUrlPrefix = Core_Controller_Front::getInstance()->getModelName ()=='wap' ? 'wap/' : '';
		        	Core_Fun::showmsg('', $backUrlPrefix . 'login/r/msg/' . Core_Comm_Modret::RET_ACCOUNT_INVALID, 0, false);
            	}
                elseif(in_array($errcode, array(3002, 3004, 3005, 3006, 3007, 3008, 3009, 3010, 3011)))
                {
                    $msg = Core_Comm_Apiret::getMsg($errcode);
                    throw new Core_Api_Exception($msg, $errcode);
                }
                else
                {
                    $msg = Core_Comm_Apiret::getMsg(Core_Comm_Apiret::RET_AUTH_FAIL);
                    throw new Core_Api_Exception($msg, Core_Comm_Apiret::RET_AUTH_FAIL);
                }
                break;
            case 4:
            	$errcode = $jsonArr["errcode"];
                $errcode = (!in_array($errcode,array(0,4,5,6,8,9,10,11,12,13)) || $errcode<0) ? -1 : $errcode;//不在指定列表里的errorcode 为非预期错误 默认-1

            	if($errcode==0)
            	{
            		return $jsonArr;
            	}elseif($errcode<0){//非预期错误 默认-1
                    $msg = Core_Comm_Apiret::getMsg(Core_Comm_Apiret::RET_SERVER_ERROR);
                    throw new Core_Api_Exception($msg, Core_Comm_Apiret::RET_SERVER_ERROR);
                }else{
                    $errcode = 4000 + $errcode;
                    $msg = Core_Comm_Apiret::getMsg($errcode);
                    throw new Core_Api_Exception($msg, $errcode);
                }
                break;
            default:
                $msg = Core_Comm_Apiret::getMsg(Core_Comm_Apiret::RET_SERVER_ERROR);
                throw new Core_Api_Exception($msg, Core_Comm_Apiret::RET_SERVER_ERROR);
                break;
        }
    }

    /**
     * 重新封装的get请求.
     *
     * @return mixed
     */
    function get($url, $parameters)
    {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if($this->format === 'json')
        {
            return $this->jsonDecode($response, true);
        }
        return $response;
    }

    /**
     * 重新封装的post请求.
     *
     * @return mixed
     */
    function post($url, $parameters = array(), $multi = false)
    {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi);
        if($parameters['format'] === 'json')
        {
            return $this->jsonDecode($response, true);
        }
        return $response;
    }

    /**
     * DELTE wrapper for oAuthReqeust.
     *
     * @return mixed
     */
    function delete($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters);
        if($this->format === 'json')
        {
            return $this->jsonDecode($response, true);
        }
        return $response;
    }

    /**
     * 发送请求的具体类
     *
     * @return string
     */
    function oAuthRequest($url, $method, $parameters, $multi = false)
    {
        //var_dump($parameters);
        if(strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0)
        {
            $url = "http://{$this->host}/{$url}.{$this->format}";
        }
        $request = Core_Open_OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url,
        $parameters);
        $request->sign_request($this->sha1_method, $this->consumer, $this->token);

        switch($method)
        {
            case 'GET':
                return $this->http($request->to_url(), 'GET');
            default:
                return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi), $multi);
        }
    }

    function http($url, $method, $postfields = NULL, $multi = false)
    {
        $tmp = '<hr>' . $url . '<hr>' . $method . '<hr>' . $postfields . '<hr>';
        //判断是否是https请求
        if(strrpos($url, 'https://') === 0)
        {
            $port = 443;
            $version = '1.1';
            $host = 'ssl://' . $this->host;

        }
        else
        {
            $port = 80;
            $version = '1.0';
            $host = $this->host;
        }
        $header = "$method $url HTTP/$version\r\n";
        $header .= "Host: " . $this->host . "\r\n";
        if($multi)
        {
            $header .= "Content-Type: multipart/form-data; boundary=" . Core_Open_OAuthUtil::$boundary . "\r\n";
        }
        else
        {
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        }
        if(strtolower($method) == 'post')
        {
            $header .= "Content-Length: " . strlen($postfields) . "\r\n";
            $header .= "Connection: Close\r\n\r\n";
            $header .= $postfields;
        }
        else
        {
            $header .= "Connection: Close\r\n\r\n";
        }

        $ret = '';
        $fp = fsockopen($host, $port, $errno, $errstr, 10);

        if(! $fp)
        {
            $error = '建立sock连接失败';
            throw new Core_Api_Exception($error);
        }
        else
        {
            fwrite($fp, $header);
            while(! feof($fp))
            {
                $ret .= fgets($fp, 4096);
            }
            fclose($fp);
            //$response=split("\r\n\r\n",$ret);
            if(strrpos($ret, 'Transfer-Encoding: chunked'))
            {
                $info = explode("\r\n\r\n", $ret);
                $response = explode("\r\n", $info[1]);
                $t = array_slice($response, 1, - 1);

                $returnInfo = implode('', $t);
            }
            else
            {
                $response = explode("\r\n\r\n", $ret);
                $returnInfo = $response[1];
            }
            //echo '<hr>';
            //print_r($ret);
            //$tmp = print_r(iconv("utf-8","utf-8//ignore",$returnInfo), true);
            /********************跟踪调试**********************/
            /*
			date_default_timezone_set('Asia/Shanghai');
			echo "<pre>";
			echo date("Y-m-d H:i:s")."\r\n";
			echo "REQUEST URL:".$url."\r\n";
			echo "REQUEST METHOD:".$method."\r\n";
			echo "WITH POSTFIEDS:".$postfields."\r\n";
			echo "THE RESULT:".$returnInfo."\r\n";
			echo "\r\n";
			echo "</pre>";
			*/
            /*************************************************/
            return iconv("utf-8", "utf-8//ignore", $returnInfo);
        }

    }

    function getHeader($ch, $header)
    {
        $i = strpos($header, ':');
        if(! empty($i))
        {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }
}
?>
