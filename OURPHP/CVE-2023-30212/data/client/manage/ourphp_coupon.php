<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2018 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';
include '../../function/ourphp_navigation.class.php';
include '../../function/ourphp_Tree.class.php';

if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){
	
	plugsclass::logs('创建优惠券',$OP_Adminname);
	$md = MD5($ourphp['safecode'].date("Y-m-d H:i:s"));
	$db -> insert("`ourphp_coupon`","`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Money` = ".admin_sql($_POST["OP_Money"]).",`OP_Timewin` = '".admin_sql($_POST["OP_Timewin"])."',`time` = '".date("Y-m-d H:i:s")."',`OP_Moneygo` = ".admin_sql($_POST["OP_Moneygo"]).",`OP_Content` = '".admin_sql($_POST["OP_Content"])."',`OP_Type` = ".intval($_POST["OP_Type"]).",`OP_Md` = '".$md."'","");

	$ourphp_font = 1;
	$ourphp_class = 'ourphp_coupon.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		plugsclass::logs('删除优惠券',$OP_Adminname);
		$db -> del("ourphp_coupon","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_coupon.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_coupon.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function couponlist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_coupon`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_coupon`"," order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
			$rows[] = array(
				"i" => $i,
				"id" => $ourphp_rs['id'],
				"title" => $ourphp_rs['OP_Title'],
				"money" => $ourphp_rs['OP_Money'],
				"timewin" => $ourphp_rs['OP_Timewin'],
				"moneygo" => $ourphp_rs['OP_Moneygo'],
				"content" => $ourphp_rs['OP_Content'],
				"type" => $ourphp_rs['OP_Type'],
				"md" => $ourphp_rs['OP_Md'],
				"time" => $ourphp_rs['time']
			);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$r = $db -> select("OP_Weburl","ourphp_web","where id = 1");
$smarty->assign("weburl",$r[0]);
Admin_click('优惠券管理','ourphp_coupon.php?id=ourphp');
$smarty->assign("coupon",couponlist());
$smarty->display('ourphp_coupon.html');
?>