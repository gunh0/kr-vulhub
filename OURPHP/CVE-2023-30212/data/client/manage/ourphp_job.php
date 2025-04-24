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
			
	$OP_Jobclass = explode("|",admin_sql($_POST["OP_Jobclass"]));
	if ($OP_Jobclass[0] == 0){
		$ourphp_font = 4;
		$ourphp_content = $ourphp_adminfont['nocolumn'];
		$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
		exit;
	}

	if (empty($_POST["OP_Jobdescription"])){
		$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Jobcontent"])), 0, 200);
	}else{
		$OP_Description = admin_sql($_POST["OP_Jobdescription"]);
	}
	
	//分词
	if($_POST["OP_Jobtag"] != '')
	{
		$wordtag = admin_sql($_POST["OP_Jobtag"]);
	}else{
		if(!empty($OP_Description)){
			$word = $OP_Description;
			$tag = admin_sql($_POST["OP_Jobtag"]);
			include '../../function/ourphp_sae.class.php';
		}else{
			$wordtag = admin_sql($_POST["OP_Jobtag"]);
		}
	}
	//结束
	
	if (!empty($_POST["OP_Jobattribute"])){
	$OP_Jobattribute = implode(',',admin_sql($_POST["OP_Jobattribute"]));
	}else{
	$OP_Jobattribute = '';
	}
	
	plugsclass::logs('创建招聘',$OP_Adminname);
	$db -> insert("`ourphp_job`","	`OP_Jobtitle` = '".admin_sql($_POST["OP_Jobtitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Jobwork` = '".admin_sql($_POST["OP_Jobwork"])."',`OP_Jobadd` = '".admin_sql($_POST["OP_Jobadd"])."',`OP_Jobnature` = '".admin_sql($_POST["OP_Jobnature"])."',`OP_Jobexperience` = '".admin_sql($_POST["OP_Jobexperience"])."',`OP_Jobeducation` = '".admin_sql($_POST["OP_Jobeducation"])."',`OP_Jobnumber` = '".admin_sql($_POST["OP_Jobnumber"])."',`OP_Jobage` = '".admin_sql($_POST["OP_Jobage"])."',`OP_Jobwelfare` = '".admin_sql($_POST["OP_Jobwelfare"])."',`OP_Jobwage` = '".admin_sql($_POST["OP_Jobwage"])."',`OP_Jobcontact` = '".admin_sql($_POST["OP_Jobcontact"])."',`OP_Jobtel` = '".admin_sql($_POST["OP_Jobtel"])."',`OP_Jobcontent` = '".admin_sql($_POST["OP_Jobcontent"])."',`OP_Jobcontent_wap` = '".admin_sql($_POST["OP_Jobcontent_wap"])."',`OP_Class` = '".$OP_Jobclass[0]."',`OP_Lang` = '".$OP_Jobclass[1]."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Jobsorting"])."',`OP_Attribute` = '".$OP_Jobattribute."',`OP_Url` = '".admin_sql($_POST["OP_Joburl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Callback` = 0","");
	
	navigationnum($OP_Jobclass[0],'+');
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
		
		plugsclass::logs('删除招聘',$OP_Adminname);
		navigationnum($_GET['c'],'-');
		$db -> del("ourphp_job","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
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
	header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=job");
	exit;
	}elseif($_POST["h"] == "y") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=job");
	exit;
	}elseif($_POST["h"] == "s") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=job");
	exit;
	}
	
	if (!empty($_POST["OP_Jobattribute"])){
	$OP_Jobattribute = implode(',',$_POST["OP_Jobattribute"]);
	}else{
	$OP_Jobattribute = '';
	}

	plugsclass::logs('批量编辑招聘',$OP_Adminname);
	$db -> update("ourphp_job","`OP_Attribute` = '".$OP_Jobattribute."'","where id in ($op_b)");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
	require 'ourphp_remind.php';
	
	}else{
		
	$ourphp_font = 4;
	$ourphp_content = '权限不够，无法编辑内容！';
	$ourphp_class = 'ourphp_job.php?id=ourphp&aid='.$aid;
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

function Joblist(){
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

	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_job`","where `OP_Callback` = 0 ".$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Jobtitle,time,OP_Jobwork,OP_Class,OP_Lang,OP_Sorting,OP_Click","`ourphp_job`","where `OP_Callback` = 0 ".$where." order by id desc LIMIT ".$start.",".$listpage);
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
				"work" => $ourphp_rs[3],
				"class" => $c,
				"class2" => $ourphp_rs[4],
				"lang" => $ourphp_rs[5],
				"att" => listattribute($ourphp_rs[0],'job'),
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

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();
$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('joblist',$arr);

$node = columnlist("job");
$smarty->assign('joblist2',$node);
Admin_click('招聘管理','ourphp_job.php?id=ourphp');
$smarty->assign("job",Joblist());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_job.html');
?>