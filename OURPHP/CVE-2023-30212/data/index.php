<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('错误！您的PHP版本不能低于 5.3.0 !');
if (!file_exists("./function/install/ourphp.lock")) {
	header("location: ./function/install/index.php");
	exit;
}

include './config/ourphp_code.php';
include './config/ourphp_config.php';
include './config/ourphp_version.php';
include './config/ourphp_Language.php';
include './function/ourphp_function.class.php';
include './function/ourphp/Smarty.class.php';
include './function/ourphp_system.class.php';
include './function/ourphp_template.class.php';
?>