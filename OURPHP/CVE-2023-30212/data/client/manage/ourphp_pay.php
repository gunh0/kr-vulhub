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

		$OP_Usermoney = !empty($_POST["OP_Usermoney"])?$_POST["OP_Usermoney"]:"0";
		$OP_Userintegral = !empty($_POST["OP_Userintegral"])?$_POST["OP_Userintegral"]:"0";
		$OP_Usercoin = !empty($_POST["OP_Usercoin"])?$_POST["OP_Usercoin"]:"0";
		$OP_Usercontent = !empty($_POST["OP_Usercontent"])?$_POST["OP_Usercontent"]:admin_sql($_POST["OP_Useradmin"]).'后台操作';
		
		$num = $db -> select("OP_Useremail","`ourphp_user`","WHERE `OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."' or `OP_Usertel` = '".admin_sql($_POST["OP_Useremail"])."'");
		if ($num == false){
		
			$ourphp_font = 5;
			$ourphp_img = 'no.png';
			$ourphp_content = '会员账户不存在！';
			$ourphp_class = 'ourphp_pay.php?id=ourphp';
			require 'ourphp_remind.php';
			
		}else{
			
			if($_POST['jj'] == 'a'){
				$db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` + '".$OP_Usermoney."',`OP_Userintegral` = `OP_Userintegral` + '".$OP_Userintegral."',`OP_Usercoin` = `OP_Usercoin` + '".$OP_Usercoin."'","where `OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."' or `OP_Usertel` = '".admin_sql($_POST["OP_Useremail"])."'");
				$jj = '+';
			}else{
				$db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` - '".$OP_Usermoney."',`OP_Userintegral` = `OP_Userintegral` - '".$OP_Userintegral."',`OP_Usercoin` = `OP_Usercoin` - '".$OP_Usercoin."'","where `OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."' or `OP_Usertel` = '".admin_sql($_POST["OP_Useremail"])."'");
				$jj = '-';
			}
			
		}

		plugsclass::logs('会员充值',$OP_Adminname);
		$db -> insert("`ourphp_userpay`","
			
			`OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."',
			`OP_Usermoney` = '".$OP_Usermoney."',
			`OP_Userintegral` = '".$OP_Userintegral."',
			`OP_Usercontent` = '".admin_sql($OP_Usercontent)."',
			`OP_Useradmin` = '".admin_sql($_POST["OP_Useradmin"])."',
			`OP_Uservoucherone` = '0',
			`OP_Uservouchertwo` = '0',
			`time` = '".date("Y-m-d H:i:s")."',
			`OP_Userpaytype`='".$jj."',
			`OP_Usercoin` = '".$OP_Usercoin."'

			","");
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_pay.php?id=ourphp';
		require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		plugsclass::logs('删除会员充值信息',$OP_Adminname);
		$db -> del("ourphp_userpay","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_pay.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_pay.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}

function paylist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_userpay`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Useremail,OP_Usermoney,OP_Userintegral,OP_Usercontent,OP_Useradmin,time,OP_Userpaytype,OP_Usercoin","`ourphp_userpay`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"email" => $ourphp_rs[1],
			"money" => $ourphp_rs[2],
			"integral" => $ourphp_rs[3],
			"content" => $ourphp_rs[4],
			"admin" => $ourphp_rs[5],
			"time" => $ourphp_rs[6],
			"paytype" => $ourphp_rs[7],
			"coin" => $ourphp_rs[8]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

Admin_click('会员充值','ourphp_pay.php?id=ourphp');
$smarty->assign("OP_Adminname",$OP_Adminname);
$smarty->assign("pay",paylist());
$smarty->display('ourphp_userpay.html');
?>