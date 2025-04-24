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
	
		if(substr($_POST["OP_Downimg"],0,4) == 'http')
		{
			$ourphp_xiegang = admin_sql($_POST["OP_Downimg"]);
		}else{
			$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Downimg"]));
		}
	
		$OP_Downdurl = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Downdurl"]));
		
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
		
		$OP_Downclass = explode("|",admin_sql($_POST["OP_Downclass"]));
		
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
		
		plugsclass::logs('编辑下载',$OP_Adminname);
		$db -> update("`ourphp_down`","`OP_Downtitle` = '".admin_sql($_POST["OP_Downtitle"])."',`time` = '".admin_sql($_POST["time"])."',`OP_Downimg` = '".$ourphp_xiegang."',`OP_Downdurl` = '".$OP_Downdurl."',`OP_Downcontent` = '".admin_sql($_POST["OP_Downcontent"])."',`OP_Downcontent_wap` = '".admin_sql($_POST["OP_Downcontent_wap"])."',`OP_Downempower` = '".admin_sql($_POST["OP_Downempower"])."',`OP_Downtype` = '".admin_sql($_POST["OP_Downtype"])."',`OP_Downlang` = '".admin_sql($_POST["OP_Downlang"])."',`OP_Class` = '".$OP_Downclass[0]."',`OP_Lang` = '".$OP_Downclass[1]."',`OP_Downsize` = '".admin_sql($_POST["OP_Downsize"].$_POST["kb"])."',`OP_Downmake` = '".admin_sql($_POST["OP_Downmake"])."',`OP_Downsetup` = '".$OP_Downsetup."',`OP_Tag` = '".$wordtag."',`OP_Downrights` = '".$OP_Downrights."',`OP_Sorting` = '".admin_sql($_POST["OP_Downsorting"])."',`OP_Attribute` = '".$OP_Downattribute."',`OP_Url` = '".admin_sql($_POST["OP_Downurl"])."',`OP_Description` = '".compress_html($OP_Description)."',`OP_Random` = '".randomkeys(18)."'","where id = ".intval($_GET['id']));
		
		navigationnum($OP_Downclass[0],'+');
		navigationnum($_POST['c'],'-');
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_down.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';

	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_down.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';
		
	}
		
}

function Userleveto(){
	global $db;
	$query = $db -> listgo("OP_Userlevename","`ourphp_userleve`","order by id asc");
	$rows[]='任何人';
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=$ourphp_rs[0];
	}
	return $rows;
}

//$op= new Tree(columnlist(""));
//$arr=$op->leaf();

$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('downlist',$arr);

$ourphp_rs = $db -> select("*","`ourphp_down`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_down',$ourphp_rs);
//属性
$ourphp_text=explode(",",$ourphp_rs['OP_Attribute']);
for($i=0;$i<sizeof($ourphp_text);$i++){ 
	$selected[] = $ourphp_text[$i]; 
}
$smarty->assign('selected',$selected); 
$ourphph_qx=array('头条','热门','滚动','推荐'); 
$smarty->assign('ourphph_qx',$ourphph_qx); 
//运行环境
$ourphp_text2=explode(",",$ourphp_rs['OP_Downsetup']);
for($o=0;$o<sizeof($ourphp_text2);$o++){ 
	$selected2[] = $ourphp_text2[$o]; 
} 
$smarty->assign('selected2',$selected2); 
$ourphph_qx2=array('Win XP','Win 7','Win 8','Win 9','Win 10','Linux','Mac OS','Android','IOS','Windows Phone'); 
$smarty->assign('ourphph_qx2',$ourphph_qx2);
$ourphp_text3=explode(",",$ourphp_rs['OP_Downrights']);
for($i=0;$i<sizeof($ourphp_text3);$i++){ 
	$selected3[] = $ourphp_text3[$i]; 
}
$smarty->assign('selected3',$selected3); 
$ourphph_qx3=Userleveto(); 
$smarty->assign('ourphph_qx3',$ourphph_qx3);
$smarty->assign('aid',$aid);
$smarty->display('ourphp_downview.html');
?>