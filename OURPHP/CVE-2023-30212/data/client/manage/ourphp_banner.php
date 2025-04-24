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
	
	
	if(substr($_POST["OP_Bannerimg"],0,4) == 'http')
	{
		$ourphp_xiegang = $_POST["OP_Bannerimg"];
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Bannerimg"]));
	}
	
	plugsclass::logs('创建BANNER',$OP_Adminname);
	$ourphp_text = implode("|",admin_sql($_POST['OP_Bannertext']));
	$db -> insert("`ourphp_banner`","`OP_Bannerimg` = '".$ourphp_xiegang."',`OP_Bannertitle` = '".admin_sql($_POST["OP_Bannertitle"])."',`OP_Bannerurl` = '".admin_sql($_POST["OP_Bannerurl"])."',`OP_Bannerlang` = '".admin_sql($_POST["OP_Bannerlang"])."',`time` = '".date("Y-m-d H:i:s")."',`OP_Bannerclass` = '".admin_sql($_POST["OP_Bannerclass"])."',`OP_Bannertext` = '".$ourphp_text."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_banner.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "bannergroup"){

	plugsclass::logs('批量设置BANNER',$OP_Adminname);
	$op_a = admin_sql($_POST["bannerid"]);
	$op_b = implode(',',$op_a);
	$bannergroup = $_POST["bannergroup"];
	foreach ($op_a as $key => $op)
	{
		$db -> update("ourphp_banner","`OP_Bannerclass` = ".admin_sql($bannergroup[$key]),"where id = ".intval($op));
	}
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_banner.php?id=ourphp';
	require 'ourphp_remind.php';
	
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除BANNER',$OP_Adminname);
		$ourphp_rs = $db -> select("`OP_Bannerimg`","`ourphp_banner`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0]);
		}

		$db -> del("ourphp_banner","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_banner.php?id=ourphp';
		require 'ourphp_remind.php';

	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
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

function Bannerlist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_banner`","order by id desc");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Bannerimg,OP_Bannertitle,OP_Bannerurl,OP_Bannerlang,time,OP_Bannerclass","`ourphp_banner`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"img" => $ourphp_rs[1],
			"title" => $ourphp_rs[2],
			"url" => $ourphp_rs[3],
			"lang" => $ourphp_rs[4],
			"time" => $ourphp_rs[5],
			"class" => $ourphp_rs[6],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

Admin_click('Banner管理','ourphp_banner.php?id=ourphp');
$web = $db -> select("*","ourphp_web","where id = 1");
$smarty->assign("ourphp_web",$web);
$smarty->assign("langlist",Langlist());
$smarty->assign("banner",Bannerlist());
$smarty->display('ourphp_banner.html');
?>