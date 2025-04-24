<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
$imgdel = $_GET["url"];
$ourphp_rs = $db -> select("`OP_Webfile`","`ourphp_webdeploy`","where id = 1");
if($ourphp_rs[0] == 2){
		
	if(strpos($imgdel,"function/uploadfile") > 0){
		
		if(strpos($imgdel,"..") > 0){
			echo '2';
			exit;
		}
		
		if(file_exists($imgdel)){
			
			$result = unlink($imgdel);
			echo '1';
			
		}
		
	}else{
		
		echo '2';
		
	}
	
}else{
	
	echo '2';
	
}
?>
</body>
</html>