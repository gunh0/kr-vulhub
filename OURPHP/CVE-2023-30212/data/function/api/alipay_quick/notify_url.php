<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
include '../../../config/ourphp_config.php';
include '../../ourphp_function.class.php';

$api = plugsclass::plugs(3);
if (!$api){
	exit(0);
}

$group = new userplus\group();

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
date_default_timezone_set('Asia/Shanghai'); //设置时区
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {

	$out_trade_no = $_POST['out_trade_no'];
	$trade_no = $_POST['trade_no'];
	$trade_status = $_POST['trade_status'];
	$total_fee = $_POST['total_fee'];
	$body = $_POST['body'];
	
    if($_POST['trade_status'] == 'TRADE_FINISHED'){
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//该种交易状态只在两种情况下出现
		//1、开通了普通即时到账，买家付款成功后。
		//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }else if($_POST['trade_status'] == 'TRADE_SUCCESS'){
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

	//OURPHP 校验逻辑
	//支付验证
	$ourphppay = explode('|',$body);
	if(md5($ourphppay[2].$ourphp['safecode']) != $ourphppay[3]){
		$msg = "支付验证出错!~ 请联系网站管理员";
		file_put_contents('./error.txt', $msg);
		exit;
	}
	
	//判断此订单是否已经付款
	$query = $db -> select("id","`ourphp_userpay`","WHERE `OP_Uservoucherone` = '".dowith_sql($out_trade_no)."' && `OP_Uservouchertwo` = '".dowith_sql($trade_no)."'");
	if($query){
		
		$msg = "订单已存在或return_url.php优先完成,不做处理";
		file_put_contents('./error.txt', $msg);
		return false;
		
	}else{
		
		//获取会员账号
		$rs = $db -> select("`OP_Useremail`","`ourphp_user`","WHERE `id` = ".intval($ourphppay[2]));
		
		if($ourphppay[0] == 'fk'){
			//付款
			$query = $db -> listgo("`OP_Ordersnum`,`OP_Ordersusermarket`,`OP_Ordersfreight`,`OP_Ordersid`,`OP_Ordersproductatt`,`OP_Tuanset`,`OP_Tuanid`","`ourphp_orders`","where `id` in (".$ourphppay[1].")");
			$zj = '';
			while($ourphp_rs = $db -> whilego($query)){
				$zj += ($ourphp_rs[0] * $ourphp_rs[1]) + $ourphp_rs[2];
			
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
					$querythree = $db -> update("`ourphp_product`","`OP_Specifications` = '".substr($php,1)."',`OP_Buynum` = `OP_Buynum` + ".$ourphp_rs[0],"where id = ".$ourphp_rs[3]);
				}
				
				//加入积分表
				if($ourphp_rsrs[5] > 0){
					$queryfor = $db -> insert("`ourphp_integral`","`OP_Iid` = '".$ourphp_rsrs[1]."', `OP_Iname` = '".$ourphp_rsrs[2]."', `OP_Imarket` = '".$ourphp_rsrs[3]."', `OP_Iwebmarket` = '".$ourphp_rsrs[4]."', `OP_Iintegral` = '".$ourphp_rsrs[5]."',`OP_Iconfirm` = 0, `OP_Iuseremail` = '".$rs[0]."', `time` = '".date("Y-m-d H:i:s")."'","");
				}
				//处理团购
				$group -> grouppay($ourphp_rs[3], $ourphp_rs[5], $ourphp_rs[6], $rs[0], $ourphppay[1], $ourphp_rsrs[6]);
			}
			
			// 1.8.2 生成统一订单列表
			function orders_buylist($a, $b, $c, $d, $e, $f){
				global $db,$rs,$out_trade_no;
				$buy = $db -> insert("`ourphp_orderslist`","`OP_Ordersnum` = '".'OP_'.$out_trade_no."',`OP_Ordersid` = '".$a."',`OP_Orderscouponid` = ".intval($b).",`OP_Ordersusername` = '".admin_sql($c)."',`OP_Ordersusertel` = '".admin_sql($d)."',`OP_Ordersuseradd` = '".admin_sql($e)."',`OP_Orderscouponmoney` = ".admin_sql($f).",`OP_Ordersuser` = '".$rs[0]."',`time` = '".date("Y-m-d H:i:s")."'","");
				return $db -> insertid();
			}
			
			$shopadd = $db -> select("`id`,`OP_Add`,`OP_Addtel`,`OP_Addname`","`ourphp_usershopadd`","where OP_Addindex = 1 && `id` = ".intval($ourphppay[6]));
			$newid = orders_buylist($ourphppay[1],$ourphppay[4],$shopadd[3],$shopadd[2],$shopadd[1],$ourphppay[5]);
			
			$query = $db -> update("`ourphp_orders`","`OP_Orderspay` = 2,
			`OP_Ordersclassid` = ".intval($newid)." where id in (".$ourphppay[1].") && OP_Ordersemail = '".$rs[0]."'","");
			$query = $db -> insert("`ourphp_userpay`","`OP_Useremail` = '".$rs[0]."',`OP_Usermoney` = '".dowith_sql($total_fee)."',`OP_Usercontent` = '订单号:".dowith_sql($out_trade_no)."<br />交易号:".dowith_sql($trade_no)."<br />交易状态:".dowith_sql($trade_status)."<br />用户备注:订单ID：".dowith_sql($ourphppay[1])."',`OP_Useradmin` = '商品付款',`time` = '".date("Y-m-d H:i:s")."',`OP_Uservoucherone` = '".dowith_sql($out_trade_no)."',`OP_Uservouchertwo` = '".dowith_sql($trade_no)."'","");
			
			$msg = "付款成功！";
			file_put_contents('./error.txt', $msg);
			
		}else{ ///////////////////////////////////
			
			//充值
			$query = $db -> insert("`ourphp_userpay`","`OP_Useremail` = '".$rs[0]."',`OP_Usermoney` = '".dowith_sql($total_fee)."',`OP_Usercontent` = '订单号:".dowith_sql($out_trade_no)."<br />交易号:".dowith_sql($trade_no)."<br />交易状态:".dowith_sql($trade_status)."<br />用户备注:".dowith_sql($ourphppay[0])."',`OP_Useradmin` = '用户在线充值',`time` = '".date("Y-m-d H:i:s")."',`OP_Uservoucherone` = '".dowith_sql($out_trade_no)."',`OP_Uservouchertwo` = '".dowith_sql($trade_no)."'","");
			//写入充值金额
			$query = $db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` + '".$total_fee."'","where `id` = ".intval($ourphppay[2]));
			
			$msg = "充值成功！";
			file_put_contents('./error.txt', $msg);
			
		}
		
	}
	echo "success";//请不要修改或删除
	
}else{
    //验证失败
    echo "fail";
}
?>