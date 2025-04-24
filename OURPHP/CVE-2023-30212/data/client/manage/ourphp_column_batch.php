<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include '../../function/ourphp_navigation.class.php';
include '../../function/ourphp_Tree.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "batch"){

	if (strstr($OP_Adminpower,"34")){
	
		for($i = 0; $i < count($_POST["op_b"]); $i ++){
			$query = $db -> update("ourphp_column","`OP_Columntitle` = '".admin_sql($_POST["title"][$i])."',`OP_Columntitleto` = '".admin_sql($_POST["titleto"][$i])."',`OP_Weight` = '".admin_sql($_POST["weight"][$i])."',`OP_Hide` = '".admin_sql($_POST["hide"][$i])."',`OP_Sorting` = '".admin_sql($_POST["sorting"][$i])."'","where `id` = ".intval($_POST["op_b"][$i]));
		}

		$ourphp_font = 1;
		$ourphp_class = 'ourphp_column.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_column.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function Langlist(){
	global $db;
	$query = $db -> listgo("*","`ourphp_lang`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs['id'],
			"lang" => $ourphp_rs['OP_Lang'],
			"font" => $ourphp_rs['OP_Font'],
			"default" => $ourphp_rs['OP_Default'],
			"note" => $ourphp_rs['OP_Note'],
			"langtitle" => $ourphp_rs['OP_Langtitle'],
			"keywords" => $ourphp_rs['OP_Langkeywords'],
			"description" => $ourphp_rs['OP_Langdescription']
		);
	}
	return $rows;
}

$op= new Tree(columnlist(""));
$arr=$op->leaf();
$smarty->assign('arr',$arr);
$smarty->assign("langlist",Langlist());
$smarty->display('ourphp_column_batch.html');
?>