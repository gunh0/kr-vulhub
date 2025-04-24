<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * OURPHP 唐晓伟 20201027
 */
function smarty_modifier_newdate_format($string)
{
	
    include WEBROOT . 'function/ourphp_function.class.php';
	return newtime($string);
    
}
