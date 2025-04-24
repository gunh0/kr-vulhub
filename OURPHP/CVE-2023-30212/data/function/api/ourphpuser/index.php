<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2023 www.ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * OURPHP系统 API接口
 *-------------------------------
*/
include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_version.php';
include '../../../config/ourphp_Language.php';
include '../../ourphp_function.class.php';
include './ourphp_system.php';

$getkey = dowith_sql($_POST['key']);
$table = dowith_sql($_POST['table']);
$page = intval($_POST['page']);
$limit = intval($_POST['limit']);
$desc = admin_sql($_POST['desc']);
$sort = admin_sql($_POST['sort']);

if(empty($getkey) || empty($table) || empty($desc) || empty($sort))
{
	webapi::jsoncode("fail","1001","key不能为空或请求参数不能为空！");
}

if($getkey != $ourphp['safecode'])
{
	webapi::jsoncode("fail","1001","key出错！");
}

session_start();
webapi::data($table, $page, $limit, $desc, $sort);

?>