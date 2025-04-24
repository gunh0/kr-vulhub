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
include '../../function/ourphp_Tree.class.php';

$aid = isset($_GET['aid']) ? $_GET['aid'] : "0";
if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){
		
		navigationnum($_GET['c'],'-');
		$ourphp_rs = $db -> select("`OP_Minimg`,`OP_Maximg`,`OP_Img`","`ourphp_product`","where id = ".intval($_GET['id']));
		if($ourphp_rs[0] != '' || $ourphp_rs[1] != '' || $ourphp_rs[2] != ''){
			include './ourphp_del.php';
			ourphp_imgdel($ourphp_rs[0],$ourphp_rs[1],$ourphp_rs[2]);
		}
		
		$query = $db -> del("ourphp_product","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}
			
}elseif ($_GET["ourphp_cms"] == "Batch"){

	if (strstr($OP_Adminpower,"34")){
	
		if (!empty($_POST["op_b"])){
		$op_b = implode(',',admin_sql($_POST["op_b"]));
		}else{
		$op_b = '';
		}

		if ($_POST["h"] == "h") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=h&xx=product");
		exit;
		}elseif($_POST["h"] == "y") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=y&xx=product");
		exit;
		}elseif($_POST["h"] == "s") {
		header("location: ./ourphp_cmd.php?id=$op_b&lx=s&xx=product");
		exit;
		}
		
		if (!empty($_POST["OP_Attribute"])){
		$OP_Attribute = implode(',',admin_sql($_POST["OP_Attribute"]));
		}else{
		$OP_Attribute = '';
		}
		
		if (!empty($_POST["down"])){
		$down = implode(',',admin_sql($_POST["down"]));
		}else{
		$down = '2';
		}
		
		$query = $db -> update("ourphp_product","`OP_Attribute` = '".$OP_Attribute."',`OP_Down` = '".$down."'","where id in ($op_b)");
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
	
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&aid='.$aid;
		require 'ourphp_remind.php';
		
	}	
			
}

function columncycle($cid = 1){
	global $db,$id;
	$newid = "0".$id;
	$ourphp_rs = $db -> select("`OP_Columntitle`","`ourphp_column`","where id = ".$cid." and (`OP_Adminopen` LIKE '%$newid%' or `OP_Adminopen` LIKE '00%')");
	if($ourphp_rs){
		return $ourphp_rs[0];
	}else{
		return false;
	}
}

function pingjia($id,$type){
	global $db;
	if($type == 'h'){
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_comment`","where OP_Class = ".$id." && OP_Vote = 1");
	}elseif($type == 'z'){
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_comment`","where OP_Class = ".$id." && OP_Vote = 2");
	}elseif($type == 'c'){
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_comment`","where OP_Class = ".$id." && OP_Vote = 3");
	}
	$ourphptotal = $db -> whilego($ourphptotal);
	return $ourphptotal['tiaoshu'];
}


function Productlist(){
	global $_page,$db,$smarty,$aid;
	$listpage = 15;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;


	$stock = $db -> select("OP_Stock","ourphp_productset","where id = 1");
	
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_product`","where `OP_Down` = 2 and `OP_Stock` < ".$stock[0]);
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("`id`,`OP_Class`,`OP_Lang`,`OP_Title`,`OP_Webmarket`,`OP_Stock`,`OP_Minimg`,`OP_Down`,`time`,OP_Sorting,OP_Click,OP_Usermoneyclass","`ourphp_product`","where `OP_Down` = 2 and `OP_Stock` < ".$stock[0]." order by OP_Stock asc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$c = columncycle($ourphp_rs[1]);
		if($c){
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"class" => $c,
				"class2" => $ourphp_rs[1],
				"lang" => $ourphp_rs[2],
				"title" => $ourphp_rs[3],
				"webmarket" => $ourphp_rs[4],
				"stock" => $ourphp_rs[5],
				"minimg" => $ourphp_rs[6],
				"down" => $ourphp_rs[7],
				"time" => $ourphp_rs[8],
				"h" => pingjia($ourphp_rs[0],'h'),
				"z" => pingjia($ourphp_rs[0],'z'),
				"c" => pingjia($ourphp_rs[0],'c'),
				"att" => listattribute($ourphp_rs[0],'product'),
				"sorting" => $ourphp_rs[9],
				"click" => $ourphp_rs[10],
				"moneyclass" => $ourphp_rs[11],
			);
		}
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

$node = columnlist("product");
$smarty->assign('productlist',$node);
$smarty->assign("product",Productlist());
$smarty->assign('aid',$aid);
$smarty->display('ourphp_product_stock.html');
?>