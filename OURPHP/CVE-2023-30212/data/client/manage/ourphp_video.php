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
			
			
			if(substr($_POST["OP_Videoimg"],0,4) == 'http')
			{
				$ourphp_xiegang = admin_sql($_POST["OP_Videoimg"]);
			}else{
				$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Videoimg"]));
			}
			
			$OP_Videoclass = explode("|",admin_sql($_POST["OP_Videoclass"]));
			if ($OP_Videoclass[0] == 0){
				$ourphp_font = 4;
				$ourphp_content = $ourphp_adminfont['nocolumn'];
				$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
				require 'ourphp_remind.php';
				exit;
			}
			

			if (empty($_POST["OP_Videodescription"])){
				$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Videocontent"])), 0, 200);
			}else{
				$OP_Description = admin_sql($_POST["OP_Videodescription"]);
			}
			
			//分词
			if($_POST["OP_Videotag"] != '')
			{
				$wordtag = admin_sql($_POST["OP_Videotag"]);
			}else{
					if(!empty($OP_Description)){
						$word = $OP_Description;
						$tag = admin_sql($_POST["OP_Videotag"]);
						include '../../function/ourphp_sae.class.php';
					}else{
						$wordtag = admin_sql($_POST["OP_Videotag"]);
					}
			}
			//结束
			
			
			if (!empty($_POST["OP_Videoattribute"])){
				$OP_Videoattribute = implode(',',admin_sql($_POST["OP_Videoattribute"]));
			}else{
				$OP_Videoattribute = '';
			}

			plugsclass::logs('创建视频',$OP_Adminname);
			$db -> insert("`ourphp_video`","`OP_Videotitle` = '".admin_sql($_POST["OP_Videotitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Videoimg` = '".$ourphp_xiegang."',`OP_Videovurl` = '".admin_sql($_POST["OP_Videovurl"])."',`OP_Videoformat` = '".admin_sql($_POST["OP_Videoformat"])."',`OP_Videowidth` = '".admin_sql($_POST["OP_Videowidth"])."',`OP_Videoheight` = '".admin_sql($_POST["OP_Videoheight"])."',`OP_Videocontent` = '".admin_sql($_POST["OP_Videocontent"])."',`OP_Videocontent_wap` = '".admin_sql($_POST["OP_Videocontent_wap"])."',`OP_Class` = '".$OP_Videoclass[0]."',`OP_Lang` = '".$OP_Videoclass[1]."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Videosorting"])."',`OP_Attribute` = '".$OP_Videoattribute."',`OP_Url` = '".admin_sql($_POST["OP_Videourl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Callback` = 0","");
			
			navigationnum($OP_Videoclass[0],'+');
			$ourphp_font = 1;
			$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
			require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		navigationnum($_GET['c'],'-');
		$ourphp_rs = $db -> select("`OP_Videoimg`","`ourphp_video`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0]);
		}
		
		plugsclass::logs('删除视频',$OP_Adminname);
		$db -> del("ourphp_video","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';

				
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
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
	header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=video");
	exit;
	}elseif($_POST["h"] == "y") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=video");
	exit;
	}elseif($_POST["h"] == "s") {
	header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=video");
	exit;
	}
	
	if (!empty($_POST["OP_Videoattribute"])){
		$OP_Videoattribute = implode(',',admin_sql($_POST["OP_Videoattribute"]));
	}else{
		$OP_Videoattribute = '';
	}

		plugsclass::logs('批量操作视频',$OP_Adminname);
		$db -> update("ourphp_video","`OP_Attribute` = '".$OP_Videoattribute."'","where id in ($op_b)");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
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

function Videolist(){
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

	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_video`","where `OP_Callback` = 0 ".$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Videotitle,OP_Videoimg,time,OP_Class,OP_Lang,OP_Sorting,OP_Click","`ourphp_video`","where `OP_Callback` = 0 ".$where." order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$c = columncycle($ourphp_rs[4]);
		if($c){
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"title" => $ourphp_rs[1],
				"img" => $ourphp_rs[2],
				"time" => $ourphp_rs[3],
				"class" => $c,
				"class2" => $ourphp_rs[4],
				"lang" => $ourphp_rs[5],
				"att" => listattribute($ourphp_rs[0],'video'),
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
$smarty->assign('videolist',$arr);

$node = columnlist("video");
$smarty->assign('videolist2',$node);
Admin_click('视频管理','ourphp_video.php?id=ourphp');
$smarty->assign("video",Videolist());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_video.html');
?>