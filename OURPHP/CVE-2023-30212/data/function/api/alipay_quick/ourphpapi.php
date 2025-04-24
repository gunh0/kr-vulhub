<?php
include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_Language.php';
include '../../ourphp_function.class.php';

date_default_timezone_set('Asia/Shanghai');
session_start();

if(empty($_SESSION['username'])){
	
	exit($ourphp_adminfont['power']);
	
}else{
	
	$rs = $db -> select("id,OP_Useremail","`ourphp_user`","WHERE `OP_Useremail` = '".admin_sql($_SESSION['username'])."'");	
	if (!$rs){
		exit($ourphp_adminfont['power']);
	}else{
		$id = $rs[0];
		$username = $rs[1];
	}
	
}
//安全码
$safecode = array(
	'id' => $id,
	'username' => $username,
	'code' => $ourphp['safecode'],
	'md5code' => MD5($id.$ourphp['safecode']),
);


//通用类
$ourphp_rs = $db -> select("*","`ourphp_web`","where `id` = 1");
$web = array(
	'website' => $ourphp_rs["OP_Website"],
	'weburl' => $ourphp_rs["OP_Weburl"],
	'weblogo' => $ourphp['webpath'].$ourphp_rs["OP_Weblogo"],
	'webname' => $ourphp_rs["OP_Webname"],
	'webadd' => $ourphp_rs["OP_Webadd"],
	'webtel' => $ourphp_rs["OP_Webtel"],
	'webmobi' => $ourphp_rs["OP_Webmobi"],
	'webfax' => $ourphp_rs["OP_Webfax"],
	'webemail' => $ourphp_rs["OP_Webemail"],
	'webzip' => $ourphp_rs["OP_Webzip"],
	'webqq' => $ourphp_rs["OP_Webqq"],
	'weblinkman' => $ourphp_rs["OP_Weblinkman"],
	'webicp' => $ourphp_rs["OP_Webicp"],
	'webstatistics' => $ourphp_rs["OP_Webstatistics"],
	'webtime' => $ourphp_rs["OP_Webtime"],
	'webhttp' => $ourphp_rs["OP_Webhttp"],
);

/*
 * 调用插件类，并判断此插件是否开启~ 
 * 取值：$api[0] 表示API名称，$api[1] 表示开关，$api[2~N] API值
 */
$api = plugsclass::plugs(3);
if (!$api){
	exit($api[0].$ourphp_adminfont['plugsno']);
}
?>