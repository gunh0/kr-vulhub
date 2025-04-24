<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';
include '../../function/ourphp_navigation.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){

	if($_POST["OP_Class"] == 1){
		$OP_Value = $_POST["OP_Value"];
	}else{
		$OP_Value = str_replace("/function/","function/",$_POST["OP_Value2"]);
	}
	
		plugsclass::logs('编辑产品规格',$OP_Adminname);
		$db -> update("`ourphp_productspecifications`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Titleto` = '".admin_sql($_POST["OP_Titleto"])."',`OP_Value` = '".admin_sql($OP_Value)."',`OP_Class` = '".admin_sql($_POST["OP_Class"])."',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['id']));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';

	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
			
}

$ourphp_rs = $db -> select("*","`ourphp_productspecifications`","where `id` = ".intval($_GET['id']));
$img = explode("|",$ourphp_rs['OP_Value']);
$smarty->assign('imglist',$img);
$smarty->assign('ourphp_productspecifications',$ourphp_rs);
$smarty->display('ourphp_productspecifications_o.html');
?>