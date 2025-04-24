<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 模板操作类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

$temptypetoo = $temptype;
$temptypetoo = str_replace(" and ","and",$temptypetoo);
$temptypetoo = str_replace(" or ","or",$temptypetoo);

switch($temptypetoo){
case "cn":
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_index.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_index.html');
			}else{
		echo $ourphp_tempno;
		exit();
		}
break;

case "index.html":
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_index.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_index.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "article":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "articleview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "product":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "productview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_shop.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "shop.html":
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shop.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_shop.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "brand":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_brand.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_brand.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "photo":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "photoview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "video":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "videoview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "about":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "club.html":
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_club.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_club.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "clubview":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_clubview.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_clubview.html");
			}else{
		echo $ourphp_tempno;
		}
break;

case "down":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "downview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "job":
		include './function/ourphp_page.class.php';
		include './function/ourphp_list.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['listtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['listtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "jobview":
		include './function/ourphp_list.class.php';
		include './function/ourphp_view.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$Listname['viewtemp'])){
		$smarty->display($ourphp_Language.'/'.$Listname['viewtemp']);
			}else{
		echo $ourphp_tempno;
		}
break;

case "shoppingcart.html":
		include './function/ourphp_list.class.php';
		include './function/ourphp_shop.class.php';
		include './function/ourphp_shoppingcart.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shoppingcart.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_shoppingcart.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "shoppingorders.html":
		include './function/ourphp_list.class.php';
		include './function/ourphp_shop.class.php';
		include './function/ourphp_shoppingorders.class.php';
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_shoppingorders.html")){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language.'_shoppingorders.html');
			}else{
		echo $ourphp_tempno;
		}
break;

case "404.html":
		if($smarty->templateExists("templates/404.html")){
		$smarty->display("templates/404.html");
			}else{
		echo $ourphp_tempno;
		}
break;

default:
		if($smarty->templateExists($ourphp_templates."/".$ourphp_Language."/".$ourphp_Language."_".$temptype)){
		$smarty->display($ourphp_Language.'/'.$ourphp_Language."_".$temptype);
			}else{
		echo $ourphp_tempno.'No:'.$ourphp_Language."_".$temptype;
		}
break;
}
?>