<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php'; 
$weburl = $_SERVER['HTTP_HOST'];

$ourphp_rs = $db -> select("`OP_Key`","`ourphp_api`","where `id` = 5");
$api = explode('|',$ourphp_rs[0]);


#判断接口是否开启
if ($api[1] == 2){
	exit($api[0].$ourphp_adminfont['plugsno']);
}
#接口查询结束
#调用方式：$api[0] 表示API名称，$api[1] 表示开关，$api[2~N] API值

if($api[2] == '0' || $api[3] == '0'){
	exit($api[0].$ourphp_adminfont['plugsno']);
}


echo '<script src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>';
echo '<script src="../../function/plugs/layer/layer.min.js"></script>';
echo '<script>';
echo '    parent.$.layer({';
echo '        type: 2,';
echo '        title: \''.$api[0].'\',';
echo '        shade: [0],';
echo '		  border: [5, 0.3, \'#000\'],';
echo '        area: [\'1360px\', \'700px\'],';
echo '		  iframe:{src: \''.$api[4].'\',scrolling: \'yes\'}';
echo '    });';
echo '</script>';
?>