<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(isset($_GET["our"]) == ""){
	
	echo '';
	
}else{

	if($id != @intval($_GET['id'])){
		exit;
	}
	
	if ($_POST['pass'] != ''){
		$query = $db -> update("`ourphp_admin`","`OP_Adminpass` = '".admin_sql(substr(md5(md5($_POST['pass'])),0,16))."'","where id = ".intval($_GET['id']));
		echo "<script language=javascript> alert('".$ourphp_adminfont['upok']."');history.go(-1);</script>";
	}else{
		$query = $db -> update("`ourphp_admin`","`OP_Adminpass` = '".admin_sql($_POST['passto'])."'","where id = ".intval($_GET['id']));
		echo "<script language=javascript> alert('".$ourphp_adminfont['upok']."');history.go(-1);</script>";
	}
	
}
$smarty->assign('OP_Adminname',$OP_Adminname);
$smarty->assign('OP_Adminpower',$OP_Adminpower);
$smarty->display('ourphp_assistuse.html');
?>
