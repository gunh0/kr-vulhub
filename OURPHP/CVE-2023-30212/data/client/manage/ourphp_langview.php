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
}elseif($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){
	
		$query = $db -> update("`ourphp_lang`","`OP_Lang` = '".admin_sql($_POST["OP_Lang"])."',`OP_Font` = '".admin_sql($_POST["OP_Font"])."',`OP_Note` = '".admin_sql($_POST["OP_Note"])."',`OP_Langtitle` = '".admin_sql($_POST["OP_Langtitle"])."',`OP_Langkeywords` = '".admin_sql($_POST["OP_Langkeywords"])."',`OP_Langdescription` = '".admin_sql($_POST["OP_Langdescription"])."',`OP_Webname` = '".admin_sql($_POST["OP_Webname"])."',`OP_Webadd` = '".admin_sql($_POST["OP_Webadd"])."',`OP_Weblinkman` = '".admin_sql($_POST["OP_Weblinkman"])."'","where id = ".$_GET["id"]." order by id asc");
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_lang.php';
		require 'ourphp_remind.php';
	
	}else{
			
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_lang.php';
		require 'ourphp_remind.php';
		
	}

}

$ourphp_rs = $db -> select("*","`ourphp_lang`","where `id` = ".intval($_GET["id"]));
$smarty->assign('ourphp_lang',$ourphp_rs);
$smarty->display('ourphp_langview.html');
?>