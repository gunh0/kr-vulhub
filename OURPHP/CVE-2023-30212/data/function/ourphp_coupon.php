<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2018 www.ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 * 优惠券领取处理文件
*/
include '../config/ourphp_code.php';
include '../config/ourphp_config.php';
include '../config/ourphp_Language.php';
include './ourphp_function.class.php';

session_start();
date_default_timezone_set('Asia/Shanghai'); //设置时区


if(strpos($_SERVER['HTTP_USER_AGENT'],'Mobile')!==false){
   $css = '
			.coupon { margin:50px auto 0 auto; width:100%; height:120px; overflow:hidden; background:url(../skin/coupon.png) center top no-repeat; background-size:100%;}
			.coupon .left {font-size:60px; color:#fff; float:left; margin:20px 0 0 35px; font-family:Arial; width:100px;}
			.coupon .right {float:left; margin:20px 0 0 35px; font-size:15px; color:#66604B;}
			.coupon .right p { margin-top:0; margin-bottom:10px; font-family:Arial; font-weight:bold;}
			.coupon .right p.f1 {font-size:20px;}
			.coupon .right p span{color:#A75312;}
			.couponfont { text-align:center; padding:20px 0;}
   ';
}else{
   $css = '
			.coupon { margin:50px auto 0 auto; width:500px; height:150px; overflow:hidden; background:url(../skin/coupon.png) center top no-repeat; background-size:100%;}
			.coupon .left {font-size:80px; color:#fff; float:left; margin:35px 0 0 55px; font-family:Arial; width:100px;}
			.coupon .right {float:left; margin:30px 0 0 75px; font-size:27px; color:#66604B;}
			.coupon .right p { margin-top:0; margin-bottom:10px; font-family:Arial; font-weight:bold;}
			.coupon .right p.f1 {font-size:32px;}
			.coupon .right p span{color:#A75312;}
			.couponfont { text-align:center; padding:20px 0;}
   ';
}


@$id = $_GET['class'];
@$md = $_GET['md'];

if(!isset($id) || !isset($md)){
	
	echo 'no!';
	exit;
	
}else{
	
	if(isset($_GET['lang'])){
		$lang = $_GET['lang'];
	}else{
		$lang = 'cn';
	}
	if(!isset($_SESSION['username'])){
		if(isset($_GET['type'])){
			$gouserurl = $ourphp['webpath']."client/wap/?".$lang."-userlogin.html";
			header("location: ".$gouserurl);
		}else{
			$gouserurl = $ourphp['webpath']."client/user/?".$lang."-login.html";
			header("location: ".$gouserurl);
		}
		exit;
	}

	if(isset($_GET['type'])){

			$gouserurl2 = $ourphp['webpath']."client/wap/?".$lang."-usercenter.html";
			$gouserurl3 = "&type";
	}else{

			$gouserurl2 = $ourphp['webpath']."client/user/?".$lang."-usercoupon.html";
			$gouserurl3 = "";
	}
	
	
	if(isset($_GET['coupon']))
	{
		
		echo '
						<!DOCTYPE html>
						<html lang="zh-cmn-Hans">
						<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
						<meta name="author" content="www.ourphp.net"/>
						<meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
						<meta name="apple-mobile-web-app-title" content="优惠券领取">
						<meta name="apple-mobile-web-app-capable" content="yes"/>
						<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
						<meta name="renderer" content="webkit">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta http-equiv="Cache-Control" content="no-siteapp" />
						<meta name="HandheldFriendly" content="true">
						<meta name="MobileOptimized" content="320">
						<meta name="screen-orientation" content="portrait">
						<meta name="x5-orientation" content="portrait">
						<meta name="full-screen" content="yes">
						<meta name="x5-fullscreen" content="true">
						<meta name="browsermode" content="application">
						<meta name="x5-page-mode" content="app">
						<meta name="msapplication-tap-highlight" content="no">
						<title>优惠券领取</title>
						<link rel="stylesheet" href="plugs/YIQI-UI/YIQI-UI.min.css">
						<style type="text/css">
							.clear { clear:both;}
							#YIQI-UI .table-border th, #YIQI-UI .table-border td {
								border-bottom: 1px solid #f4f4f4;
							}
						</style>
						</head>
						<body>
							<div id="YIQI-UI">
							  <table class="table table-border" style="width:100%">
								<thead>
								  <tr>
									<td colspan="2"><h6>可领取优惠券</h6></td>
								  </tr>
								</thead>
								<tbody>
						';
						
						$a = $db -> select("OP_Couponset","ourphp_product","where id = ".intval($id));
						$b = $db -> listgo("*","ourphp_coupon","where id in (".$a[0].") && OP_Type = 0");
						while($rs = $db -> whilego($b)){
							echo '
								  <tr>
									<td>'.$rs['OP_Title'].'</td>
									<td class="text-r"><a href="ourphp_coupon.php?class='.$rs['id'].'&md='.$rs['OP_Md'].$gouserurl3.'" class="btn btn-green radius-4 pt-5 pb-5 pl-20 pr-20 f-12">领券</a></td>
								  </tr>
							';
						}
								  
					echo '
								</tbody>
							  </table>
							 </div>
						</body>
						</html>
		';
		exit;
		
	}else{
	
		switch($lang){
			case "cn":
				$font = array('此优惠券不对全部用户开放!','您已经领取过了,不能重复领取!','领取成功,请返回会员中心查看或在购物时使用!');
			break;
			default:
				$font = array('此优惠券不对全部用户开放!','您已经领取过了,不能重复领取!','领取成功,请返回会员中心查看或在购物时使用!');
			break;
		}
		
		$r = $db -> select("*","ourphp_coupon","where id = ".intval($id)." && `OP_Md` = '".dowith_sql($md)."'");
		if($r){
			
			if($r['OP_Type'] == 0){
				
				$op = $db -> select("*","ourphp_couponlist","where `OP_Couponid` = ".intval($id)." && `OP_Username` = '".$_SESSION['username']."' && `OP_Md` = '".dowith_sql($md)."'");
				if($op){
					
					echo $font[1];
					exit;
					
				}else{
					
					$coupon = $db -> insert("ourphp_couponlist","
					`OP_Couponid` =	".intval($id).",
					`OP_Username` =	'".$_SESSION['username']."',
					`OP_Type` =	0,
					`OP_Timewin` =	'".$r['OP_Timewin']."',
					`OP_Moneygo` =	'".$r['OP_Moneygo']."',
					`OP_Md` =	'".dowith_sql($md)."',
					`OP_Time` =	'0000-00-00 00:00:00',
					`time` =	'".date("Y-m-d H:i:s")."'
					","") or die ($db -> error());
					
					if($r['OP_Moneygo'] == 0){
						$moneygo = '全场使用';
					}else{
						$moneygo = '满 <span>'.intval($r['OP_Moneygo']).'</span> 元使用';
					}
					if($r['OP_Timewin'] == '' || $r['OP_Timewin'] == '0000-00-00 00:00:00'){
						$timewin = '请在结算时使用';
					}else{
						$timewin = date("Y-m-d",strtotime($r['OP_Timewin'])).' 前使用';
					}

					echo '
					

						<!DOCTYPE html>
						<html lang="zh-cmn-Hans">
						<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
						<meta name="author" content="www.ourphp.net"/>
						<meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
						<meta name="apple-mobile-web-app-title" content="优惠券领取">
						<meta name="apple-mobile-web-app-capable" content="yes"/>
						<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
						<meta name="renderer" content="webkit">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta http-equiv="Cache-Control" content="no-siteapp" />
						<meta name="HandheldFriendly" content="true">
						<meta name="MobileOptimized" content="320">
						<meta name="screen-orientation" content="portrait">
						<meta name="x5-orientation" content="portrait">
						<meta name="full-screen" content="yes">
						<meta name="x5-fullscreen" content="true">
						<meta name="browsermode" content="application">
						<meta name="x5-page-mode" content="app">
						<meta name="msapplication-tap-highlight" content="no">
						<title>优惠券领取</title>
						<style type="text/css">
							.clear { clear:both;}
							'.$css.'
						</style>
						</head>
						<body>
							<div class="coupon">
								<div class="left">'.intval($r['OP_Money']).'</div>
								<div class="right">
									<p class="f1">'.$moneygo.'</p>
									<p>'.$timewin.'</p>
								</div>
							</div>
							<div class="clear"></div>
							<div class="couponfont">
								<p><img src="../skin/accept_check_login_success_16px_1534_easyicon.net.png"> 领取成功!~</p>
								<p>[请在会员中心查看]</p>
							</div>
						</body>
						</html>
					
					';
					exit;
				}
				
			}else{
				
				echo $font[0];
				exit;
				
			}
			
		}else{
			
			echo 'no!';
			exit;
		
		}
	
	}
	
}

?>