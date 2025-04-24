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
}elseif ($_GET["ourphp_cms"] == "add"){

	if($_POST["OP_Class"] == 1){
		$OP_Value = $_POST["OP_Value"];
	}else{
		$OP_Value = str_replace("/function/","function/",$_POST["OP_Value2"]);
	}
	
	plugsclass::logs('创建产品规格',$OP_Adminname);
	$db -> insert("`ourphp_productspecifications`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Titleto` = '".admin_sql($_POST["OP_Titleto"])."',`OP_Value` = '".admin_sql($OP_Value)."',`OP_Class` = '".admin_sql($_POST["OP_Class"])."',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){

		plugsclass::logs('编辑产品规格',$OP_Adminname);
		$db -> update("`ourphp_productspecifications`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Titleto` = '".admin_sql($_POST["OP_Titleto"])."',`OP_Class` = '".admin_sql($_POST["OP_Class"])."',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['id']));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';

	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){		

		plugsclass::logs('删除产品规格',$OP_Adminname);
		$db -> del("ourphp_productspecifications","where id = ".intval($_GET['id']));
		$db -> del("ourphp_productspecifications","where OP_Oid = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_productspecifications.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function Specifications(){
	global $db;
	$query = $db -> listgo("id,OP_Title,OP_Titleto,OP_Sorting,OP_Class","`ourphp_productspecifications`","order by OP_Sorting asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"nameto" => $ourphp_rs[2],
			"sorting" => $ourphp_rs[3],
			"class" => $ourphp_rs[4]
		);
	$i = $i + 1;
	}
	return $rows;
}

$smarty->assign("Specifications",Specifications());
$smarty->display('ourphp_productspecifications.html');
?>