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

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除团购列表信息',$OP_Adminname);
		$db -> del("ourphp_tuan","where id = ".intval($_GET['id']));
		$db -> del("ourphp_tuanuserlist","where OP_Tuanid = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_grouplist.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_grouplist.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
			
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
	$listpage = 15;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_tuan`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Tuanpid,OP_Tuanoid,OP_Tuanuser,OP_Tuannum,OP_Tuanznum,time","`ourphp_tuan`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$pr = $db -> select("id,OP_Class,OP_Title,OP_Webmarket,OP_Minimg,OP_Tuantime","ourphp_product","where id = ".$ourphp_rs[1]);
		$user = $db -> select("OP_Username","ourphp_user","where OP_Useremail = '".$ourphp_rs[3]."' || OP_Usertel = '".$ourphp_rs[3]."'");
		$c = columncycle($pr[1]);

			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"productid" => $ourphp_rs[1],
				"ordersid" => $ourphp_rs[2],
				"tuanuser" => $ourphp_rs[3],
				"tuannum" => $ourphp_rs[4],
				"tuannumall" => $ourphp_rs[5],
				"time" => $ourphp_rs[6],
				"productclass" => $c,
				"producttitle" => $pr[2],
				"productmoney" => $pr[3],
				"productimg" => $pr[4],
				"producttuantime" => $pr[5],
				"username" => $user[0],
			);
		
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

Admin_click('团购管理','ourphp_grouplist.php?id=ourphp');
$smarty->assign("product",Productlist());
$smarty->display('ourphp_grouplist.html');
?>