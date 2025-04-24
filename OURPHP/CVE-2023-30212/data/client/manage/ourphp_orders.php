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

if(intval($_GET['page']) == 0)
{
	$page = 1;
}else{
	$page = intval($_GET['page']);
}
			
if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "Batch"){

	if($_POST['buy_del_print'] == 'del'){
		
		if (strstr($OP_Adminpower,"35")){
		
			if (!empty($_POST["op_b"])){
			$op_b = implode(',',admin_sql($_POST["op_b"]));
			}else{
			$op_b = '';
			}

			plugsclass::logs('删除订单(总)',$OP_Adminname);
			$db -> del("ourphp_orderslist","where id in ($op_b)");
			$orderid = explode(",",$op_b);
			foreach($orderid as $op){
				$orders = $db -> del("ourphp_orders","where OP_Ordersclassid = ".intval($op));
			}
			$ourphp_font = 1;
			$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
			require 'ourphp_remind.php';
		
		}else{
			
			$ourphp_font = 4;
			$ourphp_content = '权限不够，无法删除！';
			$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
			require 'ourphp_remind.php';
			
		}
		
	}
	
	if($_POST['buy_del_print'] == 'print'){
		
		if (!empty($_POST["op_b"])){
			$op_b = implode(',',$_POST["op_b"]);
			}else{
			$op_b = '';
		}
		if($op_b == ''){
			$ourphp_font = 4;
			$ourphp_content = '请选择要打印的订单！';
			$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
			require 'ourphp_remind.php';
		}
		
		plugsclass::logs('打印订单',$OP_Adminname);
		echo '<div style="width:100%; text-align:center; float:left; margin-top:150px;"><a href="ourphp_buyprint.php?id='.$op_b.'&type=min" target="_blank">小票打印页面</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="ourphp_buyprint.php?id='.$op_b.'&type=max" target="_blank">A4纸打印页面</a></div>';
		exit;
	}
	
	if($_POST['buy_del_print'] == 'go'){
		
		if (!empty($_POST["op_b"])){
			$op_b = implode(',',$_POST["op_b"]);
			}else{
			$op_b = '';
		}
		if($op_b == ''){
			$ourphp_font = 4;
			$ourphp_content = '请选择要发货的订单！';
			$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
			require 'ourphp_remind.php';
		}
		
		echo '
		<script>
		 function showandhide(v){
		  //alert(v);
		  for(i=1;i<3;i++){
		   document.getElementById(i).style.display = \'none\';
		   if(i==v){
			document.getElementById(v).style.display = \'block\';
		   }
		  }
		 }
		</script>
		<div style="width:90%; float:left; margin:50px 0 0 10%;">
			<form id="form2" name="form2" method="post" action="?ourphp_cms=go&page='.intval($_GET['page']).'">
				<p>
					批量发货订单ID : '.$op_b.'
				</p>
				<p>
				<input name="OP_Orderssend" type="radio" value="1" id="a1" />
                    <label for="a1">不发货</label>
                <input type="radio" name="OP_Orderssend" value="2" id="a2" checked="checked" />
                    <label for="a2">发货</label>
				</p>
				<p>
					<select name="OP_Ordersexpress" onchange = "showandhide(this.value)" class="win3">
						<option value="no" >不使用物流</option>
						<option value="EMS" >EMS</option>
						<option value="SFEXPRESS" >顺丰速运</option>
						<option value="JITU" >极兔速递</option>
						<option value="STO" >申通快递</option>
						<option value="TTKDEX" >天天快递</option>
						<option value="YTO" >圆通速递</option>
						<option value="YUNDA" >韵达快运</option>
						<option value="ZTO" >中通速递</option>
						<option value="ZJS" >宅急送</option>
						<option value="FEDEX" >联邦快递（国内）</option>
						<option value="FEDEX_GJ" >联邦快递（国际）</option>
						<option value="QFKD" >全峰快递</option>
						<option value="CRE" >中铁快运</option>
						<option value="1" >===其它===</option>
					</select>
				</p>
				<p>
					<div id="1" style="float:left;display:none;">
					  快递代码：<input name="OP_Ordersexpress2" type="text" class="win3" />
					  (目前采用的接口不支持中文，如果是发顺发快递，请输入 SFEXPRESS &nbsp;&nbsp;&nbsp;<a href="https://www.ourphp.net/kuaidi_code.html" target="_blank"><font color="#0099FF">查看快递参考代码</font></a>)
					</div>
				</p>
				<p style="clear:both; padding-top:10px;">
				快递单号：<input name="OP_Ordersexpressnum" type="text" class="win"  />
				<input name="oid" type="hidden" value="'.$op_b.'">
				</p>
				<p><input type="submit" name="Submit" value="提交订单" class="ourphp-anniu"/></p>
			</form>
		</div>
		
		';
		exit;
	}
	
	if($_POST['buy_del_print'] == 'buyok'){
		
		if (!empty($_POST["op_b"])){
			$op_b = implode(',',$_POST["op_b"]);
			}else{
			$op_b = '';
		}
		if($op_b == ''){
			$ourphp_font = 4;
			$ourphp_content = '请选择要签收的订单！';
			$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
			require 'ourphp_remind.php';
		}
		
		plugsclass::logs('签收订单',$OP_Adminname);
		$db -> update("ourphp_orders","`OP_Sign` = 1","where id in (".admin_sql($op_b).")");
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
		require 'ourphp_remind.php';
		exit;
	}
	
}elseif ($_GET["ourphp_cms"] == "go"){
	
	$oid = explode(",",$_POST['oid']);
	if($_POST["OP_Ordersexpress"] == 1){
	$OP_Ordersexpress = $_POST["OP_Ordersexpress2"];
	}else{
	$OP_Ordersexpress = $_POST["OP_Ordersexpress"];
	}
	foreach($oid as $op){
		$db -> update("ourphp_orders","`OP_Orderssend` = ".intval($_POST['OP_Orderssend']).",`OP_Ordersexpress` = '".admin_sql($OP_Ordersexpress)."',`OP_Ordersexpressnum` = '".admin_sql($_POST["OP_Ordersexpressnum"])."',`OP_Ordersgotime` = '".date("Y-m-d H:i:s")."'","where OP_Ordersclassid = ".intval($op));
		$db -> update("ourphp_orderslist","OP_Look = 1","where id = ".intval($op));
	}
	
	plugsclass::logs('订单批量发货',$OP_Adminname);
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_orders.php?id=ourphp&page='.$page;
	require 'ourphp_remind.php';
	
}

