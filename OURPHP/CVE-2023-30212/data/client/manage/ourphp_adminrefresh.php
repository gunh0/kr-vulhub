<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2018 www.ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(isset($_GET['look']))
{
	$o = $db -> select("id","ourphp_orderslist","where OP_Look = 0");
	if($o)
	{
		$ourphp_buylook = array("error" => 1);
	}else{
		$ourphp_buylook = array("error" => 0);
	}
	echo json_encode($ourphp_buylook);
	exit;
}

if(isset($_GET['buylookok']))
{
	$o = $db -> update("ourphp_orderslist","OP_Look = 1","where id = ".intval($_GET['id']));
	if($o)
	{
		$ourphp_buylook = array("error" => 1);
	}else{
		$ourphp_buylook = array("error" => 0);
	}
	echo json_encode($ourphp_buylook);
	exit;
}

echo '<meta http-equiv="refresh"content="50;url=ourphp_adminrefresh.php">';
echo 'ok';
?>