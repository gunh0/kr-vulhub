<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';


if(strstr($OP_Adminpower,"36")): else: echo "无权限操作"; exit; endif;

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){	
	
		if ($_POST["num"] == '' || $_POST["num"] < 1){
			exit("<script language=javascript> alert('生成数量不能为空');history.go(-1);</script>");
		}
		
		$webrs = $db -> select("*","ourphp_web","where id = 1");
		$num = intval($_POST["num"]);
		include '../../function/api/sitemap/sitemapxml.php';
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_sitemap.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_api.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}			
}

$ourphp_rs = $db -> select("*","`ourphp_web`","where `id` = 1");
$smarty->assign('ourphp_web',$ourphp_rs);
$smarty->assign('ourphp_key',$ourphp['safecode']);
$smarty->display('ourphp_sitemap.html');
?>