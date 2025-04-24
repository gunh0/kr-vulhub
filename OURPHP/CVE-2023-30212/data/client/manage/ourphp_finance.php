<?php

/*
    财务统计
    v1.1.0 20230210
*/


include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(!isset($_GET['type'])){

	$aw = '';
	$bw = '';

}elseif ($_GET['type'] == 'all'){

	$aw = '';
	$bw = '';

}elseif ($_GET['type'] == 'today'){

	$aw = 'where to_days(time) = to_days(now())';
	$bw = ' and to_days(time) = to_days(now())';	

}elseif ($_GET['type'] == 'yesterday'){

	$aw = 'where DATEDIFF(time,NOW()) = -1';
	$bw = ' and DATEDIFF(time,NOW()) = -1';	

}elseif ($_GET['type'] == 'time'){

	if(empty($_GET['time'])){
		exit("<script language=javascript> alert('请输入时间查询范围');history.go(-1);</script>");
	}

	$time = explode(" - ", admin_sql($_GET['time']));

	$aw = "where `time` >= '".$time[0]."' and `time` <= '".$time[1]."'";
	$bw = " and (`time` >= '".$time[0]."' and `time` <= '".$time[1]."')";	
}


	$a = $db -> create("
		SELECT a, sum(b) as b, sum(c) as c, sum(d) as d, sum(e) as e, sum(f) as f, sum(g) as g, sum(h) as h, sum(i) as i, sum(j) as j, sum(k) as k, sum(l) as l, sum(m) as m, sum(n) as n, sum(o) as o, sum(p) as p, sum(q) as q FROM (
		 
		 SELECT count(*) as a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,sum(OP_Ordersusermarket) as m, 0 n, 0 o, 0 p, 0 q FROM ourphp_orders ".$aw." /*订单数量*/

		 UNION ALL

		 SELECT 0 a,count(*) as b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_orders where OP_Orderssend = 2 ".$bw." /*发货量*/

		 UNION ALL

		 SELECT 0 a,0 b,count(*) as c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, sum(OP_Ordersusermarket) as n, 0 o, 0 p, 0 q FROM ourphp_orders where OP_Orderspay = 2 ".$bw." /*交易量*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,count(*) as d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_usermoneyout ".$aw." /*提现量*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,count(*) as e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_coupon ".$aw." /*优惠券*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,count(*) as f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_couponlist ".$aw." /*优惠券领取*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,count(*) as g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_couponlist where OP_Type = 1 ".$bw." /*优惠券领取使用*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,count(*) as h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_user ".$aw." /*会员数量*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,OP_Usermoney as i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_userpay where OP_Userpaytype = '+' ".$bw." /*会员充值*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,OP_Usermoney as j,0 k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_userpay where OP_Userpaytype = '-' ".$bw." /*会员扣款*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,OP_Money as k,0 l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_coupon ".$aw." /*优惠券金额总计*/

		 UNION ALL

		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,OP_Useroutmoney as l,0 m, 0 n, 0 o, 0 p, 0 q FROM ourphp_usermoneyout ".$aw." /*提现金额总计*/

		 UNION ALL

		 SELECT count(*) as a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, sum(OP_Orderscouponmoney) as o, 0 p, 0 q FROM ourphp_orderslist ".$aw." /*优惠金额*/

		 UNION ALL

		 SELECT count(*) as a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, sum(OP_Usermoney) as p, 0 q FROM ourphp_userregreward ".$aw." /*邀请奖励*/
		 
		 UNION ALL
		 
		 SELECT 0 a,0 b,0 c,0 d,0 e,0 f,0 g,0 h,0 i,0 j,0 k,0 l,0 m, 0 n, 0 o, 0 p, count(*) as q FROM ourphp_orders where OP_Orderspay = 1 ".$bw." /*未付款订单*/
		 
		) ourphp group by a
	",2);
	$a = $db -> whilego($a);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>[.$ourphp_adminfont.admintitle.]</title>
<link href="templates/images/ourphp_login.css" rel="stylesheet" type="text/css"> 
<link href="../../function/plugs/YIQI-UI/YIQI-UI.min.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../function/plugs/laydate/laydate.js"></script>
<style type="text/css">
body {}
.title {line-height: 25px; font-size: 16px; border-left: 5px solid #4682B4; padding-left: 10px; width: 100%;}
.list li {padding: 10px 20px; margin-right: 10px; float: left;}
#YIQI-UI .list li h2 {border-right: 1px #5eb95e solid; padding-right: 20px;}
#YIQI-UI .list li h2 small{color: #5eb95e;}
.input {width: 300px; height: 36px; border: 1px #ccc solid; padding: 0 10px;}
.top {width: 93%; background: #f4f4f4;}
</style>
</head>
<body>
<div id="YIQI-UI">
	<div class="pt-30 pl-50 pr-50 pb-30 top">
		<p>
			<form name="search" method="get" action="" autocomplete="off">
			选择时间段 <input type="text" class="border input" id="test10" name="time" placeholder="选择时间" autocomplete="off">
			<input type="hidden" name="type" value="time">
			<input class="btn btn-success pl-20 pr-20 pt-10 pb-10" type="submit" value="查询">
			<a href="?type=all" class="btn btn-secondary radius-5 pd-10">全部</a>
			<a href="?type=today" class="btn btn-warning radius-5 pd-10">今天</a>
			<a href="?type=yesterday" class="btn btn-danger radius-5 pd-10">昨天</a> 
			</form>
		</p>
	</div>
	<div class="pt-20 pl-50 pr-50 pb-50">
		<div class="title">订单</div>
		<ul class="mt-20 list">
			<li>
					<h2><?php echo $a['a'];?>&nbsp;&nbsp;<small>订单总量</small></h2>
			</li>
			<li>
					<h2><?php echo $a['b'];?>&nbsp;&nbsp;<small>已发货订单</small></h2>
			</li>
			<li>
					<h2><?php echo $a['c'];?>&nbsp;&nbsp;<small>已成交订单</small></h2>
			</li>
			<li>
					<h2><?php echo $a['q'];?>&nbsp;&nbsp;<small>未付款订单</small></h2>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="pd-50">
		<div class="title">收支</div>
		<ul class="mt-20 list">
			<li>
					<h2>￥<?php echo $a['m'];?>&nbsp;&nbsp;<small>订单总额</small></h2>
			</li>
			<li>
					<h2>￥<?php echo $a['n'];?>&nbsp;&nbsp;<small>入账金额</small></h2>
			</li>
			<li>

					<h2>￥<?php echo $a['o'];?>&nbsp;&nbsp;<small>优惠金额</small></h2>
			</li>
			<li>

					<h2>￥<?php echo $a['p'];?>&nbsp;&nbsp;<small>邀请奖励</small></h2>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="pd-50">
		<div class="title">提现</div>
		<ul class="mt-20 list">
			<li>
					<h2><?php echo $a['d'];?>&nbsp;&nbsp;<small>笔</small></h2>
			</li>
			<li>

					<h2>￥<?php echo $a['l'];?>&nbsp;&nbsp;<small>合计金额</small></h2>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="pd-50">
		<div class="title">退款</div>
		<ul class="mt-20 list">
			<li>
				<?php
                  $aa = $db -> listgo("*","ourphp_orders","where OP_Usermoneyback = 3 ".$bw);
                  $ii = 0;
                  while($r = $db -> whilego($aa)){
                  	$ii = $ii + $r['OP_Ordersusermarket'];
                  }
                  ?>
					<h2>￥<?php echo $ii;?>&nbsp;&nbsp;<small>合计退款</small></h2>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="pd-50">
		<div class="title">优惠券</div>
		<ul class="mt-20 list">
			<li>
					<h2><?php echo $a['e'];?>&nbsp;&nbsp;<small>发布</small></h2>
			</li>
			<li>
					<h2><?php echo $a['f'];?>&nbsp;&nbsp;<small>领取</small></h2>
			</li>
			<li>
					<h2><?php echo $a['g'];?>&nbsp;&nbsp;<small>使用</small></h2>
			</li>
			<li>
					<h2>￥<?php echo $a['k'];?>&nbsp;&nbsp;<small>合计金额</small></h2>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="pd-50">
		<div class="title">会员</div>
		<ul class="mt-20 list">
			<li>
					<h2><?php echo $a['h'];?>&nbsp;&nbsp;<small>位</small></h2>
			</li>
			<li>

					<h2>￥<?php echo $a['i'];?>&nbsp;&nbsp;<small>后台充值</small></h2>
			</li>
			<li>

					<h2>￥<?php echo $a['j'];?>&nbsp;&nbsp;<small>后台扣款</small></h2>
			</li>
		</ul>
	</div>


	<div class="clear mt-50 f-l">&nbsp;<div>
</div>
<script type="text/javascript">
laydate.render({
  elem: '#test10',
  type: 'datetime',
  range: true
}); 
</script>
</body>
</html>