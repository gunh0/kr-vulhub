<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include '../../function/ourphp_navigation.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){
	
		if(substr($_POST["OP_Bannerimg"],0,4) == 'http')
		{
			$ourphp_xiegang = $_POST["OP_Bannerimg"];
		}else{
			$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Bannerimg"]));
		}
	
		plugsclass::logs('修改BANNER',$OP_Adminname);
		$ourphp_text = implode("|",admin_sql($_POST['OP_Bannertext']));
		$db -> update("`ourphp_banner`","`OP_Bannerimg` = '".$ourphp_xiegang."',`OP_Bannertitle` = '".admin_sql($_POST["OP_Bannertitle"])."',`OP_Bannerurl` = '".admin_sql($_POST["OP_Bannerurl"])."',`OP_Bannerlang` = '".admin_sql($_POST["OP_Bannerlang"])."',`time` = '".date("Y-m-d H:i:s")."',`OP_Bannerclass` = '".admin_sql($_POST["OP_Bannerclass"])."',`OP_Bannertext` = '".$ourphp_text."'","where id = ".intval($_GET['id']));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_banner.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_banner.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}			
}

function Langlist(){
	global $db;
	$query = $db -> listgo("id,OP_Lang,OP_Font,OP_Default","`ourphp_lang`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"lang" => $ourphp_rs[1],
			"font" => $ourphp_rs[2],
			"default" => $ourphp_rs[3]
		);
	}
	return $rows;
}

$ourphp_rs = $db -> select("*","`ourphp_banner`","where `id` = ".intval($_GET['id']));
$ourphp_text = explode("|",$ourphp_rs['OP_Bannertext']);
$smarty->assign('ourphp_banner',$ourphp_rs);
$smarty->assign('ourphp_text',$ourphp_text);
$smarty->assign('langlist',Langlist());
$smarty->display('ourphp_bannerview.html');
?>