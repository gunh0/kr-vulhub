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
include '../../function/ourphp_Tree.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';

}elseif ($_GET["ourphp_cms"] == "edit"){
	
	if (strstr($OP_Adminpower,"34")){
		
		plugsclass::logs('编辑建站备忘录',$OP_Adminname);
		$db -> update("ourphp_webdeploy","`OP_Adminrecord` = '".admin_sql($_POST["OP_Adminrecord"])."'","where id = 1");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_record.php?id=ourphp';
		require 'ourphp_remind.php';
			
	}else{
			
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_record.php?id=ourphp';
		require 'ourphp_remind.php';
			
	}
}

$ourphp_rs = $db -> select("OP_Adminrecord","`ourphp_webdeploy`","where id = 1");
Admin_click('建站备忘录','ourphp_record.php?id=ourphp');
$smarty->assign('content',$ourphp_rs[0]);
$smarty->display('ourphp_record.html');
?>