<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*
*
*
* 插件管理引用文件 (20221202)
*******************************************************************************/
session_start();
date_default_timezone_set('Asia/Shanghai');

include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_version.php';
include '../../../config/ourphp_Language.php';
include '../../../function/ourphp_function.class.php';

if(isset($_SESSION['ourphp_outtime'])) {

	if($_SESSION['ourphp_outtime'] < time()) {
		unset($_SESSION['ourphp_outtime']);
		echo '登录超时或未登录，请重新登录！';
		exit(0);
	} else {
		$_SESSION['ourphp_outtime'] = time() + 3600;
	}
	
}else{
	echo '登录超时或未登录，请重新登录！';
	exit(0);
}


$plusfile = array(
	"css" => '<LINK href="../../../function/plugs/YIQI-UI/YIQI-UI.min.css" rel="stylesheet">',
	"jq1.7.2" => '<script language="JavaScript" type="text/javascript" src="../../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>',
	"jq1.8.3" => '<script language="JavaScript" type="text/javascript" src="../../../function/plugs/jquery/1.8.3/jquery-1.8.3.min.js"></script>',
	"jq2.1.1" => '<script language="JavaScript" type="text/javascript" src="../../../function/plugs/jquery/2.1.1/jquery-2.1.1.min.js"></script>',
);
?>