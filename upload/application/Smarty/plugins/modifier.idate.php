<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');

function smarty_modifier_idate($string, $format = 'Y-m-d H:i:s', $default_date = '')
{
	if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
	return Core_Fun::date($format, $timestamp);
}