<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

	
		$ourphp_rs = $db -> select("`OP_Img`","`ourphp_column`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0]);
		}
		
		$queryto = $db -> del("`ourphp_column`","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_column.php';
		require 'ourphp_remind.php';

	
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


function columnlist() { 
    global $db;
	$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Templist,OP_Tempview,OP_Hide,OP_Sorting,OP_Weight,OP_Url","`ourphp_column`","where (`id` = `OP_Uid` || `OP_Uid` > `id`) or `OP_Adminopen` not like '%00,01' order by OP_Sorting asc,id asc"); 
	$arr = array();
	$i = 0;
	while($ourphp_rs = $db -> whilego($query)){
		$arr[] = array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"uid" => $ourphp_rs[1],
			"lang" => $ourphp_rs[2],
			"title" => $ourphp_rs[3],
			"titleto" => $ourphp_rs[4],
			"model" => $ourphp_rs[5],
			"templist" => $ourphp_rs[6],
			"tempview" => $ourphp_rs[7],
			"hide" => $ourphp_rs[8],
			"sorting" => $ourphp_rs[9],
			"weight" => $ourphp_rs[10],
			"weburl" => $ourphp_rs[11],
			); 
		$i += 1;
	}
    return $arr;
}

$smarty->assign('arr',columnlist());
$smarty->assign("langlist",Langlist());
$smarty->display('ourphp_columnback.html');
?>