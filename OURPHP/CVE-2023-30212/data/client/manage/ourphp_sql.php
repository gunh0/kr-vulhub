<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php'; 

if(strstr($OP_Adminpower,"32")): else: echo "无权限操作"; exit; endif;

if (isset($_GET["ourphp_cms"]) == "add"){

	if($_POST["kl"] == ''){
	
		$ourphp_font = 4;
		$ourphp_content = '口令码不能为空!';
		$ourphp_class = 'ourphp_sql.php?id=ourphp';
		require 'ourphp_remind.php';
							
	}elseif($_POST["kl"] != $ourphp['validation']){
	
		$ourphp_font = 4;
		$ourphp_content = '口令码错误!';
		$ourphp_class = 'ourphp_sql.php?id=ourphp';
		require 'ourphp_remind.php';

	}elseif($_POST["sql"] == ''){
	
		$ourphp_font = 4;
		$ourphp_content = 'SQL语句不能为空!';
		$ourphp_class = 'ourphp_sql.php?id=ourphp';
		require 'ourphp_remind.php';

	}
	
	$query = '';
	$sql = stripslashes($_POST['sql']);
	$sql = explode(';',$sql);
	foreach($sql as $op){
		$db -> create($op,2);	
	}
	
	plugsclass::logs('执行SQL语句',$OP_Adminname);
	
	$ourphp_font = 5;
	$ourphp_img = 'ok.png';
	$ourphp_content = '操作成功!';
	$ourphp_class = 'ourphp_sql.php?id=ourphp';
	require 'ourphp_remind.php';

}

$smarty->display('ourphp_sql.html');
?>