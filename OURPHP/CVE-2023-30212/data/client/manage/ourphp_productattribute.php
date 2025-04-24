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

	plugsclass::logs('创建产品属性',$OP_Adminname);
	$db -> insert("`ourphp_productattribute`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Text` = '',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`OP_Class` = '0',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_productattribute.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){
	
		plugsclass::logs('编辑产品属性',$OP_Adminname);
		$db -> update("`ourphp_productattribute`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Text` = '',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`OP_Class` = '0',`time` = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['id']));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_productattribute.php?id=ourphp';
		require 'ourphp_remind.php';

	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productattribute.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){		
	
		plugsclass::logs('删除产品属性',$OP_Adminname);
		$db -> del("ourphp_productattribute","where id = ".intval($_GET['id']));
		$db -> del("ourphp_productattribute","where OP_Class = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_productattribute.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_productattribute.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function Attribute(){
	global $db;
	$query = $db -> listgo("id,OP_Title,OP_Sorting","`ourphp_productattribute`","where `OP_Class` = 0 order by OP_Sorting asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"sorting" => $ourphp_rs[2]
		);
		$i = $i + 1;
	}
	return $rows;
}

$smarty->assign("Attribute",Attribute());
$smarty->display('ourphp_productattribute.html');
?>