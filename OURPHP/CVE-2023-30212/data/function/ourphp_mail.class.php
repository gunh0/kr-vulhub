<?php 
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 处理邮件提醒(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

function mailcontent($content,$type,$keyone = '',$keytow = '',$keythree = ''){
	if($type == 1){
		$content = str_replace('#opcms#useremail#',$keyone,$content);
		$content = str_replace('#opcms#userpass#',$keytow,$content);
		$content = str_replace('#opcms#username#',$keythree,$content);
	}elseif($type == 3){
		$content = str_replace('#opcms#express#',$keyone,$content);
		$content = str_replace('#opcms#expressnum#',$keytow,$content);
		$content = str_replace('#opcms#buynumber#',$keythree,$content);
	}elseif($type == 4){
		$content = str_replace('#opcms#code#',$keyone,$content);
	}
	return $content;
}

function ourphp_mailgo($a,$b,$c,$d,$e,$f,$g,$h,$i,$mailclass,$url){
	include_once($url."function/ourphp_mail.php");
	$smtpserver = "$a";//SMTP服务器
	$smtpserverport = intval($b);//SMTP服务器端口
	$smtpusermail = "$c";//SMTP服务器的用户邮箱
	$smtpemailto = "$d";//发送给谁
	$smtpuser = "$e";//SMTP服务器的用户帐号
	$smtppass = "$f";//SMTP服务器的用户密码
	$mailsubject = "$g";//邮件主题
	$mailbody = "$h";//邮件内容
	$mailtype = "$i";//邮件格式（HTML/TXT）,TXT为文本邮件
	$smtp= new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
	$smtp->debug = FALSE;//是否显示发送的调试信息
	$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
}

switch($ourphp_mail){

	case "reguser":
		$ourphp_rs = $db -> select("*","`ourphp_mail`","where `id` = 1");
		if($ourphp_rs['OP_Mailclass'] == 1){
			$OP_Mailcontent = mailcontent($ourphp_rs['OP_Mailcontent'],1,$OP_Useremail,$OP_Userpass,$OP_Username);
			ourphp_mailgo($ourphp_rs['OP_Mailsmtp'],$ourphp_rs['OP_Mailport'],$ourphp_rs['OP_Mailusermail'],$OP_Useremail,$ourphp_rs['OP_Mailuser'],$ourphp_rs['OP_Mailpass'],$ourphp_rs['OP_Mailsubject'],$OP_Mailcontent,$ourphp_rs['OP_Mailtype'],1,'../../');
		}
	break;
	case "send":
		$ourphp_rs = $db -> select("*","`ourphp_mail`","where `id` = 3");
		if($ourphp_rs['OP_Mailclass'] == 1){
			$OP_Mailcontent = mailcontent($ourphp_rs['OP_Mailcontent'],3,$OP_Ordersexpress,$OP_Ordersexpressnum,$OP_Ordersnumber);
			ourphp_mailgo($ourphp_rs['OP_Mailsmtp'],$ourphp_rs['OP_Mailport'],$ourphp_rs['OP_Mailusermail'],$OP_Useremail,$ourphp_rs['OP_Mailuser'],$ourphp_rs['OP_Mailpass'],$ourphp_rs['OP_Mailsubject'],$OP_Mailcontent,$ourphp_rs['OP_Mailtype'],1,'../../');
		}
	break;
	case "userbuy":
		$ourphp_rs = $db -> select("*","`ourphp_mail`","where `id` = 2");
		if($ourphp_rs['OP_Mailclass'] == 1){
			ourphp_mailgo($ourphp_rs['OP_Mailsmtp'],$ourphp_rs['OP_Mailport'],$ourphp_rs['OP_Mailusermail'],$OP_Useremail,$ourphp_rs['OP_Mailuser'],$ourphp_rs['OP_Mailpass'],$ourphp_rs['OP_Mailsubject'],$ourphp_rs['OP_Mailcontent'],$ourphp_rs['OP_Mailtype'],1,'');
		}
	break;
	case "regcode":
		$ourphp_rs = $db -> select("*","`ourphp_mail`","where `id` = 4");
		if($ourphp_rs['OP_Mailclass'] == 1){
			$OP_Mailcontent = mailcontent($ourphp_rs['OP_Mailcontent'],4,$OP_Regcode);
			ourphp_mailgo($ourphp_rs['OP_Mailsmtp'],$ourphp_rs['OP_Mailport'],$ourphp_rs['OP_Mailusermail'],$OP_Useremail,$ourphp_rs['OP_Mailuser'],$ourphp_rs['OP_Mailpass'],$ourphp_rs['OP_Mailsubject'],$OP_Mailcontent,$ourphp_rs['OP_Mailtype'],1,'../../');
		}
	break;

}
?> 