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

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	$num = $db -> select("OP_Adminname","`ourphp_admin`","WHERE `OP_Adminname` = '".admin_sql($_POST["OP_Adminname"])."'");
	if ($num != false){
	
		$ourphp_font = 3;
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';
	
			}else{			

		if (!empty($_POST["OP_Adminpower"])){
		$OP_Adminpower = implode(',',admin_sql($_POST["OP_Adminpower"]));
		}else{
		$OP_Adminpower = '';
		}
		
		plugsclass::logs('创建新管理员',$OP_Adminname);
		$db -> insert("`ourphp_admin`","`OP_Adminname` = '".admin_sql($_POST["OP_Adminname"])."',`OP_Adminpass` = '".admin_sql(substr(md5(md5($_REQUEST["OP_Adminpass"])),0,16))."',`OP_Adminpower` = '".$OP_Adminpower."',`OP_Admin` = '".intval($_POST["OP_Admin"])."',`time` = '".date("Y-m-d H:i:s")."'","");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';
	}
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
	if (intval($_GET['id']) == 1){
		$ourphp_font = 4;
		$ourphp_content = '主管理员无法删除！';
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';			
	}
	
	plugsclass::logs('删除管理员账户',$OP_Adminname);
	$db -> del("`ourphp_admin`","where id = ".intval($_GET['id']));
	$ourphp_font = 2;
	$ourphp_class = 'ourphp_manage.php?id=ourphp';
	require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function Adminlist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_admin`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Adminname,OP_Adminpower,OP_Admin,time","`ourphp_admin`","order by id asc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"adminname" => $ourphp_rs[1],
			"power" => $ourphp_rs[2],
			"admin" => $ourphp_rs[3],
			"time" => $ourphp_rs[4]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("Adminlist",Adminlist());
$smarty->display('ourphp_manage.html');
?>