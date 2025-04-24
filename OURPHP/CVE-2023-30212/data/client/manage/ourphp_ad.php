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

	if (strstr($OP_Adminpower,"34")){
	
		$adid = $_GET['id'];
		if($adid == 5){
			
			if(substr($_POST["OP_Adpiaofui"],0,4) == 'http')
			{
				$OP_Adpiaofui = admin_sql($_POST["OP_Adpiaofui"]);
			}else{
				$OP_Adpiaofui = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Adpiaofui"]));
			}
			
			if(substr($_POST["OP_Adduilianli"],0,4) == 'http')
			{
				$OP_Adduilianli = admin_sql($_POST["OP_Adduilianli"]);
			}else{
				$OP_Adduilianli = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Adduilianli"]));
			}
			
			if(substr($_POST["OP_Adduilianri"],0,4) == 'http')
			{
				$OP_Adduilianri = admin_sql($_POST["OP_Adduilianri"]);
			}else{
				$OP_Adduilianri = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Adduilianri"]));
			}
			
			plugsclass::logs('编辑广告信息',$OP_Adminname);
			$db -> update("`ourphp_ad`","`OP_Adpiaofui` = '".$OP_Adpiaofui."',`OP_Adpiaofuu` = '".admin_sql($_POST["OP_Adpiaofuu"])."',`OP_Adyouxiat` = '".admin_sql($_POST["OP_Adyouxiat"])."',`OP_Adyouxiaf` = '".admin_sql($_POST["OP_Adyouxiaf"])."',`OP_Adduilianli` = '".$OP_Adduilianli."',`OP_Adduilianlu` = '".admin_sql($_POST["OP_Adduilianlu"])."',`OP_Adduilianri` = '".$OP_Adduilianri."',`OP_Adduilianru` = '".admin_sql($_POST["OP_Adduilianru"])."',`OP_Adstateo` = '".intval($_POST["OP_Adstateo"])."',`OP_Adstatet` = '".intval($_POST["OP_Adstatet"])."',`OP_Adstates` = '".intval($_POST["OP_Adstates"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = 5");
		}elseif($adid == 7){
			
			if(substr($_POST["OP_Adduilianli1"],0,4) == 'http')
			{
				$OP_Adduilianli = $_POST["OP_Adduilianli1"];
			}else{
				$OP_Adduilianli = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Adduilianli1"]));
			}
			
			plugsclass::logs('编辑广告信息',$OP_Adminname);
			$db -> update("`ourphp_ad`","`OP_Adduilianli` = '".$OP_Adduilianli."',`OP_Adduilianlu` = '".admin_sql($_POST["OP_Adduilianlu1"])."',`OP_Adstates` = '".intval($_POST["OP_Adstates1"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = 7") or die ($db -> error());
			
		}elseif($adid == 8){
			
			if(substr($_POST["OP_Adduilianli1"],0,4) == 'http')
			{
				$OP_Adduilianli = $_POST["OP_Adduilianli1"];
			}else{
				$OP_Adduilianli = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Adduilianli1"]));
			}
			
			plugsclass::logs('编辑广告信息',$OP_Adminname);
			$db -> update("`ourphp_ad`","`OP_Adduilianli` = '".$OP_Adduilianli."',`OP_Adduilianlu` = '".intval($_POST["OP_Adduilianlu1"])."',`OP_Adstates` = '".intval($_POST["OP_Adstates1"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = 8") or die ($db -> error());
			
		}
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_ad.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
			
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_ad.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
}

function ADlist(){
	global $db;
	$id = '1,2,3,4,6';
	$query = $db -> listgo("id,OP_Adtitle,OP_Adpiaofui,OP_Adclass","`ourphp_ad`","where id in($id) order by id asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"img" => $ourphp_rs[2],
			"position" => $ourphp_rs[3]
		);
		$i = $i + 1;
	}
	return $rows;
}

Admin_click('广告管理','ourphp_ad.php?id=ourphp');
$smarty->assign("ADList",ADlist());
$ourphp_rs = $db -> select("*","`ourphp_ad`","where `id` = 5");
$ourphp_rs2 = $db -> select("*","`ourphp_ad`","where `id` = 7");
$ourphp_rs3 = $db -> select("*","`ourphp_ad`","where `id` = 8");
$smarty->assign('ourphp_ad',$ourphp_rs);
$smarty->assign('ourphp_ad2',$ourphp_rs2);
$smarty->assign('ourphp_ad3',$ourphp_rs3);
$smarty->display('ourphp_ad.html');
?>