<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * OURPHP系统 会员处理接口
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

class webapi{
	
	public $db;
	
	public static function jsoncode($error, $code, $msg, $info = '')
	{
		$ourphp = array(
			"code" => $code,
			"error" => $error,
			"info" => $info,
			"msg" => $msg,
		);
		echo json_encode($ourphp);
		exit;
	}
	
	public static function data($table, $page, $limit, $desc, $sort)
	{
		global $db;
		switch($table){
			case "userreg":
			
				$useremail = dowith_sql($_POST['useremail']);
				$password = dowith_sql($_POST['password']);
				if(empty($useremail) || empty($password)){
					
					self::jsoncode("fail","1001","账号密码不能为空！");
					
				}
				$user = $db -> select("`OP_Useremail`","`ourphp_user`","where `OP_Useremail` = '".dowith_sql($useremail)."' || `OP_Usertel` = '".dowith_sql($useremail)."'");
				if($user)
				{
					self::jsoncode("fail","1001","账号已存在，请更换一个！");
				}
				
				$rs = $db -> select("`OP_Regtyle`,`OP_Usergroup`,`OP_Usermoney`","`ourphp_usercontrol`","where `id` = 1");
				if($rs[0] == 'email'){
					$emailvar = filter_var($useremail, FILTER_VALIDATE_EMAIL);
					if(!$emailvar){
						self::jsoncode("fail","1001","EMAIL格式不正确！");	
					}
					$useremail = $useremail;
					$usertel = '';
					
				}elseif($rs[0] == 'tel'){
					if(!preg_match("/^1[34578]{1}\d{9}$/",$useremail)){ 
						self::jsoncode("fail","1001","电话号码格式不正确！");
					}
					$useremail = $useremail;
					$usertel = $useremail;
					
				}
				$money = explode("|",$rs[2]);
				$randcode = rand(11111,99999);
				$reg_username = "昵称".$randcode;
				$db -> insert("`ourphp_user`","`OP_Useremail` = '".dowith_sql($useremail)."',`OP_Username` = '".dowith_sql($reg_username)."',`OP_Userpass` = '".dowith_sql(substr(md5(md5($password)),0,16))."',`OP_Usertel` = '".dowith_sql($usertel)."',`OP_Userclass` = '".$rs[1]."',`OP_Usersource` = 'API注册',`OP_Userstatus` = 1,`OP_Usercode` = '".randomkeys(18)."',`time` = '".date("Y-m-d H:i:s")."',`OP_Usermoney` = '".$money[0]."',`OP_Userintegral` = '".$money[1]."'","");
				$newid = $db -> insertid();
				$db -> update("ourphp_user","OP_Userregcode = ".$newid.$randcode,"where id = ".$newid);
				
				$outdata = array(
					"useremail" => $useremail,
					"usertel" => $usertel,
					"username" => $reg_username,
					"userid" => $newid,
					"code" => $newid.$randcode,
				);
				self::jsoncode("success","1002","注册成功！",$outdata);
			
			break;
			
			case "userlogin":
				$useremail = dowith_sql($_POST['useremail']);
				$password = dowith_sql($_POST['password']);
				if(empty($useremail) || empty($password)){
					
					self::jsoncode("fail","1001","账号密码不能为空！");
					
				}
				$ourphp_rs = $db -> select("`id`,`OP_Userstatus`,`OP_Userclass`,`OP_Username`,`OP_Usertel`,`OP_Userregcode`","`ourphp_user`","where (`OP_Useremail` = '".dowith_sql($useremail)."' || `OP_Usertel` = '".dowith_sql($useremail)."') && `OP_Userpass` = '".dowith_sql(substr(md5(md5($password)),0,16))."'");
				if(!$ourphp_rs)
				{
					self::jsoncode("fail","1001","账号或密码错误或不存在！");
				}
				$userclass = $db -> select("OP_Useropen","ourphp_userleve","where id = ".intval($ourphp_rs[2]));
				if($ourphp_rs[1] == 2 || $userclass[0] == 1){
					self::jsoncode("fail","1001","账号被锁定无法登录！");
				}
				
				$_SESSION['userid'] = $ourphp_rs[0];
				$_SESSION['username'] = $useremail;
				$_SESSION['name'] = $ourphp_rs[3];
				
				$outdata = array(
					"useremail" => $useremail,
					"usertel" => $ourphp_rs[4],
					"username" =>$ourphp_rs[3],
					"userid" => $ourphp_rs[0],
					"code" => $ourphp_rs[5],
				);
				self::jsoncode("success","1002","登录成功！",$outdata);
			
			break;
			
			case "userout":
				unset($_SESSION['userid']);
				unset($_SESSION['username']);
				unset($_SESSION['name']);
				self::jsoncode("success","1002","退出成功！");
			break;
			
			default:
			
				$id = intval($_POST['id']);
				if($id == 0)
				{
					$wid = '';
				}else{
					$wid = 'id = '.$id;
				}
				$mysql = $db -> create("SHOW TABLES LIKE '".$db -> datatable($table)."'" ,2);
				$num = $db -> rows($mysql);
				if($num == 1)
				{
					$ourphp_list = array();
					$listpage = intval($limit);
					$where = admin_sql($_POST['where']);
					
					if (intval(isset($page)) == 0){
						$listpagesum = 1;
							}else{
						$listpagesum = intval($page);
					}
					$start=($listpagesum-1)*$listpage;
					$newdescsort = ' ORDER BY '.$sort.' '.$desc.' LIMIT '.$start.','.$listpage;
					
					if(empty($where))
					{
						if($wid == '')
						{
							$w = $newdescsort;
						}else{
							$w = 'where '.$wid.$newdescsort;
						}
					}else{
						if($wid == '')
						{
							$w = 'where '.$where.$newdescsort;
						}else{
							$w = 'where '.$where . " && " . $wid.$newdescsort;
						}
					}
					
					$list = $db -> listgo("*",$db -> datatable($table),$w);
					while($rs = $db -> whilego($list)){
						$ourphp_lis[] = $rs;
					}
					self::jsoncode("success","1002","加载成功！",$ourphp_lis);
					
				}else{
					self::jsoncode("fail","1001","数据库表不存在！");
				}
			
			break;
		}
	}

}

?>