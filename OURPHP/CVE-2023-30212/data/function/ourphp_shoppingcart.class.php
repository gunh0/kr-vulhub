<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

$shoppinglogin = $ourphp_adminfont['shoppinglogin'];
$shoppingnum = $ourphp_adminfont['shoppingnum'];
$shoppingatt = $ourphp_adminfont['shoppingatt'];
$shoppingatt2 = $ourphp_adminfont['shoppingatt2'];
$tuanusernum = $ourphp_adminfont['tuanusernum'];
$tuantime = $ourphp_adminfont['tuantime'];
$tuanok = $ourphp_adminfont['tuanok'];

if($shopsetgg['buy'] == 1){

		header("location: ".$ourphp['webpath']."touristcart.php?".$ourphp_Language."-&lang=".$ourphp_Language."&ourphp_cms=shopping&pid=".$_POST['pid']."&kc=".$_POST['ourphp_kc']."&hh=".$_POST['ourphp_hh']."&sl=".$_POST['sl']."&sx=".$_POST['ourphp_sx']."&pcwap=".$_POST['ourphp_pcwap']);
		exit;

}else{
	
	if(!isset($_SESSION['username'])){
		exit("<script language=javascript> alert('".$shoppinglogin."');location.replace('".$ourphp['webpath']."client/user/?".$ourphp_Language."-login.html');</script>");
	}
}


