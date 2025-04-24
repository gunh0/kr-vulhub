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
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

	$query = $db -> select("OP_Uid","`ourphp_column`","where OP_Uid = ".intval($_GET['id']));
	if ($query >= 1){
		
		$ourphp_font = 4;
		$ourphp_content = '请先删除它下面的子栏目!';
		$ourphp_class = 'ourphp_column.php';
		require 'ourphp_remind.php';
		
	}else{
	
		$ourphp_rs = $db -> select("`OP_Img`,`id`,`OP_Model`","`ourphp_column`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0]);
		}
		
		//如果栏目被删除，下面的数据被移动到回收站
		if($ourphp_rs[2] != 'weburl' or $ourphp_rs[2] != 'about'){
			if($ourphp_rs[2] == 'product')
			{
				$recovery = $db -> update("`ourphp_".$ourphp_rs[2]."`","`OP_Down` = 1","where OP_Class = ".intval($ourphp_rs[1]));
			}else{
				$recovery = $db -> update("`ourphp_".$ourphp_rs[2]."`","`OP_Callback` = 1","where OP_Class = ".intval($ourphp_rs[1]));
			}
		}
		
		$queryto = $db -> del("`ourphp_column`","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_column.php';
		require 'ourphp_remind.php';
	}
	
	}else{

		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_column.php';
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

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();
if(isset($_GET['lang'])){
	$node = columnlist("",admin_sql($_GET['lang']));
}else{
	$node = columnlist("");
}

if($node[0]["id"] == 0)
{
	$arr = array();
}else{
	$arr = array2tree($node,0);	
}

$smarty->assign('arr',$arr);
Admin_click('网站栏目列表','ourphp_column.php');
$smarty->assign("langlist",Langlist());
$smarty->display('ourphp_column.html');
?>