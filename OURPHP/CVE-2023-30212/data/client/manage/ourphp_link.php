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
	
	if(substr($_POST["OP_Linkimg"],0,4) == 'http')
	{
		$ourphp_xiegang = $_POST["OP_Linkimg"];
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Linkimg"]));
	}
	
	plugsclass::logs('添加友情链接',$OP_Adminname);
	$db -> insert("`ourphp_link`","`OP_Linkname` = '".admin_sql($_POST["OP_Linkname"])."',`OP_Linkurl` = '".admin_sql($_POST["OP_Linkurl"])."',`OP_Linkclass` = '".admin_sql($_POST["OP_Linkclass"])."',`OP_Linkimg` = '".$ourphp_xiegang."',`OP_Linksorting` = '".intval($_POST["OP_Linksorting"])."',`OP_Linkstate` = '".intval($_POST["OP_Linkstate"])."',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_link.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		$ourphp_rs = $db -> select("`OP_Linkimg`","`ourphp_link`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0]);
		}

		plugsclass::logs('删除友情链接',$OP_Adminname);
		$db -> del("ourphp_link","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_link.php?id=ourphp';
		require 'ourphp_remind.php';

				
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_link.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function Linklist(){
	global $db;
	$query = $db -> listgo("id,OP_Linkname,OP_Linkurl,OP_Linkclass,OP_Linkimg,OP_Linkstate,time","`ourphp_link`","order by OP_Linksorting asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"url" => $ourphp_rs[2],
			"class" => $ourphp_rs[3],
			"img" => $ourphp_rs[4],
			"kstate" => $ourphp_rs[5],
			"time" => $ourphp_rs[6]
		);
		$i = $i + 1;
	}
	return $rows;
}

Admin_click('友情链接管理','ourphp_link.php?id=ourphp');
$smarty->assign("Linklist",Linklist());
$smarty->display('ourphp_link.html');
?>