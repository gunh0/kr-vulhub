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
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		plugsclass::logs('删除优惠券领取信息',$OP_Adminname);
		$db -> del("ourphp_couponlist","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_couponlist.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_couponlist.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function couponview($id = 0){
	global $db;
	$r = $db -> select("*","ourphp_coupon","where id = ".$id);
	return '
	<p>标题:'.$r['OP_Title'].'</p>
	<p>面值:'.$r['OP_Money'].'</p>
	<p>时限:'.$r['OP_Timewin'].'</p>
	<p>满限:'.$r['OP_Moneygo'].'</p>
	<p>描述:'.$r['OP_Content'].'</p>
	';
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
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_couponlist`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_couponlist`"," order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
			$rows[] = array(
				"i" => $i,
				"id" => $ourphp_rs['id'],
				"classid" => couponview($ourphp_rs['OP_Couponid']),
				"username" => $ourphp_rs['OP_Username'],
				"type" => $ourphp_rs['OP_Type'],
				"md" => $ourphp_rs['OP_Md'],
				"timewin" => $ourphp_rs['OP_Time'],
				"time" => $ourphp_rs['time']
			);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("coupon",couponlist());
$smarty->display('ourphp_couponlist.html');
?>