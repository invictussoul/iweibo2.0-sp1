<?php
/**
 * Smarty iurlencode modifier plugin
 * Smarty Url编码修正
 *
 * @author   Icehu
 */
function smarty_modifier_iurlencode($string)
{
    return Core_Fun::iurlencode($string);
}
