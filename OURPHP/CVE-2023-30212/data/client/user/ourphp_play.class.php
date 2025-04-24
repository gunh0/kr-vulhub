<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
include '../../config/ourphp_code.php';
include '../../config/ourphp_config.php';
include '../../config/ourphp_Language.php';
include '../../function/ourphp_function.class.php';

function ourphp_usercontrol(){
	global $db;
	$ourphp_rs = $db -> select("a.`OP_Userreg`,a.`OP_Userlogin`,a.`OP_Usergroup`,a.`OP_Usermoney`,a.`OP_Useripoff` ,b.`OP_Ucenter`,a.`OP_Withdrawal`","`ourphp_usercontrol` a , `ourphp_webdeploy` b","where a.`id` = 1 && b.`id` = 1");
	$rows = array(
		'regoff' => $ourphp_rs[0],
		'loginoff' => $ourphp_rs[1],
		'group' => $ourphp_rs[2],
		'money' => explode("|",$ourphp_rs[3]),
		'ipoff' => $ourphp_rs[4],
		'ucenter' => $ourphp_rs[5],
		'withdrawal' => $ourphp_rs[6],
	);
	return $rows;
}

session_start();
date_default_timezone_set('Asia/Shanghai');
$ValidateCode = $_SESSION["code"]; //验证码 没搞明白为毛要写在这里才可以兼容其它虚拟主机
@$RegValidateCode = $_SESSION["vcode"];

$ourphp_usercontrol = ourphp_usercontrol();
$inputno = $ourphp_adminfont['inputno'];
$code = $ourphp_adminfont['code'];
$passwordto = $ourphp_adminfont['passwordto'];
$regyes = $ourphp_adminfont['regyes'];
$usernameyes = $ourphp_adminfont['usernameyes'];
$userip = $ourphp_adminfont['userip'];
$userloginno = $ourphp_adminfont['userloginno'];
$upok = $ourphp_adminfont['upok'];
$usernameno = $ourphp_adminfont['usernameno'];
$mailsend = $ourphp_adminfont['mailsend'];
$accessno = $ourphp_adminfont['accessno'];
$mobilecode = $ourphp_adminfont['mobilecode'];
$nophone = $ourphp_adminfont['nophone'];

