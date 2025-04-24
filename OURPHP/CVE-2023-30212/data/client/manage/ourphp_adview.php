<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include '../../function/ourphp_navigation.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){


	if (!empty($_POST["OP_Adclass"])){
		$OP_Adclass = implode(',',admin_sql($_POST["OP_Adclass"]));
	}else{
		$OP_Adclass = '';
	}

	plugsclass::logs('编辑广告信息',$OP_Adminname);
	$db -> update("`ourphp_ad`","`OP_Adcontent` = '".admin_sql($_POST["OP_Adcontent"])."',`OP_Adclass` = '".$OP_Adclass."',`time` = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['id']));
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_ad.php?id=ourphp';
	require 'ourphp_remind.php';
			
}

$ourphp_rs = $db -> select("*","`ourphp_ad`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_ad',$ourphp_rs);
$ourphp_text2=explode(",",$ourphp_rs['OP_Adclass']);
for($o=0;$o<sizeof($ourphp_text2);$o++){ 
	$selected2[] = $ourphp_text2[$o]; 
} 
$smarty->assign('selected2',$selected2); 
$ourphph_qx2=array('首页','文章','商品','图集','视频','下载','招聘','单页面','会员登录左侧'); 
$smarty->assign('ourphph_qx2',$ourphph_qx2);
$smarty->display('ourphp_adview.html');
?>