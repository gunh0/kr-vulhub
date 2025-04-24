<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 视频播放器(2014-10-15)
 *-------------------------------
*/
include '../config/ourphp_code.php';
include '../config/ourphp_config.php';
include '../config/ourphp_Language.php';
include './ourphp_function.class.php';

session_start();
date_default_timezone_set('Asia/Shanghai');

//验证码 没搞明白为毛要写在这里才可以兼容其它虚拟主机
$ValidateCode = $_SESSION["code"];
$bookcontentnumber = array(
	//留言板字数控制
	20,30,100
);

//处理留言
if(isset($_GET["ourphp_cms"])){
	
	$book = $ourphp_adminfont['bookadd'];
	$code = $ourphp_adminfont['code'];
	$outlogin = $ourphp_adminfont['outlogin'];
	$booknumber = $ourphp_adminfont['booknumber'];

	$ourphp_rs = $db -> select("`OP_Bookuser`","`ourphp_webdeploy`","where `id` = 1");
	if($ourphp_rs[0] == 1){
		if(empty($_SESSION['username'])){
			echo "<script language=javascript> alert('".$outlogin."');history.go(-1);</script>";
			exit;
		}
	}

	$booksection = $db -> select("id","ourphp_booksection","where id = ".intval($_POST["class"]));
	if(!$booksection){
		echo "<script language=javascript> alert('".$ourphp_adminfont['nocolumn']."');history.go(-1);</script>";
		exit;
	}

	if($_POST["bookname"] == '' || $_POST["booktel"] == '' || $_POST["bookcontent"] == '' || $_POST["bookcode"] == ''){
		
		echo "<script language=javascript> alert('".$book."');history.go(-1);</script>";
		exit;
		
	}else{
		
		$bookfg = explode('|',$booknumber);
		if(mb_strwidth($_POST["bookname"]) > $bookcontentnumber[0]){
			echo "<script language=javascript> alert('".$bookfg[0].$bookfg[3].$bookcontentnumber[0].$bookfg[4]."');history.go(-1);</script>";
			exit;
		}
		if(mb_strwidth($_POST["booktel"]) > $bookcontentnumber[1]){
			echo "<script language=javascript> alert('".$bookfg[1].$bookfg[3].$bookcontentnumber[1].$bookfg[4]."');history.go(-1);</script>";
			exit;
		}
		if(mb_strwidth($_POST["bookcontent"]) > $bookcontentnumber[2]){
			echo "<script language=javascript> alert('".$bookfg[2].$bookfg[3].$bookcontentnumber[2].$bookfg[4]."');history.go(-1);</script>";
			exit;
		}
		
	}

	if($_POST["bookcode"] != $ValidateCode){
		echo "<script language=javascript> alert('".$code."');history.go(-1);</script>";
		exit;
	}

	$db -> insert("`ourphp_book`","`OP_Bookcontent` = '".dowith_sql($_POST["bookcontent"])."',`OP_Bookname` = '".dowith_sql($_POST["bookname"])."',`OP_Booktel` = '".dowith_sql($_POST["booktel"])."',`OP_Bookip` = '".dowith_sql($_POST["ip"])."',`OP_Bookclass` = '".intval($_POST["class"])."',`OP_Booklang` = '".dowith_sql($_POST["lang"])."',`time` = '".date("Y-m-d H:i:s")."'","");

	if(isset($_POST["wapbook"])){
		echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?".$_POST["lang"]."-clubview-".$_POST["class"].".html');</script>";
	}else{
		echo "<script language=javascript>location.replace('".$ourphp['webpath']."?".$_POST["lang"]."-clubview-".$_POST["class"].".html');</script>";
	}

}

//处理下载
if(isset($_GET["ourphp_down"])){

	$power = $ourphp_adminfont['power'];
	$ourphp_rs = $db -> select("`OP_Downrights`,`OP_Downdurl`","`ourphp_down`","where id = ".intval($_GET["ourphp_down"])." && OP_Random = '".dowith_sql($_GET["code"])."'");
	
	$OP_Downrights = $ourphp_rs[0];
	$OP_Downdurl = $ourphp_rs[1];
	if(substr($OP_Downdurl,0,4) == 'http')
	{
		$downflie = $OP_Downdurl;
	}else{
		$downflie = $ourphp['webpath'].$OP_Downdurl;
	}
	
	if(!$ourphp_rs){
		
		echo "<script language=javascript> alert('".$power."');javascript:window.close()</script>";
		
			}else{
			
		if($OP_Downrights == '0'){
			
			header("location: ".$downflie);
			
		}else{
				//会员权限
				@session_start();
				$ourphp_userrs = $db -> select("`OP_Userclass`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
				
				if (strstr($OP_Downrights,$ourphp_userrs[0])){
					header("location: ".$downflie);
				}else{
					exit("<script language=javascript> alert('".$power."');javascript:window.close()</script>");
				}
		}
		
	}
	exit;
}

//购物车删除
if(isset($_GET["ourphp_shopping"])){

	if(isset($_GET["type"])){
		$urltype = $ourphp['webpath']."client/wap/?".$_GET["lang"]."-shoppingcart.html"; //手机
	}else{
		$urltype = $ourphp['webpath']."?".$_GET["lang"]."-shoppingcart.html"; //电脑
	}

	$power= $ourphp_adminfont['power'];
	$result = $db -> select("`id`","`ourphp_shoppingcart`","where id = ".intval($_GET["ourphp_shopping"])." && OP_Shopusername = '".$_SESSION['username']."'");
	
	if($result == false){
		
		exit("<script language=javascript> alert('".$power."');javascript:window.close()</script>");
		
			}else{
				
		$result = $db -> del("`ourphp_shoppingcart`","where id = ".intval($_GET["ourphp_shopping"])." && OP_Shopusername = '".$_SESSION['username']."'");
		echo "<script language=javascript>location.replace('".$urltype."');</script>";
		exit;
	}
}

//合并订单
if(isset($_GET["ourphp_shoporders"])){
	if (!empty($_POST["id"])){
		$op_b = implode('_',$_POST["id"]);
	}else{
		if(!empty($_POST["type"])){
			exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?".$_POST["lang"]."-usershopping-op.html');</script>");
		}else{
			exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/user/?".$_POST["lang"]."-usershopping-op.html');</script>");
		}
	}
	if(!empty($_POST["type"])){
		exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?".$_POST["lang"]."-shoppingorders.html-&id=".$op_b."');</script>");
	}else{
		exit("<script language=javascript>location.replace('".$ourphp['webpath']."?".$_POST["lang"]."-shoppingorders.html-&id=".$op_b."');</script>");
	}
}
?>