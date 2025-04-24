<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('仓库删除产品',$OP_Adminname);
		$db -> del("ourphp_product","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_productlibrary.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_productlibrary.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
			
}elseif ($_GET["ourphp_cms"] == "xj"){

	if (strstr($OP_Adminpower,"34")){
	
		if (!empty($_POST["op_xj"])){
		$op_xj = implode(',',$_POST["op_xj"]);
		}else{
		$op_xj = '';
		}
		
		plugsclass::logs('批量上架产品',$OP_Adminname);
		header("location: ./ourphp_cmd.php?id=$op_xj&lx=y&xx=product");
		exit;
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productlibrary.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}	
			
}

function columncycle($id=1){
	global $db;
	$ourphp_rs = $db -> select("`OP_Columntitle`","`ourphp_column`","where id = ".$id);
	return $ourphp_rs[0];
}

function Productlist(){
	global $_page,$db,$smarty;
	$listpage = 15;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_product`","where `OP_Down` = 1");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("`id`,`OP_Class`,`OP_Lang`,`OP_Title`,`OP_Webmarket`,`OP_Stock`,`OP_Minimg`,`OP_Down`,`time`","`ourphp_product`","where `OP_Down` = 1 order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"class" => columncycle($ourphp_rs[1]),
			"lang" => $ourphp_rs[2],
			"title" => $ourphp_rs[3],
			"webmarket" => $ourphp_rs[4],
			"stock" => $ourphp_rs[5],
			"minimg" => $ourphp_rs[6],
			"down" => $ourphp_rs[7],
			"time" => $ourphp_rs[8]
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign("product",Productlist());
$smarty->display('ourphp_productlibrary.html');
?>