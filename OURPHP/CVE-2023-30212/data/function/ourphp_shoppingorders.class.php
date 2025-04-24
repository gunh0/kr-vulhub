<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

/*
	$buyurltype = 1; 1为直接跳转到结算页  2跳转到订单页
*/
$buyurltype = 1;

$bookadd = $ourphp_adminfont['bookadd'];
$accessno = $ourphp_adminfont['accessno'];
$shoppingok = $ourphp_adminfont['shoppingok'];
$usermoneyno = $ourphp_adminfont['usermoneyno'];
$couponfont = $ourphp_adminfont['coupon'];
$buynumber = $ourphp_adminfont['buynumber'];

if(empty($_SESSION['username'])){
	echo @ourphp_pcwapurl($_GET['type'],'?'.$ourphp_Language.'-login.html','?'.$ourphp_Language.'-userlogin.html',0,$shoppinglogin);
	exit;
}

if(isset($_REQUEST["type"])){
	$urltype = array(
		"shop" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-usershopping-op.html",
		"car" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-shoppingcart.html",
		"buy" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-shoppingorders.html",
		"tuan" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-usertuanlist-op.html",
		"usershopadd" => $ourphp['webpath']."client/wap/?".$ourphp_Language."-usershopadd.html",
	); //手机
}else{
	$urltype = array(
		"shop" => $ourphp['webpath']."client/user/?".$ourphp_Language."-usershopping-op.html",
		"car" => $ourphp['webpath']."?".$ourphp_Language."-shoppingcart.html",
		"buy" => $ourphp['webpath']."?".$ourphp_Language."-shoppingorders.html",
		"tuan" => $ourphp['webpath']."client/user/?".$ourphp_Language."-usertuanlist-op.html",
		"usershopadd" => $ourphp['webpath']."client/user/?".$ourphp_Language."-usershopadd.html",
	); //电脑
}

//计算运费
function ourphp_freight($add,$weight,$freight,$number){ 
	global $db;
	$ourphp_rs = $db -> select("`OP_Freighttext`,`OP_Freightweight`","`ourphp_freight`","where `id` = ".intval($freight));
	$freightop = explode('|',$ourphp_rs[0]."|0"); //首重
	$weightop = $ourphp_rs[1]; //续重
	
	$city = explode('|','北京市|天津市|上海市|重庆市|国外|河北省|河南省|云南省|辽宁省|黑龙江省|湖南省|安徽省|山东省|新疆|江苏省|浙江省|江西省|湖北省|广西|甘肃省|山西省|内蒙古|陕西省|吉林省|福建省|贵州省|广东省|青海省|西藏|四川省|宁夏|海南省|台湾|香港|澳门|本地IP');

	$i=0;
	foreach($city as $op){
		if(strstr($add,$op)){
			$ok = $i;
			break;
		}else{
			$ok = 35;
		}
		$i += 1;
	}
	
	if($number < 2){
		if($weight < 2){
		$yf = $freightop[$ok];
		}else{
		$yf = ($weight - 1) * $weightop + $freightop[$ok];
		}
	}else{
	
		$yfop = $number * $weight;
		$yf = ($yfop - 1) * $weightop + $freightop[$ok];
		
	}

	return $yf;
}

