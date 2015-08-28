<?php
/**
 * iweibo2.0
 * 
 * 验证类
 *
 * @author luckyxiang
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Comm_Validator.php 2011-06-09 15:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Core_Comm_Validator
{

    /**
     * 判断是否是通过手机访问
     * @return bool 是否是移动设备    
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
//        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
//        {
//            return true;
//        }
//        
//        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
//        if(isset($_SERVER['HTTP_VIA']))
//        {
//            //找不到为flase,否则为true
//            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
//        }
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        //判断手机发送的客户端标志
        if(isset($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 
            'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 
            'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'opera mobi', 'openwave', 'nexusone', 'cldc', 'midp', 
            'wap', 'mobile');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", $userAgent) && strpos($userAgent,'ipad') < 0)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 验证json回调函数名
     * @param $callback
     * @
     */
    public static function checkCallback($callback)
    {
        if(empty($callback))
        {
            return false;
        }
        if(preg_match("/^[a-zA-Z_][a-zA-Z0-9_\.]*$/", $callback))
        {
            return true;
        }
        return false;
    }

    /**
     * 验证话题id(64位无符号整数)
     * @param $tid
     * @
     */
    public static function isTopicId($tid)
    {
        if(preg_match('/^[1-9][0-9]{0,19}$/', $tid))
        {
            return true;
        }
        return false;
    }

    /**
     * 验证微博id(64位无符号整数)
     * @param $tid
     * @
     */
    public static function isTId($tid)
    {
        if($tid && preg_match('/^[1-9][0-9]{0,19}$/', $tid))
        {
            return true;
        }
        return false;
    }

    /**
     * 是否是微博帐号
     * @param $account
     * @
     */
    public static function isUserAccount($account)
    {
        if(preg_match('/^[A-Za-z][A-Za-z0-9_\-]{2,20}$/', $account))
        {
            return true;
        }
        return false;
    }

    /**
     * 是否是微博昵称
     * @param $nick
     * @
     */
    public static function isUserNick($nick)
    {
        $len = strlen($nick);
        if($len == 0 || $len > 36 || preg_match("/[^\x{4e00}-\x{9fa5}\w\-\&]/u", $nick) == 1)
        {
            return false;
        }
        return true;
    }

    /**
     * 是否是话题名称
     * @param $topic
     * @
     */
    public static function isTopicName($topic)
    {
        $len = strlen($topic);
        if($len <= 0 || $len > 280)
        {
            return false;
        }
        return true;
    }
    
	/**
	 * 检验email
	 *
	 *@access   public
	 *@param    str $str
	 *@return   bool
	 **/
	public function isEmail($str){
		return filter_var($str, FILTER_VALIDATE_EMAIL);
		//return preg_match ( "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $str );
	}
	
    /**
     * 检查搜索关键字的合法性
     * @
     */
    public static function checkSearchKey($key)
    {
        $len = strlen($key);
        if($len > 420)
        {
            return false;
        }
        return true;
    }

    /**
     * 检查微博广播的合法性
     * @param $t
     * @
     */
    public static function checkT($t)
    {
        $len = strlen($t);
        if($len <= 0 || $len > 420)
        {
            return false;
        }
        return true;
    }
    /**
     * 检查上传文件的合法性
     * @param $file
     * @author echoyang
     * return 未上传文件 false
     *        上传文件成功 ture
     *        上传文件失败 <0
     */
    public static function checkUploadFile(&$file)
    {
        if(empty($file) || empty($file['name']) )
        {
            return false;
        }
        if($file['error']!==0)//上传失败
         {
             switch ($file['error'])
            { 
                case UPLOAD_ERR_INI_SIZE: //文件超php大小
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_INI_SIZE;
                    break; 
                case UPLOAD_ERR_FORM_SIZE: //文件超form大小
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_FORM_SIZE;
                    break; 
                case UPLOAD_ERR_PARTIAL: //上传不完整
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_PARTIAL;
                    break; 
                case UPLOAD_ERR_NO_FILE: //没有上传
                    $code = false;
                    break; 
                case UPLOAD_ERR_NO_TMP_DIR: //服务器文件夹错误
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_NO_TMP_DIR;
                    break; 
                case UPLOAD_ERR_CANT_WRITE: //服务器写权限错误
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_CANT_WRITE;
                    break; 
                case UPLOAD_ERR_EXTENSION: //上传异常
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_EXTENSION;
                    break; 
                default: //上传异常
                    $code = Core_Comm_Modret::RET_T_UPLOAD_ERR_EXTENSION;
                    break; 
            }

        }else{
            $code = true;//上传成功
        }
        
         return $code;
    }
    /**
     * 检查上传文件的合法性
     * @param $file
     * @
     */
    public static function isUploadFile(&$file)
    {
        if(empty($file) || empty($file['tmp_name']))
        {
            return false;
        }
        if( is_uploaded_file($file['tmp_name']))
        {
            $fileSize = intval($file['size']);
            $tmpFile = $file['tmp_name'];
            
            if($fileSize < 0 || $file['error'] > 0 || empty($tmpFile))
            {
                return false;
            }
            return true;
        }
        
        return false;
    }

    /**
     * 获取数值参数
     * @param $num 输入数值
     * @param $min 最小值
     * @param $max 最大值
     * @param $default 默认值，为空表示必须有
     * @
     */
    public static function getNumArg($num, $min, $max, $default = NULL)
    {
        $n = intval($num);
        if(! isset($num) || ($n < $min || $n > $max))
        {
            if(isset($default))
            {
                return $default;
            }
            throw new Core_Exception();
        }
        
        return $n;
    }

    /**
     * 获取微博id参数
     * @param $tId
     * @
     */
    public static function getTidArg($tId, $default = NULL)
    {
        if(! isset($tId))
        {
            if(isset($default))
            {
                return $default;
            }
            else
            {
                throw new Core_Exception('微博tid为空');
            }
        }
        if(! Core_Comm_Validator::isTId($tId))
        {
            throw new Core_Exception('微博tid格式错误');
        }
        return $tId;
    }
}
?>