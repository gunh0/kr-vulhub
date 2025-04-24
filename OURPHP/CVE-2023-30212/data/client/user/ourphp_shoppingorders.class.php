<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "del"){

	$ourphp_rs = $db -> select("`id`,`OP_Orderspay`,`OP_Orderssend`","`ourphp_orders`","where `id` = ".intval($_GET['id'])." && `OP_Ordersnumber` = '".dowith_sql($_GET['dh'])."'");
	if(!$ourphp_rs){
		exit("no!");
	}
	if($ourphp_rs[1] == 2){
		exit("no!");
	}
	if($ourphp_rs[2] == 2){
		exit("no!");
	}
	
	$db -> del("`ourphp_orders`","where `id` = '".intval($_GET['id'])."'");
	if(isset($_GET['o'])){
		exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/user/?".$ourphp_Language."-usershopping-op.html');</script>");
	}else{
		exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/user/?".$ourphp_Language."-usershopping-op.html');</script>");
	}
	
	
}elseif ($_GET["ourphp_cms"] == "sign"){
	
	$ourphp_rs = $db -> select("`id`,`OP_Sign`","`ourphp_orders`","where `id` = ".intval($_GET['id'])." && `OP_Ordersnumber` = '".dowith_sql($_GET['dh'])."'");
	if(!$ourphp_rs){
		exit("no!");
	}
	if($ourphp_rs[1] == 1){
		exit("no!");
	}
	
	$db -> update("`ourphp_orders`","`OP_Sign` = 1","where `id` = ".$ourphp_rs[0]);
	exit("<script language=javascript>location.replace('".$ourphp['webpath']."client/user/?".$ourphp_Language."-usershopping-op.html');</script>");
}

function ourphp_orderslist($type = 1){
	global $db,$smarty,$ourphp;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	
	if(isset($_GET['tag'])){
		switch($_GET['tag']){
			case "dfk":
				$while = '&& `OP_Orderspay` = 1';
			break;
			case "dfh":
				$while = '&& `OP_Orderssend` = 1';
			break;
			case "dqs":
				$while = '&& `OP_Sign` = 1';
			break;
			default:
				$while = '';
			break;
		}
	}else{
		$while = '';
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersemail` = '".$_SESSION['username']."' ".$while);
	$ourphptotal = $db -> whilego($ourphptotal);
	
	$query = $db -> listgo("`id`,`OP_Ordersname`,`OP_Ordersid`,`OP_Ordersnum`,`OP_Ordersusetext`,`OP_Ordersproductatt`,`OP_Orderswebmarket`,`OP_Ordersusermarket`,`OP_Ordersnumber`,`OP_Orderspay`,`OP_Orderssend`,`OP_Ordersexpress`,`OP_Ordersexpressnum`,`OP_Ordersfreight`,`OP_Sign`,`OP_Usermoneyback`,`OP_Tuanset`,`OP_Tuanid`,`OP_Ordersimg`","`ourphp_orders`","where `OP_Ordersemail` = '".$_SESSION['username']."' ".$while." && `OP_Tuanset` = ".$type." order by id desc LIMIT ".$start.",".$listpage); 
	$rows = array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		$prinfo = $db -> select("OP_Tuanset,OP_Down,OP_Minimg,OP_Specifications,OP_Class,`OP_Buyoffnum`","ourphp_product","where id = ".$ourphp_rs[2]);
		$tuan = array(0,0,0);
		if($ourphp_rs[16] == 2){
			$tuan = $db -> select("`OP_Tuannum`,`OP_Tuanznum`,`id`","ourphp_tuan","where `OP_Tuanoid` = ".$ourphp_rs[0]." || `id` = ".$ourphp_rs[17]);
		}
		if($ourphp_rs[9] == 1){
			if($prinfo[3] != ''){
				$a = explode("|", $prinfo[3]);
				$b = str_replace("、", ",", $ourphp_rs[5]);
				foreach ($a as $op) {
					if(strstr($op,$b)){
						$arr = explode(",", $op);
						$c = $arr[count($arr)-1];
					}
				}		
			}else{
				$c = 999999;
			}
		}else{
			$c = 999999;
		}
		
		if($prinfo && $prinfo[1] == 2 && $c >= $ourphp_rs[3]){

			$newatt = '';
			$att = explode("、",$ourphp_rs[5]);
			foreach($att as $op){
				if(strstr($op,"uploadfile")){
					$newatt .= '<img src="'.$ourphp['webpath'].$op.'" width="20" height="20" />';
				}else{
					$newatt .= $op;
				}
			}
			
			if($prinfo[5] != 0){
				$prxg = quota($prinfo[5]);
			}else{
				$prxg = '';
			}

			if(substr($ourphp_rs[18],0,4) == 'http')
				{
					$minimg = $ourphp_rs[18];
					}elseif($ourphp_rs[18] == ''){
						$minimg = $ourphp['webpath'].'skin/noimage.png';
						}else{
						$minimg = $ourphp['webpath'].$ourphp_rs[18];
			}
	
			$rows[] = array(
				'i' => $i,
				'id' => $ourphp_rs[0],
				'title' => $ourphp_rs[1],
				'prid' => $ourphp_rs[2],
				'prclass' => $prinfo[4],
				'num' => $ourphp_rs[3],
				'text' => $ourphp_rs[4],
				'pratt' => $newatt,
				'webmarket' => $ourphp_rs[6],
				'usermarket' => $ourphp_rs[7],
				'number' => $ourphp_rs[8],
				'pay' => $ourphp_rs[9],
				'send' => $ourphp_rs[10],
				'express' => $ourphp_rs[11],
				'expressnum' => $ourphp_rs[12],
				'freight' => $ourphp_rs[13],
				'sign' => $ourphp_rs[14],
				'moneyback' => $ourphp_rs[15],
				'tuantype' => $ourphp_rs[16],
				'tuanid' => $tuan[2],
				'tuannum' => $tuan[0],
				'tuanznum' => $tuan[1],
				'minimg' => $minimg,
				"quota" => $prxg
			);

		}
		$i+=1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty -> assign('ourphppage',$_page->showpage());
	return $rows;
}

$smarty->assign('orderslist',ourphp_orderslist(1));
$smarty->assign('orderslist2',ourphp_orderslist(2));
?>