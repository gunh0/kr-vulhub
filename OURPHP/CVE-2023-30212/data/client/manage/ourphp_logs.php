<?php 
/*******************************************************************************
* OurPHP - CMS建站系统
* Copyright (C) 2022 www.ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';

function Logslist(){
	global $_page,$db,$smarty;
	$listpage = 50;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_logs`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_logs`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs["id"],
			"content" => $ourphp_rs["OP_Logscontent"],
			"account" => $ourphp_rs["OP_Logsaccount"],
			"ip" => $ourphp_rs["OP_Logsip"],
			"time" => $ourphp_rs["time"]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("Logslist",Logslist());
$smarty->display('ourphp_logs.html');
?>