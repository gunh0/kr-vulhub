<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function columnlist($model = '',$lang = '') { 
    global $db,$id;
	$newid = "0".$id;
	
	if($lang == ''){
		$l = "";
	}else{
		$l = " && `OP_Lang` = '".$lang."'";
	}
	
	if ($model == ''){
		$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Templist,OP_Tempview,OP_Hide,OP_Sorting,OP_Weight,OP_Url,OP_Total,`OP_Click`","`ourphp_column`","where (`OP_Adminopen` LIKE '%$newid%' or `OP_Adminopen` LIKE '%00%') ".$l." order by OP_Sorting asc,id asc"); 
	}else{
		$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Templist,OP_Tempview,OP_Hide,OP_Sorting,OP_Weight,OP_Url,OP_Total,`OP_Click`","`ourphp_column`","where OP_Model = '".$model."' and (`OP_Adminopen` LIKE '%$newid%' or `OP_Adminopen` LIKE '%00%') ".$l." order by OP_Sorting asc,id asc");
	}
	
	$arr = array();
	$i = 0;
	$rows = $db -> rows($query,1);
	if ($rows >= 1){
        while($ourphp_rs = $db -> whilego($query)){
			$uptitle = $db -> select("OP_Columntitle","ourphp_column","where id = ".$ourphp_rs[1]);
            $arr[] = array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"uid" => $ourphp_rs[1],
				"lang" => $ourphp_rs[2],
				"title" => $ourphp_rs[3],
				"titleto" => $ourphp_rs[4],
				"model" => $ourphp_rs[5],
				"templist" => $ourphp_rs[6],
				"tempview" => $ourphp_rs[7],
				"hide" => $ourphp_rs[8],
				"sorting" => $ourphp_rs[9],
				"weight" => $ourphp_rs[10],
				"weburl" => $ourphp_rs[11],
				"total" => $ourphp_rs[12],
				"uptitle" => $uptitle[0],
				"click" => $ourphp_rs[13],
				); 
			$i += 1;
		}
	}else{
		$arr[] = array(
			"i" => '0',
			"id" => '0',
			"uid" => '0',
			"lang" => '0',
			"title" => '暂无栏目',
			"titleto" => '暂无栏目',
			"model" => 'ourphp',
			"templist" => '0',
			"tempview" => '0',
			"hide" => '0',
			"sorting" => '0',
			"weight" => '0',
			"weburl" => '0',
			"total" => '0',
			"uptitle" => '0',
			"click" => '0',
		);
	}
    return $arr;
}


function navigationnum($num = 0,$class = '+'){
	global $db;
	$a = $db -> update("`ourphp_column`","`OP_Total` = `OP_Total` ".$class." 1","where id = ".intval($num));
	if($a){
		return true;
	}else{
		return false;
	}
}
?>