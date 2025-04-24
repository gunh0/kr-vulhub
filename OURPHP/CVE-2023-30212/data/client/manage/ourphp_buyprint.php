<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单打印</title>
<link href="../../function/plugs/YIQI-UI/YIQI-UI.min.css" rel=stylesheet>
<style media=print type="text/css"> 
.noprint{visibility:hidden} 
</style> 
</head>
<body id="YIQI-UI">
<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2018 www.ourphp.net
* 开发者：哈尔滨伟成科技有限公司
* 订单打印
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(empty($_GET['id']) || empty($_GET['type'])){
	echo 'no!';
	exit;
}

$type = $_GET['type'];
if($type == 'min'){
	
	$rs = $db -> listgo("*","ourphp_orderslist","where id in(".admin_sql($_GET['id']).")");
	while($r = $db -> whilego($rs)){
		$id = explode(",",$r['OP_Ordersid']);
		echo '
			<table class="table table-border table-bordered mt-15" style="width:260px;">
			  <tr>
				<td>
					<p>会员账号：'.$r['OP_Ordersuser'].'</p>
					<p>时间：'.$r['time'].'</p>
				</td>
			  </tr>
			  <tr>
				<td>

					<table class="table table-border table-bordered table-striped">
					';
					$yf = 0;
					$money = 0;
					foreach ($id as $op){
						$rss = $db -> listgo("*","ourphp_orders","where id = ".$op);
						while($rr = $db -> whilego($rss)){
							if($rr['OP_Ordersproductatt'] == ''){
								$att = '';
							}else{
								$att = '('.$rr['OP_Ordersproductatt'].')';
							}
							echo '
							  <tr>
								<td>
									<p>'.$rr['OP_Ordersname'].' × '.$rr['OP_Ordersnum'].'&nbsp;&nbsp;'.$att.'</p>
									<p>价格:￥'.$rr['OP_Ordersusermarket'].'&nbsp;<del>￥'.$rr['OP_Orderswebmarket'].'</del>&nbsp;运费:'.$rr['OP_Ordersfreight'].'元</p>
								</td>
							  </tr>
							';
							$yf = $rr['OP_Ordersusermarket'] + $rr['OP_Ordersfreight'];
							$money = $money + $yf * $rr['OP_Ordersnum'];
						}
					}
					
					echo '
					</table>
				
				</td>
			  </tr>
			  <tr>
				<td>
					<p>优惠券：'.$r['OP_Orderscouponmoney'].' 元</p>
					<p>合计：'.($money - $r['OP_Orderscouponmoney']).' 元</p>
					<p><strong>物流信息</strong></p>
					<p>电话：'.$r['OP_Ordersusertel'].'</p>
					<p>姓名：'.$r['OP_Ordersusername'].'</p>
					<p>地址：'.str_replace("|","-",$r['OP_Ordersuseradd']).'</p>
				</td>
			  </tr>
			</table>
		';
	}
	
}elseif($type == 'max'){
	
	$rs = $db -> listgo("*","ourphp_orderslist","where id in(".admin_sql($_GET['id']).")");
	while($r = $db -> whilego($rs)){
		$id = explode(",",$r['OP_Ordersid']);
		echo '
			<table class="table table-border table-bordered mt-15" style="width:1200px;">
			  <tr>
				<td width="50%">
					会员账号：'.$r['OP_Ordersuser'].'
				</td>
				<td>
					订单时间：'.$r['time'].'
				</td>
			  </tr>
			  <tr>
				<td colspan="2">

					<table class="table table-border table-bordered table-striped">
					<tr>
						<td>商品名称</td>
						<td width="20%">购买数量</td>
						<td width="10%">网站价格</td>
						<td width="10%">成交价格</td>
						<td width="10%">运费</td>
					</tr>
					';
					$yf = 0;
					$money = 0;
					foreach ($id as $op){
						$rss = $db -> listgo("*","ourphp_orders","where id = ".$op);
						while($rr = $db -> whilego($rss)){
							if($rr['OP_Ordersproductatt'] == ''){
								$att = '';
							}else{
								$att = '('.$rr['OP_Ordersproductatt'].')';
							}
							echo '
							  <tr>
								<td>'.$rr['OP_Ordersname'].'</td>
								<td>'.$rr['OP_Ordersnum'].'&nbsp;&nbsp;'.$att.'</td>
								<td><del>￥'.$rr['OP_Orderswebmarket'].'</del></td>
								<td>￥'.$rr['OP_Ordersusermarket'].'</td>
								<td>'.$rr['OP_Ordersfreight'].'</td>
							  </tr>
							';
							$yf = $rr['OP_Ordersusermarket'] + $rr['OP_Ordersfreight'];
							$money = $money + $yf * $rr['OP_Ordersnum'];
						}
					}
					
					echo '
					</table>
				
				</td>
			  </tr>
			  <tr>
				<td>
					<p><strong>物流信息</strong></p>
					<p>电话：'.$r['OP_Ordersusertel'].'</p>
					<p>姓名：'.$r['OP_Ordersusername'].'</p>
					<p>地址：'.str_replace("|","-",$r['OP_Ordersuseradd']).'</p>
				</td>
				<td>
					<p>优惠券：'.$r['OP_Orderscouponmoney'].' 元</p>
					<p>合计：'.($money - $r['OP_Orderscouponmoney']).' 元</p>
				</td>
			  </tr>
			</table>
		';
	}
	
}else{
	
	echo 'no!';
	exit;
	
}
echo '<p class="noprint mt-20"><a href="javascript:print();" target="_self">打印本页</a>(建议使用google浏览器)</p>';
?>
</body>
</html>