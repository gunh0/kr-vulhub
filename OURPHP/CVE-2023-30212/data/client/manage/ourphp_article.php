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

	$OP_Articleclass = explode("|",admin_sql($_POST["OP_Articleclass"]));
	if ($OP_Articleclass[0] == 0){
		$ourphp_font = 4;
		$ourphp_content = $ourphp_adminfont['nocolumn'];
		$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
		exit;
	}
	

	if (empty($_POST["OP_Articledescription"])){
		$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Articlecontent"])), 0, 200);
	}else{
		$OP_Description = admin_sql($_POST["OP_Articledescription"]);
	}
	
	//分词
	if($_POST["OP_Articletag"] != '')
	{
		$wordtag = admin_sql($_POST["OP_Articletag"]);
	}else{
		if(!empty($OP_Description)){
			$word = $OP_Description;
			$tag = admin_sql($_POST["OP_Articletag"]);
			include '../../function/ourphp_sae.class.php';
		}else{
			$wordtag = admin_sql($_POST["OP_Articletag"]);
		}
	}
	//结束
	
	
	if (!empty($_POST["OP_Articleattribute"])){
	$OP_Articleattribute = implode(',',admin_sql($_POST["OP_Articleattribute"]));
	}else{
	$OP_Articleattribute = '';
	}
	
	if(empty($_POST["tu"])){
		if(empty($_POST["a_upimg"])){
			$OP_Minimg = 'skin/noimage.png';
		}else{
			
			if(substr($_POST["a_upimg"],0,4) == 'http')
			{
				$OP_Minimg = admin_sql($_POST["a_upimg"]);
			}else{
				$OP_Minimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["a_upimg"]));
			}
		}
	}else{
			$con = stripslashes($_POST["OP_Articlecontent"]);
			preg_match_all("/<img.*\>/isU",$con,$ereg);
			@$img=$ereg[0][0];
			$p="#src=('/|\"/)(.*)('|\")#isU";
			preg_match_all ($p, $img, $img1);
			@$OP_Minimg =$img1[2][0];
			if(!$OP_Minimg){
				$OP_Minimg='skin/noimage.png';
			}
	}

	plugsclass::logs('添加文章',$OP_Adminname);
	$db -> insert("`ourphp_article`","`OP_Articletitle` = '".admin_sql($_POST["OP_Articletitle"])."',`OP_Articleauthor` = '".admin_sql($_POST["OP_Articleauthor"])."',`OP_Articlesource` = '".admin_sql($_POST["OP_Articlesource"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Articlecontent` = '".admin_sql($_POST["OP_Articlecontent"])."',`OP_Articlecontent_wap` = '".admin_sql($_POST["OP_Articlecontent_wap"])."',`OP_Tag` = '".$wordtag."',`OP_Class` = '".$OP_Articleclass[0]."',`OP_Lang` = '".$OP_Articleclass[1]."',`OP_Sorting` = '".admin_sql($_POST["OP_Articlesorting"])."',`OP_Attribute` = '".$OP_Articleattribute."',`OP_Url` = '".admin_sql($_POST["OP_Articleurl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Minimg` = '".$OP_Minimg."',`OP_Callback` = 0","") or die($db -> error());
	
	navigationnum($OP_Articleclass[0],'+');
	
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		plugsclass::logs('删除文章',$OP_Adminname);
		navigationnum($_GET['c'],'-');
		$db -> del("ourphp_article","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
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
		header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=article");
		exit;
		}elseif($_POST["h"] == "y") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=article");
		exit;
		}elseif($_POST["h"] == "s") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=article");
		exit;
		}
		
		if (!empty($_POST["OP_Articleattribute"])){
		$OP_Articleattribute = implode(',',admin_sql($_POST["OP_Articleattribute"]));
		}else{
		$OP_Articleattribute = '';
		}

		plugsclass::logs('批量操作文章',$OP_Adminname);
		$db -> update("ourphp_article","`OP_Attribute` = '".$OP_Articleattribute."'","where id in ($op_b)");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_article.php?id=ourphp&aid='.$aid;
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

function Articlelist(){
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

	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_article`","where `OP_Callback` = 0 ".$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Articletitle,OP_Articleauthor,OP_Articlesource,time,OP_Articlecontent,OP_Class,OP_Lang,OP_Sorting,OP_Click","`ourphp_article`","where `OP_Callback` = 0 ".$where." order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$c = columncycle($ourphp_rs[6]);
		if($c){
			$rows[] = array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"title" => $ourphp_rs[1],
				"author" => $ourphp_rs[2],
				"source" => $ourphp_rs[3],
				"time" => $ourphp_rs[4],
				"content" => $ourphp_rs[5],
				"class" => $c,
				"class2" => $ourphp_rs[6],
				"lang" => $ourphp_rs[7],
				"sorting" => $ourphp_rs[8],
				"att" => listattribute($ourphp_rs[0],'article'),
				"click" => $ourphp_rs[9],
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
$smarty->assign('articlelist',$arr);

$node = columnlist("article");
$smarty->assign('articlelist2',$node);

Admin_click('文章管理','ourphp_article.php?id=ourphp');
$smarty->assign("article",Articlelist());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_article.html');
?>