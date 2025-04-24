<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include './config/ourphp_code.php';
include './config/ourphp_config.php';
include './config/ourphp_version.php';
include './config/ourphp_Language.php';
include './function/ourphp_function.class.php';
include './function/ourphp/Smarty.class.php';
include './function/ourphp_system.class.php';

include './function/ourphp_page.class.php';
include './function/ourphp_list.class.php';
include './function/ourphp_search.class.php';

if($smarty->templateExists($ourphp_templates."/".$lang."/".$lang."_search.html")){
$smarty->display($lang.'/'.$lang.'_search.html');
	}else{
echo $ourphp_tempno;
}
?>