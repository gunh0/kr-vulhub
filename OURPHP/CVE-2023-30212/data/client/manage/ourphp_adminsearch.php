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
	$rows = array();
}elseif ($_GET["ourphp_cms"] == "search"){

	$content = admin_sql($_POST['content']);
	$sid = admin_sql($_POST['sid']);
	$lang = admin_sql($_POST['lang']);

	header("location: ourphp_adminsearch.php?ourphp_cms=searchlist&content=".$content."&sid=".$sid."&lang=".$lang);
	exit;

}elseif ($_GET["ourphp_cms"] == "searchlist"){

$content = admin_sql($_GET['content']);
$sid = admin_sql($_GET['sid']);
$lang = admin_sql($_GET['lang']);
$inputno = $ourphp_adminfont['inputno'];

if($content == '' || $sid == '' || $lang == ''){
	exit("<script language=javascript> alert('".$inputno."');location.replace('".$ourphp["webpath"]."');</script>");
}

if($sid == 'article'){

	$top = '`OP_Articletitle`,`OP_Description`';
	$where = "(`OP_Articletitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";

}elseif($sid == 'product'){

	$top = '`OP_Title`,`OP_Description`';
	$where = "(`OP_Title` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
	
}elseif($sid == 'photo'){

	$top = '`OP_Phototitle`,`OP_Description`';
	$where = "(`OP_Phototitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
	
}elseif($sid == 'video'){

	$top = '`OP_Videotitle`,`OP_Description`';
	$where = "(`OP_Videotitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
	
}elseif($sid == 'down'){

	$top = '`OP_Downtitle`,`OP_Description`';
	$where = "(`OP_Downtitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
	
}elseif($sid == 'job'){

	$top = '`OP_Jobtitle`,`OP_Description`';
	$where = "(`OP_Jobtitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
	
}elseif($sid == 'user'){

	$top = '`OP_Useremail`,`OP_Username`';
	$where = "(`OP_Useremail` LIKE '%$content%' || `OP_Username` LIKE '%$content%')";
	
}elseif($sid == 'orders'){

	$top = '`OP_Ordersnumber`,`OP_Ordersusername`';
	$where = "(`OP_Ordersnumber` LIKE '%$content%' || `OP_Ordersusername` LIKE '%$content%')";
	
}else{
	$top = '`OP_Articletitle`,`OP_Description`';
	$where = "(`OP_Articletitle` LIKE '%$content%' || `OP_Description` LIKE '%$content%')";
}

	plugsclass::logs('后台搜索记录',$OP_Adminname);
	
	$listpage = 15;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_".$sid."`","where ".$where." order by id desc");
	$ourphptotal = $db -> whilego($ourphptotal);

	$query = $db -> listgo("`id`,".$top,"ourphp_".$sid," where ".$where." order by id desc LIMIT ".$start.",".$listpage);
	$rows = array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
			$title = str_replace($content,'<font color=red><b>'.$content.'</b></font>',$ourphp_rs[1]);
			$scontent = str_replace($content,'<font color=red><b>'.$content.'</b></font>',$ourphp_rs[2]);
			
			if($sid == 'article'){
			$url = 'ourphp_articleview.php?id='.$ourphp_rs[0];
			}elseif($sid == 'product'){
			$url = 'ourphp_productedit.php?id='.$ourphp_rs[0];
			}elseif($sid == 'photo'){
			$url = 'ourphp_photoedit.php?id='.$ourphp_rs[0];
			}elseif($sid == 'video'){
			$url = 'ourphp_videoview.php?id='.$ourphp_rs[0];
			}elseif($sid == 'down'){
			$url = 'ourphp_downview.php?id='.$ourphp_rs[0];
			}elseif($sid == 'job'){
			$url = 'ourphp_jobview.php?id='.$ourphp_rs[0];
			}elseif($sid == 'user'){
			$url = 'ourphp_userview.php?id='.$ourphp_rs[0];
			}elseif($sid == 'orders'){
			$url = 'ourphp_ordersview.php?id='.$ourphp_rs[0];
			}
			
			$rows[] = array(
				'i' => $i,
				'title' => $title,
				'content' => $scontent,
				'url' => $url,
			);
			$i+=1;
	}
}

@$_page = new Page($ourphptotal['tiaoshu'],$listpage);
$smarty->assign('ourphppage',$_page->showpage());
$smarty->assign('search',$rows);
$smarty->display('ourphp_adminsearch.html');
?>