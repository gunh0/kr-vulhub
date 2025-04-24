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

	plugsclass::logs('设置商城功能',$OP_Adminname);
	$OP_Sendout = implode("^^^",$_POST['OP_Sendout']);
	
	$db -> update("`ourphp_productset`","`OP_Pattern` = '".intval($_POST["OP_Pattern"])."',`OP_Scheme` = '".intval(0)."',`OP_Stock` = '".intval($_POST["OP_Stock"])."',`OP_buy` = '".intval($_POST["OP_buy"])."',`OP_Sendout` = '".admin_sql($OP_Sendout)."',`time` = '".date("Y-m-d H:i:s")."',`OP_Delivery` = '".admin_sql($_POST["OP_Delivery"])."',`OP_Userbuysms` = ".intval($_POST["OP_Userbuysms"])."","where id = 1");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_productset.php?id=ourphp';
	require 'ourphp_remind.php';
			
}

$ourphp_rs = $db -> select("*","`ourphp_productset`","where `id` = 1");
@$sendout = explode("^^^",$ourphp_rs['OP_Sendout']);
$smarty->assign('ourphp_set',$ourphp_rs);
$smarty->assign('sendout',$sendout);
$smarty->display('ourphp_productset.html');
?>