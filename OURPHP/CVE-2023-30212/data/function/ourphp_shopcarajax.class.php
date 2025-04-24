<?php
include '../config/ourphp_code.php';
include '../config/ourphp_config.php';
include 'ourphp_function.class.php';

function msg($error,$font)
{
	$a = array(
		"error" => $error,
		"msg" => $font
	);

	return json_encode($a);

}

$type = $_POST['type'];

if($type == 1){


	if($_POST['ourphp_kc'] == '' || $_POST['ourphp_hh'] == ''){
		echo msg(1,"库存或货号出错");
		exit;
	}

	if($_POST['ourphp_kc'] == "0" || $_POST['ourphp_hh'] == "0" || @$_POST['ourphp_sx'] == "0"){
		echo msg(1,"参数出错");
		exit;
	}

	if(intval($_POST['sl']) == 0){
		echo msg(1,"购买数量不能是零");
		exit;
	}

	@$id=intval($_POST['pid']) ? $_POST['pid'] : null;
	if (!is_numeric($id) || !isset($id)) {
		echo msg(1,"商品ID出错");
		exit;
	}

	if(empty($_POST['ourphp_sx'])){
			$att = "";
		}else{
			$att = dowith_sql($_POST['ourphp_sx']);
	}


	$p = $db -> select("OP_Tuanset,OP_Tuanusernum,OP_Tuantime,OP_Tuannumber,OP_Specifications","ourphp_product","where id = ".intval($id));
	if($p[0] == 2){
		if($p[3] >= $p[1]){
			echo msg(1,"拼团人数已够，请选择其它商品拼团!~");
			exit;
		}
		$nowdate = date("Y-m-d H:i:s");
		if($nowdate >= $p[2]){
			echo msg(1,"拼团时间已过，请选择其它商品拼团!~");
			exit;
		}
	}
	if($att != ''){
		if(!strpos($p[4],str_replace("、", ",", $att)) > 0){
			echo msg(1,"您选择的商品规格已断货，请选择其它规格！");
			exit;
		}
	}
	
	if(empty($_POST['user']) || empty($_POST['usermd'])){
		echo msg(1,"请先登陆");
		exit;
	}
	
	$user = MD5($_POST['user'].$ourphp['safecode']);
	if($user != $_POST['usermd']){
		echo msg(1,"请先登陆");
		exit;
	}
	
	$pr = $db -> select("OP_Buyoffnum","ourphp_product","where id = ".intval($id));
	$op = $db -> select("`id`,`OP_Shopnum`","`ourphp_shoppingcart`","where `OP_Shopproductid` = '".intval($id)."' && `OP_Shopusername` = '".dowith_sql($_POST['user'])."' && `OP_Shopatt` = '".$att."'");
	if($op){
		
		if($pr[0] != 0){
			if($op[1] >= $pr[0] || intval($_POST['sl']) > $pr[0]){
				echo msg(1,"此商品每位会员只限购".$pr[0]."份");
				exit;
			}
		}
		
		$db -> update("`ourphp_shoppingcart`","`OP_Shopnum` = `OP_Shopnum` + ".intval($_POST['sl']),"where `OP_Shopproductid` = '".intval($id)."' && `OP_Shopusername` = '".dowith_sql($_POST['user'])."'");
		
	}else{
		
		if($pr[0] != 0){
			if(intval($_POST['sl']) > $pr[0]){
				echo msg(1,"此商品每位会员只限购".$pr[0]."份");
				exit;
			}
			$prbuy = $db -> select("id","ourphp_orders","where `OP_Ordersid` = ".intval($id)." && `OP_Ordersemail` = '".dowith_sql($_POST['user'])."'");
			if($prbuy[0]){
				echo msg(1,"此商品每位会员只限购".$pr[0]."份");
				exit;
			}
		}
		
		$db -> insert("`ourphp_shoppingcart`","`OP_Shopproductid` = '".intval($id)."',`OP_Shopnum` = '".intval($_POST['sl'])."',`OP_Shopusername` = '".dowith_sql($_POST['user'])."',`OP_Shopatt` = '".$att."',`OP_Shopkc` = '".intval($_POST['ourphp_kc'])."',`OP_Shophh` = '".dowith_sql($_POST['ourphp_hh'])."',`time` = '".date("Y-m-d H:i:s")."'","");
		
	}

	echo msg(2,"此商品成功加入购物车！");
	exit;


}


if($type == 2){

	session_start();
	if(empty($_SESSION['username']))
	{
		echo 'no';
		exit;
	}
	if($_POST["md"] != MD5($_POST['id'].$ourphp['safecode']))
	{
		echo 'no';
		exit;	
	}

	$typec = $_POST["typec"];
	if($typec == 1)
	{
		$ourphp_carid = "+ 1";
	}
	if($typec == 2)
	{
		$carnum = $db -> select("OP_Shopnum","ourphp_shoppingcart","where id = ".intval($_POST['id'])." && `OP_Shopusername` = '".$_SESSION['username']."'");
		if($carnum[0] <= 1){
			echo 'no';
			exit;
		}
		$ourphp_carid = "- 1";
	}
	$db -> update("ourphp_shoppingcart","OP_Shopnum = OP_Shopnum ".$ourphp_carid,"where id = ".intval($_POST['id'])." && `OP_Shopusername` = '".$_SESSION['username']."'") or die ($db -> error());
	echo 1;

}
?>