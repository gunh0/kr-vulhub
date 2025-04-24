<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 模板操作类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

$temptypetoo = $temptype;
$temptypetoo = str_replace(" and ","and",$temptypetoo);
$temptypetoo = str_replace(" or ","or",$temptypetoo);

switch($temptypetoo){
case "cn":
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_index.html")){
		$smarty->display($ourphp_Language.'_index.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "reg.html":

		if(isset($_SESSION['introducer_userid']))
		{
			if(!isset($_GET['introducer']))
			{
				
				header("location: ?".$ourphp_Language."-reg.html-&introducer=".$_SESSION['introducer_userid']);
				exit;
				
			}else{
				
				$_SESSION['introducer_userid'] = intval($_GET['introducer']);
				
			}
		}else{
			
			if(isset($_GET['introducer']))
			{
				$_SESSION['introducer_userid'] = intval($_GET['introducer']);
			}
			
		}

		include '../../function/ourphp_userreg.class.php';
		$regconfig = array(
			'introducer' => @$_GET['introducer'],
		);
		$smarty->assign('regtable',$reg -> reg_table());
		$smarty->assign('regconfig',$regconfig);
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_reg.html")){
		$smarty->display($ourphp_Language.'_reg.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "login.html":

		if(isset($_SESSION['introducer_userid']))
		{
			if(!isset($_GET['introducer']))
			{
				
				header("location: ?".$ourphp_Language."-login.html-&introducer=".$_SESSION['introducer_userid']);
				exit;
				
			}else{
				
				$_SESSION['introducer_userid'] = intval($_GET['introducer']);
				
			}
		}else{
			
			if(isset($_GET['introducer']))
			{
				$_SESSION['introducer_userid'] = intval($_GET['introducer']);
			}
			
		}
		
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_login.html")){
		$smarty->display($ourphp_Language.'_login.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "useredit.html":
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_edit.html")){
		$smarty->display($ourphp_Language.'_edit.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usermail":
		include './ourphp_userview.class.php';
		$smarty->assign('mail',ourphp_usermail());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_mail.html")){
		$smarty->display($ourphp_Language.'_mail.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usershopping":
		include './ourphp_userview.class.php';
		include './ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_usershopping.html")){
		$smarty->display($ourphp_Language.'_usershopping.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usertuanlist":
		include './ourphp_userview.class.php';
		include './ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_usertuanlist.html")){
		$smarty->display($ourphp_Language.'_usertuanlist.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "userpay":
		include './ourphp_userview.class.php';
		$smarty->assign('paylist',ourphp_userpaylist());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_userpay.html")){
		$smarty->display($ourphp_Language.'_userpay.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "userintegral":
		include './ourphp_userview.class.php';
		$smarty->assign('integrallist',ourphp_userintegral());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_userintegral.html")){
		$smarty->display($ourphp_Language.'_userintegral.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "password.html":
		include './ourphp_password.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_password.html")){
		$smarty->display($ourphp_Language.'_password.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usershopadd.html":
		include './ourphp_userview.class.php';
		$smarty->assign('usershopadd',ourphp_usershopadd());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_usershopadd.html")){
		$smarty->display($ourphp_Language.'_usershopadd.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usercoupon.html":
		include './ourphp_userview.class.php';
		$smarty->assign('couponlist',ourphp_usercoupon());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_usercoupon.html")){
		$smarty->display($ourphp_Language.'_usercoupon.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usermoneyback.html":
		$sendout = $db -> select("OP_Sendout","`ourphp_productset`","where `id` = 1");
		$smarty->assign('sendout',explode("^^^",$sendout[0]));
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_usermoneyback.html")){
		$smarty->display($ourphp_Language.'_usermoneyback.html');
			}else{
		echo $ourphp_tempno;
		}
break;

default:
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."_".$temptype)){
		$smarty->display($ourphp_Language."_".$temptype);
			}else{
		echo $ourphp_tempno.'No:'.$ourphp_Language."_".$temptype;
		}
break;
}
?>