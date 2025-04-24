<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';
include '../../function/ourphp_navigation.class.php';

if(strstr($OP_Adminpower,"36")): else: echo "无权限操作"; exit; endif;

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	if (empty($_POST["plus"])){
		exit("<script language=javascript> alert('参数不能为空');history.go(-1);</script>");
	}

	plugsclass::logs('创建API接口',$OP_Adminname);
	$apicontent = implode("|",$_POST["plus"]);
	$apicontent = str_replace("||","|0|0",$apicontent);
	$apicontent = str_replace("|||","|0|0|0",$apicontent);
	$apicontent = str_replace("||||","|0|0|0|0",$apicontent);
	$apicontent = str_replace("|||||","|0|0|0|0|0",$apicontent);
	$apicontent = str_replace("||||||","|0|0|0|0|0|0",$apicontent);
	$db -> insert("`ourphp_api`","`OP_Key` = '".compress_html(admin_sql($apicontent))."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_api.php?id=ourphp';
	require 'ourphp_remind.php';
	
}elseif ($_GET["ourphp_cms"] == "del"){
	
	plugsclass::logs('删除API接口 ID:'.intval($_GET['id']),$OP_Adminname);
	$db -> del("ourphp_api","where id = ".intval($_GET['id']));
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_api.php?id=ourphp';
	require 'ourphp_remind.php';	
}

function Apilist(){
	global $_page,$db,$smarty;
	$listpage = 30;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_api`","order by id desc");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("`id`,`OP_Key`","`ourphp_api`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"key" => explode('|',$ourphp_rs[1]),
			"keyto" => $ourphp_rs[1],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("api",Apilist());
$smarty->display('ourphp_api.html');
?>