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
}elseif ($_GET["ourphp_cms"] == "edit"){

			if (!empty($_POST["OP_Usermoney"])){
				$OP_Usermoney = implode('|',$_POST["OP_Usermoney"]);
			}else{
				$OP_Usermoney = '0|0|0|0';
			}
			
			plugsclass::logs('设置会员选项',$OP_Adminname);
			$db -> update("`ourphp_usercontrol`","`OP_Userreg` = '".intval($_POST["OP_Userreg"])."',`OP_Userlogin` = '".intval($_POST["OP_Userlogin"])."',`OP_Userprotocol` = '".admin_sql(compress_html($_POST["OP_Userprotocol"]))."',`OP_Usergroup` = '".intval($_POST["OP_Usergroup"])."',`OP_Usermoney` ='".$OP_Usermoney."',`OP_Useripoff` ='".intval($_POST["OP_Useripoff"])."',`time` = '".date("Y-m-d H:i:s")."',`OP_Regtyle` = '".admin_sql($_POST["OP_Regtyle"])."',`OP_Regcode` = '".intval($_POST["OP_Regcode"])."',`OP_Userpassgo` = ".intval($_POST["OP_Userpassgo"]).",`OP_Coinset` = '".admin_sql($_POST["OP_Coinset"])."',`OP_Withdrawal` = '".admin_sql($_POST["OP_Withdrawal"])."'","where id = 1");
			$ourphp_font = 1;
			$ourphp_class = 'ourphp_usercontrol.php?id=ourphp';
			require 'ourphp_remind.php';
}
function Userleve(){
	global $db;
	$query = $db -> listgo("id,OP_Userlevename","`ourphp_userleve`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1]
		);
	}
	return $rows;
}

Admin_click('会员选项','ourphp_usercontrol.php?id=ourphp');
$ourphp_rs = $db -> select("*","`ourphp_usercontrol`","where `id` = 1");
$smarty->assign('ourphp_usercontrol',$ourphp_rs);
$OP_Usermoney = explode('|',$ourphp_rs["OP_Usermoney"]);
$smarty->assign('ourphp_Usermoney',$OP_Usermoney);
$smarty->assign("userleve",Userleve());
$smarty->display('ourphp_usercontrol.html');
?>