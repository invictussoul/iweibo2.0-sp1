<?php
/**
 * iweibo2.0
 * 
 * 开放平台API
 *
 * @author echoyang 
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Open_Api.php 2011-06-09 10:16:00Z gionouyang $
 * @package Core
 * @since 2.0
 */
class Core_Open_Api
{
    private static $apiClient;
    private static $noneTokenClient;

    /**
     * 获取API调用的客户端
     * @return Core_Open_Client
     */
    public static function getClient()
    {
        if(isset(self::$apiClient))
        {
            return self::$apiClient;
        }
        
        $token['access_token'] = $_SESSION['access_token'];
        $token['access_token_secret'] = $_SESSION['access_token_secret'];
        $token['name'] = $_SESSION['name'];
        
        //必须要获取访问授权先
        if(empty($token))
        {
            Core_Fun::error('必须要获取访问授权', 101);
        }
        
        //获取安装时候的key
        $akey = Core_Config::get('appkey', 'basic');
        $skey = Core_Config::get('appsecret', 'basic');
        
        if(empty($akey) || empty($skey))
        {
            Core_Fun::error('key丢失', 102);
        }
        self::$apiClient = new Core_Open_Client($akey, $skey, $token['access_token'], $token['access_token_secret']);
        
        return self::$apiClient;
    }

    /**
     * 获取API调用的客户端，无需token
     * @return Core_Open_Client
     */
    public static function getNoneTokenClient()
    {
        if(isset(self::$noneTokenClient))
        {
            return self::$noneTokenClient;
        }
        //获取安装时候的key
        $akey = Core_Config::get('appkey', 'basic');
        $skey = Core_Config::get('appsecret', 'basic');
        
        if(empty($akey) || empty($skey))
        {
            Core_Fun::error('key丢失', 102);
        }
        self::$noneTokenClient = new Core_Open_Client($akey, $skey);

        return self::$noneTokenClient;
    }
}