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

$aid = isset($_GET['aid']) ? $_GET['aid'] : "0";
if(isset($_GET["page"]) == ""){
	$smarty->assign("page",1);
	}else{
	$smarty->assign("page",$_GET["page"]);
}

if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';
	
}elseif ($_GET["ourphp_cms"] == "edit"){
	
	if (strstr($OP_Adminpower,"34")){
		$OP_Class = explode("|",admin_sql($_POST["OP_Class"]));
		if ($OP_Class[0] == 0){
			$ourphp_font = 4;
			$ourphp_content = $ourphp_adminfont['nocolumn'];
			$ourphp_class = 'ourphp_productlist.php?id=ourphp&aid='.$aid;
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
	
		$OP_Img = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Img"]));
		
		if(empty($_POST["OP_Imgtoone"]))
		{
			$OP_Imgtoone = "";
			
		}else{
			
			$OP_Imgtoone = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Imgtoone"]));
			$OP_Imgtoone = $OP_Imgtoone."|";
		}
		
		if (!empty($_POST["OP_Img2"])){
			$OP_Img2 = implode('|',admin_sql($_POST["OP_Img2"]));
			if (!empty($OP_Img)){
				$OP_imga = $OP_Imgtoone.$OP_Img2.'|'.$OP_Img;
			}else{
				$OP_imga = $OP_Imgtoone.$OP_Img2;
			}
		}else{
			$OP_Img2 = '';
			$OP_imga = $OP_Imgtoone.$OP_Img;
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
			
			$value = '';
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
			$OP_Usermoney = str_replace(':|',':0.00|',rtrim($d, ":|"));
		}else{
			$OP_Usermoney = '';
		}
		
		if (!empty($_POST["OP_Productimgname"])){
			$OP_Productimgname = implode('|',admin_sql($_POST["OP_Productimgname"]));
		}else{
			$OP_Productimgname = '';
		}

		plugsclass::logs('编辑产品',$OP_Adminname);
		$db -> update("`ourphp_product`","`OP_Class` = '".$OP_Class[0]."',`OP_Lang` = '".$OP_Class[1]."',`OP_Title` = '".admin_sql($_POST["OP_Title"])."',`OP_Number` = '".admin_sql($_POST["OP_Number"])."',`OP_Goodsno` = '".admin_sql($_POST["OP_Goodsno"])."',`OP_Brand` = '".admin_sql($_POST["OP_Brand"])."',`OP_Market` = '".admin_sql($_POST["OP_Market"])."',`OP_Webmarket` = '".admin_sql($_POST["OP_Webmarket"])."',`OP_Stock` = '".admin_sql($_POST["OP_Stock"])."',`OP_Usermoney` = '".$OP_Usermoney."',`OP_Specificationsid` = '".$optitleid."',`OP_Specificationstitle` = '".$optitle."',`OP_Specifications` = '".$OP_Specifications."',`OP_Pattribute` = '".$OP_Pattribute."',`OP_Minimg` = '".$OP_Minimg."',`OP_Maximg` = '".$OP_Maximg."',`OP_Img` = '".rtrim($OP_imga,"|")."',`OP_Content` = '".admin_sql($_POST["OP_Content"])."',`OP_Content_wap` = '".admin_sql($_POST["OP_Content_wap"])."',`OP_Weight` = '".admin_sql($_POST["OP_Weight"])."',`OP_Freight` = '".intval($_POST["OP_Freight"])."',`OP_Tag` = '".$wordtag."',`OP_Sorting` = '".admin_sql($_POST["OP_Sorting"])."',`OP_Attribute` = '".$OP_Attribute."',`OP_Url` = '".admin_sql($_POST["OP_Url"])."',`OP_Description` = '".compress_html($OP_Description)."',`time` = '".admin_sql($_POST["time"])."',`OP_Integral` = '".admin_sql($_POST["OP_Integral"])."',`OP_Integralok` = '".admin_sql($_POST["OP_Integralok"])."',`OP_Integralexchange` = '".admin_sql($_POST["OP_Integralexchange"])."',`OP_Suggest` = '".admin_sql($_POST["OP_Suggest"])."',`OP_Productimgname` = '".admin_sql($OP_Productimgname)."',`OP_Usermoneyclass` = '".intval($_POST['OP_Usermoneyclass'])."',`OP_Tuanset` = '".intval($_POST['OP_Tuanset'])."',`OP_Tuanusernum` = '".intval($_POST['OP_Tuanusernum'])."',`OP_Tuantime` = '".admin_sql($_POST['OP_Tuantime'])."',`OP_Couponset` = '".admin_sql($_POST['OP_Couponset'])."',`OP_Buyoffnum` = '".intval($_POST['OP_Buyoffnum'])."'","where id = ".intval($_GET["id"]));
		
		navigationnum($OP_Class[0],'+');
		navigationnum($_POST['c'],'-');
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_productlist.php?id=ourphp&page='.$_GET["page"].'&aid='.$aid;
		require 'ourphp_remind.php';
		
	}
}

function Attribute($optype){
	global $db;
	if($optype == 'op'){
		$query = $db -> listgo("id,OP_Title","`ourphp_productattribute`","where `OP_Class` = 0 order by OP_Sorting asc");
	}else{
		$query = $db -> listgo("id,OP_Title,OP_Text","`ourphp_productattribute`","where `OP_Class` != 0 order by OP_Sorting asc");
	}
	
	$rows=array();
	$i = 1;
	if($optype == 'op'){
	
		while($ourphp_rs = $db -> whilego($query)){
			$rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"name" => $ourphp_rs[1]
			);
			$i = $i + 1;
		}
		
	}else{
		
		$x=0;
		while($ourphp_rs = $db -> whilego($query)){
			$cfsz = explode("|",$ourphp_rs[2]);
			$op=array();
			foreach($cfsz as $u){
				$op[]=$u;
			}
			$rows[] = array(
				'x'=>$x,
				'id'=>$ourphp_rs[0],
				'name'=>$ourphp_rs[1],
				'three'=>$op
			);
			$x+=1;
		}
	
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
			"img" => $ourphp_rs[2],
		);
		$i = $i + 1;
	}
	return $rows;
}


