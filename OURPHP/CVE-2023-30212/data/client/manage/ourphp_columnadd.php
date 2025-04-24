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

	if($_POST["OP_Columntitle"] == '' && $_POST["OP_Columntitle_pl"] == ''){
		echo '标题或批量标题不能全部为空。请重新操作 <a href="javascript:history.go(-1)">返回</a>';
		exit;
	}
	
	$templist = str_replace("{L}",admin_sql($_POST["OP_Lang"]),admin_sql($_POST["OP_Templist"]));
	$tempview = str_replace("{L}",admin_sql($_POST["OP_Lang"]),admin_sql($_POST["OP_Tempview"]));
	
	if(substr($_POST["OP_Img"],0,4) == 'http')
	{
		$ourphp_xiegang = $_POST["OP_Img"];
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Img"]));
	}
			
	if (!empty($_POST["OP_Userright"])){
		$OP_Userright = implode(',',admin_sql($_POST["OP_Userright"]));
	}else{
		$OP_Userright = '0';
	}
	
	if (!empty($_POST["OP_Adminopen"])){
		$OP_Adminopen = implode(',',admin_sql($_POST["OP_Adminopen"]));
		if($OP_Adminopen == "00"){
			$OP_Adminopen = '00,01';
		}else{
			if(strstr($OP_Adminopen,"01")){
				$OP_Adminopen = $OP_Adminopen;
			}else{
				$OP_Adminopen = "01,".$OP_Adminopen;
			}
		}
	}else{
		$OP_Adminopen = '00,01';
	}
	
	if(empty($_POST["pl"])){
		
		//未批量
		plugsclass::logs('创建栏目:'.$_POST["OP_Columntitle"],$OP_Adminname);
		$db -> insert("`ourphp_column`","`OP_Uid` = '".admin_sql(intval($_POST["OP_Uid"]))."',`OP_Lang` = '".admin_sql($_POST["OP_Lang"])."',`OP_Columntitle` = '".admin_sql($_POST["OP_Columntitle"])."',`OP_Columntitleto` = '".admin_sql($_POST["OP_Columntitleto"])."',`OP_Model` = '".admin_sql($_POST["OP_Model"])."',`OP_Templist` = '".$templist."',`OP_Tempview` = '".$tempview."',`OP_Url` = '".admin_sql($_POST["OP_Url"])."',`OP_About` = '".admin_sql($_POST["OP_About"])."',`OP_About_wap` = '".admin_sql($_POST["OP_About_wap"])."',`OP_Hide` = '".admin_sql($_POST["OP_Hide"])."',`OP_Sorting` = '".admin_sql(intval($_POST["OP_Sorting"]))."',`OP_Briefing` = '".admin_sql($_POST["OP_Briefing"])."',`OP_Img` = '".$ourphp_xiegang."',`OP_Userright` = '".$OP_Userright."',`OP_Weight` = '".admin_sql($_POST["OP_Weight"])."',`OP_Adminopen` = '".admin_sql($OP_Adminopen)."'","");
		
		}else{
		
		plugsclass::logs('创建栏目:'.$_POST["OP_Columntitle_pl"],$OP_Adminname);
		$ourphp_fgbt = explode("|",admin_sql($_POST["OP_Columntitle_pl"]));
		foreach ($ourphp_fgbt as $op){
			$db -> insert("`ourphp_column`","`OP_Uid` = '".admin_sql(intval($_POST["OP_Uid"]))."',`OP_Lang` = '".admin_sql($_POST["OP_Lang"])."',`OP_Columntitle` = '".$op."',`OP_Columntitleto` = '".admin_sql($_POST["OP_Columntitleto"])."',`OP_Model` = '".admin_sql($_POST["OP_Model"])."',`OP_Templist` = '".$templist."',`OP_Tempview` = '".$tempview."',`OP_Url` = '".admin_sql($_POST["OP_Url"])."',`OP_About` = '".admin_sql($_POST["OP_About"])."',`OP_About_wap` = '".admin_sql($_POST["OP_About_wap"])."',`OP_Hide` = '".admin_sql($_POST["OP_Hide"])."',`OP_Sorting` = '".admin_sql(intval($_POST["OP_Sorting"]))."',`OP_Briefing` = '".admin_sql($_POST["OP_Briefing"])."',`OP_Img` = '".$ourphp_xiegang."',`OP_Userright` = '".$OP_Userright."',`OP_Weight` = '".admin_sql($_POST["OP_Weight"])."',`OP_Adminopen` = '".admin_sql($OP_Adminopen)."'","");
		}

	}
	
	
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_column.php';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){
		
	$templist = str_replace("{L}",admin_sql($_POST["OP_Lang"]),admin_sql($_POST["OP_Templist"]));
	$tempview = str_replace("{L}",admin_sql($_POST["OP_Lang"]),admin_sql($_POST["OP_Tempview"]));
	
	if(substr($_POST["OP_Img"],0,4) == 'http')
	{
		$ourphp_xiegang = $_POST["OP_Img"];
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Img"]));
	}
	
	if (!empty($_POST["OP_Userright"])){
		$OP_Userright = implode(',',admin_sql($_POST["OP_Userright"]));
	}else{
		$OP_Userright = '0';
	}
	
	if (!empty($_POST["OP_Adminopen"])){
		$OP_Adminopen = implode(',',admin_sql($_POST["OP_Adminopen"]));
		if($OP_Adminopen == "00"){
			$OP_Adminopen = '00,01';
		}else{
			if(strstr($OP_Adminopen,"01")){
				$OP_Adminopen = $OP_Adminopen;
			}else{
				$OP_Adminopen = "01,".$OP_Adminopen;
			}
			
		}
	}else{
		$OP_Adminopen = '00,01';
	}
	
		plugsclass::logs('修改栏目:'.$_POST["OP_Columntitle"],$OP_Adminname);
		$db -> update("`ourphp_column`","`OP_Uid` = '".admin_sql(intval($_POST["OP_Uid"]))."',`OP_Lang` = '".admin_sql($_POST["OP_Lang"])."',`OP_Columntitle` = '".admin_sql($_POST["OP_Columntitle"])."',`OP_Columntitleto` = '".admin_sql($_POST["OP_Columntitleto"])."',`OP_Model` = '".admin_sql($_POST["OP_Model"])."',`OP_Templist` = '".$templist."',`OP_Tempview` = '".$tempview."',`OP_Url` = '".admin_sql($_POST["OP_Url"])."',`OP_About` = '".admin_sql($_POST["OP_About"])."',`OP_About_wap` = '".admin_sql($_POST["OP_About_wap"])."',`OP_Hide` = '".admin_sql($_POST["OP_Hide"])."',`OP_Sorting` = '".admin_sql(intval($_POST["OP_Sorting"]))."',`OP_Briefing` = '".admin_sql($_POST["OP_Briefing"])."',`OP_Img` = '".$ourphp_xiegang."',`OP_Userright` = '".$OP_Userright."',`OP_Weight` = '".admin_sql($_POST["OP_Weight"])."',`OP_Adminopen` = '".admin_sql($OP_Adminopen)."'","where id = ".intval($_GET["id"]));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_column.php';
		require 'ourphp_remind.php';
			
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_column.php';
		require 'ourphp_remind.php';
		
	}
			
}

