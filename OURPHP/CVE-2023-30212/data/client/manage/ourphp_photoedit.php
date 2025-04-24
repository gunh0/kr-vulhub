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
	
		$OP_Photoclass = explode("|",admin_sql($_POST["OP_Photoclass"]));
		if ($OP_Photoclass[0] == 0){
			$ourphp_font = 4;
			$ourphp_content = $ourphp_adminfont['nocolumn'];
			$ourphp_class = 'ourphp_photo.php?id=ourphp';
			require 'ourphp_remind.php';
			exit;
		}
		
		if(substr($_POST["OP_Photocminimg"],0,4) == 'http')
		{
			$ourphp_xiegang = admin_sql($_POST["OP_Photocminimg"]);
		}else{
			$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Photocminimg"]));
		}
		
		$OP_Photoimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Photoimg"]));

		if(empty($_POST["OP_Imgtoone"]))
		{
			$OP_Imgtoone = "";
			
		}else{
			
			$OP_Imgtoone = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]));
			$OP_Imgtoone = $OP_Imgtoone."|";
		}
		
		if (!empty($_POST["OP_Photoimg2"])){
			$OP_Photoimg2 = implode('|',$_POST["OP_Photoimg2"]);
			if (!empty($OP_Photoimg)){
				$OP_img = $OP_Imgtoone.$OP_Photoimg2.'|'.$OP_Photoimg;
			}else{
				$OP_img = $OP_Imgtoone.$OP_Photoimg2;
			}
		}else{
			$OP_Photoimg2 = '';
			$OP_img = $OP_Imgtoone.$OP_Photoimg;
		}
		
		
		if (!empty($_POST["OP_Photoname"])){
		$OP_Photoname = implode('|',admin_sql($_POST["OP_Photoname"]));
		}else{
		$OP_Photoname = '';
		}
		
		if (empty($_POST["OP_Photodescription"])){
			$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Photocontent"])), 0, 200);
		}else{
			$OP_Description = admin_sql($_POST["OP_Photodescription"]);
		}
		
		//分词
		if($_POST["OP_Phototag"] != '')
		{
			$wordtag = admin_sql($_POST["OP_Phototag"]);
		}else{
			if(!empty($OP_Description)){
				$word = $OP_Description;
				$tag = admin_sql($_POST["OP_Phototag"]);
				include '../../function/ourphp_sae.class.php';
			}else{
				$wordtag = admin_sql($_POST["OP_Phototag"]);
			}
		}
		//结束
		
		if (!empty($_POST["OP_Photoattribute"])){
		$OP_Photoattribute = implode(',',admin_sql($_POST["OP_Photoattribute"]));
		}else{
		$OP_Photoattribute = '';
		}
		
		plugsclass::logs('编辑图文',$OP_Adminname);
		$db -> update("`ourphp_photo`","`OP_Phototitle` = '".admin_sql($_POST["OP_Phototitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Photocminimg` = '".$ourphp_xiegang."',`OP_Photoimg` = '".$OP_img."',`OP_Photocontent` = '".admin_sql($_POST["OP_Photocontent"])."',`OP_Photocontent_wap` = '".admin_sql($_POST["OP_Photocontent_wap"])."',`OP_Class` = '".$OP_Photoclass[0]."',`OP_Lang` = '".$OP_Photoclass[1]."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Photosorting"])."',`OP_Attribute` = '".$OP_Photoattribute."',`OP_Url` = '".admin_sql($_POST["OP_Photourl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Photoname` = '".admin_sql($OP_Photoname)."'","where id = ".intval($_GET["id"]));
		
		navigationnum($OP_Photoclass[0],'+');
		navigationnum($_POST['c'],'-');
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_photo.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';

				
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_photo.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';
		
	}
}

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();

$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('photolist',$arr);
$ourphp_rs = $db -> select("*","`ourphp_photo`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_photo',$ourphp_rs);
$ourphp_text=explode(",",$ourphp_rs['OP_Attribute']);
for($i=0;$i<sizeof($ourphp_text);$i++){ 
	$selected[] = $ourphp_text[$i];
}
$smarty->assign('selected',$selected); 
$ourphph_qx=array('头条','热门','滚动','推荐'); 
$smarty->assign('ourphph_qx',$ourphph_qx); 
if ($ourphp_rs['OP_Photoimg'] != ''){
	$OP_Photoimg = explode("|",$ourphp_rs['OP_Photoimg']);
	$OP_Photoname = explode("|",$ourphp_rs['OP_Photoname']);
	$i = 1;
	$ii = 0;
	foreach($OP_Photoimg as $u){
		$OP_Photoimgarr = explode("|",$u);
		foreach($OP_Photoimgarr as $newstr){
			$rows[]=array(
				"i" => $i,
				"img" => $newstr,
				"imgname" => @$OP_Photoname[$ii],
			); 
			$i = $i + 1;
			$ii = $ii + 1;
		}
	}
}else{
	$rows = '';
}
$smarty->assign('photoimglist',$rows);
$smarty->assign('aid',$aid);
$smarty->display('ourphp_photoedit.html');
?>