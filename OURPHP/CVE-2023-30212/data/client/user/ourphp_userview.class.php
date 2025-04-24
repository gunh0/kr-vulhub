<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

if(!isset($_SESSION['username'])){
	
	header("location: ?".$ourphp_Language."-login.html");
	exit;
	
}else{
	
	$ourphp_user = $db -> select("`id`,`OP_Userclass`,`OP_Userstatus`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
	if($ourphp_user[2] == 2){
		
		unset($_SESSION['username']);
		echo "<script language=javascript> alert('".$ourphp_adminfont['userloginno']."');location.replace('?".$ourphp_Language."-login.html');</script>";
		exit;
		
	}else{
		
		$ourphp_userclass = $db -> select("`OP_Useropen`,`OP_Userlevename`","ourphp_userleve","where id = ".intval($ourphp_user[1]));
		if($ourphp_userclass[0] == 1){
			
			unset($_SESSION['username']);
			echo "<script language=javascript> alert('".$ourphp_adminfont['userloginno']."');location.replace('?".$ourphp_Language."-login.html');</script>";
			exit;
			
		}
		
	}

}


function ourphp_userview(){ 
	global $db,$ourphp_userclass;
	$ourphp_rs = $db -> select("`id`,`OP_Useremail`,`OP_Username`,`OP_Usertel`,`OP_Userqq`,`OP_Userskype`,`OP_Useraliww`,`OP_Useradd`,`OP_Userclass`,`OP_Usermoney`,`OP_Userintegral`,`OP_Userproblem`,`OP_Useranswer`,`OP_Usertext`,`OP_Usercode`,`OP_Userpass`,`OP_Userhead`,`OP_Usercoin`,`OP_Userregcode`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
	
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_couponlist`","where `OP_Username` = '".$_SESSION['username']."'");
	$ourphptotal = $db -> whilego($ourphptotal);
	//cookies 储存用户基本信息
	setcookie("name", $ourphp_rs[2], time()+3600*24);
	setcookie("tel", $ourphp_rs[3], time()+3600*24);
	setcookie("add", $ourphp_rs[7], time()+3600*24);
	setcookie("money", $ourphp_rs[9], time()+3600*24);
	setcookie("integral", $ourphp_rs[10], time()+3600*24);
	setcookie("coin", $ourphp_rs[17], time()+3600*24);
	setcookie("coupon", $ourphptotal['tiaoshu'], time()+3600*24);
	setcookie("class", $ourphp_userclass[1], time()+3600*24);
	
	$userrows = array(
		'id' => $ourphp_rs[0],
		'email' => $ourphp_rs[1],
		'name' => $ourphp_rs[2],
		'tel' => $ourphp_rs[3],
		'qq' => $ourphp_rs[4],
		'skype' => $ourphp_rs[5],
		'aliww' => $ourphp_rs[6],
		'add' => $ourphp_rs[7],
		'class' => $ourphp_userclass[1],
		'money' => $ourphp_rs[9],
		'integral' => $ourphp_rs[10],
		'problem' => $ourphp_rs[11],
		'answer' => $ourphp_rs[12],
		'text' => $ourphp_rs[13],
		'code' => $ourphp_rs[14],
		'password' => $ourphp_rs[15],
		'coupon' => $ourphptotal['tiaoshu'],
		'img' => $ourphp_rs[16],
		'coin' => $ourphp_rs[17],
		'regcode' => $ourphp_rs[18],
	);
	
	return $userrows;
}

function ourphp_usermail(){ 
global $db,$smarty;
	
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_usermessage`","where `OP_Usersend` = '".$_SESSION['username']."' || `OP_Usercollect` = '".$_SESSION['username']."' || `OP_Usercollect` = '全体会员'");
	$ourphptotal = $db -> whilego($ourphptotal);
	
	$query = $db -> listgo("`id`,`OP_Usersend`,`OP_Usercollect`,`OP_Usercontent`,`OP_Userclass`,`time`","`ourphp_usermessage`","where `OP_Usersend` = '".$_SESSION['username']."' || `OP_Usercollect` = '".$_SESSION['username']."' || `OP_Usercollect` = '全体会员' order by time desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
	   $rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"send" => $ourphp_rs[1],
			"collect" => $ourphp_rs[2],
			"content" => $ourphp_rs[3],
			"class" => $ourphp_rs[4],
			"time" => $ourphp_rs[5],
		); 
		$i+=1;
		//改为已读状态
		$msgok = $db -> update("`ourphp_usermessage`","`OP_Userclass` = 2","where id = ".intval($ourphp_rs[0]));
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty -> assign('ourphppage',$_page->showpage());
	return $rows;
}

function ourphp_userintegral(){
	global $db;
	$query = $db -> listgo("`id`,`OP_Iname`,`OP_Iintegral`,`OP_Iconfirm`,`OP_ITime`","`ourphp_integral`","where `OP_Iuseremail` = '".$_SESSION['username']."' order by id desc");
	$userrows = array();
	while($ourphp_rs = $db -> whilego($query)){
		$userrows[] = array(
			'id' => $ourphp_rs[0],
			'name' => $ourphp_rs[1],
			'integral' => $ourphp_rs[2],
			'confirm' => $ourphp_rs[3],
			'lqtime' => $ourphp_rs[4],
		);
	}
	return $userrows;
}

function ourphp_usershopadd(){ 
	global $db;
	$query = $db -> listgo("*","`ourphp_usershopadd`","where `OP_Adduser` = '".$_SESSION['username']."' order by OP_Addindex desc");
	$rows=array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
	   $rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs['id'],
			"name" => $ourphp_rs['OP_Addname'],
			"tel" => $ourphp_rs['OP_Addtel'],
			"add" => $ourphp_rs['OP_Add'],
			"index" => $ourphp_rs['OP_Addindex'],
			"time" => $ourphp_rs['time'],
		); 
		$i+=1;
	}
	return $rows;
}

