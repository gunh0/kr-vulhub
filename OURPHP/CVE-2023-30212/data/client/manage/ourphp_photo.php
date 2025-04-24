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

$aid = isset($_GET['aid']) ? $_GET['aid'] : "0";
if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		navigationnum($_GET['c'],'-');
		$ourphp_rs = $db -> select("`OP_Photocminimg`,`OP_Photoimg`","`ourphp_photo`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != '' || $ourphp_rs[1] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0],'',$ourphp_rs[1]);
		}
		
		plugsclass::logs('删除图文',$OP_Adminname);
		$db -> del("ourphp_photo","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_photo.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_photo.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}
	
}elseif ($_GET["ourphp_cms"] == "Batch"){


	if (strstr($OP_Adminpower,"34")){
	
		if (!empty($_POST["op_b"])){
		$op_b = implode(',',admin_sql($_POST["op_b"]));
		}else{
		$op_b = '';
		}
		
		if ($_POST["h"] == "h") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=photo");
		exit;
		}elseif($_POST["h"] == "y") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=photo");
		exit;
		}elseif($_POST["h"] == "s") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=photo");
		exit;
		}
		
		if (!empty($_POST["OP_Photoattribute"])){
		$OP_Photoattribute = implode(',',admin_sql($_POST["OP_Photoattribute"]));
		}else{
		$OP_Photoattribute = '';
		}

		plugsclass::logs('批量编辑图文',$OP_Adminname);
		$db -> update("ourphp_photo","`OP_Attribute` = '".$OP_Photoattribute."'","where id in ($op_b)");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_photo.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_photo.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
		
	}	
			
}

function columncycle($cid = 1){
	global $db,$id;
	$newid = "0".$id;
	$ourphp_rs = $db -> select("`OP_Columntitle`","`ourphp_column`","where id = ".$cid." and (`OP_Adminopen` LIKE '%$newid%' or `OP_Adminopen` LIKE '00%')");
	if($ourphp_rs){
		return $ourphp_rs[0];
	}else{
		return false;
	}
}

function Photolist(){
	global $_page,$db,$smarty;
	$listpage = 20;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;

	if($_GET["aid"] == 0){
		$where = '';
	}else{
		$where = ' and OP_Class = '.intval($_GET['aid']);
	}

	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_photo`","where `OP_Callback` = 0 ".$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("`id`,`OP_Phototitle`,`time`,`OP_Photocminimg`,`OP_Class`,`OP_Lang`,OP_Sorting,OP_Click","`ourphp_photo`","where `OP_Callback` = 0  ".$where." order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$c = columncycle($ourphp_rs[4]);
		if($c){
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"title" => $ourphp_rs[1],
				"time" => $ourphp_rs[2],
				"img" => $ourphp_rs[3],
				"class" => $c,
				"class2" => $ourphp_rs[4],
				"lang" => $ourphp_rs[5],
				"att" => listattribute($ourphp_rs[0],'photo'),
				"sorting" => $ourphp_rs[6],
				"click" => $ourphp_rs[7],
			);
		}
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$node = columnlist("photo");
$smarty->assign('photolist',$node);
Admin_click('图集管理','ourphp_photo.php?id=ourphp');
$smarty->assign("photo",Photolist());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_photo.html');
?>