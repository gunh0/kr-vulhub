<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "ourphpapi.php";
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

$buynumber = $ourphp_adminfont['buynumber'];

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

/*
 * OURPHP 逻辑模块
 * 验证开始
*/
$rs = $db -> select("`id`,`OP_Add`,`OP_Addtel`,`OP_Addname`","`ourphp_usershopadd`","where OP_Addindex = 1 && `OP_Adduser` = '".$_SESSION['username']."'");
if(!$rs){
	exit("<script language=javascript> alert('".$buynumber."');location.replace('".$ourphp['webpath']."client/wap/?cn-usershopadd.html');</script>");
}

$getinfo = explode('_',$_GET['state']);

if ($getinfo[0] == 0){
	exit("请先选购商品!");
}

if(preg_match('/[a-zA-Z]/',$getinfo[0])){
	exit("no2!"); 
}else{
	$shopid = $getinfo[0];
}

$idsafe = str_replace(",","",$shopid);
$idsafe = md5($idsafe.$ourphp['safecode']);
if($getinfo[1] != $idsafe){
	exit("参数出错!");
}

$query = $db -> listgo("`OP_Ordersnum`,`OP_Ordersusermarket`,`OP_Ordersfreight`,`OP_Ordersid`,`OP_Ordersproductatt`","`ourphp_orders`","where `id` in (".$shopid.") && OP_Ordersemail = '".$_SESSION['username']."'");
$zj = '';
while($ourphp_rs = $db -> whilego($query)){
	$zj += ($ourphp_rs[0] * $ourphp_rs[1]) + $ourphp_rs[2];
}

if($zj < 0.01){
	exit("<script language=javascript> alert('金额不能低于0.01元');history.go(-1);</script>");
}

$zje = $zj * 100;

//优惠券
if($getinfo[2] == 'ourphp'){ 
	
	$zje = $zje;
	$coupon = explode('|','0|0|0');
	
}else{
	
	$coupon = explode('|',$getinfo[2]);
	if($zje < $coupon[2]){
		
		exit("<script language=javascript> alert('优惠券已过期,无法使用!~');history.go(-1);</script>");
		
	}else{
		
		$c = $db -> select("`id`,`OP_Timewin`","`ourphp_couponlist`","where `id` = ".intval($coupon[0])." && `OP_Type` = 0 && `OP_Md` = '".dowith_sql($coupon[1])."' && `OP_Username` = '".$_SESSION['username']."'");
		if($c){
			
			$date = date("Y-m-d H:i:s");
			if($c[1] != '0000-00-00 00:00:00'){
				if(strtotime($date) > strtotime($c[1])){
					exit("<script language=javascript> alert('优惠券已过期,无法使用!~');history.go(-1);</script>");
				}
			}
			
			$zje = $zje - $coupon[2];
			
		}else{
			
			exit("优惠券出错!");
			
		}
		
	}
	
}

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("商品购买微信付款");
$input->SetAttach("safeinfo|".$shopid."|".$safecode['id']."|".$safecode['md5code']."|".$coupon[0]."|".$coupon[2]."|".$rs[0]);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($zje);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("ourphp");
$input->SetNotify_url($web['webhttp'].$web['weburl']."/function/api/weixinh5pay/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付</title>
    <script language="JavaScript" type="text/javascript" src="../../plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg == "get_brand_wcpay_request:ok"){  
          
                     window.location.href = "<?php echo $ourphp['webpath']; ?>client/wap/?cn-usershopping-op.html";  
                     alert("微信支付成功!");  
          
                }else if(res.err_msg == "get_brand_wcpay_request:cancel"){  

                	window.location.href = "<?php echo $ourphp['webpath']; ?>client/wap/?cn-usershopping-op.html"; 
                    alert("取消支付!");  

                }else{  

                	window.location.href = "<?php echo $ourphp['webpath']; ?>client/wap/?cn-usershopping-op.html"; 
                    alert("支付失败请联系客服!");  

                } 
			}
		);
	}

	function callpay()
	{

		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}

	$(function(){
		callpay();
	})
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				//alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};
	</script>
</head>
<body>
    <div style="text-align:center; width:100%; height:50px; color:#9ACD32; margin:20px auto; float:left;">
    	<b>需要支付：<span style="color:#f00;font-size:40px"><?php echo $zje / 100; ?></span>元</b>
    </div>
</body>
</html>