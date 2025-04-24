<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/

include '../../config/ourphp_code.php';
include '../../config/ourphp_config.php';
include '../../config/ourphp_version.php';
include '../../config/ourphp_Language.php';
include '../../function/ourphp_dz.class.php';
include '../../function/ourphp_function.class.php';
include '../../function/ourphp/Smarty.class.php';

$ourphp_templates = "templates/";
$ourphp_templates_c = "../../function/_compile/";
$ourphp_cache = "../../function/_cache/";
$ourphp_bgcolor = "onmouseover=this.style.backgroundColor='#FFFFCC' onmouseout=this.style.backgroundColor='#ffffff'";

date_default_timezone_set('Asia/Shanghai');
$smarty = new Smarty;
$smarty->caching = false; 
$smarty->setTemplateDir($ourphp_templates);
$smarty->setCompileDir($ourphp_templates_c);
$smarty->setCacheDir($ourphp_cache);
$smarty->addPluginsDir(array('../../function/class','../../function/data',));
$smarty->assign('ourphp','<h1>hello,ourphp!</h1>');
$smarty->assign('ourphp_access',$ourphp_access);
$smarty->assign('version',$ourphp_version);
$smarty->assign('versiondate',$ourphp_versiondate);
$smarty->assign('webpath',$ourphp['webpath']);
$smarty->assign('adminpath',$ourphp['adminpath']);
$smarty->assign('templatepath',$ourphp_templates);
$smarty->assign('ourphp_adminfont',$ourphp_adminfont);
$smarty->assign('ourphp_bgcolor',$ourphp_bgcolor);

function Admin_click($webname='模板标签调用',$weburl='outphp_tag.php'){
	global $db;
	$query = $db -> select("`id`,`OP_Title`,`OP_Url`,`OP_Click`","`ourphp_adminclick`","where OP_Title = '".$webname."'");
	if(!$query){
		$insert = $db -> insert("`ourphp_adminclick`","`OP_Title` = '".$webname."',`OP_Url` = '".$weburl."',`OP_Click` = 0","");
	}else{
		$update = $db -> update("`ourphp_adminclick`","`OP_Click` = `OP_Click` + 1","where OP_Title = '".$webname."'");
	}
}
?>