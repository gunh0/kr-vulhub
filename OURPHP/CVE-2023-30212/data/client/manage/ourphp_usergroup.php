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
			
		if($_POST["OP_Userlevename"] == ''){
		
				$ourphp_font = 4;
				$ourphp_content = '不能为空!';
				$ourphp_class = 'ourphp_usergroup.php?id=ourphp';
				require 'ourphp_remind.php';
		
			}else{

				plugsclass::logs('编辑会员组',$OP_Adminname);
				$db -> update("`ourphp_userleve`","`OP_Userlevename` = '".admin_sql($_POST["OP_Userlevename"])."',`OP_Userweight` = '".admin_sql($_POST["OP_Userweight"])."',`OP_Useropen` = '".admin_sql($_POST["OP_Useropen"])."'","where id = ".intval($_GET['id']));
				$ourphp_font = 1;
				$ourphp_class = 'ourphp_usergroup.php?id=ourphp';
				require 'ourphp_remind.php';
		}
			
	}else{
				
			$ourphp_font = 4;
			$ourphp_content = '权限不够，无法编辑内容！';
			$ourphp_class = 'ourphp_usergroup.php?id=ourphp';
			require 'ourphp_remind.php';
				
	}
}

function Userleve(){
	global $db;
	$query = $db -> listgo("id,OP_Userlevename,OP_Userweight,OP_Useropen","`ourphp_userleve`","order by id asc");
	$rows = array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"weight" => $ourphp_rs[2],
			"open" => $ourphp_rs[3],
		);
	}
	return $rows;
}

Admin_click('用户组管理','ourphp_usergroup.php?id=ourphp');
$smarty->assign("Userleve",Userleve());
$smarty->display('ourphp_usergroup.html');
?>