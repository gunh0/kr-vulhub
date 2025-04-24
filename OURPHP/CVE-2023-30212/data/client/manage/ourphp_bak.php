<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

function myscandir($path){

	$mydir=dir($path);
	
	$rows=array();
	$i = 1;
	while($file=$mydir->read()){
		$p=$path.'/'.$file;
	
		if(($file!=".") AND ($file!="..")){
			$rows[] = array('i'=>$i,'url'=>mb_convert_encoding($p,"utf-8","gb2312"));
		}
		
	$i = $i + 1;
	}  
	return $rows;
}

Admin_click('数据库操作','ourphp_bak.php?id=ourphp');
$smarty->assign("myscandir",myscandir('../../function/backup'));
$smarty->display('ourphp_bak.html');
?>