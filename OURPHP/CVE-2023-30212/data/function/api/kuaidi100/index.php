<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="author" content="www.ourphp.net"/>
<meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-title" content="[.$ourphp_web.website.]">
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="HandheldFriendly" content="true">
<meta name="MobileOptimized" content="320">
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">
<meta name="full-screen" content="yes">
<meta name="x5-fullscreen" content="true">
<meta name="browsermode" content="application">
<meta name="x5-page-mode" content="app">
<meta name="msapplication-tap-highlight" content="no">
<title>快递接口</title>
<link href="../../plugs/YIQI-UI/YIQI-UI.min.css" rel=stylesheet>
</head>
<body>
<div id="YIQI-UI">
	<?php
	/*
	 * Ourphp - CMS建站系统
	 * Copyright (C) 2022 www.ourphp.net
	 * 开发者：哈尔滨伟成科技有限公司
	 *-------------------------------
	 * 阿里云快递查询接口
	 *-------------------------------
	*/

	include '../../../config/ourphp_code.php';
	include '../../../config/ourphp_config.php';
	include '../../../config/ourphp_Language.php';
	include '../../ourphp_function.class.php';

	session_start();
	if(!isset($_SESSION['username'])){
		exit($ourphp_adminfont['power']);
	}

	$title = $_GET['title'];
	$number = $_GET['number'];
	if($title == 'no'){
		exit('此商品不需要物流!');
	}else{
		if($title == '' || $number == ''){
			exit('参数不能为空'.$title);
		}
	}

	#查询API接口数据
	$api = plugsclass::plugs(1);
	if (!$api){
		exit($api[0].$ourphp_adminfont['plugsno']);
	}
	$AppKey = $api[2];


	error_reporting(E_ALL || ~E_NOTICE);
	$host = "https://wuliu.market.alicloudapi.com";//api访问链接
	$path = "/kdi";
	$method = "GET";
	$appcode = $AppKey; //AppCode
	$headers = array();
	array_push($headers, "Authorization:APPCODE " . $appcode);
	$querys = "no=".$number."&type=";
	$bodys = "";
	$url = $host . $path . "?" . $querys;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, true);
	if (1 == strpos("$" . $host, "https://")) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	}
	$out_put = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	list($header, $body) = explode("\r\n\r\n", $out_put, 2);
	if ($httpCode == 200) {
		//print("正常请求计费(其他均不计费)<br>");
		$info = json_decode($body,true);
		if($info['msg'] == 'ok')
		{
			echo '
				<div class="pd-20">
				<p class="f-12 f-999">快递单号 <span class="f-333 f-14">'.$info['result']['number'].'</span></p>
				<p class="f-12 f-999">快递公司 <span class="f-333 f-14">'.$info['result']['type'].'</span></p>
				<div class="line"></div>
			';
			foreach($info['result']['list'] as $op)
			{
				echo '<p><span class="f-999 pl-5">'.$op['time'].'</span><br />'.$op['status'].'</p>';
				echo '<div class="line"></div>';
			}
			echo '
				</div>
			';
		}else{
			echo '
				<div class="pd-20">
				<h4>暂无快递信息</h4>
				<p class="f-12 f-999">快递单号 <span class="f-333">'.$info['result']['number'].'</span></p>
				<p class="f-12 f-999">快递公司 <span class="f-333">'.$info['result']['type'].'</span></p>
				</div>
			';
		}
		
	} else {
		if ($httpCode == 400 && strpos($header, "Invalid Param Location") !== false) {
			print("参数错误");
		} elseif ($httpCode == 400 && strpos($header, "Invalid AppCode") !== false) {
			print("AppCode错误");
		} elseif ($httpCode == 400 && strpos($header, "Invalid Url") !== false) {
			print("请求的 Method、Path 或者环境错误");
		} elseif ($httpCode == 403 && strpos($header, "Unauthorized") !== false) {
			print("服务未被授权（或URL和Path不正确）");
		} elseif ($httpCode == 403 && strpos($header, "Quota Exhausted") !== false) {
			print("套餐包次数用完");
		} elseif ($httpCode == 500) {
			print("API网关错误");
		} elseif ($httpCode == 0) {
			print("URL错误");
		} else {
			print("参数名错误 或 其他错误");
			print($httpCode);
			$headers = explode("\r\n", $header);
			$headList = array();
			foreach ($headers as $head) {
				$value = explode(':', $head);
				$headList[$value[0]] = $value[1];
			}
			print($headList['x-ca-error-message']);
		}
	}

	?>
</div>
</body>
</html>