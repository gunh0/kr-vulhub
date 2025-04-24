<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 模板操作类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

$ourphp_web = ourphp_web();
	
function ourphp_usercontrol(){ 
	global $db,$ourphp_web;
	$ourphp_rs = $db -> select("`OP_Userreg`,`OP_Userlogin`,`OP_Userprotocol`,`OP_Usergroup`,`OP_Usermoney`,`OP_Useripoff`,`OP_Regtyle`,`OP_Regcode`,`OP_Userpassgo`,`OP_Withdrawal`","`ourphp_usercontrol`","where `id` = 1"); 
	$rows = array(
		'regoff' => $ourphp_rs[0],
		'loginoff' => $ourphp_rs[1],
		'protocol' => str_replace('[.$ourphp_web.website.]',$ourphp_web['website'],$ourphp_rs[2]),
		'group' => $ourphp_rs[3],
		'money' => explode("|",$ourphp_rs[4]),
		'ipoff' => $ourphp_rs[5],
		'type' => $ourphp_rs[6],
		'code' => $ourphp_rs[7],
		'telsms' => $ourphp_rs[8],
		'withdrawal' => $ourphp_rs[9],
	);
	return $rows;
}

function ourphp_userproblem(){ 
	global $db,$ourphp; 
	$query = $db -> listgo("`OP_Userproblem`","`ourphp_userproblem`","order by id desc"); 
	$rows=array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[] = array(
			'i' => $i,
			'title' => $ourphp_rs[0],
		);
		$i+=1;
	}
	return $rows;
}

$ourphp_usercontrol = ourphp_usercontrol();
$smarty->assign('usercontrol',$ourphp_usercontrol);
$smarty->assign('problem',ourphp_userproblem());
?>