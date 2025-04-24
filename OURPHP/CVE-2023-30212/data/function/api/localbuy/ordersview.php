<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * OURPHP系统 会员处理接口
 *-------------------------------
*/

include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_Language.php';
include '../../ourphp_function.class.php';

$apikey = $ourphp['safecode'];
if(!isset($_GET['key'])){
	
	echo 'KEY不存在，请重试!';
	exit;
	
}else{
	
	$key = $_GET['key'];
	if($apikey != $key){
		echo 'KEY出错，请重试!';
		exit;
	}
	
}
if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

		if($_POST["OP_Ordersexpress"] == 1){
			$OP_Ordersexpress = $_POST["OP_Ordersexpress2"];
		}else{
			$OP_Ordersexpress = $_POST["OP_Ordersexpress"];
		}
			
		$query = $db -> update("`ourphp_orders`","`OP_Ordersusermarket` = '".dowith_sql($_POST["OP_Ordersusermarket"])."',`OP_Orderssend` = '".dowith_sql($_POST["OP_Orderssend"])."',`OP_Ordersexpress` = '".dowith_sql($OP_Ordersexpress)."',`OP_Ordersexpressnum` = '".dowith_sql($_POST["OP_Ordersexpressnum"])."',`OP_Ordersfreight` = '".dowith_sql($_POST["OP_Ordersfreight"])."',`OP_Ordersgotime` = '".date("Y-m-d H:i:s")."',`OP_Sign` = '".dowith_sql($_POST["OP_Sign"])."'","where id = ".intval($_GET['id']));

		//注册成功，邮件提醒			
		if($_POST["OP_Orderssend"] == 2){
		$ourphp_mail = 'send';
		$OP_Ordersexpress = $OP_Ordersexpress;
		$OP_Ordersexpressnum = $_POST["OP_Ordersexpressnum"];
		$OP_Ordersnumber = $_POST["OP_Ordersnumber"];
		$OP_Useremail = dowith_sql(htmlspecialchars($_POST["OP_Useremail"]));
		include '../../ourphp_mail.class.php';
		}
		
		header("location: buylist.php?key=".$key);
		exit(0);	
}