function Langlist(){
	global $db;
	$query = $db -> listgo("id,OP_Lang,OP_Font,OP_Default","`ourphp_lang`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"lang" => $ourphp_rs[1],
			"font" => $ourphp_rs[2],
			"default" => $ourphp_rs[3]
		);
	}
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

function Userleveto(){
	global $db;
	$query = $db -> listgo("OP_Userlevename","`ourphp_userleve`","order by id asc");
	$rows[] = '任何人';
	while($ourphp_rs = $db -> whilego($query)){
		$rows[] = $ourphp_rs[0];
	}
	return $rows;
}

function Adminleve(){
	global $db;
	$query = $db -> listgo("id,OP_Adminname","`ourphp_admin`","order by id asc");
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
$smarty->assign('arr',$arr);
Admin_click('创建网站栏目','ourphp_columnadd.php');
if (isset($_GET["ourphp_cms"])){
	$ourphp_rs = $db -> select("*","`ourphp_column`","where `id` = ".intval($_GET["id"]));
	$smarty -> assign("columnedit",$ourphp_rs);

	$ourphp_text = explode(",",$ourphp_rs['OP_Userright']);
	for($i = 0;$i < sizeof($ourphp_text);$i++){ 
		$selected[] = $ourphp_text[$i]; 
	}
	$smarty -> assign('selected',$selected); 
	$ourphph_qx = Userleveto(); 
	$smarty -> assign('ourphph_qx',$ourphph_qx);
}

$smarty->assign("langlist",Langlist());
$smarty->assign("userleve",Userleve());
$smarty->assign("adminleve",Adminleve());
$smarty->assign("webupdate",$homedeploy[1]);
$smarty->display('ourphp_columnadd.html');
?>