if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "shopping"){

	if($_POST['ourphp_kc'] == '' || $_POST['ourphp_hh'] == ''){
		exit("<script language=javascript> alert('".$shoppingatt."');history.go(-1);</script>");
	}

	if($_POST['ourphp_kc'] == "0" || $_POST['ourphp_hh'] == "0" || @$_POST['ourphp_sx'] == "0"){
		exit("<script language=javascript> alert('".$shoppingatt2."');history.go(-1);</script>");
	}

	if(intval($_POST['sl']) == 0){
		exit("<script language=javascript> alert('".$shoppingnum."');history.go(-1);</script>");
	}

	@$id=intval($_POST['pid']) ? $_POST['pid'] : null;
	if (!is_numeric($id) || !isset($id)) {
		exit("no!");
	}

	if(empty($_POST['ourphp_sx'])){
			$att = "";
		}else{
			$att = dowith_sql($_POST['ourphp_sx']);
	}

	$p = $db -> select("OP_Tuanset,OP_Tuanusernum,OP_Tuantime,OP_Tuannumber,OP_Specifications,OP_Title,OP_Market,OP_Webmarket,OP_Weight,OP_Minimg","ourphp_product","where id = ".intval($id));
	if($p[0] == 2){
		
	    $tuanbuyo = $db -> select("id","ourphp_orders","where OP_Ordersemail = '".$_SESSION['username']."' && OP_Ordersid = ".intval($id)." && OP_Tuanid = ".intval($_GET['tuanid']));
		if($tuanbuyo){
			exit("<script language=javascript> alert('".$tuanok."');history.go(-1);</script>");
		}
		
		$tuanbuy = $db -> select("id","ourphp_tuan","where OP_Tuanuser = '".$_SESSION['username']."' && OP_Tuanpid = ".intval($id)." && OP_Tuannum != OP_Tuanznum");
		if($tuanbuy){
			exit("<script language=javascript> alert('".$tuanok."');history.go(-1);</script>");
		}
		
		if(isset($_GET['tuanid'])){
			
			if(intval($_GET['tuanid']) != 0){
				$tuan = $db -> select("OP_Tuannum,OP_Tuanznum","ourphp_tuan","where id = ".intval($_GET['tuanid']));
				if($tuan){
					if($tuan[1] >= $tuan[0]){
						exit("<script language=javascript> alert('".$tuanusernum."');history.go(-1);</script>");
					}
				}else{
					exit("<script language=javascript> alert('".$tuantime."');history.go(-1);</script>");
				}
			}

		}else{
			exit("<script language=javascript> alert('".$tuantime."');history.go(-1);</script>");
		}

		$nowdate = date("Y-m-d H:i:s");
		if($nowdate >= $p[2]){
			exit("<script language=javascript> alert('".$tuantime."');history.go(-1);</script>");
		}
		
	}

	if($att != ''){
		if(!strpos($p[4],str_replace("、", ",", $att)) > 0){
			exit("<script language=javascript> alert('".$shoppingatt2."');history.go(-1);</script>");
		}
	}
	
	//团购
	if(isset($_GET['tuan'])){
		
		
		$db -> insert("`ourphp_orders`","
		`OP_Ordersname` = '".$p[5]."',
		`OP_Ordersid` = '".intval($id)."',
		`OP_Ordersnum` = '".intval($_POST['sl'])."',
		`OP_Ordersemail` = '".$_SESSION['username']."',
		`OP_Ordersusername` = '',
		`OP_Ordersusertel` = '',
		`OP_Ordersuseradd` = '',
		`OP_Ordersusetext` = '',
		`OP_Ordersproductatt` = '".dowith_sql($att)."',
		`OP_Orderswebmarket` = '".$p[6]."',
		`OP_Ordersusermarket` = '".$p[7]."',
		`OP_Ordersweight` = '".$p[8]."',
		`OP_Ordersfreight` = 0,
		`time` = '".date("Y-m-d H:i:s")."',
		`OP_Ordersnumber` = '".'OP'.randomkeys(7)."',
		`OP_Orderssend` = 1,
		`OP_Orderspay` = 1,
		`OP_Integralok` = 0,
		`OP_Tuanset` = 2,
		`OP_Ordersimg` = '".$p[9]."',
		`OP_Tuanid` = ".intval($_GET['tuanid']),"") or die($db -> error());
		$oid = $db -> insertid();
		
		if(isset($_REQUEST["type"])){
			$urltype = array(
				"shop" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-shoppingorders.html-&id=".$oid."&tuan&tuanid=".intval($_GET['tuanid'])
			); //手机
		}else{
			$urltype = array(
				"shop" => $ourphp['webpath']."?".$ourphp_Language."-shoppingorders.html-&id=".$oid."&tuan&tuanid=".intval($_GET['tuanid'])
			); //电脑
		}

		exit("<script language=javascript>location.replace('".$urltype['shop']."');</script>");
		
		
	}else{
		
		$pr = $db -> select("OP_Buyoffnum","ourphp_product","where id = ".intval($id));
		$op = $db -> select("`id`,`OP_Shopnum`","`ourphp_shoppingcart`","where `OP_Shopproductid` = '".intval($id)."' && `OP_Shopusername` = '".$_SESSION['username']."' && `OP_Shopatt` = '".$att."'");
		if($op){

			if($pr[0] != 0){
				if($op[1] >= $pr[0] || intval($_POST['sl']) > $pr[0]){
					echo "<script language=javascript> alert('此商品每位会员只限购".$pr[0]."份');history.go(-1);</script>";
					exit;
				}
			}
		
			$db -> update("`ourphp_shoppingcart`","`OP_Shopnum` = `OP_Shopnum` + ".intval($_POST['sl']),"where `OP_Shopproductid` = '".intval($id)."' && `OP_Shopusername` = '".$_SESSION['username']."' && `OP_Shopatt` = '".$att."'");
			
		}else{

			if($pr[0] != 0){
				if(intval($_POST['sl']) > $pr[0]){
					echo "<script language=javascript> alert('此商品每位会员只限购".$pr[0]."份');history.go(-1);</script>";
					exit;
				}
				$prbuy = $db -> select("COUNT(id) as num","ourphp_orders","where `OP_Ordersid` = ".intval($id)." && `OP_Ordersemail` = '".$_SESSION['username']."'");
				if($prbuy['num'] >= $pr[0]){
					echo "<script language=javascript> alert('此商品每位会员只限购".$pr[0]."份');history.go(-1);</script>";
					exit;
				}
			}
			
			$db -> insert("`ourphp_shoppingcart`","`OP_Shopproductid` = '".intval($id)."',`OP_Shopnum` = '".intval($_POST['sl'])."',`OP_Shopusername` = '".$_SESSION['username']."',`OP_Shopatt` = '".$att."',`OP_Shopkc` = '".intval($_POST['ourphp_kc'])."',`OP_Shophh` = '".dowith_sql($_POST['ourphp_hh'])."',`time` = '".date("Y-m-d H:i:s")."'","");
			
		}
		
		exit("<script language=javascript>location.replace('?".$ourphp_Language."-shoppingcart.html');</script>");
		
	}

}

function ourphp_productshop($id){
	global $db,$ourphp;
	$ourphp_rs = $db -> select("id,OP_Title,OP_Number,OP_Goodsno,OP_Brand,OP_Market,OP_Webmarket,OP_Stock,OP_Minimg,OP_Maximg,time,OP_Class,OP_Usermoney,OP_Weight,OP_Freight ,OP_Specifications ,OP_Usermoneyclass,`OP_Buyoffnum`","`ourphp_product`","where `id` = ".intval($id)); 
	if(substr($ourphp_rs[8],0,4) == 'http'){$minimg = $ourphp_rs[8];}elseif($ourphp_rs[8] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg = $ourphp['webpath'].$ourphp_rs[8];}
	if(substr($ourphp_rs[9],0,4) == 'http'){$maximg = $ourphp_rs[9];}elseif($ourphp_rs[9] == ''){$maximg = $ourphp['webpath'].'skin/noimage.png';}else{$maximg = $ourphp['webpath'].$ourphp_rs[9];}
	$rows = array(
		'id' => $ourphp_rs[0],
		'title' => $ourphp_rs[1],
		'number' => $ourphp_rs[2],
		'goodsno' => $ourphp_rs[3],
		'brand' => explode("|",$ourphp_rs[4]),
		'market' => $ourphp_rs[5],
		'webmarket' => $ourphp_rs[6],
		'stock' => $ourphp_rs[7],
		'minimg' => $minimg,
		'maximg' => $maximg,
		'time' => $ourphp_rs[10],
		'class' => $ourphp_rs[11],
		'usermoney' => $ourphp_rs[12],
		'weight' => $ourphp_rs[13],
		'freight' => $ourphp_rs[14],
		'specifications' => $ourphp_rs[15],
		'usermoneyclass' => $ourphp_rs[16],
		'quota' => $ourphp_rs[17],
	);
	return $rows;
}

function ourphp_moneyextract($type='',$class='',$money=''){
	if($type == "ourphp"){
		return array($money,$money);
	}elseif($class == null){
		return array($money,$money);
	}else{
		$type = str_replace('、',',',$type);
		$ourphp = strstr($class,$type);
		$a = explode("|",$ourphp);
		$b = explode(",",$a[0]);
		$c = array_slice($b,-3,2);
		return array($c[0],$c[1]);
	}
}

function ourphp_usermoney($usermoney,$webmarket,$usermoneyclass){
	global $db,$ourphp_productshop;
	$ourphp_rs = $db -> select("`OP_Userclass`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
	$Useremail = explode("|",$usermoney);
	foreach($Useremail as $op){
		$Useremailto = explode(":",$op);
		if($ourphp_rs[0] == $Useremailto[0]){
			$opcms = $Useremailto[1];
		}
	}
	if($usermoneyclass == 1){
		return $webmarket - $opcms;
	}else{
		if($opcms < 1){
			return $webmarket;
		}else{
			return $webmarket * ($opcms / 10);
		}
	}
	
}

$shopset = shopset();
function ourphp_shoppingcart(){
	global $db,$ourphp,$ourphp_productshop,$shopset; 
	$query = $db -> listgo("*","`ourphp_shoppingcart`","where `OP_Shopusername` = '".$_SESSION['username']."' order by id desc"); 
	$rows = array();
	$i = 1;
	$zj = '';	
	while($ourphp_rs = $db -> whilego($query)){
	
		$ourphp_productshop = ourphp_productshop($ourphp_rs['OP_Shopproductid']);
		if($ourphp_rs['OP_Shopatt'] == null){
			$zhdh = "ourphp";
		}else{
			$zhdh = $ourphp_rs['OP_Shophh'].'、'.$ourphp_rs['OP_Shopatt'];
		}
		$ourphp_moneyextract = ourphp_moneyextract($zhdh,$ourphp_productshop['specifications'],$ourphp_productshop['webmarket']);
		$usermarket = ourphp_usermoney($ourphp_productshop['usermoney'],$ourphp_moneyextract[1],$ourphp_productshop['usermoneyclass']);

		$zj += $usermarket * $ourphp_rs['OP_Shopnum'];
		
		$newatt = '';
		$att = explode("、",$ourphp_rs['OP_Shopatt']);
		foreach($att as $op){
			if(strstr($op,"uploadfile")){
				$newatt .= '<img src="'.$ourphp['webpath'].$op.'" width="20" height="20" />';
			}else{
				$newatt .= $op;
			}
		}
		
		if($ourphp_productshop['quota'] != 0){
			$prxg = quota($ourphp_productshop['quota']);
		}else{
			$prxg = '';
		}

			$rows[] = array(
				'i' => $i,
				'id' => $ourphp_productshop['id'],
				'cartid' => $ourphp_rs['id'],
				'title' => $ourphp_productshop['title'],
				'number' => $ourphp_rs['OP_Shopnum'],
				'attribute' => $ourphp_rs['OP_Shopatt'],
				'attribute2' => $newatt,
				'stock' => $ourphp_rs['OP_Shopkc'],
				'barcode' => $ourphp_rs['OP_Shophh'],
				'time' => $ourphp_rs['time'],
				'webmarket' => $ourphp_moneyextract[0],
				'usermarket' => $usermarket,
				'img' => $ourphp_productshop['minimg'],
				'weight' => $ourphp_productshop['weight'],
				'freight' => $ourphp_productshop['freight'],
				'totalt' => $usermarket * $ourphp_rs['OP_Shopnum'],
				'total' => $zj,
				'quota' => $prxg,
			);
			$i+=1;
	}
	return $rows;
}

function ourphp_userview(){ 
	global $db;
	$ourphp_rs = $db -> select("`OP_Useremail`,`OP_Username`,`OP_Usertel`,`OP_Useradd`,`OP_Usermoney`,`OP_Userintegral`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'"); 
	$userrows = array(
		'email' => $ourphp_rs[0],
		'name' => $ourphp_rs[1],
		'tel' => $ourphp_rs[2],
		'add' => $ourphp_rs[3],
		'money' => $ourphp_rs[4],
		'integral' => $ourphp_rs[5],
	);
	return $userrows;
}


if(ourphp_shoppingcart()){
	$ordersarray = 1;
}else{
	$ordersarray = 2;
}
$smarty->assign('ordersarray',$ordersarray);
$smarty->assign('shoppingcart',ourphp_shoppingcart());
$smarty->assign('userop',ourphp_userview());

?>