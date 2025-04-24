<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';
	
}elseif ($_GET["ourphp_cms"] == "edit"){
	
	plugsclass::logs('选择模板',$OP_Adminname);
	$db -> update("`ourphp_temp`","`OP_Temppath` = '".admin_sql($_GET["temp"])."'","where id = 1");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_template.php?id=ourphp';
	require 'ourphp_remind.php';
	
}

function myscandir($path){

	$mydir=dir($path);
	
	$rows=array();
	while($file=$mydir->read()){
		$p=$path.'/'.$file;
		$f=mb_convert_encoding($p,"utf-8","gb2312");
		if(file_exists($f.'/Author.tpl')){
		$tempfile = $f.'/Author.tpl';
		}else{
		$tempfile = '../../templates/index.htm';
		}
		if(($file!=".") AND ($file!="..")){
			$rows[] = array('url'=>$f,'img'=>$f.'/index.jpg','author'=>$tempfile);
		}
	}  
	return $rows;
}
	
	
Admin_click('模板安装使用','ourphp_template.php?id=ourphp');
$ourphp_rs = $db -> select("*","`ourphp_temp`","where `id` = 1");
$smarty->assign('ourphp_temp',$ourphp_rs);
$smarty->assign("myscandir",myscandir('../../templates'));
$smarty->display('ourphp_template.html');
?>