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

function Userlist(){
	global $_page,$db,$smarty;
	$name = dowith_sql($_GET['name']);
	$listpage = 40;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_userregreward`","where `OP_Useremail` = '".$name."'");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Useremail,OP_Usermoney,OP_Userintegral,OP_Userid,time,OP_Useremailto","`ourphp_userregreward`","where `OP_Useremail` = '".$name."' order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$user = $db -> select("OP_Useremail,OP_Username","ourphp_user","where OP_Usersource = '".$name."'");
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"email" => $ourphp_rs[1],
			"name" => $user[1],
			"money" => $ourphp_rs[2],
			"integral" => $ourphp_rs[3],
			"userid" => $ourphp_rs[4],
			"time" => $ourphp_rs[5],
			"emailto" => $ourphp_rs[6]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}


function Userlist2(){
	global $_page,$db,$smarty;
	$listpage = 40;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_userregreward`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Useremail,OP_Usermoney,OP_Userintegral,OP_Userid,time,OP_Useremailto","`ourphp_userregreward`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$user = $db -> select("OP_Useremail,OP_Username","ourphp_user","where OP_Usersource = '".$ourphp_rs[1]."'");
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"email" => $ourphp_rs[1],
			"name" => $user[1],
			"money" => $ourphp_rs[2],
			"integral" => $ourphp_rs[3],
			"userid" => $ourphp_rs[4],
			"time" => $ourphp_rs[5],
			"emailto" => $ourphp_rs[6]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

if($_GET['list'] == 1){
	$smarty->assign("Userlist",Userlist());
	$smarty->display('ourphp_userinvitation.html');
}else{
	$smarty->assign("Userlist",Userlist2());
	$smarty->display('ourphp_userinvitation2.html');
}

?>