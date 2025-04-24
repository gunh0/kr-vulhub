<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include '../../function/ourphp_navigation.class.php';
include '../../function/ourphp_Tree.class.php';


if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	$OP_Class = explode("|",admin_sql($_POST["OP_Class"]));
	if ($OP_Class[0] == 0){
		$ourphp_font = 4;
		$ourphp_content = $ourphp_adminfont['nocolumn'];
		$ourphp_class = 'ourphp_productlist.php?id=ourphp';
		require 'ourphp_remind.php';
		exit;
	}
	
	if(substr($_POST["OP_Minimg"],0,4) == 'http')
	{
		$OP_Minimg = admin_sql($_POST["OP_Minimg"]);
	}else{
		$OP_Minimg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Minimg"]));
	}
	
	if(substr($_POST["OP_Maximg"],0,4) == 'http')
	{
		$OP_Maximg = admin_sql($_POST["OP_Maximg"]);
	}else{
		$OP_Maximg = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Maximg"]));
	}
	
	if(empty($_POST["OP_Imgtoone"]))
	{
		$OP_Img = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Img"]));
		
	}else{
		
		if(empty($_POST["OP_Img"]))
		{
			$OP_Img = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]));
		}else{
			$OP_Img = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]."|".$_POST["OP_Img"]));
		}
	}
	
	if (empty($_POST["OP_Description"])){
		$OP_Description = utf8_strcut(strip_tags(admin_sql($_POST["OP_Content"])), 0, 200);
	}else{
		$OP_Description = admin_sql($_POST["OP_Description"]);
	}
	
	//分词
	if($_POST["OP_Tag"] != '')
	{
		$wordtag = admin_sql($_POST["OP_Tag"]);
	}else{
		if(!empty($OP_Description)){
			$word = $OP_Description;
			$tag = admin_sql($_POST["OP_Tag"]);
			include '../../function/ourphp_sae.class.php';
		}else{
			$wordtag = admin_sql($_POST["OP_Tag"]);
		}
	}
	//结束
	
	if (!empty($_POST["OP_Attribute"])){
	$OP_Attribute = implode(',',admin_sql($_POST["OP_Attribute"]));
	}else{
	$OP_Attribute = '';
	}
	
	//自定义属性处理
	if (!empty($_POST["OP_Pattribute"])){
		
		$OP_Pattribute = implode('|',admin_sql($_POST["OP_Pattribute"]));
		foreach ($_POST["OP_Pattribute"] as $op){
			$str = str_replace("：",":",$op);
			$str = explode(":",$str);
			
			$pattr = $db -> select("`OP_Title`,`OP_Text`","ourphp_productattribute","where `id` = ".intval($str[0]));
			if($pattr){
				
				if(!strstr($pattr[1],$str[2])){
					$edit = $db -> update("ourphp_productattribute","`OP_Text` = '".$pattr[1].'|'.admin_sql($str[2])."'","where `id` = ".intval($str[0]));
				}
				
				$value .= $pattr[0].':'.$str[2].'|';
				
			}else{
				
				exit('<script language=javascript> alert("商品属性输入出错\r\n不能修改属性标题,只能自定义修改参数值.");history.go(-1);</script>');
				
			}
		}
		
		$OP_Pattribute = rtrim($value,'|');
	
	}else{
	$OP_Pattribute = '';
	}
	
	if (!empty($_POST["optitle"])){
	$optitle = implode(',',admin_sql($_POST["optitle"]));
	}else{
	$optitle = '';
	}

	if (!empty($_POST["optitleid"])){
	$optitleid = implode(',',admin_sql($_POST["optitleid"]));
	}else{
	$optitleid = '';
	}
	
	if (!empty($_POST["op"])){
	$a = implode(',',admin_sql($_POST["op"]));
	$b = str_replace(',|,','|',$a);
	$OP_Specifications = str_replace(',|','',$b);
	}else{
	$OP_Specifications = '';
	$optitleid = '';
	}
	
	if($optitleid != ''){
		$aa = explode(",",$optitleid);
		$i = 1;
		foreach ($aa as $id)
		{
		    if($OP_Specifications != ''){
		    	$bb = explode("|", $OP_Specifications);
		    	foreach ($bb as $op) {
		    		$cc = explode(",", $op);
		    		//echo $cc[$i]."-".$id."<br />";
		    		$sql = $db -> create("update ourphp_productspecifications set OP_Value = if(OP_Value like '%".$cc[$i]."%',OP_Value,CONCAT(OP_Value,'|".$cc[$i]."')) where id = ".$id,2);
		    	}
		    }
		    $i ++;
		}
	}
		
	if (!empty($_POST["OP_Userj"])){
	$c = implode(':',admin_sql($_POST["OP_Userj"]));
	$d = str_replace(':|:','|',$c);
	$OP_Usermoney = str_replace(':|','',$d);
	}else{
	$OP_Usermoney = '';
	}
	
	if (!empty($_POST["OP_Productimgname"])){
	$OP_Productimgname = implode('|',$_POST["OP_Productimgname"]);
	}else{
	$OP_Productimgname = '';
	}

	plugsclass::logs('创建产品',$OP_Adminname);
	$db -> insert("`ourphp_product`","`OP_Class` = '".$OP_Class[0]."',`OP_Lang` = '".$OP_Class[1]."',`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Number` = '".admin_sql($_POST["OP_Number"])."',`OP_Goodsno` = '".admin_sql($_POST["OP_Goodsno"])."',`OP_Brand` = '".admin_sql($_POST["OP_Brand"])."',`OP_Market` = '".admin_sql($_POST["OP_Market"])."',`OP_Webmarket` = '".admin_sql($_POST["OP_Webmarket"])."',`OP_Stock` = '".admin_sql($_POST["OP_Stock"])."',`OP_Usermoney` = '".$OP_Usermoney."',`OP_Specificationsid` = '".$optitleid."',`OP_Specificationstitle` = '".$optitle."',`OP_Specifications` = '".$OP_Specifications."',`OP_Pattribute` = '".$OP_Pattribute."',`OP_Minimg` = '".$OP_Minimg."',`OP_Maximg` = '".$OP_Maximg."',`OP_Img` = '".$OP_Img."',`OP_Content` = '".admin_sql($_POST["OP_Content"])."',`OP_Content_wap` = '".admin_sql($_POST["OP_Content_wap"])."',`OP_Down` = '2',`OP_Weight` = '".admin_sql($_POST["OP_Weight"])."',`OP_Freight` = '".intval($_POST["OP_Freight"])."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`OP_Attribute` = '".$OP_Attribute."',`OP_Url` = '".admin_sql($_POST["OP_Url"])."',`OP_Description` = '".compress_html($OP_Description)."',`time` = '".admin_sql($_POST["time"])."',`OP_Integral` = '".admin_sql($_POST["OP_Integral"])."',`OP_Integralok` = '".admin_sql($_POST["OP_Integralok"])."',`OP_Integralexchange` = '".admin_sql($_POST["OP_Integralexchange"])."',`OP_Suggest` = '".admin_sql($_POST["OP_Suggest"])."',`OP_Productimgname` = '".admin_sql($OP_Productimgname)."',`OP_Usermoneyclass` = '".intval($_POST['OP_Usermoneyclass'])."',`OP_Tuanset` = '".intval($_POST['OP_Tuanset'])."',`OP_Tuanusernum` = '".intval($_POST['OP_Tuanusernum'])."',`OP_Tuantime` = '".admin_sql($_POST['OP_Tuantime'])."',`OP_Couponset` = '".admin_sql($_POST['OP_Couponset'])."',`OP_Buyoffnum` = '".intval($_POST['OP_Buyoffnum'])."'","");
	
	navigationnum($OP_Class[0],'+');
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_productlist.php?id=ourphp';
	require 'ourphp_remind.php';
}

