<?php
/**
 * 验证用户相关数据
 *
 * @author lvfeng
 */
class Model_User_Validator
{
    /**
     * 验证字符串长度
     *
     * @param string $str
     * @param int $minlen
     * @param int $maxlen
     * @return boolean
     */
    public static function checkStrLength($str, $minlen, $maxlen)
    {
        $len = strlen($str);
        if($len < $minlen || $len > $maxlen)
            return 0;
        return 1;
    }
    
    /**
     * 验证字符串（含中文）长度
     *
     * @param string $str
     * @param int $minlen
     * @param int $maxlen
     * @return boolean
     */
    public static function checkMbStrLength($str, $minlen, $maxlen)
    {
        mb_internal_encoding('UTF-8');
        $len = mb_strlen($str);
        if($len < $minlen || $len > $maxlen)
            return 0;
        return 1;
    }

    /**
     * 验证数值大小
     *
     * @param int $num
     * @param int $min
     * @param int $max
     * @return boolean
     */
    public static function checkNumRange($num, $min, $max)
    {
        if($num < $min || $num > $max)
            return 0;
        return 1;
    }

    /**
     * 验证用户名
     *
     * @param string $nickname
     * @return boolean
     */
    public static function checkUsername($username)
    {
        if(! self::checkStrLength($username, 3, 15))
            return 0;
        if(preg_match('/[^\w]+/', $username))
            return 0;
        return 1;
    }
    
    /**
     * 验证密码
     *
     * @param string $pwd
     * @param string $pwdconfirm
     * @return boolean
     */
    public static function checkPassword($password)
    {
        if(! self::checkStrLength($password, 3, 15))
            return 0;
        return 1;
    }
	
    /**
     * 验证昵称
     *
     * @param string $nickname
     * @return boolean
     */
    public static function checkNickname($nickname)
    {
        $len = strlen($nickname);
        if($len == 0 || $len > 36 || preg_match("/[^\x{4e00}-\x{9fa5}\w\-\&]/u",$nickname) == 1)
            return false;
        return true;
    }

    /**
     * 验证邮箱
     *
     * @param string $email
     * @return boolean
     */
    public static function checkEmail($email)
    {
    	if (strlen($email) <= 75 && preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$email))
            return true;
        return false;
    }

    /**
     * 验证主页
     *
     * @param string $homepage
     * @return boolean
     */
    public static function checkHomepage($homepage)
    {
		if (empty($homepage))
            return true;
        if (preg_match("/^((news|telnet|nttp|file|http|ftp|https):\/\/)(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*$/", $homepage))
            return true;
        return false;
    }

    /**
     * 验证家乡国家
     *
     * @param sting $nation
     * @return boolean
     */
    public static function checkHomeNation($nation)
    {
        if(! self::checkStrLength($nation, 0, 6))
            return 0;
        if(! empty($nation) && ! in_array($nation, array_keys(Core_Comm_Util::getNationList())))
            return 0;
        return 1;
    }

    /**
     * 验证家乡省份
     *
     * @param string $nation
     * @param string $province
     * @return boolean
     */
    public static function checkHomeProvince($nation, $province)
    {
        if(! self::checkStrLength($province, 0, 6))
            return 0;
        if(! empty($province) && ! in_array($province, array_keys(Core_Comm_Util::getProvinceList($nation))))
            return 0;
        return 1;
    }

    /**
     * 验证家乡城市
     *
     * @param string $nation
     * @param string $province
     * @param string $city
     * @return boolean
     */
    public static function checkHomeCity($nation, $province, $city)
    {
        if(! self::checkStrLength($city, 0, 6))
            return 0;
        if(! empty($city) && ! in_array($city, array_keys(Core_Comm_Util::getCityList($nation, $province))))
            return 0;
        return 1;
    }

    /**
     * 验证国家
     *
     * @param string $nation
     * @return boolean
     */
    public static function checkNation($nation)
    {
        if(! self::checkStrLength($nation, 1, 6))
            return 0;
        if(! empty($nation) && ! in_array($nation, array_keys(Core_Comm_Util::getNationList())))
            return 0;
        return 1;
    }

    /**
     * 验证省份
     *
     * @param string $nation
     * @param string $province
     * @return boolean
     */
    public static function checkProvince($nation, $province)
    {
        if(! self::checkStrLength($province, 0, 6))
            return 0;
        if(! empty($province) && ! in_array($province, array_keys(Core_Comm_Util::getProvinceList($nation))))
            return 0;
        return 1;
    }

    /**
     * 验证城市
     *
     * @param string $nation
     * @param string $province
     * @param string $city
     * @return boolean
     */
    public static function checkCity($nation, $province, $city)
    {
        if(! self::checkStrLength($city, 0, 6))
            return 0;
        if(! empty($city) && ! in_array($city, array_keys(Core_Comm_Util::getCityList($nation, $province))))
            return 0;
        return 1;
    }
}