function Usermoney(){
	global $db;
	$ourphp_rs = $db -> select("`OP_Usermoney`","`ourphp_product`","where `id` = ".intval($_GET['id']));
	$ourphp_fg = explode("|",$ourphp_rs[0]);
	$rows = array();
	$i=0;
	foreach($ourphp_fg as $op){
		$ourphp_fgo = explode(":",$op);
		$rows[] = array(
			'i' => $i,
			'id' => $ourphp_fgo[0],
			'money' => $ourphp_fgo[1],
		);
		$i+=1;
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

function Pattribute(){
	global $db;
	$ourphp_rs = $db -> select("`OP_Pattribute`","`ourphp_product`","where `id` = ".intval($_GET['id']));
	if (!empty($ourphp_rs[0])){
		$ourphp_afg = explode("|",$ourphp_rs[0]);
		$rows = array();
		$i=0;
		foreach($ourphp_afg as $op){
			$ourphp_afgo = explode(":",$op);
			$rows[] = array(
				'i' => $i,
				'class' => $ourphp_afgo[0],
				'name' => $ourphp_afgo[1]
			);
			$i+=1;
		}
		return $rows;
	}
}

function Specifications(){
	global $db;
	$ourphp_rs = $db -> select("`OP_Specifications`","`ourphp_product`","where `id` = ".intval($_GET['id']));
	if (!empty($ourphp_rs[0])){
		$OP_Specifications = explode("|",$ourphp_rs[0]);
		$rows = array();
			foreach($OP_Specifications as $op){
				$opo = explode(",",$op);
				$a = array();
				foreach($opo as $opop){
					$a[] = $opop;
				}
				$rows[] = array('three'=>$a);
			}
	return $rows;
	}
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
$smarty->assign('Attribute',Attribute('op'));
$smarty->assign('Attributeto',Attribute('oo'));
$smarty->assign('Brand',Brand());
$smarty->assign('Usermoney',Usermoney());
$smarty->assign('Userleve',Userleve());
$smarty->assign('Set',Productset());
$smarty->assign('Pattribute',Pattribute());
$smarty->assign('Specifications',Specifications());
$smarty->assign('Freight',Freight());

$ourphp_rs = $db -> select("*","`ourphp_product`","where `id` = ".intval($_GET['id']));
$smarty->assign('ourphp_product',$ourphp_rs);
$ourphp_text=explode(",",$ourphp_rs['OP_Attribute']);
for($i=0;$i<sizeof($ourphp_text);$i++){ 
	$selected[] = $ourphp_text[$i];
}
$smarty->assign('selected',$selected); 
$ourphph_qx=array('头条','热门','滚动','推荐'); 
$smarty->assign('ourphph_qx',$ourphph_qx); 

$rowsop = array();
if ($ourphp_rs['OP_Specificationstitle'] != ''){
$OP_Specificationstitle = explode(",",$ourphp_rs['OP_Specificationstitle']);
	foreach($OP_Specificationstitle as $op){
		$rowsop[]=array(
			"name" => $op
		); 
	}
}
$smarty->assign('Specificationstitle',$rowsop);

if ($ourphp_rs['OP_Img'] != ''){
$OP_Img = explode("|",$ourphp_rs['OP_Img']);
$OP_Productimgname = explode("|",$ourphp_rs['OP_Productimgname']);
$i = 1;
$ii = 0;
foreach($OP_Img as $u){
    $OP_Imgarr = explode("|",$u);
    foreach($OP_Imgarr as $newstr){
        $rows[]=array(
			"i" => $i,
			"img" => $newstr,
			"imgname" => @$OP_Productimgname[$ii],
		); 
		$i = $i + 1;
		$ii = $ii + 1;
    }
}
}else{
	$rows = '';
}

$smarty->assign('productimglist',$rows);
/* if ($ourphp_rs['OP_Brand'] != ''){
$OP_Brand = explode("|",$ourphp_rs['OP_Brand']);
}else{
$OP_Brand = '|';
}
$smarty->assign('Prbrand',$OP_Brand); */

if($ourphp_rs['OP_Couponset'] != '0'){
	$coupon = explode(",",$ourphp_rs['OP_Couponset']);
	foreach ($coupon as $op)
	{
		$title = $db -> select("OP_Title","ourphp_coupon","where id = ".intval($op));
        $rows[]=array(
			"title" => $title[0]
		); 
	}
	$smarty->assign('coupon',$rows);
}

$smarty->assign('aid',$aid);
$smarty->display('ourphp_productedit.html');
?>