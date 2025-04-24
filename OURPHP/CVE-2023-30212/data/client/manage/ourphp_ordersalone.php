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

if(intval($_GET['page']) == 0)
{
	$page = 1;
}else{
	$page = intval($_GET['page']);
}

if(isset($_GET["ourphp_cms"]) == ""){
	
	$keyword = '';
	
}elseif ($_GET["ourphp_cms"] == "Batch"){

	if (strstr($OP_Adminpower,"35")){
	
		if (!empty($_POST["op_b"])){
		$op_b = implode(',',admin_sql($_POST["op_b"]));
		}else{
		$op_b = '';
		}

		plugsclass::logs('批量删除订单(单)',$OP_Adminname);
		$query = $db -> del("ourphp_orders","where id in ($op_b)");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_ordersalone.php?id=ourphp&page='.$page;
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_ordersalone.php?id=ourphp&page='.$page;
		require 'ourphp_remind.php';
		
	}	
}elseif ($_GET["ourphp_cms"] == "search"){
	
	if($_POST['content'] == '' || $_POST['content'] == '输入会员账号或商品订单号搜索TA的订单'){
		exit("<script language=javascript> alert('输入正确的搜索关键词');location.replace('ourphp_ordersalone.php?id=ourphp&page=".$page."');</script>");
	}
	
	$keyword = $_POST['content'];
	
}


function Orderslist($keyword = ''){
	global $_page,$db,$smarty;
	
	if($keyword == '' || $keyword == null){
		$where = '';
	}else{
		$where = "where `OP_Ordersemail` like '%".admin_sql($keyword)."%' or `OP_Ordersnumber` like '%".admin_sql($keyword)."%' ";
	}
	
	$listpage = 40;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`",$where);
	$ourphptotal = $db -> whilego($ourphptotal);
	
	$query = $db -> listgo("`id`,`OP_Ordersname`,`time`,`OP_Ordersnumber`,`OP_Orderspay`,`OP_Orderssend`,`OP_Ordersgotime`,`OP_Integralok`,`OP_Sign`,`OP_Usermoneyback`","`ourphp_orders`",$where."order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"title" => $ourphp_rs[1],
			"time" => $ourphp_rs[2],
			"number" => $ourphp_rs[3],
			"pay" => $ourphp_rs[4],
			"send" => $ourphp_rs[5],
			"gotime" => $ourphp_rs[6],
			"integralok" => $ourphp_rs[7],
			"sign" => $ourphp_rs[8],
			"moneyback" => $ourphp_rs[9],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
	
}

Admin_click('订单管理','ourphp_orders.php?id=ourphp');
$smarty->assign("orders",Orderslist($keyword));
$smarty->display('ourphp_ordersalone.html');
?>