<?php 
/* 
* 积分处理
*/ 
include '../config/ourphp_code.php';
include '../config/ourphp_config.php';
include '../config/ourphp_Language.php';
include './ourphp_function.class.php';

session_start();
date_default_timezone_set('Asia/Shanghai');

$outlogin = $ourphp_adminfont['outlogin'];
$lackintegral = $ourphp_adminfont['lackintegral'];
$integraltook = $ourphp_adminfont['integraltook'];
$urltype = $_REQUEST["type"];

if(!isset($_SESSION['username'])){
	echo '<h5 style="width:100%; text-align:center; float:left; margin-top:50px;">请先登录在兑换！</h5>';
	exit;
}

$id = intval($_GET["id"]);
$ourphp_rs = $db -> select("`id`,`OP_Title`,`OP_Integralexchange`,`OP_Integralok`","`ourphp_product`","where `id` = ".$id);
if($ourphp_rs < 1 || $ourphp_rs[3] == 0){
	echo '此商品不支持用积分兑换!';
	exit(0);
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	$useremail = $db -> select("`OP_Userintegral`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
	if($useremail[0] < $ourphp_rs[2]){
		echo "<script language=javascript> alert('".$lackintegral."');history.go(-1);</script>";
		exit;
	}else{
		// 1.8.2 生成统一订单列表
		function orders_buylist($a, $b, $c, $d, $e, $f){
			global $db;
			$buy = $db -> insert("`ourphp_orderslist`","`OP_Ordersnum` = '".'OP'.randomkeys(7)."',`OP_Ordersid` = '".$a."',`OP_Orderscouponid` = ".intval($b).",`OP_Ordersusername` = '".admin_sql($c)."',`OP_Ordersusertel` = '".admin_sql($d)."',`OP_Ordersuseradd` = '".admin_sql($e)."',`OP_Orderscouponmoney` = ".admin_sql($f).",`OP_Ordersuser` = '".$_SESSION['username']."',`time` = '".date("Y-m-d H:i:S")."'","");
			return $db -> insertid();
		}
		$newid = orders_buylist($id,0,dowith_sql($_POST['name']),dowith_sql($_POST['tel']),dowith_sql($_POST['add']),0);
		
		$query = $db -> insert("`ourphp_orders`","`OP_Ordersname` = '".$ourphp_rs[1]."',`OP_Ordersid` = '".$ourphp_rs[0]."',`OP_Ordersnum` = 1,`OP_Ordersemail` = '".$_SESSION['username']."',`OP_Ordersusername` = '".dowith_sql($_POST['name'])."',`OP_Ordersusertel` = '".dowith_sql($_POST['tel'])."',`OP_Ordersuseradd` = '".dowith_sql($_POST['add'])."',`time` = '".date("Y-m-d H:i:s")."',`OP_Ordersnumber` = 'OP".randomkeys(7)."',`OP_Orderspay` = 2,`OP_Orderssend` = 1,`OP_Integralok` = 1,`OP_Ordersfreight` = 0,`OP_Ordersclassid` = ".intval($newid),"");
		$dbid = $db -> insertid();
		$dblist = $db -> update("ourphp_orderslist","`OP_Ordersid` = '".$dbid."'","where id = ".intval($newid));
		$userint = $db -> update("`ourphp_user`","`OP_Userintegral` = `OP_Userintegral` - ".$ourphp_rs[2],"where `OP_Useremail` = '".$_SESSION['username']."'");
		
		echo '<h5 style="width:100%; text-align:center; float:left; margin-top:50px;">兑换成功！在会员中心查看！</h5>';
		exit;
	}
}


?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
*{ font-size:14px; font-family:Arial, Helvetica, sans-serif;}
body{ background:url(../skin/ingb.jpg) top center}
.input { width:90%; height:25px; line-height:25px; border:1px #f4f4f4 solid;}
.input2 { width:90%; height:25px; line-height:25px; border:1px #f4f4f4 solid;}
</style>
<link rel="stylesheet" href="plugs/Validform/style.css" type="text/css" />
<script type="text/javascript" src="plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugs/Validform/Validform_v5.3.2.js"></script>
</head>

<body>
<form id="form1" name="form1" method="post" action="?ourphp_cms=add&id=<?php echo $id; ?>&type=<?php echo $urltype; ?>" class="registerform">
<table width="100%" border="0" cellpadding="2">
  <tr>
    <td width="85"><div align="right">商品名称：</div></td>
    <td>&nbsp;<?php echo $ourphp_rs[1];?></td>
  </tr>
  <tr>
    <td><div align="right">所需积分：</div></td>
    <td>&nbsp;<?php echo $ourphp_rs[2];?></td>
  </tr>
  <tr>
    <td><div align="right">收货人姓名：</div></td>
    <td><input type="text" name="name" class="input" datatype="*" nullmsg="收货人姓名是必填项!" /> *</td>
  </tr>
  <tr>
    <td><div align="right">收货人电话：</div></td>
    <td><input type="text" name="tel" class="input" datatype="m" nullmsg="收货人电话是必填项!" /> *</td>
  </tr>
  <tr>
    <td><div align="right">收货人地址：</div></td>
    <td><input type="text" name="add" class="input2" datatype="*" nullmsg="收货人地址是必填项!" /> *</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="确认兑换" style="width:100px; height:35px; border:0px; background:#CC0000; color:#FFFFFF;border-radius:5px" /></td>
  </tr>
</table>
</form>

<script type="text/javascript">
$(function(){
	$(".registerform").Validform();
})
</script>
</body>
</html>