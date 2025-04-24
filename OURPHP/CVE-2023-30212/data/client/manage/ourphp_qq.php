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

	plugsclass::logs('创建客服',$OP_Adminname);
	$db -> insert("`ourphp_qq`","`OP_QQname` = '".admin_sql($_POST["OP_QQname"])."',`OP_QQnumber` = '".admin_sql($_POST["OP_QQnumber"])."',`OP_QQclass` = '".admin_sql($_POST["OP_QQclass"])."',`OP_QQsorting` = '".admin_sql($_POST["OP_QQsorting"])."',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_qq.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){		

		plugsclass::logs('删除客服',$OP_Adminname);
		$db -> del("ourphp_qq","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_qq.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_qq.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function QQlist(){
	global $db;
	$query = $db -> listgo("id,OP_QQname,OP_QQnumber,OP_QQclass,OP_QQsorting,time","`ourphp_qq`","order by OP_QQsorting asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"number" => $ourphp_rs[2],
			"class" => $ourphp_rs[3],
			"sorting" => $ourphp_rs[4],
			"time" => $ourphp_rs[5]
		);
	$i = $i + 1;
	}
	return $rows;
}

Admin_click('浮动客服管理','ourphp_qq.php?id=ourphp');
$smarty->assign("QQlist",QQlist());
$smarty->display('ourphp_qq.html');
?>