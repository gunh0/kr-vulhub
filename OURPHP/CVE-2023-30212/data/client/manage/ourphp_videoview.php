<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
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
}elseif ($_GET["ourphp_cms"] == "edit"){

		if (strstr($OP_Adminpower,"34")){

			$OP_Videoclass = explode("|",admin_sql($_POST["OP_Videoclass"]));
			if ($OP_Videoclass[0] == 0){
				$ourphp_font = 4;
				$ourphp_content = $ourphp_adminfont['nocolumn'];
				$ourphp_class = 'ourphp_video.php?id=ourphp&aid='.$aid;
				require 'ourphp_remind.php';
				exit;
			}
			
			if(substr($_POST["OP_Videoimg"],0,4) == 'http')
			{
				$ourphp_xiegang = admin_sql($_POST["OP_Videoimg"]);
			}else{
				$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Videoimg"]));
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
			
			$OP_Videoclass = explode("|",admin_sql($_POST["OP_Videoclass"]));
			
			if (!empty($_POST["OP_Videoattribute"])){
				$OP_Videoattribute = implode(',',admin_sql($_POST["OP_Videoattribute"]));
			}else{
				$OP_Videoattribute = '';
			}

			plugsclass::logs('编辑视频',$OP_Adminname);
			$db -> update("`ourphp_video`","`OP_Videotitle` = '".admin_sql($_POST["OP_Videotitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Videoimg` = '".$ourphp_xiegang."',`OP_Videovurl` = '".admin_sql($_POST["OP_Videovurl"])."',`OP_Videoformat` = '".admin_sql($_POST["OP_Videoformat"])."',`OP_Videowidth` = '".admin_sql($_POST["OP_Videowidth"])."',`OP_Videoheight` = '".admin_sql($_POST["OP_Videoheight"])."',`OP_Videocontent` = '".admin_sql($_POST["OP_Videocontent"])."',`OP_Videocontent_wap` = '".admin_sql($_POST["OP_Videocontent_wap"])."',`OP_Class` = '".$OP_Videoclass[0]."',`OP_Lang` = '".$OP_Videoclass[1]."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Videosorting"])."',`OP_Attribute` = '".$OP_Videoattribute."',`OP_Url` = '".admin_sql($_POST["OP_Videourl"])."',`OP_Description` = '".compress_html($OP_Description)."'","where id = ".intval($_GET['id']));
			
			navigationnum($OP_Videoclass[0],'+');
			navigationnum($_POST['c'],'-');
			
			$ourphp_font = 1;
			$ourphp_class = 'ourphp_video.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
			require 'ourphp_remind.php';

		
		}else{
			
			$ourphp_font = 4;
			$ourphp_content = '权限不够，无法编辑内容！';
			$ourphp_class = 'ourphp_video.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
			require 'ourphp_remind.php';
			
		}			
}

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();

$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('videolist',$arr);

$ourphp_rs = $db -> select("*","`ourphp_video`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_video',$ourphp_rs);
$ourphp_text=explode(",",$ourphp_rs['OP_Attribute']);
for($i=0;$i<sizeof($ourphp_text);$i++){ 
	$selected[] = $ourphp_text[$i]; 
}
$smarty->assign('selected',$selected); 
$ourphph_qx=array('头条','热门','滚动','推荐'); 
$smarty->assign('ourphph_qx',$ourphph_qx);
$smarty->assign('aid',$aid);
$smarty->display('ourphp_videoview.html');
?>