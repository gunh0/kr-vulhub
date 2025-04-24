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

	if (strstr($OP_Adminpower,"34")){
			
		if (admin_sql($_POST["OP_Adminname"]) == admin_sql($_POST["name"])){
		
			echo '';
		
		}else{
			$num = $db -> select("OP_Adminname","`ourphp_admin`","WHERE `OP_Adminname` = '".admin_sql($_POST["OP_Adminname"])."'");
			if ($num != false){
				
				$ourphp_font = 3;
				$ourphp_class = 'ourphp_manage.php?id=ourphp';
				require 'ourphp_remind.php';
				
			}else{
			
				if (admin_sql($_POST["power"]) == 1){
				echo '<script language=javascript> alert("请重新用{'.$_POST["OP_Adminname"].'}登录！");</script>';
				unset($_SESSION['ourphp_adminname']);
				}
				
			}
		}
		
		if (admin_sql($_POST["power"]) == 1){
			$OP_Adminpower = '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60';
		}else{
		
			if (!empty($_POST["OP_Adminpower"])){
			$OP_Adminpower = implode(',',admin_sql($_POST["OP_Adminpower"]));
			}else{
			$OP_Adminpower = '';
			}
			
		}
		
		if (admin_sql($_POST["OP_Adminpass"]) == ''){
			$OP_Adminpass = admin_sql($_POST["password"]);
		}else{
		
		if (admin_sql($_POST["OP_Adminpass"]) != admin_sql($_POST["OP_Adminpassto"])){
			$ourphp_font = 4;
			$ourphp_content = '两次密码输入的不一样，请重新操作！';
			$ourphp_class = 'ourphp_manage.php?id=ourphp';
			require 'ourphp_remind.php';
		}
		$OP_Adminpass = admin_sql(substr(md5(md5($_REQUEST["OP_Adminpass"])),0,16));
		
		}
		
		plugsclass::logs('编辑管理员账户',$OP_Adminname);
		$db -> update("`ourphp_admin`","`OP_Adminname` = '".admin_sql($_POST["OP_Adminname"])."',`OP_Adminpass` = '".$OP_Adminpass."',`OP_Adminpower` = '".$OP_Adminpower."',`OP_Admin` = '".intval($_POST["power"])."'","where id = ".intval($_GET['id']));
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';

	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_manage.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
}

$ourphp_rs = $db -> select("*","`ourphp_admin`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_admin',$ourphp_rs);
$smarty->display('ourphp_manageview.html');
?>