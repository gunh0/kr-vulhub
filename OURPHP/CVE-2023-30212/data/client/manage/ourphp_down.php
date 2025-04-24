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
}elseif ($_GET["ourphp_cms"] == "add"){
			
	
	if(substr($_POST["OP_Downimg"],0,4) == 'http')
	{
		$ourphp_xiegang = admin_sql($_POST["OP_Downimg"]);
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Downimg"]));
	}
	
	$OP_Downdurl = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Downdurl"]));
	
	$OP_Downclass = explode("|",admin_sql($_POST["OP_Downclass"]));
	if ($OP_Downclass[0] == 0){
		$ourphp_font = 4;
		$ourphp_content = $ourphp_adminfont['nocolumn'];
		$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
		exit;
	}

	if (empty($_POST["OP_Downdescription"])){
		$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Downcontent"])), 0, 200);
	}else{
		$OP_Description = admin_sql($_POST["OP_Downdescription"]);
	}
	
	//分词
	if($_POST["OP_Downurl"] != '')
	{
		$wordtag = admin_sql($_POST["OP_Downurl"]);
	}else{
		if(!empty($OP_Description)){
			$word = $OP_Description;
			$tag = admin_sql($_POST["OP_Downurl"]);
			include '../../function/ourphp_sae.class.php';
		}else{
			$wordtag = admin_sql($_POST["OP_Downurl"]);
		}
	}
	//结束

	
	if (!empty($_POST["OP_Downattribute"])){
	$OP_Downattribute = implode(',',admin_sql($_POST["OP_Downattribute"]));
	}else{
	$OP_Downattribute = '';
	}
	
	if (!empty($_POST["OP_Downsetup"])){
	$OP_Downsetup = implode(',',admin_sql($_POST["OP_Downsetup"]));
	}else{
	$OP_Downsetup = '';
	}
	
	if (!empty($_POST["OP_Downrights"])){
	$OP_Downrights = implode(',',admin_sql($_POST["OP_Downrights"]));
	}else{
	$OP_Downrights = '0';
	}
	
	plugsclass::logs('创建下载',$OP_Adminname);
	$db -> insert("`ourphp_down`","`OP_Downtitle` = '".admin_sql($_POST["OP_Downtitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Downimg` = '".$ourphp_xiegang."',`OP_Downdurl` = '".$OP_Downdurl."',`OP_Downcontent` = '".admin_sql($_POST["OP_Downcontent"])."',`OP_Downcontent_wap` = '".admin_sql($_POST["OP_Downcontent_wap"])."',`OP_Downempower` = '".admin_sql($_POST["OP_Downempower"])."',`OP_Downtype` = '".admin_sql($_POST["OP_Downtype"])."',`OP_Downlang` = '".admin_sql($_POST["OP_Downlang"])."',`OP_Class` = '".$OP_Downclass[0]."',`OP_Lang` = '".$OP_Downclass[1]."',`OP_Downsize` = '".admin_sql($_POST["OP_Downsize"].$_POST["kb"])."',`OP_Downmake` = '".admin_sql($_POST["OP_Downmake"])."',`OP_Downsetup` = '".$OP_Downsetup."',`OP_Tag` = '".$wordtag."',`OP_Downrights` = '".$OP_Downrights."',`OP_Sorting` = '".admin_sql($_POST["OP_Downsorting"])."',`OP_Attribute` = '".$OP_Downattribute."',`OP_Url` = '".admin_sql($_POST["OP_Downurl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Random` = '".randomkeys(18)."',`OP_Callback` = 0","");
	
	navigationnum($OP_Downclass[0],'+');
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		navigationnum($_GET['c'],'-');
		$ourphp_rs = $db -> select("`OP_Downimg`,`OP_Downdurl`","`ourphp_down`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != '' || $ourphp_rs[1] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0],$ourphp_rs[1],'');
		}
		
		plugsclass::logs('删除下载',$OP_Adminname);
		$db -> del("ourphp_down","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';

				
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
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
	header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=down");
	exit;
	}elseif($_POST["h"] == "y") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=down");
	exit;
	}elseif($_POST["h"] == "s") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=down");
	exit;
	}
	
	if (!empty($_POST["OP_Downattribute"])){
	$OP_Downattribute = implode(',',$_POST["OP_Downattribute"]);
	}else{
	$OP_Downattribute = '';
	}

	plugsclass::logs('批量编辑下载',$OP_Adminname);
	$db -> update("ourphp_down","`OP_Attribute` = '".$OP_Downattribute."'","where id in ($op_b)");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
	require 'ourphp_remind.php';
	
	}else{
		
	$ourphp_font = 4;
	$ourphp_content = '权限不够，无法编辑内容！';
	$ourphp_class = 'ourphp_down.php?id=ourphp&aid='.$aid;
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

function Downlist(){
	global $_page,$db,$smarty;
	$listpage = 25;
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

	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_down`","where `OP_Callback` = 0 ".$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Downtitle,time,OP_Class,OP_Lang,OP_Sorting,OP_Click","`ourphp_down`","where `OP_Callback` = 0 ".$where." order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$c = columncycle($ourphp_rs[3]);
		if($c){
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"title" => $ourphp_rs[1],
				"time" => $ourphp_rs[2],
				"class" => $c,
				"class2" => $ourphp_rs[3],
				"lang" => $ourphp_rs[4],
				"att" => listattribute($ourphp_rs[0],'down'),
				"sorting" => $ourphp_rs[5],
				"click" => $ourphp_rs[6],
			);
		}
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

function Userleve(){
	global $db;
	$query = $db -> listgo("id,OP_Userlevename","`ourphp_userleve`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1]
		);
	}
	return $rows;
}

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();
$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('downlist',$arr);

$node = columnlist("down");
$smarty->assign('downlist2',$node);
Admin_click('下载管理','ourphp_down.php?id=ourphp');
$smarty->assign("down",Downlist());
$smarty->assign("userleve",Userleve());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_down.html');
?>