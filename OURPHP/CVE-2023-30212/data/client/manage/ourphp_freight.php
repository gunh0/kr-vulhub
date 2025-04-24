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

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';

}elseif ($_GET["ourphp_cms"] == "add"){

	if (!empty($_POST["sheng"])){
	$sheng = implode('|',$_POST["sheng"]);
	}else{
	$sheng = '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0';
	}

	plugsclass::logs('创建运费模板',$OP_Adminname);
	$db -> insert("`ourphp_freight`","`OP_Freightname` = '".admin_sql($_POST["OP_Freightname"])."',`OP_Freighttext` = '".$sheng."',`OP_Freightdefault` = 0,`OP_Freightweight` = '".admin_sql($_POST["OP_Freightweight"])."',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_freight.php?id=ourphp';
	require 'ourphp_remind.php';	
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除运费模板',$OP_Adminname);
		$db -> del("ourphp_freight","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_freight.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_freight.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}elseif ($_GET["ourphp_cms"] == "Batch"){

	if (strstr($OP_Adminpower,"34")){
		
		plugsclass::logs('设置运费模板',$OP_Adminname);
		$db -> update("ourphp_freight","`OP_Freightdefault` = 1","where id = ".intval($_POST['default']));
		$db -> update("ourphp_freight","`OP_Freightdefault` = 0","where id != ".intval($_POST['default']));
		
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_freight.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_article.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}	
			
}

function freightlist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_freight`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_freight`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs['id'],
			"title" => $ourphp_rs['OP_Freightname'],
			"content" => $ourphp_rs['OP_Freighttext'],
			"default" => $ourphp_rs['OP_Freightdefault'],
			"time" => $ourphp_rs['time'],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}
$smarty->assign("freight",freightlist());
$smarty->display('ourphp_freight.html');
?>