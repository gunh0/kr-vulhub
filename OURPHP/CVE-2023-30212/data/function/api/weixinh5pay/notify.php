<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

include '../../../config/ourphp_config.php';
include '../../ourphp_function.class.php';

$api = plugsclass::plugs(10);
if (!$api){
	exit(0);
}
					
// 1.8.2 生成统一订单列表
function orders_buylist($a, $b, $c, $d, $e, $f, $g, $h){
	global $db;
	$buy = $db -> insert("`ourphp_orderslist`","`OP_Ordersnum` = '".'OP_'.$g."',`OP_Ordersid` = '".$a."',`OP_Orderscouponid` = ".intval($b).",`OP_Ordersusername` = '".admin_sql($c)."',`OP_Ordersusertel` = '".admin_sql($d)."',`OP_Ordersuseradd` = '".admin_sql($e)."',`OP_Orderscouponmoney` = ".admin_sql($f).",`OP_Ordersuser` = '".$h."',`time` = '".date("Y-m-d H:i:s")."'","");
	return $db -> insertid();
}

$group = new userplus\group();

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	public $ourphp;
	public $db;
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		//$data中各个字段在return_code为SUCCESS的时候有返回 SUCCESS/FAIL
		//此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
		//成功后写入自己的数据库
		if ($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS') {
			
			$out_trade_no = $data['out_trade_no']; 
			$transaction_id = $data['transaction_id'];
			$bank_type = $data['bank_type'];
			$fee_type = $data['fee_type'];
			$time_end = $data['time_end'];
			$amount = $data['total_fee'];
			$attach = $data['attach'];
			
			//OURPHP 校验逻辑
			//支付验证
			global $ourphp,$db,$group;
			$ourphppay = explode('|',$attach);
			if(md5($ourphppay[2].$ourphp['safecode']) != $ourphppay[3]){
				
				$msg = "支付验证出错!~ 请联系网站管理员";
				file_put_contents('./error.txt', $msg);
				return false;
				
			}else{
				
				//判断此订单是否已经付款
				$query = $db -> select("id","`ourphp_userpay`","WHERE `OP_Uservoucherone` = '".dowith_sql($out_trade_no)."' && `OP_Uservouchertwo` = '".dowith_sql($transaction_id)."'");

				if($query){
					
					$msg = "订单已存在或return_url.php优先完成,不做处理";
					file_put_contents('./error.txt', $msg);
					return false;
					
				}else{
					
					//获取会员账号
					$rs = $db -> select("`OP_Useremail`","`ourphp_user`","WHERE `id` = ".intval($ourphppay[2]));
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
					
					$shopadd = $db -> select("`id`,`OP_Add`,`OP_Addtel`,`OP_Addname`","`ourphp_usershopadd`","where OP_Addindex = 1 && `id` = ".intval($ourphppay[6]));
					$newid = orders_buylist($ourphppay[1],$ourphppay[4],$shopadd[3],$shopadd[2],$shopadd[1],$ourphppay[5],$out_trade_no,$rs[0]);
					
					$query = $db -> update("`ourphp_orders`","`OP_Orderspay` = 2,
					`OP_Ordersclassid` = ".intval($newid)." where id in (".$ourphppay[1].") && OP_Ordersemail = '".$rs[0]."'","");
					$query = $db -> insert("`ourphp_userpay`","`OP_Useremail` = '".$rs[0]."',`OP_Usermoney` = '".dowith_sql($amount / 100)."',`OP_Usercontent` = '订单号:".dowith_sql($out_trade_no)."<br />交易号:".dowith_sql($transaction_id)."<br />交易状态:".dowith_sql($fee_type)."<br />用户备注:订单ID：".dowith_sql($ourphppay[1])."',`OP_Useradmin` = '商品付款',`time` = '".date("Y-m-d H:i:s")."',`OP_Uservoucherone` = '".dowith_sql($out_trade_no)."',`OP_Uservouchertwo` = '".dowith_sql($transaction_id)."'","");
					
					$msg = "异步支付成功！";
					file_put_contents('./error.txt', $msg);
						
				}
				
				echo 'SUCCESS';
			}
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
?>