<?php
/**
 * iweibo2.0
 * 
 * 微博广播工具类
 *
 * @author feixiang
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Lib_Tutil.php 2011-06-07 12:06:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Core_Lib_Tutil
{
    private static $replacePattern = "/(#\s*[^#\s]{1}[^#]{0,59}?\s*#)|(@[A-Za-z][A-Za-z0-9_-]{0,19}(?![A-Za-z0-9_-]))|(http:\/\/url\.cn\/[A-Za-z0-9]{6}\b)/";
    private static $userMap = array();

    public static function setUserMap($usermap)
    {
        if(! $usermap)
        { //双重保险,防止报错
            $usermap = array();
        }
        self::$userMap = $usermap;
    }

    /**
     * 格式化微博广播
     * @param $content
     * @return unknown_type
     */
    public static function tContentFormat($content)
    {
        $content = htmlspecialchars($content);
        $content = preg_replace_callback(self::$replacePattern, array("Core_Lib_Tutil", "replaceCallback"), $content);
        $content = Core_Comm_Emotion::replace($content);
        return $content;
    }

    /**
     * 微博内容替换回调函数
     * @param $matches
     * @return unknown_type
     */
    private static function replaceCallback($matches)
    {
        $front = Core_Controller_Front::getInstance();
        $pathinfo = $front->getPathinfo();
        
        $prefix = strpos($pathinfo, '/wap/') > - 1 ? '/wap' : '';
        $match = $matches[0];
        if($match[0] == '@') //@xxx
        {
            $account = substr($match, 1);
            return '<em rel="' . $match . '"><a href="' . $prefix . '/u/' . $account . '" title="' . $match . '">' .
             $match . '</a></em>';
        }
        elseif($match[0] == '#') //#这个话题#
        {
            
            //话题
            $topic = trim(substr($match, 1, - 1));
            $topic = trim(Core_Comm_Util::trim($topic, "　"));
            
            if(strlen($topic) == 0 || $topic == "." || mb_strlen($topic, 'UTF-8') > 20)
            {
                return $match;
            }
            $key = self::keywordEncode($topic);
            return '<a href="' . $prefix . '/topic/show/k/' . $key . '">#' . htmlspecialchars($topic) . '#</a>';
        }
        elseif(substr($match, 0, 4) == "http") //短URL
        {
            return "<a href=\"$match\" target=\"_blank\">$match</a>";
        }
        
        return $match;
    }

    /**
     * timestamp转换成显示时间格式
     * @param $timestamp
     * @return unknown_type
     */
    public static function tTimeFormat($timestamp)
    {
        $curTime = time();
        $space = $curTime - $timestamp;
        //1分钟
        if($space < 60)
        {
            $string = "刚刚";
            return $string;
        }
        elseif($space < 3600) //一小时前
        {
            $string = floor($space / 60) . "分钟前";
            return $string;
        }
        $curtimeArray = getdate($curTime);
        $timeArray = getDate($timestamp);
        if($curtimeArray['year'] == $timeArray['year'])
        {
            if($curtimeArray['yday'] == $timeArray['yday'])
            {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "今天 {$string}";
            }
            elseif(($curtimeArray['yday'] - 1) == $timeArray['yday'])
            {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "昨天 {$string}";
            }
            else
            {
                $string = sprintf("%d月%d日 %02d:%02d", $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], 
                $timeArray['minutes']);
                return $string;
            }
        }
        $string = sprintf("%d年%d月%d日 %02d:%02d", $timeArray['year'], $timeArray['mon'], $timeArray['mday'], 
        $timeArray['hours'], $timeArray['minutes']);
        return $string;
    }

    /**
     * 关键字编码
     * @param $key
     * @return string
     */
    public static function keywordEncode($key)
    {
        return Core_Fun::iurlencode($key);
    }
}