function ourphp_userpaylist(){
	global $db;
	$query = $db -> listgo("`id`,`OP_Useremail`,`OP_Usermoney`,`OP_Userintegral`,`OP_Usercontent`,`time`","`ourphp_userpay`","where `OP_Useremail` = '".$_SESSION['username']."'");
	$rows = array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[] = array(
			'id' => $ourphp_rs[0],
			'email' => $ourphp_rs[1],
			'money' => $ourphp_rs[2],
			'integral' => $ourphp_rs[3],
			'content' => $ourphp_rs[4],
			'time' => $ourphp_rs[5],
		);
	}
	return $rows;
}

function ourphp_usercoupon(){
	global $db;
	$query = $db -> listgo("`id`,`OP_Type`,`OP_Timewin`,`OP_Moneygo`,`OP_Md`,`OP_Couponid`","`ourphp_couponlist`","where `OP_Username` = '".$_SESSION['username']."' order by id desc");
	$money = 0;
	$userrows = array();
	while($ourphp_rs = $db -> whilego($query)){
		$money = $db -> select("`OP_Money`","ourphp_coupon","where id = ".$ourphp_rs[5]);
		if($ourphp_rs[3] == 0){
			$moneygo = '全场使用';
		}else{
			$moneygo = '满 <span>'.intval($ourphp_rs[3]).'</span> 使用';
		}
		if($ourphp_rs[2] == '' || $ourphp_rs[2] == '0000-00-00 00:00:00'){
			$timewin = '请在结算时使用';
		}else{
			$timewin = date("Y-m-d",strtotime($ourphp_rs[2])).' 前使用';
		}
		$userrows[] = array(
			'id' => $ourphp_rs[0],
			'type' => $ourphp_rs[1],
			'timewin' => $timewin,
			'moneygo' => $moneygo,
			'md' => $ourphp_rs[4],
			'money' => intval($money[0])
		);
	}
	return $userrows;
}

function ourphp_userinvitation(){
        global $db,$smarty;
        
        $listpage = 25;
        if (intval(isset($_GET['page'])) == 0){
                $listpagesum = 1;
                        }else{
                $listpagesum = intval($_GET['page']);
        }
        $start=($listpagesum-1)*$listpage;
        $ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_user`","where `OP_Usersource` = '".$_SESSION['username']."'");
        
        $ourphptotal = $db -> whilego($ourphptotal);
        $query = $db-> listgo("`id`,`OP_Useremail`,`OP_Username`,`time`,`OP_Userclass`","`ourphp_user`","where `OP_Usersource` = '".$_SESSION['username']."' order by time desc LIMIT ".$start.",".$listpage);
        $rows = array();
        while($ourphp_rs = $db -> whilego($query)){
        		$zu = $db -> select("OP_Userlevename","ourphp_userleve","where id = ".$ourphp_rs[4]);
        		$upuser = $db -> select("*","ourphp_userregreward","where `OP_Useremail` = '".$_SESSION['username']."'");
                $rows[] = array(
                            'id' => $ourphp_rs[0],
                            'email' => $ourphp_rs[1].'('.$zu[0].')',
                            'name' => $ourphp_rs[2],
                            'money' => '
                            		<p>获得现金:'.$upuser['OP_Usermoney'].'</p>
                            		<p>获得积分:'.$upuser['OP_Userintegral'].'</p>
							',
                            'time' => $ourphp_rs[3],
                            'zu' => $zu[0],
                );
        }
        $_page = new Page($ourphptotal['tiaoshu'],$listpage);
        $smarty->assign('ourphppage',$_page->showpage());
        return $rows;
}


function ourphp_usermoneyout(){
	global $db,$smarty;
	$i = 1;
	$rs = array();
	$a = $db -> listgo("*","`ourphp_usermoneyout`","where `OP_Useremail` = '".$_SESSION['username']."'");
	while($r = $db -> whilego($a)){
		$rs[]=array (
			"i" => $i,
			"id" => $r['id'],
			"email" => $r['OP_Useremail'],
			"money" => $r['OP_Useroutmoney'],
			"type" => $r['OP_Type'],
			"admin" => $r['OP_User'],
			"typetime" => $r['OP_Usertime'],
			"time" => $r['time']
		);
		$i+=1;
	}
	return $rs;
}

$smarty->assign('user',ourphp_userview());
$smarty->assign('invitation',ourphp_userinvitation());
$smarty->assign('userpaypai',array(
	'alipay_quick' => plugsclass::plugs(3),
	'alipay_webpay' => plugsclass::plugs(4),
	'alipay_mobilepay' => plugsclass::plugs(8),
	'weixinpay' => plugsclass::plugs(9),
	'weixinh5pay' => plugsclass::plugs(10),
));
$smarty->assign('moneyout',ourphp_usermoneyout());

?>