function Orderslist(){
	global $_page,$db,$smarty;
	$listpage = 30;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orderslist`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	
	if(isset($_GET['tuanall'])){
		$query = $db -> listgo("*","`ourphp_orderslist`","where OP_Ordersid in (".admin_sql($_GET['tid']).") order by id desc");
	}else{
		$query = $db -> listgo("*","`ourphp_orderslist`","order by id desc LIMIT ".$start.",".$listpage);
	}
	
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs['id'],
			"num" => $ourphp_rs['OP_Ordersnum'],
			"oid" => explode(",",$ourphp_rs['OP_Ordersid']),
			"fname" => $ourphp_rs['OP_Ordersusername'],
			"ftel" => $ourphp_rs['OP_Ordersusertel'],
			"fadd" => $ourphp_rs['OP_Ordersuseradd'],
			"couponmoney" => $ourphp_rs['OP_Orderscouponmoney'],
			"couponid" => $ourphp_rs['OP_Orderscouponid'],
			"user" => $ourphp_rs['OP_Ordersuser'],
			"time" => $ourphp_rs['time'],
			"look" => $ourphp_rs['OP_Look'],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

Admin_click('订单管理','ourphp_orders.php?id=ourphp');
$smarty->assign("orders",Orderslist());
$smarty->display('ourphp_orders.html');
?>