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

	function ourphp_parameters(){ 
		global $db;
		$ourphp_rs = $db -> select("`OP_Pagestype` ,`OP_Pages` ,`OP_Pagefont`","`ourphp_webdeploy`","where `id` = 1"); 
		$rows = array(
			'pagetype' => $ourphp_rs[0],
			'pagecss' => $ourphp_rs[1],
			'pagefont' => $ourphp_rs[2],
			);
		return $rows;
	}
	$Parameterse = ourphp_parameters();
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
<table width="100%" border="1" align="center" cellpadding="5" bordercolor="#f4f4f4" style="border-collapse:collapse; font-size:12px">
  <tr>
    <td colspan="8" bgcolor="#f5f5f5">订单宝管理中心:</td>
  </tr>
  <tr>
    <td width="30"><div align="center">ID</div></td>
    <td><div align="center">商品</div></td>
    <td width="150"><div align="center">订单号</div></td>
    <td width="130"><div align="center">订单时间</div></td>
    <td width="60"><div align="center">付款?</div></td>
    <td width="60"><div align="center">发货?</div></td>
    <td width="130"><div align="center">发货时间</div></td>
    <td width="80"><div align="center">操作</div></td>
  </tr>
  <?php
	
	include '../../ourphp_page.class.php';
	
	$listpage = 15;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Orderspay` = 1 || `OP_Orderssend` = 1");
	$ourphptotal = $db -> whilego($ourphptotal);
	
	$query = $db -> listgo("`id`,`OP_Ordersname`,`time`,`OP_Ordersnumber`,`OP_Orderspay`,`OP_Orderssend`,`OP_Ordersgotime`,`OP_Integralok`,`OP_Usermoneyback`","`ourphp_orders`","where `OP_Orderspay` = 1 || `OP_Orderssend` = 1 order by id desc LIMIT ".$start.",".$listpage);
	
	while($rs = $db -> whilego($query)){
  ?>
  <tr>
    <td><div align="center"><?php echo $rs[0]; ?></div></td>
    <td><?php echo $rs[1]; ?></td>
    <td><div align="center"><?php echo $rs[3]; ?></div></td>
    <td><div align="center"><?php echo $rs[2]; ?></div></td>
    <td><div align="center">
    <?php
	if($rs[8] != 3){
		if($rs[7] == 0){
			if($rs[4] == 1){
			echo '<img src="../../../skin/weifukuan.gif" border="0" />';
				}elseif ($rs[4] == 2){
			echo '<img src="../../../skin/yifukuan.gif" border="0" />';
				}elseif ($rs[4] == 3){
			echo '<img src="../../../skin/hdfukuan.gif" border="0" />';
				}elseif ($rs[4] == 4){
			echo '使用【虚拟币】支付';
			}
		}else{
			echo '<img src="../../../skin/jfdh.gif" border="0" />';
		}
	}else{
		echo '已退款';
	}
	?>
    </div></td>
    <td><div align="center">
    <?php 
		if($rs[5] == 1){
		echo '<img src="../../../skin/weifahuo.gif" border="0" />';
			}else{
		echo '<img src="../../../skin/yifahuo.gif" border="0" />';
		} 
	?>
    </div></td>
    <td><div align="center"><?php echo $rs[6]; ?></div></td>
    <td><div align="center"><a href="ordersview.php?id=<?php echo $rs[0]; ?>&key=<?php echo $key; ?>" target="_self"><img src="../../../skin/chulidingdan.gif" border="0" /></a></div></td>
  </tr>
  <?php
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
  ?>
  <tr>
    <td colspan="8">
		<?php echo $_page->showpage(); ?>
	</td>
  </tr>
</table>
</body>
</html>