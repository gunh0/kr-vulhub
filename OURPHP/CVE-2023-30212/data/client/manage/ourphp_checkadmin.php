<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
@session_start();

if(isset($_SESSION['ourphp_outtime'])) {

    if($_SESSION['ourphp_outtime'] < time()) {
        unset($_SESSION['ourphp_outtime']);
        echo '登录超时或未登录，请重新登录！';
        exit(0);
    } else {
        $_SESSION['ourphp_outtime'] = time() + 3600;
    }
	
}else{
	echo '登录超时或未登录，请重新登录！';
	exit(0);
}
		
$ourphp_rs = $db -> select("id,OP_Adminname,OP_Adminpass,OP_Adminpower,OP_Admin","`ourphp_admin`","where `OP_Adminname` = '".$_SESSION['ourphp_adminname']."'");
$id = $ourphp_rs[0];
$OP_Adminname = $ourphp_rs[1];
$OP_Adminpass = $ourphp_rs[2];
$OP_Adminpower = $ourphp_rs[3];
$OP_Admin = $ourphp_rs[4];

function listattribute($id = 1,$type = '')
{
	global $db;
	if($type != '')
	{
		$a = '';
		$b = '';
		$c = '';
		$d = '';
		$rs = $db -> select("OP_Attribute","ourphp_".$type,"where id = ".intval($id));
		if(strstr($rs[0],"0"))
		{
			$a = '<font style="background:#EE94F9; color:#FFFFFF; padding:2px; text-align:center;">头条</font>';
		}
		if(strstr($rs[0],"1"))
		{
			$b = '<font style="background:#EE94F9; color:#FFFFFF; padding:2px; text-align:center;">热门</font>';
		}
		if(strstr($rs[0],"2"))
		{
			$c = '<font style="background:#EE94F9; color:#FFFFFF; padding:2px; text-align:center;">滚动</font>';
		}
		if(strstr($rs[0],"3"))
		{
			$d = '<font style="background:#EE94F9; color:#FFFFFF; padding:2px; text-align:center;">推荐</font>';
		}
		if($rs[0] == '0')
		{
			$a = '<font style="background:#EE94F9; color:#FFFFFF; padding:2px; text-align:center;">头条</font>';
		}else{
			$a = $a;
		}
		return $a.$b.$c.$d;
	}else{
		return false;
	}
}

?>