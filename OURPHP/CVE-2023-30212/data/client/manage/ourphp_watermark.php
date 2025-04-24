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

	plugsclass::logs('修改图片水印设置',$OP_Adminname);
	$db -> update("`ourphp_watermark`","`OP_Watermarkimg` = '".admin_sql($_POST["OP_Watermarkimg"])."',`OP_Watermarkfont` = '".admin_sql($_POST["OP_Watermarkfont"])."',`OP_Watermarkcolor` = '".admin_sql($_POST["OP_Watermarkcolor"])."',`OP_Watermarksize` = '".admin_sql($_POST["OP_Watermarksize"])."',`OP_Watermarkposition` ='".admin_sql($_POST["OP_Watermarkposition"])."',`OP_Watermarkoff` ='".admin_sql($_POST["OP_Watermarkoff"])."',`OP_Imgcompress` ='".admin_sql($_POST["OP_Imgcompress"])."'","where id = 1");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_watermark.php?id=ourphp';
	require 'ourphp_remind.php';
	
}

Admin_click('图像水印及压缩','ourphp_watermark.php?id=ourphp');
$ourphp_rs = $db -> select("*","`ourphp_watermark`","where `id` = 1");
$smarty->assign('ourphp_watermark',$ourphp_rs);
$smarty->display('ourphp_watermark.html');
?>