function Attribute(){
	global $db;
	$query = $db -> listgo("id,OP_Title","`ourphp_productattribute`","where `OP_Class` = 0 order by OP_Sorting asc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1]
		);
		$i = $i + 1;
	}
	return $rows;
}

function Brand(){
	global $db;
	$query = $db -> listgo("id,OP_Brand,OP_Img","`ourphp_productcp`","where `OP_Class` = 2 order by id desc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"img" => $ourphp_rs[2]
		);
		$i = $i + 1;
	}
	return $rows;
}

function Userleve(){
	global $db;
	$query = $db -> listgo("id,OP_Userlevename","`ourphp_userleve`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1]
		);
	}
	return $rows;
}

function Productset(){
	global $db;
	$query = $db -> listgo("OP_Pattern,OP_Scheme","`ourphp_productset`","where id = 1");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"set" => $ourphp_rs[0],
			"scheme" => $ourphp_rs[1]
		);
	}
	return $rows;
}

function Freight(){
	global $db;
	$query = $db -> listgo("id,OP_Freightname","`ourphp_freight`","order by OP_Freightdefault desc,id desc");
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"title" => $ourphp_rs[1],
		);
		$i = $i + 1;
	}
	return $rows;
}


//$op= new Tree(columnlist(""));
//$arr=$op->leaf();

$node = columnlist("");
$arr = array2tree($node,0);	
$smarty->assign('product',$arr);
$smarty->assign('Attribute',Attribute());
$smarty->assign('Brand',Brand());
$smarty->assign('Userleve',Userleve());
$smarty->assign('Set',Productset());
$smarty->assign('Freight',Freight());
$smarty->display('ourphp_product.html');
?>