//处理注册用户
if(empty($_GET["ourphp_cms"])){

	exit('no!');
	
}elseif($_GET["ourphp_cms"] == 'reg'){

	// 验证开始
	$ourphp_rs = $db -> select("`OP_Userreg`,`OP_Userlogin`,`OP_Userprotocol`,`OP_Usergroup`,`OP_Usermoney`,`OP_Useripoff`,`OP_Regtyle`,`OP_Regcode`","`ourphp_usercontrol`","where `id` = 1");
	
	if($ourphp_usercontrol['regoff'] == 2){
		exit('no!');
	}
	
	if($ourphp_rs[6] == 'email'){
		$userloginemail = $_POST["OP_Useremail"];
		$userlogintel = $_POST["OP_Useremail"];
		if ($userloginemail == '' || $_POST["OP_Userpass"] == '' || $_POST["OP_Userpass2"] == ''){
			exit("<script language=javascript> alert('".$inputno."');history.go(-1);</script>");
		}elseif(strlen($userloginemail) > 50){
			exit("<script language=javascript> alert('".$usernameyes."');history.go(-1);</script>");
		}
		$emailvar = filter_var($userloginemail, FILTER_VALIDATE_EMAIL);
		if(!$emailvar){
			exit("<script language=javascript> alert('".$accessno."');history.go(-1);</script>");
		}
	}elseif($ourphp_rs[6] == 'tel'){
		$userloginemail = $_POST["OP_Usertel"];
		$userlogintel = $_POST["OP_Usertel"];
		if ($userlogintel == '' || $_POST["OP_Userpass"] == '' || $_POST["OP_Userpass2"] == ''){
			exit("<script language=javascript> alert('".$inputno."');history.go(-1);</script>");
		}elseif(strlen($userlogintel) > 11){
			exit("<script language=javascript> alert('".$usernameyes."');history.go(-1);</script>");
		}
	}
	
	if($ourphp_rs[7] == 1){
		
		if ($_POST["vcode"] == ''){
			exit("<script language=javascript> alert('".$code."');history.go(-1);</script>");
		}
		if ($_POST["vcode"] != $RegValidateCode){
			exit("<script language=javascript> alert('".$code."');history.go(-1);</script>");
		}
		
	}else{
		
		if ($_POST["code"] != $ValidateCode){
			exit("<script language=javascript> alert('".$code."');history.go(-1);</script>");
		}
		
	}
	
	if ($_POST["OP_Userpass"] != $_POST["OP_Userpass2"]){
		exit("<script language=javascript> alert('".$passwordto."');history.go(-1);</script>");
	}

	$query = $db -> select("OP_Useremail","`ourphp_user`","WHERE `OP_Useremail` = '".dowith_sql($userloginemail)."' || `OP_Usertel` = '".dowith_sql($userlogintel)."'");
	if ($query){
	
		exit("<script language=javascript> alert('".$usernameyes."');history.go(-1);</script>");
	
	}else{
			
		if ($ourphp_usercontrol['ipoff'] == 1){
			$query = $db -> select("id","`ourphp_user`","WHERE `OP_Userip` = '".dowith_sql($_POST["ip"])."'");
			if ($query){
				exit("<script language=javascript> alert('".$userip."');history.go(-1);</script>");
			}
		}
		
		if(dowith_sql($_POST["introducer"]) == ''){

			$introducer = '';

		}else{

			$ourphp_rs = $db -> select("`OP_Useremail`","`ourphp_user`","WHERE `id` = ".intval($_POST["introducer"]));
			if ($ourphp_rs){

				$db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` + ".$ourphp_usercontrol['money'][2].",`OP_Userintegral` = `OP_Userintegral` + ".$ourphp_usercontrol['money'][3],"where id = ".intval($_POST["introducer"]));

				$finance = new userplus\regmoney();
				$finance -> regaddmoney($ourphp_rs[0],$userloginemail,$ourphp_usercontrol['money'][2],$ourphp_usercontrol['money'][3]);
				$introducer = $ourphp_rs[0];

			}else{

				$introducer = '';

			}

		}
		
		$randcode = rand(11111,99999);
		$reg_username = "昵称".$randcode;
		$db -> insert("`ourphp_user`","`OP_Useremail` = '".dowith_sql($userloginemail)."',`OP_Username` = '".dowith_sql($reg_username)."',`OP_Userpass` = '".dowith_sql(substr(md5(md5($_REQUEST["OP_Userpass"])),0,16))."',`OP_Usertel` = '".dowith_sql($userlogintel)."',`OP_Userclass` = '".$ourphp_usercontrol['group']."',`OP_Usersource` = '".$introducer."',`OP_Usermoney` = '".$ourphp_usercontrol['money'][0]."',`OP_Userintegral` = '".$ourphp_usercontrol['money'][1]."',`OP_Userip` = '".dowith_sql($_POST["ip"])."',`OP_Userstatus` = 1,`OP_Usercode` = '".randomkeys(18)."',`time` = '".date("Y-m-d H:i:s")."'","");
		$newid = $db -> insertid();
		$db -> update("ourphp_user","OP_Userregcode = ".$newid.$randcode,"where id = ".$newid);
		
		//处理Ucenter
		if($ourphp_usercontrol['ucenter'] == 1){
		
				include_once '../../config.inc.php';
				include_once '../../uc_client/client.php';
				$OP_Useremail = dowith_sql($_POST["OP_Useremail"]);
				$OP_Userpass = dowith_sql($_REQUEST["OP_Userpass"]);
				$OP_Username = dowith_sql($reg_username);
				
				$uid = uc_user_register($OP_Username, $OP_Userpass, $OP_Useremail);
				if ($uid <= 0) {
					if ($uid == -1) {
						exit("<script language=javascript> alert('姓名不合法');history.go(-1);</script>");
					} elseif ($uid == -2) {
						exit("<script language=javascript> alert('包含要允许注册的词语');history.go(-1);</script>");
					} elseif ($uid == -3) {
						exit("<script language=javascript> alert('姓名已经存在');history.go(-1);</script>");
					} elseif ($uid == -4) {
						exit("<script language=javascript> alert('Email 格式有误');history.go(-1);</script>");
					} elseif ($uid == -5) {
						exit("<script language=javascript> alert('Email 不允许注册');history.go(-1);</script>");
					} elseif ($uid == -6) {
						exit("<script language=javascript> alert('该 Email 已经被注册');history.go(-1);</script>");
					} else {
						echo '未定义';
					}
				} else {
					echo ''; //注册成功
				}
				
		}
		//注册成功，邮件提醒
		$ourphp_rs = $db -> select("`OP_Regtyle`","`ourphp_usercontrol`","where `id` = 1"); 
		if($ourphp_rs[0] == 'email'){
			$ourphp_mail = 'reguser';
			$OP_Useremail = dowith_sql($userloginemail);
			$OP_Userpass = dowith_sql($_POST["OP_Userpass"]);
			$OP_Username = dowith_sql($reg_username);
			include '../../function/ourphp_mail.class.php';
		}
		
		echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-login.html','?'.$_GET["lang"].'-userlogin.html',0,'');
		exit;
}

			
//处理会员登录
}elseif($_GET["ourphp_cms"] == 'login'){
	
	if($ourphp_usercontrol['loginoff'] == 2){
		exit('no!');
	}

	$loginerror = $ourphp_adminfont['loginerror'];
	$ourphp_rs = $db -> select("`id`,`OP_Useremail`,`OP_Userpass`,`OP_Userstatus`,`OP_Username`,`OP_Userclass`","`ourphp_user`","WHERE (`OP_Useremail` = '".dowith_sql($_POST["OP_Useremail"])."' || `OP_Usertel` = '".dowith_sql($_POST["OP_Useremail"])."') and `OP_Userpass` = '".dowith_sql(substr(md5(md5($_REQUEST["OP_Userpass"])),0,16))."'");
	if (!$ourphp_rs){
	
		exit("<script language=javascript> alert('".$loginerror."');history.go(-1);</script>");
		
	}else{
		
		$userclass = $db -> select("OP_Useropen","ourphp_userleve","where id = ".intval($ourphp_rs[5]));
		if($ourphp_rs[3] == 2 || $userclass[0] == 1){
			exit("<script language=javascript> alert('".$userloginno."');history.go(-1);</script>");
		}
		
		$_SESSION['userid'] = $ourphp_rs[0];
		$_SESSION['username'] = $ourphp_rs[1];
		$_SESSION['name'] = $ourphp_rs[4];
		
		
		//处理Ucenter
		if($ourphp_usercontrol['ucenter'] == 1){
			include_once '../../config.inc.php';
			include_once '../../uc_client/client.php';
			$OP_Userpass = dowith_sql($_REQUEST["OP_Userpass"]);
			$OP_Username = $ourphp_rs[4];
			
			list($uid, $username, $password, $email) = uc_user_login($OP_Username, $OP_Userpass);
			if($uid > 0) {
				//echo '登录成功'.$uid;
				echo uc_user_synlogin($uid);
			} elseif($uid == -1) {
				//echo '用户不存在,或者被删除';
			} elseif($uid == -2) {
				//echo '密码错';
			} else {
				//echo '未定义';
			}
		}
		
		if(isset($_SESSION['ourphp_weburlupgo'])){
			if($_GET['type'] == '' || $_GET['type'] == 'pc'){
				echo "<script language=javascript>location.replace('".$ourphp['webpath'].$_SESSION['ourphp_weburlupgo']."');</script>";
			}else{
				echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/".$_SESSION['ourphp_weburlupgo']."');</script>";
			}
		}else{
			echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-index.html','?'.$_GET["lang"].'-usercenter.html',0,'');
		}
		exit;
	}
		
	
//退出
}elseif($_GET["ourphp_cms"] == 'out'){

	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['introducer_userid']);
	
	//处理Ucenter
	if($ourphp_usercontrol['ucenter'] == 1){
			include_once '../../config.inc.php';
			include_once '../../uc_client/client.php';
			echo uc_user_synlogout();
	}
	echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-login.html','?'.$_GET["lang"].'-userlogin.html',0,'');
	exit;
	
//修改资料
}elseif($_GET["ourphp_cms"] == 'edit'){


	if ($_POST["OP_Username"] == '' || $_POST["OP_Usertel"] == '' || $_POST["OP_Useranswer"] == ''){
		
		exit("<script language=javascript> alert('".$inputno."');history.go(-1);</script>");

	}elseif ($_POST["OP_Userpass"] != $_POST["OP_Userpass2"]){
		
		exit("<script language=javascript> alert('".$passwordto."');history.go(-1);</script>");

	}

	if ($_POST["OP_Userpass"] == '' && $_POST["OP_Userpass2"] == ''){
		$password = dowith_sql($_POST["password"]);
	}else{
		$password = dowith_sql(substr(md5(md5($_POST["OP_Userpass"])),0,16));
	}
	
	$query = $db -> select("OP_Useremail","`ourphp_user`","WHERE `OP_Useremail` != '".$_SESSION['username']."' && (`OP_Useremail` = '".dowith_sql($_POST["OP_Usertel"])."'  || `OP_Usertel` = '".dowith_sql($_POST["OP_Usertel"])."')");
	if ($query){
		
		exit("<script language=javascript> alert('".$nophone."');history.go(-1);</script>");
		
	}else{
		
		$db -> update("`ourphp_user`","`OP_Userpass` = '".$password."',`OP_Username` = '".dowith_sql($_POST["OP_Username"])."',`OP_Usertel` = '".dowith_sql($_POST["OP_Usertel"])."',`OP_Userqq` = '".dowith_sql($_POST["OP_Userqq"])."',`OP_Userskype` = '".dowith_sql($_POST["OP_Userskype"])."',`OP_Useraliww` = '".dowith_sql($_POST["OP_Useraliww"])."',`OP_Useradd` = '".dowith_sql($_POST["OP_Useradd"])."',`OP_Usertext` = '".dowith_sql($_POST["OP_Usertext"])."',`OP_Usercode` = '".randomkeys(18)."',`OP_Useranswer` = '".dowith_sql($_POST["OP_Useranswer"])."'","WHERE `OP_Useremail` = '".$_SESSION['username']."' and `OP_Usercode` = '".dowith_sql($_POST["usercode"])."'");
		
	}
	
	echo  @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-useredit.html','?'.$_GET["lang"].'-useredit.html',0,'');
	exit;

//处理站内邮件
}elseif($_GET["ourphp_cms"] == 'mail'){

	$query = $db -> select("id","`ourphp_user`","WHERE `OP_Useremail` = '".dowith_sql($_POST["OP_Usercollect"])."' || `OP_Usertel` = '".dowith_sql($_POST["OP_Usercollect"])."'");
	if (!$query){
		
		exit("<script language=javascript> alert('".$usernameno."');history.go(-1);</script>");
		
	}else{
		
		if (dowith_sql($_POST["OP_Usercollect"]) == $_SESSION['username']){
			exit("<script language=javascript> alert('".$accessno."');history.go(-1);</script>");
		}
		
		$db -> insert("`ourphp_usermessage`","`OP_Usersend` = '".$_SESSION['username']."',`OP_Usercollect` = '".dowith_sql($_POST["OP_Usercollect"])."',`OP_Usercontent` = '".dowith_sql($_POST["OP_Usercontent"])."',`time` = '".date("Y-m-d H:i:s")."'","");

		echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-usermail-op.html','?'.$_GET["lang"].'-usermail-op.html',0,'');
		exit;
		
	}
}elseif($_GET["ourphp_cms"] == 'integral'){

	$ourphp_rs = $db -> select("`id`,`OP_Iintegral`,`OP_Iid`","`ourphp_integral`","WHERE `id` = '".intval($_GET["id"])."' && OP_Iuseremail = '".$_SESSION['username']."' && `OP_Iconfirm` = 0");
	if (!$ourphp_rs){
		
		exit("<script language=javascript> alert('".$accessno."');history.go(-1);</script>");
				
	}else{
		
		$db -> update("`ourphp_integral`","`OP_Iconfirm` = 1,`OP_ITime` = '".date("Y-m-d H:i:s")."'","where `id` = '".intval($_GET["id"])."' && OP_Iuseremail = '".$_SESSION['username']."'");
		$db -> update("`ourphp_user`","`OP_Userintegral` = `OP_Userintegral` + ".$ourphp_rs[1]."","where `OP_Useremail` = '".$_SESSION['username']."'");
		$db -> insert("`ourphp_userpay`","`OP_Useremail` = '".$_SESSION['username']."',`OP_Usermoney` = 0,`OP_Userintegral` = '".$ourphp_rs[1]."',`OP_Usercontent` = '领取商品赠送积分<br />商品id:".$ourphp_rs[2]."',`OP_Useradmin` = '".$_SESSION['username']."',`time` = '".date("Y-m-d H:i:s")."'","");
		
		echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-userintegral-op.html','?'.$_GET["lang"].'-userintegral-op.html',0,'');
		exit;
		
	}
	
}elseif($_GET["ourphp_cms"] == 'shopadd'){
	
	if ($_POST["OP_Addname"] == '' || $_POST["OP_Addtel"] == ''){
		exit("<script language=javascript> alert('".$inputno."');history.go(-1);</script>");
	}
	
	$user = $db -> select("`id`","`ourphp_usershopadd`","where `OP_Adduser` = '".$_SESSION['username']."' && `OP_Addindex` = 1");
	if($user)
	{
		$OP_Addindex = 0;
	}else{
		$OP_Addindex = 1;
	}
	
	$add = implode('|',$_POST['OP_Add']);
	$db -> insert("`ourphp_usershopadd`","`OP_Addname` = '".dowith_sql($_POST["OP_Addname"])."',`OP_Addtel` = '".dowith_sql($_POST["OP_Addtel"])."',`OP_Add` = '".dowith_sql($add)."',`OP_Addindex` = ".$OP_Addindex.",`OP_Adduser` = '".$_SESSION['username']."',`time` = '".date("Y-m-d H:i:s")."'","");
	echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-usershopadd.html','?'.$_GET["lang"].'-usershopadd.html',0,'');
	exit;
	
}elseif($_GET["ourphp_cms"] == 'shopadd_index'){

	$db -> update("`ourphp_usershopadd`","`OP_Addindex` = 0","where `OP_Adduser` = '".$_SESSION['username']."'");
	$db -> update("`ourphp_usershopadd`","`OP_Addindex` = 1","where `OP_Adduser` = '".$_SESSION['username']."' && `id` = ".intval($_POST['opcms']));
	if(isset($_POST['ajax']) && $_POST['ajax'] = 'yes')
	{
		$a = array("error" => 2);
		echo json_encode($a);
		exit;
	}else{
		echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-usershopadd.html','?'.$_GET["lang"].'-usershopadd.html',0,'');
		exit;
	}
	
}elseif($_GET["ourphp_cms"] == 'shopadd_del'){
	
	$db -> del("`ourphp_usershopadd`","where `OP_Adduser` = '".$_SESSION['username']."' && `id` = ".intval($_GET['id']));
	echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-usershopadd.html','?'.$_GET["lang"].'-usershopadd.html',0,'');
	exit;

}elseif($_GET["ourphp_cms"] == 'moneyback'){

	$a = MD5($_POST['id']+1000);
	$b = $_POST["md"];

	if($a != $b){
		exit("<script language=javascript> alert('".$accessno."');history.go(-1);</script>");
	}else{

		$oid = $db -> select("OP_Orderspay","ourphp_orders","where id = ".intval($_POST['id']));
		if($oid[0] <= 1){
			exit("<script language=javascript> alert('未付款商品无法申请退款');history.go(-1);</script>");
		}

			$db -> insert("ourphp_usermoneyback","
				`OP_Useremail` = '".$_SESSION['username']."',
				`OP_Orderid` = '".intval($_POST['id'])."',
				`OP_Userposnum` = '".dowith_sql($_POST['posnum'])."',
				`OP_Userposname` = '".dowith_sql($_POST['posname'])."',
				`OP_Admintime` = '',
				`OP_Adminname` = '',
				`time` = '".date("Y-m-d H:i:s")."'
			","");

			$db -> update("ourphp_orders","OP_Usermoneyback = 2","where id = ".intval($_POST['id'])) or die ($db -> error());
			echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-index.html','?'.$_GET["lang"].'-usercenter.html',0,'申请成功，请等待客服人员处理！');
			exit;

	}

}elseif($_GET["ourphp_cms"] == 'moneyout'){
	
	$user = $_SESSION['username'];
	if(!isset($user)){

		exit("<script language=javascript> alert('请先登陆！');history.go(-1);</script>");

	}else{

		if(!isset($_POST['tixianjine'])){
			exit("<script language=javascript> alert('提现金额必填！');history.go(-1);</script>");
		}elseif ($_POST['tixianjine'] < 50){
			exit("<script language=javascript> alert('提现金额必须大于50！');history.go(-1);</script>");
		}
		
		if(strpos($ourphp_usercontrol['withdrawal'],'%') !== false)
		{
			$sxf = ($_POST['tixianjine'] * $ourphp_usercontrol['withdrawal'] / 100) + $_POST['tixianjine'];
		}else{
			$sxf = ($_POST['tixianjine'] + $ourphp_usercontrol['withdrawal']);
		}

		$user = $db -> select("*","ourphp_user","WHERE `OP_Useremail` = '".$user."' || `OP_Usertel` = '".$user."'");
		if($user['OP_Usermoney'] < $sxf){
			
			exit("<script language=javascript> alert('账户余额不足！');history.go(-1);</script>");
			
		}else{

			if($user['OP_Userskype'] == '' || $user['OP_Useraliww'] == ''){
				exit("<script language=javascript> alert('请先在个人资料中完善收款信息！');history.go(-1);</script>");
			}
			$db -> update("ourphp_user","`OP_Usermoney` = `OP_Usermoney` - ".dowith_sql($sxf),"WHERE id = ".intval($user['id']));
			$db -> insert("ourphp_usermoneyout","
				`OP_Useremail` = '".$user['OP_Useremail']."',
				`OP_Useroutmoney` = '".dowith_sql($_POST['tixianjine'])."',
				`OP_Type` = 1,
				`OP_User` = '',
				`OP_Usertime` = '',
				`time` = '".date("Y-m-d H:i:s")."'
			","") or die ($db -> error());

			echo @ourphp_pcwapurl($_GET['type'],'?'.$_GET["lang"].'-tixian.html','?'.$_GET["lang"].'-tixian.html',0,'申请成功，请等待客服人员处理！');
			exit;

		}

	}

}
?>