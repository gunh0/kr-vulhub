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

if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';
	
	}elseif ($_GET["ourphp_cms"] == "cmd"){
	
	if($_POST["lx"]=="y" || $_POST["lx"]=="r"){
		
		$lm = explode("|",$_POST["lm"]);
		if($lm[0] == 0){
			exit($ourphp_adminfont['accessno']);
		}
		$op_b = explode(',',admin_sql($_POST["id"]));
		foreach($op_b as $op){
				$a = $db -> select("`OP_Class`","`ourphp_".admin_sql($_POST["xx"])."`","where id = ".intval($op));
				navigationnum($a[0],'-');
				navigationnum(intval($lm[0]),'+');
		}
		
		if($_POST["xx"]=="product")
		{
			$query = $db -> update("ourphp_".admin_sql($_POST["xx"]),"`OP_Class` = ".intval($lm[0]).",`OP_Down` = 2,`OP_Lang` = '".admin_sql($lm[1])."'","where id in (".admin_sql($_POST["id"]).")");
		}else{
			$query = $db -> update("ourphp_".admin_sql($_POST["xx"]),"`OP_Class` = ".intval($lm[0]).",`OP_Callback` = 0,`OP_Lang` = '".admin_sql($lm[1])."'","where id in (".admin_sql($_POST["id"]).")");
		}
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_'.$_POST["xx"].'.php?id=ourphp';
		require 'ourphp_remind.php';
			
	}elseif($_POST["lx"]=="s"){
	
		$op_b = explode(',',$_POST["id"]);
		include './ourphp_del.php';
		if($_POST["xx"]=="article"){
			
			foreach($op_b as $op){
				$a = $db -> select("`OP_Class`","`ourphp_article`","where id = ".intval($op));
				navigationnum($a[0],'-');
				$query = $db -> del("ourphp_article","where id = ".intval($op));
			}
			
		}elseif($_POST["xx"]=="product"){
			
			foreach($op_b as $op){
				$ourphp_rs = $db -> select("`OP_Minimg`,`OP_Maximg`,`OP_Img`,`OP_Class`","`ourphp_product`","where id = ".intval($op));
				if($ourphp_rs[0] != '' || $ourphp_rs[1] != '' || $ourphp_rs[2] != ''){
					ourphp_imgdel($ourphp_rs[0],$ourphp_rs[1],$ourphp_rs[2]);
				}
				navigationnum($ourphp_rs[3],'-');
				$query = $db -> del("ourphp_product","where id = ".intval($op));
			}
			
		}elseif($_POST["xx"]=="photo"){
			
			foreach($op_b as $op){
				$ourphp_rs = $db -> select("`OP_Photocminimg`,`OP_Photoimg`,`OP_Class`","`ourphp_photo`","where id = ".intval($op));
				if($ourphp_rs[0] != '' || $ourphp_rs[1] != ''){
					ourphp_imgdel($ourphp_rs[0],'',$ourphp_rs[1]);
				}
				navigationnum($ourphp_rs[2],'-');
				$query = $db -> del("ourphp_photo","where id = ".intval($op));
			}
			
		}elseif($_POST["xx"]=="video"){
			
			foreach($op_b as $op){
				$ourphp_rs = $db -> select("`OP_Videoimg`,`OP_Class`","`ourphp_video`","where id = ".intval($op));
				if($ourphp_rs[0] != ''){
					ourphp_imgdel($ourphp_rs[0]);
				};
				navigationnum($ourphp_rs[1],'-');
				$query = $db -> del("ourphp_video","where id = ".intval($op));
			}
			
		}elseif($_POST["xx"]=="down"){
			
			foreach($op_b as $op){
				$ourphp_rs = $db -> select("`OP_Downimg`,`OP_Downdurl`,`OP_Class`","`ourphp_down`","where id = ".intval($op));
				if($ourphp_rs[0] != '' || $ourphp_rs[1] != ''){
					ourphp_imgdel($ourphp_rs[0],$ourphp_rs[1],'');
				}
				navigationnum($ourphp_rs[2],'-');
				$query = $db -> del("ourphp_down","where id = ".intval($op));
			}
			
		}elseif($_POST["xx"]=="job"){
			
			foreach($op_b as $op){
				$a = $db -> select("`OP_Class`","`ourphp_job`","where id = ".intval($op));
				navigationnum($a[0],'-');
				$query = $db -> del("ourphp_job","where id = ".intval($op));
			}
			
		}
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_'.$_POST["xx"].'.php?id=ourphp';
		require 'ourphp_remind.php';

	}elseif($_POST["lx"]=="h"){
	
		$op_b = explode(',',$_POST["id"]);
		foreach($op_b as $op){
			$a = $db -> select("`OP_Class`","`ourphp_".admin_sql($_POST["xx"])."`","where id = ".intval($op));
			navigationnum($a[0],'-');
		}
		$query = $db -> update("ourphp_".admin_sql($_POST["xx"]),"`OP_Callback` = 1","where id in (".admin_sql($_POST["id"]).")");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_'.$_POST["xx"].'.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
}

$op= new Tree(columnlist(""));
$arr=$op->leaf();
$smarty->assign('articlelist',$arr);
$smarty->assign('id',$_GET["id"]);
$smarty->assign('xx',$_GET["xx"]);
$smarty->assign('lx',$_GET["lx"]);
$smarty->display('ourphp_cmd.html');
?>