function ourphp_userview(){ 
global $db;
	$ourphp_rs = $db -> select("`OP_Usermoney`,`OP_Userintegral`,`OP_Usercoin`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'"); 
	$userrows = array(
		'money' => $ourphp_rs[0],
		'integral' => $ourphp_rs[1],
		'coin' => $ourphp_rs[2],
	);
	return $userrows;
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
	global $db;
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

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "buy"){
	
	$shoppingnum = $ourphp_adminfont['shoppingnum'];

	if (!empty($_POST["ourphp_opcms"])){
		$a = str_replace(',','，',$_POST["ourphp_opcms"]);
		$b = implode(',',$a);
		$c = str_replace(',|,','|',$b);
		$ourphp_opcms = substr($c, 0, -2);
	}else{
		exit("<script language=javascript>location.replace('".$urltype['car']."');</script>");
	}
	
	$oid = '';
	$fg = explode('|',$ourphp_opcms);
	foreach($fg as $op){
		$opcms = explode(',',$op);
		
		//判断数量是否为零
		if($opcms[7] < 1){
			exit("<script language=javascript> alert('".$shoppingnum."');history.go(-1);</script>");
		}
		//判断产品价格是否被篡改
		$ourphp_rs = $db -> select("`OP_Goodsno`,`OP_Webmarket`,`OP_Usermoney`,`OP_Specifications`,`OP_Usermoneyclass`,`OP_Buyoffnum`,`OP_Minimg`","`ourphp_product`","where `id` = ".intval($opcms[0]));

		if($ourphp_rs[3] == '')
		{
			$usermarket = ourphp_usermoney($ourphp_rs[2],$ourphp_rs[1],$ourphp_rs[4]);
			
		}else{
			
			if($opcms[2] == null){
				$zhdh = "ourphp";
			}else{
				$zhdh = $opcms[9].'、'.$opcms[2];
			}
			$ourphp_moneyextract = ourphp_moneyextract($zhdh,$ourphp_rs[3],$opcms[5]);
			$usermarket = ourphp_usermoney($ourphp_rs[2],$ourphp_moneyextract[1],$ourphp_rs[4]);
		}

		if(strcmp($opcms[6],$usermarket) != 0){
			exit("<script language=javascript> alert('".$usermoneyno."');history.go(-1);</script>");
		}
		
		if($ourphp_rs[5] != 0){
			if($opcms[7] > $ourphp_rs[5]){
				exit("<script language=javascript> alert('限购商品请重新选择数量!');history.go(-1);</script>");
			}
		}

		$db -> insert("`ourphp_orders`","
		`OP_Ordersname` = '".dowith_sql($opcms[1])."',
		`OP_Ordersid` = '".dowith_sql($opcms[0])."',
		`OP_Ordersnum` = '".dowith_sql($opcms[7])."',
		`OP_Ordersemail` = '".$_SESSION['username']."',
		`OP_Ordersusername` = '',
		`OP_Ordersusertel` = '',
		`OP_Ordersuseradd` = '',
		`OP_Ordersusetext` = '".dowith_sql($opcms[8])."',
		`OP_Ordersproductatt` = '".dowith_sql($opcms[2])."',
		`OP_Orderswebmarket` = '".dowith_sql($opcms[5])."',
		`OP_Ordersusermarket` = '".dowith_sql($opcms[6])."',
		`OP_Ordersweight` = '".dowith_sql($opcms[3])."',
		`OP_Ordersfreight` = 0,
		`time` = '".date("Y-m-d H:i:s")."',
		`OP_Ordersnumber` = '".'OP'.randomkeys(7)."',
		`OP_Orderssend` = 1,
		`OP_Orderspay` = 1,
		`OP_Ordersimg` = '".$ourphp_rs[6]."',
		`OP_Integralok` = 0","") or die($db -> error());
		
		$buycarid[] = $db -> insertid();
	}
	
	
	$db -> del("`ourphp_shoppingcart`","where `OP_Shopusername` = '".$_SESSION['username']."'");
	
	if($buyurltype == 1){
		
		$op_b = implode('_',$buycarid);
		exit("<script language=javascript>location.replace('".$urltype['buy']."-&id=".$op_b."');</script>");
		
	}else if($buyurltype == 2){
		
		exit("<script language=javascript>location.replace('".$urltype['shop']."');</script>");
		
	}
			
}elseif ($_GET["ourphp_cms"] == "buyok"){

	$buynumber = $ourphp_adminfont['buynumber'];
	$rs = $db -> select("`id`,`OP_Add`,`OP_Addtel`,`OP_Addname`","`ourphp_usershopadd`","where OP_Addindex = 1 && `OP_Adduser` = '".$_SESSION['username']."'");
	if(!$rs){
		exit("<script language=javascript> alert('".$buynumber."');location.replace('".$urltype['usershopadd']."');</script>");
	}
	
	if (!empty($_POST["id"])){
		$id = implode(',',$_POST["id"]);
	}else{
		$id = 0;
	}
	
	if ($id == 0){
		exit("请先选购商品!");
	}
	
	if(preg_match('/[a-zA-Z]/',$id)){
		exit("no!"); 
	}else{
		$id = $id;
	}
	
	$idsafe = str_replace(",","",$id);
	$idsafe = md5($idsafe.$ourphp['safecode']);
	if($_POST['idmd5'] != $idsafe){
		exit("no!");
	}
	
	$query = $db -> listgo("`OP_Ordersnum`,`OP_Ordersusermarket`,`OP_Ordersfreight`,`OP_Ordersid`,`OP_Ordersproductatt`,`OP_Tuanset`,`OP_Tuanid`","`ourphp_orders`","where `id` in (".$id.") && OP_Ordersemail = '".$_SESSION['username']."'");
	$zj = '';
	$datarows = array();
	while($ourphp_rs = $db -> whilego($query)){
		$zj += ($ourphp_rs[0] * $ourphp_rs[1]) + $ourphp_rs[2];
		$datarows[] = $ourphp_rs;
	}

	
	//优惠券
	if($_POST['coupon'] == 'ourphp'){ 
		
		$zj = $zj;
		$coupon = explode('|','0|0|0');
		
	}else{
		
		$coupon = explode('|',$_POST['coupon']);
		if($zj < $coupon[2]){
			
			exit("<script language=javascript> alert('".$couponfont."');history.go(-1);</script>");
			
		}else{
			
			$c = $db -> select("`id`,`OP_Timewin`","`ourphp_couponlist`","where `id` = ".intval($coupon[0])." && `OP_Type` = 0 && `OP_Md` = '".dowith_sql($coupon[1])."' && `OP_Username` = '".$_SESSION['username']."'");
			if($c){
				
				$date = date("Y-m-d H:i:s");
				if($c[1] != '0000-00-00 00:00:00'){
					if(strtotime($date) > strtotime($c[1])){
						exit("<script language=javascript> alert('".$couponfont."');history.go(-1);</script>");
					}
				}
				
				$zj = $zj - $coupon[2];
				
			}else{
				
				exit("<script language=javascript> alert('".$couponfont."');history.go(-1);</script>");
				
			}
			
		}
		
	}
	
	// 1.8.2 生成统一订单列表
	function orders_buylist($a, $b, $c, $d, $e, $f){
		global $db;
		$buy = $db -> insert("`ourphp_orderslist`","`OP_Ordersnum` = '".'OP'.randomkeys(7)."',`OP_Ordersid` = '".$a."',`OP_Orderscouponid` = ".intval($b).",`OP_Ordersusername` = '".admin_sql($c)."',`OP_Ordersusertel` = '".admin_sql($d)."',`OP_Ordersuseradd` = '".admin_sql($e)."',`OP_Orderscouponmoney` = ".admin_sql($f).",`OP_Ordersuser` = '".$_SESSION['username']."',`time` = '".date("Y-m-d H:i:s")."'","") or die ($db -> error());
		return $db -> insertid();
	}

	if($_POST['delivery'] == 1){
		
		if($shopsetgg['delivery'] == 0){
			exit("<script language=javascript> alert('".$accessno."');location.replace('".$urltype['shop']."');</script>");
		}else{
			
			$newid = orders_buylist($id,$coupon[0],$rs[3],$rs[2],$rs[1],$coupon[2]);
			$db -> update("`ourphp_orders`","
			`OP_Orderspay` = 3,
			`OP_Ordersclassid` = ".intval($newid)." where id in (".$id.") && OP_Ordersemail = '".$_SESSION['username']."'",""); 
		}
		
	}else{
		
		$query = $db -> select("`OP_Usermoney`,`OP_Usercoin`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");

		if($_POST['coin'] == 'ok'){

			if($query[1] < $zj){
				exit("<script language=javascript> alert('虚拟币不足用于支付请重试！');location.replace('".$urltype['shop']."');</script>");
			}

			$newid = orders_buylist($id,$coupon[0],$rs[3],$rs[2],$rs[1],$coupon[2]);
			$db -> update("`ourphp_orders`","
			`OP_Orderspay` = 4,
			`OP_Ordersclassid` = ".intval($newid)." where id in (".$id.") && OP_Ordersemail = '".$_SESSION['username']."'",""); 
			$db -> update("`ourphp_user`","`OP_Usercoin` = `OP_Usercoin` - ".$zj." where `OP_Useremail` = '".$_SESSION['username']."'","");

		}else{

			if($query[0] < $zj){
				exit("<script language=javascript> alert('".$accessno."');location.replace('".$urltype['shop']."');</script>");
			}
			
			$newid = orders_buylist($id,$coupon[0],$rs[3],$rs[2],$rs[1],$coupon[2]);
			$db -> update("`ourphp_orders`","
			`OP_Orderspay` = 2,
			`OP_Ordersclassid` = ".intval($newid)." where id in (".$id.") && OP_Ordersemail = '".$_SESSION['username']."'",""); 
			$db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` - ".$zj." where `OP_Useremail` = '".$_SESSION['username']."'","");

		}
		
	}
	
	foreach($datarows as $ourphp_rs)
	{
		//减去库存
		$ourphp_rsrs = $db -> select("`OP_Specifications`,`id`,`OP_Title`,`OP_Market`,`OP_Webmarket`,`OP_Integral`,`OP_Tuanusernum`","`ourphp_product`","where id = ".$ourphp_rs[3]); 
		if($ourphp_rsrs){
			$o = '|'.$ourphp_rsrs[0];
			$u = $ourphp_rs[4];
			$r = $ourphp_rs[0];

			if(version_compare(PHP_VERSION,'5.5.0','<'))
			{

				$php = preg_replace("'((?:^|\|(?:[\A-Z0-9]+),$u),(?:[\d.]+,){2})(\d+)'e", "'$1'.($2-$r)", $o);

			}else{
			
				$u = str_replace("、", ",", $u);
				$php = preg_replace_callback("'((?:^|\|(?:[\A-Z0-9]+),$u),(?:[\d.]+,){2})(\d+)'", function($m){
					global $r;
				    return $m[1].($m[2] - $r);
				}, $o);

			}

			$querythree = $db -> update("`ourphp_product`","`OP_Specifications` = '".substr($php,1)."',`OP_Buynum` = `OP_Buynum` + ".$ourphp_rs[0].",`OP_Stock` = `OP_Stock` - ".$ourphp_rs[0].",`OP_Buytotalnum` = `OP_Buytotalnum` + ".$ourphp_rs[0],"where id = ".$ourphp_rs[3]);
		}
		
		//加入积分表
		if($ourphp_rsrs[5] > 0){
			$queryfor = $db -> insert("`ourphp_integral`","`OP_Iid` = '".$ourphp_rsrs[1]."', `OP_Iname` = '".$ourphp_rsrs[2]."', `OP_Imarket` = '".$ourphp_rsrs[3]."', `OP_Iwebmarket` = '".$ourphp_rsrs[4]."', `OP_Iintegral` = '".$ourphp_rsrs[5]."',`OP_Iconfirm` = 0, `OP_Iuseremail` = '".$_SESSION['username']."', `time` = '".date("Y-m-d H:i:s")."'","");
		}


		//处理团购
		$group = new userplus\group();
		$group -> grouppay($ourphp_rs[3], $ourphp_rs[5], $ourphp_rs[6], $_SESSION['username'], $id, $ourphp_rsrs[6]);		
	}
	
	$coupon_ok = $db -> update("`ourphp_couponlist`","`OP_Type`=1,`OP_Time`='".date("Y-m-d H:i:s")."'","where `id` = ".intval($coupon[0])." && `OP_Type` = 0 && `OP_Md` = '".dowith_sql($coupon[1])."' && `OP_Username` = '".$_SESSION['username']."'");

	//邮件提醒			
	$ourphp_mail = 'userbuy';
	$OP_Useremail = $_SESSION['username'];
	include WEB_ROOT.'/function/ourphp_mail.class.php';
	
	if($_POST['tuanset'] == 1){
		exit("<script language=javascript> alert('".$shoppingok."');location.replace('".$urltype['tuan']."');</script>");
	}else{
		exit("<script language=javascript> alert('".$shoppingok."');location.replace('".$urltype['shop']."');</script>");
	}
	
}

if (empty($_GET['id'])){
	$ordersid = 0;
}else{
	if(preg_match('/[a-zA-Z]/',$_GET['id'])){
		exit("no!"); 
	}else{
		$ordersid = str_replace("_",",",$_GET['id']);
	}
}

function ourphp_shopadd(){
	global $db;
	$rs = $db -> select("*","`ourphp_usershopadd`","where OP_Addindex = 1 && `OP_Adduser` = '".$_SESSION['username']."'");
	if($rs){
		$rows = array(
			'id' => $rs['id'],
			'name' => $rs['OP_Addname'],
			'tel' => $rs['OP_Addtel'],
			'add' => str_replace('|',',',$rs['OP_Add']),
			'index' => $rs['OP_Addindex'],
			'cityaddexplode' => explode('|',$rs['OP_Add']),
		);
	}else{
		$rows = array(
			'id' => 0,
			'name' => '',
			'tel' => '',
			'add' => '',
			'index' => 0,
			'cityaddexplode' => '',
		);
	}
	return $rows;
}

function ourphp_productshop($id){
	global $db,$ourphp;
	$ourphp_rs = $db -> select("OP_Minimg,OP_Freight,`OP_Buyoffnum`","`ourphp_product`","where `id` = ".intval($id)); 
	
	return array('img'=>'','freight'=>$ourphp_rs[1],'quota'=>$ourphp_rs[2]);
}

function ourphp_orderslist(){
	global $db,$ordersid,$ourphp; 
	if ($ordersid == 0){
		$query = $db -> listgo("`id`,`OP_Ordersname`,`OP_Ordersid`,`OP_Ordersnum`,`OP_Ordersusername`,`OP_Ordersusertel`,`OP_Ordersuseradd`,`OP_Ordersusetext`,`OP_Ordersproductatt`,`OP_Orderswebmarket`,`OP_Ordersusermarket`,`OP_Ordersnumber`,`OP_Ordersweight`,`OP_Ordersfreight`,`OP_Ordersadminoper`,`OP_Usermoneyback`,`OP_Ordersimg`","`ourphp_orders`","where `OP_Ordersemail` = '".$_SESSION['username']."' && OP_Orderspay = 1 && OP_Orderssend = 1 && `OP_Tuanset` = 1 order by id desc"); 
	}else{
		$query = $db -> listgo("`id`,`OP_Ordersname`,`OP_Ordersid`,`OP_Ordersnum`,`OP_Ordersusername`,`OP_Ordersusertel`,`OP_Ordersuseradd`,`OP_Ordersusetext`,`OP_Ordersproductatt`,`OP_Orderswebmarket`,`OP_Ordersusermarket`,`OP_Ordersnumber`,`OP_Ordersweight`,`OP_Ordersfreight`,`OP_Ordersadminoper`,`OP_Usermoneyback`,`OP_Ordersimg`","`ourphp_orders`","where (`id` in (".$ordersid.") && `OP_Ordersemail` = '".$_SESSION['username']."') && OP_Orderspay = 1 && OP_Orderssend = 1 order by id desc"); 
	}
	
	$rows = array();
	$i = 1;
	$jg = 0;
	$zj = 0;
	$yf = 0;
	$yffun = 0;
	$idmd5 = '';
	while($ourphp_rs = $db -> whilego($query)){
	
	$productshop = ourphp_productshop($ourphp_rs[2]);
	$shopaddinfo = ourphp_shopadd();
	if($ourphp_rs[14] == 0){
		$yffun = ourphp_freight($shopaddinfo['cityaddexplode'][0],$ourphp_rs[12],$productshop['freight'],$ourphp_rs[3]);
	}else{
		$yffun = $ourphp_rs[13];
	}
	
	$db -> update("`ourphp_orders`","`OP_Ordersusername` = '".$shopaddinfo['name']."',`OP_Ordersusertel` = '".$shopaddinfo['tel']."',`OP_Ordersuseradd` = '".$shopaddinfo['add']."',`OP_Ordersfreight` = '".$yffun."'","where id = ".$ourphp_rs[0]);
	
	$jg = $ourphp_rs[10] * $ourphp_rs[3];
	$zj += $jg;
	$yf = $yf + $yffun;
	$idmd5 = $idmd5 . $ourphp_rs[0];
	
	$newatt = '';
	$att = explode("、",$ourphp_rs[8]);
	foreach($att as $op){
		if(strstr($op,"uploadfile")){
			$newatt .= '<img src="'.$ourphp['webpath'].$op.'" width="20" height="20" />';
		}else{
			$newatt .= $op;
		}
	}
	
	if($productshop['quota'] != 0){
		$prxg = quota($productshop['quota']);
	}else{
		$prxg = '';
	}
	
	if(substr($ourphp_rs[16],0,4) == 'http')
		{
			$minimg = $ourphp_rs[16];
			}elseif($ourphp_rs[16] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[16];
	}
			
		$rows[] = array(
			'i' => $i,
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'prid' => $ourphp_rs[2],
			'num' => $ourphp_rs[3],
			'username' => $ourphp_rs[4],
			'usertel' => $ourphp_rs[5],
			'useradd' => str_replace("|",",",$ourphp_rs[6]),
			'text' => $ourphp_rs[7],
			'pratt' => $newatt,
			'webmarket' => $ourphp_rs[9],
			'usermarket' => $ourphp_rs[10],
			'number' => $ourphp_rs[11],
			'totalt' => $jg,
			'total' => $zj,
			'weight' => $ourphp_rs[12],
			'freight' => $yffun,
			'freightt' => $yf,
			'idmd5' => md5($idmd5.$ourphp['safecode']),
			'minimg' => $minimg,
			'moneyback' => $ourphp_rs[15],
			'quota' => $prxg,
		);
		$i+=1;
	}
	return $rows;
}

function ourphp_coupon($money = 0){ 
	global $db;
	$date = date("Y-m-d H:i:s");
	$query = $db -> listgo("*","`ourphp_couponlist`","where `OP_Type` = 0 && `OP_Username` = '".$_SESSION['username']."'"); 
	if($query){
		
		$title = '';
		$rows = array();
		while($rs = $db -> whilego($query)){
			
			$title = $db -> select("`OP_Title`,`OP_Money`","`ourphp_coupon`","where id = ".intval($rs['OP_Couponid']));
			if($rs['OP_Timewin'] != '0000-00-00 00:00:00'){
				if(strtotime($date) > strtotime($rs['OP_Timewin'])){
					$datenow = '(已过期)';
				}else{
					$datenow = '';
				}
			}else{
					$datenow = '';
			}
			
			if($rs['OP_Moneygo'] != 0){
				if($money < $rs['OP_Moneygo']){
					$rows = array();
				}else{
					$rows[] = array(
						'id' => $rs['id'],
						'md' => $rs['OP_Md'],
						'title' => $title[0].$datenow,
						'money' => $title[1],
					);
				}
			}else{
					$rows[] = array(
						'id' => $rs['id'],
						'md' => $rs['OP_Md'],
						'title' => $title[0].$datenow,
						'money' => $title[1],
					);
			}
			
		}
		
		return $rows;
		
	}else{
		
		return false;
		
	}
}

$smarty->assign('userpaypai',array(
	'alipay_quick' => plugsclass::plugs(3),
	'alipay_webpay' => plugsclass::plugs(4),
	'alipay_mobilepay' => plugsclass::plugs(8),
	'weixinpay' => plugsclass::plugs(9),
	'weixinh5pay' => plugsclass::plugs(10),
));

$ourphp_orderslist = ourphp_orderslist();
$ourphp_total = end($ourphp_orderslist);

if($ourphp_orderslist){
	$ordersarray = 1;
}else{
	$ordersarray = 2;
}

$smarty->assign('ordersarray',$ordersarray);
$smarty->assign('orderslist',$ourphp_orderslist);
$smarty->assign('coupon',ourphp_coupon($ourphp_total['total']));
$smarty->assign('userview',ourphp_userview());
$smarty->assign('shopadd',ourphp_shopadd());
?>