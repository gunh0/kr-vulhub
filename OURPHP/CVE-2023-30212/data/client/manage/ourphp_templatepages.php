<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php'; 

if (isset($_GET["ourphp_cms"]) == "edit"){

	$style = admin_sql($_POST["OP_Pages"]);
	$style = str_replace("<span>and</span>","and",$style);
	$style = str_replace("<span>or</span>","or",$style);
	
	plugsclass::logs('设置翻页样式',$OP_Adminname);
	$db -> update("`ourphp_webdeploy`","`OP_Pagestype` = '".intval($_POST["OP_Pagestype"])."',`OP_Pages` = '".$style."',`OP_Pagefont` = '".admin_sql($_POST["OP_Pagefont"])."'","where id = 1");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_templatepages.php';
	require 'ourphp_remind.php';

}

$ourphp_rs = $db -> select("OP_Pagestype,OP_Pages,OP_Pagefont","`ourphp_webdeploy`","where `id` = 1");
$rows = array(
	'OP_Pagestype' => $ourphp_rs[0],
	'OP_Pages' => $ourphp_rs[1],
	'OP_Pagefont' => $ourphp_rs[2], 
);

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
$smarty->assign('ourphp_web',$rows);
$smarty->assign("langlist",Langlist());
$smarty->display('ourphp_templatepages.html');
?>