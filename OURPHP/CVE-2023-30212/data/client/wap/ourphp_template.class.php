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
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_index.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_index.html');
			}else{
		echo $ourphp_tempno;
		exit();
		}
break;

case "index.html":
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_index.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_index.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "article":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "articleview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "product":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "productclass":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shoplistclass.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_shoplistclass.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "productview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_shop_wap.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "shop.html":
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shop.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_shop.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "brand":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_brand.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_brand.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "userreg.html":

		if(isset($_SESSION['introducer_userid']))
		{
			if(!isset($_GET['introducer']))
			{
				
				header("location: ?".$ourphp_Language."-userreg.html-&introducer=".$_SESSION['introducer_userid']);
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
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_userreg.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_userreg.html");
			}else{
		echo $ourphp_tempno;
		}
		
break;

case "userlogin.html":

		if(isset($_SESSION['introducer_userid']))
		{
			if(!isset($_GET['introducer']))
			{
				
				header("location: ?".$ourphp_Language."-userlogin.html-&introducer=".$_SESSION['introducer_userid']);
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
		
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_userlogin.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_userlogin.html");
			}else{
		echo $ourphp_tempno;
		}
		
break;

case "userpassword.html":
		include './ourphp_password.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_userpassword.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_userpassword.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "usercenter.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usercenter.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_usercenter.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "shoppingcart.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_shop_wap.class.php';
		include '../../function/ourphp_shoppingcart.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shoppingcart.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_shoppingcart.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "shoppingorders.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_shop_wap.class.php';
		include '../../function/ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shoppingorders.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_shoppingorders.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "usershopping":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		include './ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usershopping.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_usershopping.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "usertuanlist":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		include './ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usertuanlist.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_usertuanlist.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "userintegral":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		$smarty->assign('integrallist',ourphp_userintegral());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_userintegral.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_userintegral.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "userpay":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		$smarty->assign('paylist',ourphp_userpaylist());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_userpay.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_userpay.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usermail":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		$smarty->assign('mail',ourphp_usermail());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usermail.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_usermail.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usershopadd.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		include '../../function/ourphp_shop_wap.class.php';
		include '../../function/ourphp_shoppingcart.class.php';
		$smarty->assign('shopadd',ourphp_usershopadd());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usershopadd.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_usershopadd.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "photo":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "photoview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "video":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "videoview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "about":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "club.html":
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_club.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_club.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "clubview":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_clubview.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_clubview.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "down":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "downview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "job":
		include './ourphp_page.class.php';
		include '../../function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "jobview":
		include '../../function/ourphp_list.class.php';
		include '../../function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "usercoupon.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		$smarty->assign('couponlist',ourphp_usercoupon());
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usercoupon.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_usercoupon.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "useredit.html":
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_useredit.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_useredit.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "usermoneyback.html":
		$sendout = $db -> select("OP_Sendout","`ourphp_productset`","where `id` = 1");
		$smarty->assign('sendout',explode("^^^",$sendout[0]));
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_usermoneyback.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_usermoneyback.html');
			}else{
		echo $ourphp_tempno;
		}
break;

default:
		include './ourphp_page.class.php';
		include './ourphp_userview.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_".$temptype)){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_".$temptype);
			}else{
		echo $ourphp_tempno.'No:'.$ourphp_Language."_".$temptype;
		}
break;
}
?>