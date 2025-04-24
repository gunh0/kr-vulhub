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

if(isset($_GET["opcms"]) == ""){
	$mx = 'article';
}else{
	$mx = admin_sql($_GET["opcms"]);
}
if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}

if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';
		
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除回收站信息',$OP_Adminname);
		$db -> del("`ourphp_".admin_sql($_GET["mx"])."`","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_callback.php?ourphp='.$_GET["mx"];
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_callback.php?ourphp='.$_GET["mx"];
		require 'ourphp_remind.php';
	
	}
	
}elseif ($_GET["ourphp_cms"] == "Batch"){
	
	if (strstr($OP_Adminpower,"34")){
	
		plugsclass::logs('批量回收站恢复',$OP_Adminname);
		if (!empty($_POST["op_b"])){
		$op_b = implode(',',admin_sql($_POST["op_b"]));
		}else{
		$op_b = '';
		}
		
		if ($_POST["h"] == "r") {
			header("location: ./ourphp_cmd.php?id=$op_b&lx=r&xx=".$_GET["mx"]);
			exit;
		}
		
		header("location: ./ourphp_callback.php?opcms=".$_GET["mx"]);
		exit;
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_article.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}	
	
}

function columncycle($id=1){
	global $db;
	$ourphp_rs = $db -> select("`OP_Columntitle`","`ourphp_column`","where id = ".$id);
	return $ourphp_rs[0];
}

function Callbacklist(){
	global $mx,$_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_".$mx."`","where `OP_Callback` = 1");
	$ourphptotal = $db -> whilego($ourphptotal);
	if($mx=='article'){
	$query = $db -> listgo("id,OP_Articletitle,OP_Class,OP_Lang,time","`ourphp_".$mx."`","where `OP_Callback` = 1 order by id desc LIMIT ".$start.",".$listpage);
	}elseif($mx=='photo'){
	$query = $db -> listgo("id,OP_Phototitle,OP_Class,OP_Lang,time","`ourphp_".$mx."`","where `OP_Callback` = 1 order by id desc LIMIT ".$start.",".$listpage);
	}elseif($mx=='video'){
	$query = $db -> listgo("id,OP_Videotitle,OP_Class,OP_Lang,time","`ourphp_".$mx."`","where `OP_Callback` = 1 order by id desc LIMIT ".$start.",".$listpage);
	}elseif($mx=='down'){
	$query = $db -> listgo("id,OP_Downtitle,OP_Class,OP_Lang,time","`ourphp_".$mx."`","where `OP_Callback` = 1 order by id desc LIMIT ".$start.",".$listpage);
	}elseif($mx=='job'){
	$query = $db -> listgo("id,OP_Jobtitle,OP_Class,OP_Lang,time","`ourphp_".$mx."`","where `OP_Callback` = 1 order by id desc LIMIT ".$start.",".$listpage);
	}
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"title" => $ourphp_rs[1],
			"time" => $ourphp_rs[4],
			"class" => columncycle($ourphp_rs[2]),
			"lang" => $ourphp_rs[3]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("Callbacklist",Callbacklist());
$smarty->display('ourphp_callback.html');
?>