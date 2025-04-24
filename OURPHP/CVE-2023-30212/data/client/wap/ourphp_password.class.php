<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

$ourphp_rs = '';
@$username = dowith_sql($_POST['OP_Useremail']);
$usernameno = $ourphp_adminfont['usernameno'];
$faqno = $ourphp_adminfont['faqno'];
$upok = $ourphp_adminfont['upok'];
$mobilecode = $ourphp_adminfont['mobilecode'];
$ValidateCode = $_SESSION["code"]; //验证码 没搞明白为毛要写在这里才可以兼容其它虚拟主机
@$vcode = $_SESSION['vcode'];
	
if(empty($_GET['user'])){
	
	echo '';
	
}elseif ($_GET['user'] == 'email'){

	$code = $ourphp_adminfont['code'];
	if ($_POST["code"] != $ValidateCode){
		exit("<script language=javascript> alert('".$code."');history.go(-1);</script>");
	}

	$ourphp_rs = $db -> select("`id`,`OP_Useremail`,`OP_Userproblem`,`OP_Usercode`,`OP_Usertel`","`ourphp_user`","WHERE `OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."'");
	if (!$ourphp_rs){
		exit("<script language=javascript> alert('".$usernameno."');history.go(-1);</script>");
	}
	
}elseif ($_GET['user'] == 'faq'){
	
	if($ourphp_usercontrol['telsms'] == 0)
	{
		
		$ourphp_rs = $db -> select("`id`,`OP_Useremail`,`OP_Useranswer`,`OP_Usercode`","`ourphp_user`","WHERE (`OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."') && OP_Useranswer = '".dowith_sql($_POST['OP_Useranswer'])."' && OP_Usercode = '".dowith_sql($_POST['OP_Usercode'])."'");
		if (!$ourphp_rs){
			exit("<script language=javascript> alert('".$faqno."');history.go(-2);</script>");
		}
		
	}else{
		
		if($vcode != $_POST['OP_Userpasscode']){
			exit("<script language=javascript> alert('".$mobilecode."');history.go(-2);</script>");
		}
		
		$ourphp_rs = $db -> select("`id`,`OP_Useremail`,`OP_Usertel`,`OP_Usercode`","`ourphp_user`","WHERE (`OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."') && OP_Usertel = '".dowith_sql($_POST['OP_Useretel'])."' && OP_Usercode = '".dowith_sql($_POST['OP_Usercode'])."'");
		if (!$ourphp_rs){
			exit("<script language=javascript> alert('".$usernameno."');history.go(-2);</script>");
		}
		
	}
	
}elseif ($_GET['user'] == 'ok'){
	
	if($ourphp_usercontrol['telsms'] == 0)
	{
		$ourphp_rs = $db -> select("`id`","`ourphp_user`","WHERE (`OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."') && OP_Useranswer = '".dowith_sql($_POST['OP_Useranswer'])."' && OP_Usercode = '".dowith_sql($_POST['OP_Usercode'])."'");
	}else{
		$ourphp_rs = $db -> select("`id`","`ourphp_user`","WHERE (`OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."') && OP_Usercode = '".dowith_sql($_POST['OP_Usercode'])."'");
	}
	
	if (!$ourphp_rs){
		exit("<script language=javascript> alert('".$usernameno."');history.go(-3);</script>");
	}
	
	$OP_Userpass = dowith_sql(substr(md5(md5($_REQUEST["OP_Userpass"])),0,16));
	$db -> update("`ourphp_user`","`OP_Userpass` = '".$OP_Userpass."'","WHERE `OP_Useremail` = '".$username."' || `OP_Usertel` = '".$username."'");
	
	exit ("<script language=javascript> alert('".$upok."');location.replace('".$ourphp['webpath']."client/wap/?".$ourphp_Language."-userlogin.html');</script>");
	
}elseif ($_GET['user'] == 'telcode'){

	if (empty($_GET['zh']) || empty($_GET['username'])){
		$msg = '-1';
		echo htmlspecialchars($_GET['jsoncallback']) . "(".json_encode($msg).")";
		exit;
	}
	
	$ourphp_rs = $db -> select("`id`","`ourphp_user`","WHERE (`OP_Useremail` = '".dowith_sql($_GET['username'])."' || `OP_Usertel` = '".dowith_sql($_GET['username'])."') && OP_Usertel = '".dowith_sql($_GET['zh'])."'");
	
	if (!$ourphp_rs){
		$msg = '-1';
		echo htmlspecialchars($_GET['jsoncallback']) . "(".json_encode($msg).")";
		exit;
	}
	
	$a = plugsclass::plugs(6);
	if(!$a)
	{
		$msg = '-1';
		echo htmlspecialchars($_GET['jsoncallback']) . "(".json_encode($msg).")";
		exit;
	}
	
	$ourphp_rs = $db -> select("`OP_Websitemin`","`ourphp_web`","where `id` = 1");
	include '../../function/api/telcode/user_regcode.class.php';
	$OP_Usertel = $_GET['zh'];
	$OP_Regcode = rand(1000,9999);
	$_SESSION['vcode'] = $OP_Regcode;
	$c = '找回密码中,验证码:'.$OP_Regcode.'请妥善保管不要告诉他人!'.$ourphp_rs[0];
	$s = '';
	$smskey -> smsconfig($OP_Usertel,$c,$s,1);
	$msg = '200';
	echo htmlspecialchars($_GET['jsoncallback']) . "(".json_encode($msg).")";
	exit;

}

$smarty->assign('faq',$ourphp_rs);
?>