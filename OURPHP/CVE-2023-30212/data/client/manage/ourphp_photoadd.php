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

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

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
	
	if(empty($_POST["OP_Imgtoone"]))
	{
		$OP_Photoimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Photoimg"]));
		
	}else{
		
		if(empty($_POST["OP_Photoimg"]))
		{
			$OP_Photoimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]));
		}else{
			$OP_Photoimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]."|".$_POST["OP_Photoimg"]));
		}
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
	
	if (!empty($_POST["OP_Photoname"])){
	$OP_Photoname = implode('|',admin_sql($_POST["OP_Photoname"]));
	}else{
	$OP_Photoname = '';
	}

	plugsclass::logs('创建图文',$OP_Adminname);
	$db -> insert("`ourphp_photo`","`OP_Phototitle` = '".admin_sql($_POST["OP_Phototitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Photocminimg` = '".$ourphp_xiegang."',`OP_Photoimg` = '".$OP_Photoimg."',`OP_Photocontent` = '".admin_sql($_POST["OP_Photocontent"])."',`OP_Photocontent_wap` = '".admin_sql($_POST["OP_Photocontent_wap"])."',`OP_Class` = '".$OP_Photoclass[0]."',`OP_Lang` = '".$OP_Photoclass[1]."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Photosorting"])."',`OP_Attribute` = '".$OP_Photoattribute."',`OP_Url` = '".admin_sql($_POST["OP_Photourl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Callback` = 0,`OP_Photoname` = '".admin_sql($OP_Photoname)."'","");
	
	navigationnum($OP_Photoclass[0],'+');
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_photo.php?id=ourphp';
	require 'ourphp_remind.php';
}

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();

$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('photolist',$arr);
$smarty->display('ourphp_photoadd.html');
?>