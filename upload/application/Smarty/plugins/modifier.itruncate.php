<?php
/**
 * Smarty itruncate modifier plugin
 * Smarty 中文截字修正
 *
 * @author   Icehu
 * @param string
 * @param integer
 * @param string
 * @return string
 */
function smarty_modifier_itruncate($string, $length = 80, $etc = '')
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        $string = Core_Fun::cn_substr($string, $length, $etc);
		return $string;
    } else {
        return $string;
    }
}