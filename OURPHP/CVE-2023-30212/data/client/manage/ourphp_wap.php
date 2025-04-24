<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php'; 

if (isset($_GET["ourphp_cms"]) == "edit"){
	
	if(substr($_POST["OP_Weblogo"],0,4) == 'http')
	{
		$ourphp_xiegang = $_POST["OP_Weblogo"];
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Weblogo"]));
	}
	
	plugsclass::logs('编辑手机端网站基本信息',$OP_Adminname);
	$db -> update("`ourphp_wap`","`OP_Website` = '".admin_sql($_POST["OP_Website"])."',`OP_Weblogo` = '".admin_sql($ourphp_xiegang)."',`OP_Webkeywords` = '".admin_sql($_POST["OP_Webkeywords"])."',`OP_Webdescriptions` = '".admin_sql($_POST["OP_Webdescriptions"])."',`OP_Weburl` = '".admin_sql($_POST["OP_Weburl"])."'","where id = 1");
	
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_wap.php';
	require 'ourphp_remind.php';

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

Admin_click('手机网站设置','ourphp_wap.php');
$ourphp_rs = $db -> select("*","`ourphp_wap`","where `id` = 1");
$smarty->assign("langlist",Langlist());
$smarty->assign('ourphp_wap',$ourphp_rs);
$smarty->display('ourphp_wap.html');
?>