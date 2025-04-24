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

$type = $_GET['type'];
$id = $_GET['id'];

if($type == 'group'){
	$sqltype = "ourphp_product";
}else{
	$sqltype = "ourphp_".$type;
}

$o = $db -> select("`OP_Class`",$sqltype,"where id = ".intval($id));
$db -> update("ourphp_column","`OP_Total` = `OP_Total` + 1","where id = ".$o[0]);

if($type == 'product'){

	$db -> create("INSERT INTO ourphp_product(`OP_Class`,`OP_Lang`,`OP_Title`,`OP_Number`,`OP_Goodsno`,`OP_Brand`,`OP_Market`,`OP_Webmarket`,`OP_Stock`,`OP_Usermoney`,`OP_Specificationsid`,`OP_Specificationstitle`,`OP_Specifications`,`OP_Pattribute`,`OP_Minimg`,`OP_Maximg`,`OP_Img`,`OP_Content`,`OP_Down`,`OP_Weight`,`OP_Freight`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`time`,`OP_Integral`,`OP_Integralok`,`OP_Integralexchange`,`OP_Suggest`,`OP_Productimgname`,`OP_Usermoneyclass`,`OP_Tuanset`,`OP_Tuanusernum`,`OP_Tuantime`,`OP_Tuannumber`) SELECT `OP_Class`,`OP_Lang`,`OP_Title`,`OP_Number`,`OP_Goodsno`,`OP_Brand`,`OP_Market`,`OP_Webmarket`,`OP_Stock`,`OP_Usermoney`,`OP_Specificationsid`,`OP_Specificationstitle`,`OP_Specifications`,`OP_Pattribute`,`OP_Minimg`,`OP_Maximg`,`OP_Img`,`OP_Content`,`OP_Down`,`OP_Weight`,`OP_Freight`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`time`,`OP_Integral`,`OP_Integralok`,`OP_Integralexchange`,`OP_Suggest`,`OP_Productimgname`,`OP_Usermoneyclass`,`OP_Tuanset`,`OP_Tuanusernum`,`OP_Tuantime`,`OP_Tuannumber`  FROM ourphp_product where id = ".intval($id),2) or die ($db -> error());

	header('Location: ourphp_productlist.php?id=ourphp');
	exit;

}else if($type == 'article'){

	$db -> create("INSERT INTO ourphp_article(`OP_Articletitle`,`OP_Articleauthor`,`OP_Articlesource`,`time`,`OP_Articlecontent`,`OP_Class`,`OP_Lang`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`OP_Minimg`,`OP_Callback`) SELECT `OP_Articletitle`,`OP_Articleauthor`,`OP_Articlesource`,`time`,`OP_Articlecontent`,`OP_Class`,`OP_Lang`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`OP_Minimg`,`OP_Callback`  FROM ourphp_article where id = ".intval($id),2) or die ($db -> error());
	header('Location: ourphp_article.php?id=ourphp');
	exit;

}else if($type == 'photo'){

	$db -> create("INSERT INTO ourphp_photo(`OP_Phototitle`,`time` ,`OP_Photocminimg` ,`OP_Photoimg` ,`OP_Photocontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback` ,`OP_Photoname`) SELECT `OP_Phototitle`,`time` ,`OP_Photocminimg` ,`OP_Photoimg` ,`OP_Photocontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback` ,`OP_Photoname`  FROM ourphp_photo where id = ".intval($id),2) or die ($db -> error());
	header('Location: ourphp_photo.php?id=ourphp');
	exit;

}else if($type == 'video'){

	$db -> create("INSERT INTO ourphp_video(`OP_Videotitle` ,`time`,`OP_Videoimg` ,`OP_Videovurl` ,`OP_Videoformat` ,`OP_Videowidth`,`OP_Videoheight` ,`OP_Videocontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback`) SELECT `OP_Videotitle` ,`time`,`OP_Videoimg` ,`OP_Videovurl` ,`OP_Videoformat` ,`OP_Videowidth`,`OP_Videoheight` ,`OP_Videocontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback` FROM ourphp_video where id = ".intval($id),2) or die ($db -> error());
	header('Location: ourphp_video.php?id=ourphp');
	exit;

}else if($type == 'down'){

	$db -> create("INSERT INTO ourphp_down(`OP_Downtitle` ,`time` ,`OP_Downimg` ,`OP_Downdurl` ,`OP_Downcontent` ,`OP_Downempower` ,`OP_Downtype` ,`OP_Downlang` ,`OP_Downsize` ,`OP_Class` ,`OP_Lang` ,`OP_Downmake` ,`OP_Downsetup` ,`OP_Tag` ,`OP_Downrights` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Random` ,`OP_Callback`) SELECT `OP_Downtitle` ,`time` ,`OP_Downimg` ,`OP_Downdurl` ,`OP_Downcontent` ,`OP_Downempower` ,`OP_Downtype` ,`OP_Downlang` ,`OP_Downsize` ,`OP_Class` ,`OP_Lang` ,`OP_Downmake` ,`OP_Downsetup` ,`OP_Tag` ,`OP_Downrights` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Random` ,`OP_Callback` FROM ourphp_down where id = ".intval($id),2) or die ($db -> error());
	header('Location: ourphp_down.php?id=ourphp');
	exit;

}else if($type == 'job'){

	$db -> create("INSERT INTO ourphp_job(`OP_Jobtitle` ,`time` ,`OP_Jobwork` ,`OP_Jobadd` ,`OP_Jobnature` ,`OP_Jobexperience` ,`OP_Jobeducation` ,`OP_Jobnumber` ,`OP_Jobage` ,`OP_Jobwelfare` ,`OP_Jobwage` ,`OP_Jobcontact` ,`OP_Jobtel` ,`OP_Jobcontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback`) SELECT `OP_Jobtitle` ,`time` ,`OP_Jobwork` ,`OP_Jobadd` ,`OP_Jobnature` ,`OP_Jobexperience` ,`OP_Jobeducation` ,`OP_Jobnumber` ,`OP_Jobage` ,`OP_Jobwelfare` ,`OP_Jobwage` ,`OP_Jobcontact` ,`OP_Jobtel` ,`OP_Jobcontent` ,`OP_Class` ,`OP_Lang` ,`OP_Tag` ,`OP_Sorting` ,`OP_Attribute` ,`OP_Url` ,`OP_Description` ,`OP_Click` ,`OP_Callback` FROM ourphp_job where id = ".intval($id),2) or die ($db -> error());
	header('Location: ourphp_job.php?id=ourphp');
	exit;

}else if($type == 'group'){

	$db -> create("INSERT INTO ourphp_product(`OP_Class`,`OP_Lang`,`OP_Title`,`OP_Number`,`OP_Goodsno`,`OP_Brand`,`OP_Market`,`OP_Webmarket`,`OP_Stock`,`OP_Usermoney`,`OP_Specificationsid`,`OP_Specificationstitle`,`OP_Specifications`,`OP_Pattribute`,`OP_Minimg`,`OP_Maximg`,`OP_Img`,`OP_Content`,`OP_Down`,`OP_Weight`,`OP_Freight`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`time`,`OP_Integral`,`OP_Integralok`,`OP_Integralexchange`,`OP_Suggest`,`OP_Productimgname`,`OP_Usermoneyclass`,`OP_Tuanset`,`OP_Tuanusernum`,`OP_Tuantime`,`OP_Tuannumber`) SELECT `OP_Class`,`OP_Lang`,`OP_Title`,`OP_Number`,`OP_Goodsno`,`OP_Brand`,`OP_Market`,`OP_Webmarket`,`OP_Stock`,`OP_Usermoney`,`OP_Specificationsid`,`OP_Specificationstitle`,`OP_Specifications`,`OP_Pattribute`,`OP_Minimg`,`OP_Maximg`,`OP_Img`,`OP_Content`,`OP_Down`,`OP_Weight`,`OP_Freight`,`OP_Tag`,`OP_Sorting`,`OP_Attribute`,`OP_Url`,`OP_Description`,`OP_Click`,`time`,`OP_Integral`,`OP_Integralok`,`OP_Integralexchange`,`OP_Suggest`,`OP_Productimgname`,`OP_Usermoneyclass`,`OP_Tuanset`,`OP_Tuanusernum`,`OP_Tuantime`,`OP_Tuannumber`  FROM ourphp_product where id = ".intval($id),2) or die ($db -> error());

	header('Location: ourphp_grouplist.php?id=ourphp');
	exit;
}
?>