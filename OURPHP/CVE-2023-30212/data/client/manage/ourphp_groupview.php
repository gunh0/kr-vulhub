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

if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}


function columncycle($cid = 1){
	global $db,$id;
	$newid = "0".$id;
	$ourphp_rs = $db -> select("`OP_Columntitle`","`ourphp_column`","where id = ".$cid." and (`OP_Adminopen` LIKE '%$newid%' or `OP_Adminopen` LIKE '00%')");
	if($ourphp_rs){
		return $ourphp_rs[0];
	}else{
		return false;
	}
}

function Productlist(){
	global $_page,$db,$smarty;
	$query = $db -> listgo("id,OP_Tuanid,OP_Tuanpid,OP_Tuanoid,OP_Tuanuser,time","`ourphp_tuanuserlist`","where OP_Tuanid = ".intval($_GET['id'])." order by id desc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$user = $db -> select("OP_Username","ourphp_user","where OP_Useremail = '".$ourphp_rs[4]."' || OP_Usertel = '".$ourphp_rs[4]."'");

			$oid = $oid . $ourphp_rs[3] . ",";
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"tuanid" => $ourphp_rs[1],
				"productid" => $ourphp_rs[2],
				"ordersid" => $ourphp_rs[3],
				"tuanuser" => $ourphp_rs[4],
				"time" => $ourphp_rs[5],
				"username" => $user[0],
				"oid" => rtrim($oid,","),
			);
		
		$i = $i + 1;
	}
	return $rows;
}

$ourphp_rs = $db -> select("*","`ourphp_tuan`","where `id` = ".intval($_GET['id']));
$user = $db -> select("OP_Username","ourphp_user","where OP_Useremail = '".$ourphp_rs['OP_Tuanuser']."' || OP_Usertel = '".$ourphp_rs['OP_Tuanuser']."'");
$pr = $db -> select("OP_Tuantime,OP_Webmarket,OP_Title","ourphp_product","where id = ".$ourphp_rs['OP_Tuanpid']);
$smarty->assign('ourphp_tuan',$ourphp_rs);
$smarty->assign('ourphp_user',$user);
$smarty->assign('ourphp_pr',$pr);
$smarty->assign("product",Productlist());
$smarty->display('ourphp_groupview.html');
?>