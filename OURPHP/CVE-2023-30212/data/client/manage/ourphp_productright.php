<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

function ourphp_shuju($biao){
	global $db;
	$rs = $db -> select("count(id) as TOTAL","`ourphp_".$biao."`","order by id asc");
	return $rs['TOTAL'];
}

$smarty->assign('shuju',array(
	ourphp_shuju("article"),
	ourphp_shuju("product"),
	ourphp_shuju("photo"),
	ourphp_shuju("video"),
	ourphp_shuju("down"),
	ourphp_shuju("job"),
	ourphp_shuju("book"),
	ourphp_shuju("link"),
));

function ourphp_tongji($params, $smarty){
	global $db;
	if($params['type'] == 'user'){
		$rs = $db -> select("count(id) as TOTAL","`ourphp_user`","where DATE_FORMAT(`time`,'%Y-%m')='".$params['datey']."-".$params['datem']."'");
			}elseif($params['type'] == 'pay'){
		$rs = $db -> select("count(id) as TOTAL","`ourphp_orders`","where DATE_FORMAT(`time`,'%Y-%m')='".$params['datey']."-".$params['datem']."' && OP_Orderspay = 2");
			}elseif($params['type'] == 'send'){
		$rs = $db -> select("count(id) as TOTAL","`ourphp_orders`","where DATE_FORMAT(`time`,'%Y-%m')='".$params['datey']."-".$params['datem']."' && OP_Orderssend = 2");
			}elseif($params['type'] == 'buy'){
		$rs = $db -> select("count(id) as TOTAL","`ourphp_orders`","where DATE_FORMAT(`time`,'%Y-%m')='".$params['datey']."-".$params['datem']."'");
	}
	return $rs['TOTAL'];
}

$ourphp_rs = $db -> select("`OP_Adminrecord`,`OP_Webupdate`","`ourphp_webdeploy`","where id = 1");
$smarty->assign('content',$ourphp_rs[0]);
$smarty->assign('webupdate',$ourphp_rs[1]);
$smarty->assign('ourphp_rdate',date('Y'));
$smarty->assign('OP_Empowerright',op('OP_Empowerright'));
$smarty->assign('diytype',$ourphp['diytype']);
$smarty->registerPlugin("function","ourphp_tongji", "ourphp_tongji");
$smarty->display('ourphp_right.html');

?>