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

if(isset($_GET['type'])){
	$a = $_GET['type'];
	$b = $_GET['couponclass'];
	$smarty->assign("a",array($a,$b));
}else{
	$a = 'a';
	$b = 0;
	$smarty->assign("a",array($a,$b));
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	$num = $db -> select("OP_Useremail","`ourphp_user`","WHERE `OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."'");
	if ($num){
	
		$ourphp_font = 3;
		$ourphp_class = 'ourphp_user.php?id=ourphp';
		require 'ourphp_remind.php';
	
			}else{			

			if (admin_sql($_POST["OP_Userpass"]) == ''){
				$OP_Userpass = admin_sql($_POST["password"]);
			}else{
			
				if (admin_sql($_POST["OP_Userpass"]) != admin_sql($_POST["OP_Userpassto"])){
					$ourphp_font = 4;
					$ourphp_content = '两次密码输入的不一样，请重新操作！';
					$ourphp_class = 'ourphp_user.php?id=ourphp';
					require 'ourphp_remind.php';
				}
			$OP_Userpass = admin_sql(substr(md5(md5($_REQUEST["OP_Userpass"])),0,16));
			}
			
			plugsclass::logs('添加新会员',$OP_Adminname);
			$randcode = rand(11111,99999);
			$db -> insert("`ourphp_user`","`OP_Userclass` = '".intval($_POST["OP_Userclass"])."',`OP_Userstatus` = '".intval($_POST["OP_Userstatus"])."',`OP_Useremail` = '".admin_sql($_POST["OP_Useremail"])."',`OP_Userpass` = '".$OP_Userpass."',`OP_Username` = '".admin_sql($_POST["OP_Username"])."',`OP_Usertel` = '".admin_sql($_POST["OP_Usertel"])."',`OP_Userqq` = '".admin_sql($_POST["OP_Userqq"])."',`OP_Useraliww` = '".admin_sql($_POST["OP_Useraliww"])."',`OP_Userskype` = '".admin_sql($_POST["OP_Userskype"])."',`OP_Useradd` = '".admin_sql($_POST["OP_Useradd"])."',`OP_Usersource` = '".admin_sql($_POST["OP_Usersource"])."',`OP_Userhead` = '".admin_sql($_POST["OP_Userhead"])."',`OP_Userip` = '".admin_sql($_POST["OP_Userip"])."',`OP_Userproblem` = '".admin_sql($_POST["OP_Userproblem"])."',`OP_Useranswer` = '".admin_sql($_POST["OP_Useranswer"])."',`OP_Usertext` = '".admin_sql($_POST["OP_Usertext"])."',`OP_Usercode` = '".randomkeys(18)."',`time` = '".date("Y-m-d H:i:s")."'","");
			$newid = $db -> insertid();
			$db -> update("ourphp_user","OP_Userregcode = ".$newid.$randcode,"where id = ".$newid);
			
			$ourphp_font = 1;
			$ourphp_class = 'ourphp_user.php?id=ourphp';
			require 'ourphp_remind.php';
			}
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
	
		plugsclass::logs('删除会员',$OP_Adminname);
		$db -> del("ourphp_user","where id = ".intval($_GET['id']));
		$weixintable = $db -> create("SHOW TABLES LIKE 'ourphp_p_weixinlogin'",2);
		if($db -> rows($weixintable) >= 1)
		{
			$db -> del("ourphp_p_weixinlogin","where userid = ".intval($_GET['id']));
		}
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_user.php?id=ourphp';
		require 'ourphp_remind.php';

				
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_user.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
	
}elseif ($_GET["ourphp_cms"] == "Batch"){ //发放优惠券
		

	if (strstr($OP_Adminpower,"34")){
	
		if (!empty($_POST["op_b"])){
		$op_b = admin_sql($_POST["op_b"]);
		}else{
		$op_b = array();
		}
		
		$md = $db -> select("OP_Md,OP_Timewin,OP_Moneygo","ourphp_coupon","where id = ".intval($_GET['couponclass']));
		foreach($op_b as $op){
			$coupon = $db -> insert("ourphp_couponlist","
			`OP_Couponid` =	".intval($_GET['couponclass']).",
			`OP_Username` =	'".dowith_sql($op)."',
			`OP_Type` =	0,
			`OP_Timewin` =	'".$md[1]."',
			`OP_Moneygo` =	'".$md[2]."',
			`OP_Md` =	'".$md[0]."',
			`OP_Time` =	'',
			`time` =	'".date("Y-m-d H:i:s")."'
			","");
		}
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_user.php?type=coupon&couponclass='.intval($_GET['couponclass']);
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_user.php?type=coupon&couponclass='.intval($_GET['couponclass']);
		require 'ourphp_remind.php';
		
	}	
	
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

function Userproblem(){
	global $db;
	$query = $db -> listgo("id,OP_Userproblem","`ourphp_userproblem`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1]
		);
	}
	return $rows;
}

function Userlist(){
	global $_page,$db,$smarty;
	$listpage = 40;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_user`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Useremail,OP_Username,OP_Usermoney,OP_Userintegral,OP_Userip,OP_Userstatus,time,OP_Usersource,OP_Usercoin,`OP_Userclass`,`OP_Userregcode`","`ourphp_user`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$ourphp_userclass = $db -> select("`OP_Useropen`,`OP_Userlevename`","ourphp_userleve","where id = ".intval($ourphp_rs[10]));
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"email" => $ourphp_rs[1],
			"class" => $ourphp_userclass[1],
			"name" => $ourphp_rs[2],
			"money" => $ourphp_rs[3],
			"integral" => $ourphp_rs[4],
			"ip" => $ourphp_rs[5],
			"status" => $ourphp_rs[6],
			"time" => $ourphp_rs[7],
			"source" => $ourphp_rs[8],
			"coin" => $ourphp_rs[9],
			"regcode" => $ourphp_rs[11]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

Admin_click('会员管理','ourphp_user.php?id=ourphp');
$ourphp_rs = $db -> select("*","`ourphp_usercontrol`","where `id` = 1");
$smarty->assign('ourphp_usercontrol',$ourphp_rs);
$smarty->assign('Userleve',Userleve());
$smarty->assign('Userproblem',Userproblem());
$smarty->assign("Userlist",Userlist());
$smarty->display('ourphp_user.html');
?>