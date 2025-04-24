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

	if($_POST["OP_Ordersexpress"] == 1){
	$OP_Ordersexpress = $_POST["OP_Ordersexpress2"];
	}else{
	$OP_Ordersexpress = $_POST["OP_Ordersexpress"];
	}

	if (strstr($OP_Adminpower,"34")){
		
		if($_POST["OP_Orderssend"] == 2){
			$OP_Ordersgotime = date("Y-m-d H:i:s");
		}else{
			$OP_Ordersgotime = date("Y-m-d H:i:s");
		}

		plugsclass::logs('编辑订单',$OP_Adminname);
		$db -> update("`ourphp_orders`","`OP_Ordersusermarket` = '".admin_sql($_POST["OP_Ordersusermarket"])."',`OP_Orderssend` = '".admin_sql($_POST["OP_Orderssend"])."',`OP_Ordersexpress` = '".admin_sql($OP_Ordersexpress)."',`OP_Ordersexpressnum` = '".admin_sql($_POST["OP_Ordersexpressnum"])."',`OP_Ordersfreight` = '".admin_sql($_POST["OP_Ordersfreight"])."',`OP_Ordersgotime` = '". date("Y-m-d H:i:s") ."',`OP_Sign` = '".admin_sql($_POST["OP_Sign"])."',`OP_Ordersadminoper` = 1","where id = ".intval($_GET['id'])) or die ($db -> error());
		
		//短信提醒
		if($_POST["OP_Orderssend"] == 2){
			
			$a = plugsclass::plugs(6);
			if($a)
			{
				$rs = $db -> select("OP_Websitemin","ourphp_web","where id = 1");
				$ok = $db -> select("OP_Userbuysms","ourphp_productset","where id = 1");
				if($ok[0] == 1)
				{
					
					include '../../function/api/telcode/user_regcode.class.php';
					$OP_Usertel = $_POST['OP_Ordersusertel'];
					$c = "您的订单[".$_POST["OP_Ordersnumber"]."]我们已经发货啦，快递公司:".$OP_Ordersexpress."快递单号:".$_POST["OP_Ordersexpressnum"].$rs[0];
					$s = '';
					$smskey -> smsconfig($OP_Usertel,$c,$s,1);
					
				}
			}
			
		}
		//邮件提醒			
		if($_POST["OP_Orderssend"] == 2){
			$ourphp_mail = 'send';
			$OP_Ordersexpress = $OP_Ordersexpress;
			$OP_Ordersexpressnum = $_POST["OP_Ordersexpressnum"];
			$OP_Ordersnumber = $_POST["OP_Ordersnumber"];
			$OP_Useremail = admin_sql(htmlspecialchars($_POST["OP_Useremail"]));
			include '../../function/ourphp_mail.class.php';
		}
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_orders.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_orders.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}
	
}

$ourphp_rs = $db -> select("*","`ourphp_orders`","where `id` = ".intval($_GET['id']));
if($ourphp_rs['OP_Ordersproductatt'] != ''){
	$a = explode("、",$ourphp_rs['OP_Ordersproductatt']);
	$b = '';
	foreach($a as $op){
		if(strstr($op,"uploadfile")){
			$b .= '<img src="'.$ourphp['webpath'].$op.'" width=50"" height="50" />、';
		}else{
			$b .= $op."、";
		}
	}
	$smarty->assign('ourphp_buyatt',$b);
}else{
	$smarty->assign('ourphp_buyatt',"无");
}
$smarty->assign('ourphp_orders',$ourphp_rs);
$smarty->display('ourphp_ordersview.html');
?>