$rs = $db -> select("*","`ourphp_orders`","where `id` = ".intval($_GET['id']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OurPHP订单宝</title>
<script type="text/javascript">
function clickIE4(){
        if (event.button==2){
                return false;
        }
}
function clickNS4(e){
        if (document.layers||document.getElementById&&!document.all){
                if (e.which==2||e.which==3){
                        return false;
                }
        }
}
function OnDeny(){
        if(event.ctrlKey || event.keyCode==78 && event.ctrlKey || event.altKey || event.altKey && event.keyCode==115){
                return false;
        }
}
if (document.layers){
        document.captureEvents(Event.MOUSEDOWN);
        document.onmousedown=clickNS4;
        document.onkeydown=OnDeny();
}else if (document.all&&!document.getElementById){
        document.onmousedown=clickIE4;
        document.onkeydown=OnDeny();
}
document.oncontextmenu=new Function("return false");
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="ordersview.php?ourphp_cms=edit&id=<?php echo $rs['id']; ?>&key=<?php echo $key; ?>">
<table width="100%" border="1" align="center" cellpadding="5" bordercolor="#f4f4f4" style="border-collapse:collapse; font-size:12px">
  <tr>
    <td colspan="2" bgcolor="#f5f5f5">订单宝 - 订单处理:&nbsp;&nbsp;<a href="javascript:history.go(-1);">向上一页</a></td>
  </tr>
  <tr>
    <td width="20%"><div align="right">订单号：</div></td>
    <td width="80%"><?php echo $rs['OP_Ordersnumber']; ?>
					  <input type="hidden" name="OP_Ordersnumber" value="<?php echo $rs['OP_Ordersnumber']; ?>" /></td>
  </tr>
  <tr>
    <td><div align="right">订单时间：</div></td>
    <td><?php echo $rs['time']; ?></td>
  </tr>
  <tr>
    <td><div align="right">订购商品：</div></td>
    <td><?php echo $rs['OP_Ordersname']; ?></td>
  </tr>
  <tr>
    <td><div align="right">用户要求：</div></td>
    <td><?php echo $rs['OP_Ordersproductatt']; ?></td>
  </tr>
  <tr>
    <td><div align="right">备注：</div></td>
    <td><?php echo $rs['OP_Ordersusetext']; ?></td>
  </tr>
  <tr>
    <td><div align="right">网站价格：</div></td>
    <td><?php echo $rs['OP_Orderswebmarket']; ?>&nbsp;&nbsp;元</td>
  </tr>
  <tr>
    <td><div align="right">TA的价格(成交价)：</div></td>
    <td><input type="text" name="OP_Ordersusermarket" class="win3" value="<?php echo $rs['OP_Orderswebmarket']; ?>" />&nbsp;&nbsp;元</td>
  </tr>
  <tr>
    <td><div align="right">购买数量：</div></td>
    <td><?php echo $rs['OP_Ordersnum']; ?></td>
  </tr>
  <tr>
    <td><div align="right">商品重量：</div></td>
    <td><?php echo $rs['OP_Ordersweight']; ?></td>
  </tr>
  <tr>
    <td><div align="right">快递运费：</div></td>
    <td><input type="text" name="OP_Ordersfreight" class="win3" value="<?php echo $rs['OP_Ordersfreight']; ?>" />&nbsp;&nbsp;元</td>
  </tr>
  <tr>
    <td><div align="right">购买人账号：</div></td>
    <td><?php echo $rs['OP_Ordersemail']; ?>
				      <input type="hidden" name="OP_Useremail" value="<?php echo $rs['OP_Ordersemail']; ?>" /></td>
  </tr>
  <tr>
    <td><div align="right">购买人姓名：</div></td>
    <td><?php echo $rs['OP_Ordersusername']; ?></td>
  </tr>
  <tr>
    <td><div align="right">购买人电话：</div></td>
    <td><?php echo $rs['OP_Ordersusertel']; ?></td>
  </tr>
  <tr>
    <td><div align="right">发货地址：</div></td>
    <td><?php echo $rs['OP_Ordersuseradd']; ?></td>
  </tr>
  <tr>
    <td><div align="right">是否付款?：</div></td>
    <td>
	
     <?php 
	  if($rs['OP_Usermoneyback'] != 3){
			if($rs['OP_Integralok'] == 0){
				
				if($rs['OP_Orderspay'] == 1){
					echo '<img src="../../../skin/weifukuan.gif" border="0" />';
				}elseif($rs['OP_Orderspay'] == 2){
					echo '<img src="../../../skin/yifukuan.gif" border="0" />';
				}elseif($rs['OP_Orderspay'] == 3){
					echo '<img src="../../../skin/hdfukuan.gif" border="0" />(货到付款，在发货前一定要与买家联系好！)';
				}elseif($rs['OP_Orderspay'] == 4){
					echo '使用【虚拟币】支付';
				}
				
			}else{
				echo '<img src="../../../skin/jfdh.gif" border="0" />';
			}
	  }else{
		  echo '已退款';
	  }
	?>
	
	</td>
  </tr>
  <tr>
    <td><div align="right">是否发货?：</div></td>
    <td>

						<input name="OP_Orderssend" type="radio" value="1" <?php if ($rs['OP_Orderssend'] == 1) { ?>checked="checked"<?php }?> />
                        不发货 
                        <input type="radio" name="OP_Orderssend" value="2" <?php if ($rs['OP_Orderssend'] == 2) { ?>checked="checked"<?php }?> />
                        发货
	
	</td>
  </tr>
  <tr>
    <td><div align="right">用户是否签收?：</div></td>
    <td>

						<input name="OP_Sign" type="radio" value="0" <?php if ($rs['OP_Sign'] == 0) { ?>checked="checked"<?php }?> />
                        未签收 
                        <input type="radio" name="OP_Sign" value="1" <?php if ($rs['OP_Sign'] == 1) { ?>checked="checked"<?php }?> />
                        已签收
	
	</td>
  </tr>
  <tr>
    <td><div align="right">快递名称：</div></td>
    <td>
	
		<div style="float:left;">
		<select name="OP_Ordersexpress" onchange = "showandhide(this.value)" class="win3">
			<option value="no" <?php if ($rs['OP_Ordersexpress'] == 'no') { ?>selected="selected"<?php }?>>不使用物流</option>
			<option value="EMS" <?php if ($rs['OP_Ordersexpress'] == 'EMS') { ?>selected="selected"<?php }?>>EMS</option>
			<option value="SFEXPRESS" <?php if ($rs['OP_Ordersexpress'] == 'SFEXPRESS') { ?>selected="selected"<?php }?>>顺丰速运</option>
			<option value="JITU" <?php if ($rs['OP_Ordersexpress'] == 'JITU') { ?>selected="selected"<?php }?>>极兔速递</option>
			<option value="STO" <?php if ($rs['OP_Ordersexpress'] == 'STO') { ?>selected="selected"<?php }?>>申通快递</option>
			<option value="TTKDEX" <?php if ($rs['OP_Ordersexpress'] == 'TTKDEX') { ?>selected="selected"<?php }?>>天天快递</option>
			<option value="YTO" <?php if ($rs['OP_Ordersexpress'] == 'YTO') { ?>selected="selected"<?php }?>>圆通速递</option>
			<option value="YUNDA" <?php if ($rs['OP_Ordersexpress'] == 'YUNDA') { ?>selected="selected"<?php }?>>韵达快运</option>
			<option value="ZTO" <?php if ($rs['OP_Ordersexpress'] == 'ZTO') { ?>selected="selected"<?php }?>>中通速递</option>
			<option value="ZJS" <?php if ($rs['OP_Ordersexpress'] == 'ZJS') { ?>selected="selected"<?php }?>>宅急送</option>
			<option value="FEDEX" <?php if ($rs['OP_Ordersexpress'] == 'FEDEX') { ?>selected="selected"<?php }?>>联邦快递（国内）</option>
			<option value="FEDEX_GJ" <?php if ($rs['OP_Ordersexpress'] == 'FEDEX_GJ') { ?>selected="selected"<?php }?>>联邦快递（国际）</option>
			<option value="QFKD" <?php if ($rs['OP_Ordersexpress'] == 'QFKD') { ?>selected="selected"<?php }?>>全峰快递</option>
			<option value="CRE" <?php if ($rs['OP_Ordersexpress'] == 'CRE') { ?>selected="selected"<?php }?>>中铁快运</option>
			<option value="1" <?php if ($rs['OP_Ordersexpress'] == '1') { ?>selected="selected"<?php }?> >===其它===</option>
		</select>
		</div>
		<div id="1" style="float:left;display:none;">
			&nbsp;&nbsp;其它快递代码：
			<input name="OP_Ordersexpress2" type="text" class="win3" value="<?php echo $rs['OP_Ordersexpress']; ?>"  />
			(目前采用的接口不支持中文，如果是发顺发快递，请输入 SFEXPRESS &nbsp;&nbsp;&nbsp;<a href="https://www.ourphp.net/kuaidi_code.html" target="_blank"><font color="#0099FF">查看快递参考代码</font></a>)
		</div>
		<div id="2" style="float:left;display:none;"></div>
	</td>
  </tr>
  <tr>
    <td><div align="right">快递单号：</div></td>
    <td><input name="OP_Ordersexpressnum" type="text" class="win" value="<?php echo $rs['OP_Ordersexpressnum']; ?>"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="处理订单" />&nbsp;&nbsp;<a href="javascript:history.go(-1);">向上一页</a></td>
  </tr>
</table>
</form>
<script>
 function showandhide(v){
  //alert(v);
  for(i=1;i<3;i++){
   document.getElementById(i).style.display = 'none';
   if(i==v){
    document.getElementById(v).style.display = 'block';
   }
  }
 }

function stop(){ 
return false; 
} 
document.oncontextmenu=stop; 
</script> 
</body>
</html>