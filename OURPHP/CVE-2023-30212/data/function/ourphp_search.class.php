<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2016 www.ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

$content = dowith_sql($_REQUEST['content']);
$sid = dowith_sql($_REQUEST['sid']);
$lang = dowith_sql($_REQUEST['lang']);
$inputno = $ourphp_adminfont['inputno'];
$strlength = $ourphp_adminfont['strlength'];
$sensitivefont = $ourphp_adminfont['sensitive'];
$type = dowith_sql($_REQUEST['type']);

if($content == '' || $sid == '' || $lang == '' || $type == ''){
	$type == "wap" ? $a = $ourphp['webpath']."client/wap/" : $a = $ourphp['webpath'];
	exit("<script language=javascript> alert('".$inputno."');location.replace('".$a."');</script>");
}

if(strlen($content) > 40){
	$type == "wap" ? $a = $ourphp['webpath']."client/wap/" : $a = $ourphp['webpath'];
	exit("<script language=javascript> alert('".$strlength."');location.replace('".$a."');</script>");
}

$sensitive = ourphp_sensitive($content);
if(strpos($sensitive,'*') !== false)
{
	exit("<script language=javascript> alert('".$sensitivefont."');history.go(-1);</script>");
}

if(@dowith_sql($_GET['temp']) == 'search'){
	header("location: search.php?".$lang."-&content=".$content."&lang=".$lang."&sid=".$sid."&type=".$type);
	exit;
}

$searchtime = strtotime(date("Y-m-d H:i:s"));
if (empty($_SESSION['searchtime'])){
	
	$_SESSION['searchtime'] = $searchtime;
	
}else{
	
	if($Parameterse['searchtime'] == 0){
		
		$_SESSION['searchtime'] = $searchtime;
		
	}else{
		
		$ourphptime = $searchtime - $_SESSION['searchtime'];
		if(intval($ourphptime) < intval($Parameterse['searchtime'])){
			
			echo "<script language=javascript> alert('搜索间隔为: ".$Parameterse['searchtime']." 秒,请稍后在试!');location.replace('".$ourphp['webpath']."');</script>";
			exit;
			
		}else{
			$_SESSION['searchtime'] = $searchtime;
		}
		
	}

}
	
if($sid == 'article'){

	$top = '`OP_Articletitle`,`OP_Articlecontent`,`OP_Minimg`';
	$where = "(`OP_Articletitle` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";

}elseif($sid == 'product'){

	$top = '`OP_Title`,`OP_Content`,`OP_Minimg`';
	$where = "(`OP_Title` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";
	
}elseif($sid == 'photo'){

	$top = '`OP_Phototitle`,`OP_Photocontent`,`OP_Photocminimg`';
	$where = "(`OP_Phototitle` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";
	
}elseif($sid == 'video'){

	$top = '`OP_Videotitle`,`OP_Videocontent`,`OP_Videoimg`';
	$where = "(`OP_Videotitle` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";
	
}elseif($sid == 'down'){

	$top = '`OP_Downtitle`,`OP_Downcontent`,`OP_Downimg`';
	$where = "(`OP_Downtitle` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";
	
}elseif($sid == 'job'){

	$top = '`OP_Jobtitle`,`OP_Jobcontent`,`OP_Jobwork`';
	$where = "(`OP_Jobtitle` LIKE '%$content%' || `OP_Description` LIKE '$content%' || `OP_Tag` LIKE '%$content%')";
	
}else{
	exit($ourphp_adminfont['accessno']);
}

$listpage = 15;
if (intval(isset($_GET['page'])) == 0){
	$listpagesum = 1;
		}else{
	$listpagesum = intval($_GET['page']);
}
$start=($listpagesum-1)*$listpage;
$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_".$sid."`","where ".$where." && `OP_Lang` = '".$lang."'");
$ourphptotal = $db -> whilego($ourphptotal);

$query = $db -> listgo("`id`,".$top.",`OP_Class`","`ourphp_".$sid."`","where ".$where." && `OP_Lang` = '".$lang."' order by id desc LIMIT ".$start.",".$listpage); 
$rows = array();
$i = 1;
while($ourphp_rs = $db -> whilego($query)){
	$title = str_replace($content,'<font color=red><b>'.$content.'</b></font>',$ourphp_rs[1]);
	$scontent = str_replace($content,'<font color=red><b>'.$content.'</b></font>',$ourphp_rs[2]);
	if($sid == 'job')
	{
		$minimg = $ourphp['webpath'].'skin/noimage.png';
		}else{
			if(substr($ourphp_rs[3],0,4) == 'http'){
				$minimg = $ourphp_rs[3];
				}elseif($ourphp_rs[3] == ''){
					$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
					$minimg = $ourphp['webpath'].$ourphp_rs[3];
		}
	}
	$rows[] = array(
		'i' => $i,
		'title' => $title,
		'content' => strip_tags($scontent),
		'url' => $ourphp['webpath'].'?'.$lang.'-'.$sid.'view-'.$ourphp_rs[4].'-'.$ourphp_rs[0].'.html',
		'minimg' => $minimg,
		'wapurl' => $ourphp['webpath'].'client/wap/?'.$lang.'-'.$sid.'view-'.$ourphp_rs[4].'-'.$ourphp_rs[0].'.html',
	);
	$i+=1;
}


$rs = $db -> select("`id`","`ourphp_search`","where `OP_Searchtext` = '".$content."'");
if($rs)
{
	$add = $db -> update("`ourphp_search`","`OP_Searchclick` = `OP_Searchclick` + 1","where `OP_Searchtext` = '".$content."'");
}else{
	$add = $db -> insert("`ourphp_search`","`OP_Searchtext` = '".$content."',`OP_Searchclick` = 1,`time` = '".date("Y-m-d H:i:s")."'","");
}

$_page = new Page($ourphptotal['tiaoshu'],$listpage);
$smarty->assign('ourphppage',$_page->showpage());
$smarty->assign('search',$rows);
?>