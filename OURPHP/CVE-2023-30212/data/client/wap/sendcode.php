<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2022 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/

include '../../config/ourphp_code.php';
include '../../config/ourphp_config.php';
include '../../config/ourphp_version.php';
include '../../config/ourphp_Language.php';
include '../../function/ourphp_function.class.php';

$number = $_GET['zh'];
$ourphp_usercontrol = $db -> select("a.`OP_Regtyle` as type,a.`OP_Regcode` as code,b.`OP_Weburl` as weburl","`ourphp_usercontrol` a,`ourphp_web` b","where a.`id` = 1 and b.`id` = 1"); 

if(strpos($_SERVER['HTTP_REFERER'],$ourphp_usercontrol['weburl']) === false || empty($number)){
	$ourphp_ajax_msg = array(
			"error" => 0,
			"msg" => $ourphp_adminfont['accessno'],
	);
	echo json_encode($ourphp_ajax_msg);
	exit;
}

include '../../function/ourphp_userreg.class.php';

if(isset($_GET['reg']))
{
	$msg = $reg -> reg_select(dowith_sql($number));
	echo json_encode($msg);
	exit;
}

if(isset($_GET['code'])){
	$error = $reg -> reg_select(dowith_sql($number));
	if($error['error'] == 0)
	{

		echo json_encode($error);

	}else{

		$msg = $reg -> reg_vcode(dowith_sql($number),"../../");
		$ourphp_ajax_msg = array("error" => $error['error'],"msg" => $msg);
		echo json_encode($ourphp_ajax_msg);
	}
	exit;
}

?>