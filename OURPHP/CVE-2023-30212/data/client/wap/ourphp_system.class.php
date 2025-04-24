<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/


//模板全局定义
session_start();
date_default_timezone_set('Asia/Shanghai'); //设置时区
$ourphp_weburl = explode('-',$_SERVER["QUERY_STRING"]);

if (empty($ourphp_weburl[0]))
{
	$ourphp_Language = $homelang[0];
	
	}else{
		$l = $db -> select("`OP_Lang`","`ourphp_lang`","where `OP_Lang` = '".dowith_sql($ourphp_weburl[0])."'");
		if($l){
			$ourphp_Language = $l[0];
		}else{
			$ourphp_Language = $homelang[0];
		}
}

$temptype = (empty($ourphp_weburl[1]))? "cn" : dowith_sql(ourphp_urlcut($ourphp_weburl[1],"html"));
$listid = (empty($ourphp_weburl[2])) ? 0 : intval(ourphp_Cut($ourphp_weburl[2]));
$viewid = (empty($ourphp_weburl[3])) ? 0 : intval(ourphp_Cut($ourphp_weburl[3]));


function ourphp_parameters(){ 
	global $db;
	$ourphp_rs = $db -> select("`OP_Weboff`,`OP_Webofftext`,`OP_Webrewrite`,`OP_Webpage`,`OP_Webkeywords`,`OP_Webkeywordsto`,`OP_Webdescriptions`,`OP_Webweight` ,`OP_Searchtime` ,`OP_Bookuser` , `OP_Pagestype` ,`OP_Pages` ,`OP_Pagefont`","`ourphp_webdeploy`","where `id` = 1"); 
	$rows = array(
		'weboff' => $ourphp_rs[0],
		'webofftext' => $ourphp_rs[1],
		'rewrite' => $ourphp_rs[2],
		'page' => explode(",",$ourphp_rs[3]),
		'keywordsk' => $ourphp_rs[4],
		'keywords' => $ourphp_rs[5],
		'descriptions' => $ourphp_rs[6],
		'weight' => $ourphp_rs[7],
		'searchtime' => $ourphp_rs[8],
		'bookuser' => $ourphp_rs[9],
		'pagetype' => $ourphp_rs[10],
		'pagecss' => $ourphp_rs[11],
		'pagefont' => $ourphp_rs[12],
	);
	return $rows;
}
$Parameterse = ourphp_parameters();

if ($Parameterse['weboff'] == 2){
	echo $Parameterse['webofftext'];
	exit;
}

$ourphp_templates = "../../templates/wap";
$ourphp_templates_c = "../../function/_compile/";
$ourphp_cache = "function/_cache/";
$ourphp_Othercache = "../../function/_cache/"; //手机版专属
$smarty = new Smarty;
$smarty->caching = false; 
$smarty->setTemplateDir($ourphp_templates);
$smarty->setCompileDir($ourphp_templates_c);
$smarty->setCacheDir($ourphp_Othercache);
$smarty->addPluginsDir(array('../../function/class','../../function/data',));
$smarty->assign('ourphp','<h1>hello,ourphp!</h1>');
$smarty->assign('ourphp_access',$ourphp_access);
$smarty->assign('version',$ourphp_version);
$smarty->assign('webpath',$ourphp['webpath']);
$smarty->assign('adminpath',$ourphp['adminpath']);
$smarty->assign('templatepath',$ourphp['webpath'].str_replace('../../','',$ourphp_templates)."/");
$smarty->assign('listid',$listid);

function ourphp_web(){ 
	global $ourphp,$db,$ourphp_Language,$temptype,$listid,$viewid,$Parameterse,$ourphp_cache;

	$ourphp_rs = $db -> select("
		a.`OP_Weburl` as weburl,
		a.`OP_Webname` as webname,
		a.`OP_Webadd` as webadd,
		a.`OP_Webtel` as webtel,
		a.`OP_Webmobi` as webmobi,
		a.`OP_Webfax` as webfax,
		a.`OP_Webemail` as webmail,
		a.`OP_Webzip` as webzip,
		a.`OP_Webqq` as webqq,
		a.`OP_Weblinkman` as weblinkman,
		a.`OP_Webicp` as webicp,
		a.`OP_Webpoliceicp` as policeicp,
		a.`OP_Webpoliceicpurl` as policeicpurl,
		a.`OP_Webtime` as webtime,
		a.`OP_Webstatistics` as webstatistics,
		a.`OP_Websitemin` as websitemin,
		a.`OP_Weixin` as webxin,
		a.`OP_Weixinerweima` as webxinerweima,
		a.`OP_Alipayname` as alipayname,
		a.`OP_Webhttp` as webhttp,
		b.`OP_Website` as waptitle,
		b.`OP_Weblogo` as waplogo,
		b.`OP_Webkeywords` as wapkeywords,
		b.`OP_Webdescriptions` as wapdescriptions,
		c.`OP_Langtitle` as langtitle,
		c.`OP_Langkeywords` as langkeywords,
		c.`OP_Langdescription` as langdescription,
		c.`OP_Webname` as webname2,
		c.`OP_Webadd` as webadd2,
		c.`OP_Weblinkman` as webman2
		","`ourphp_web` a, `ourphp_wap` b, `ourphp_lang` c","where a.`id` = 1 && b.`id` = 1 && c.`OP_Lang` = '".$ourphp_Language."'");

	if($ourphp_Language == 'cn'){
		
		$website = $ourphp_rs["waptitle"];
		$webkeywords = $ourphp_rs["wapkeywords"];
		$webdescriptions = $ourphp_rs["wapdescriptions"];
		$webname = $ourphp_rs["webname"];
		$webadd = $ourphp_rs["webadd"];
		$webman = $ourphp_rs["weblinkman"];
		
	}else{
		
		$website = $ourphp_rs["langtitle"];
		$webkeywords = $ourphp_rs["langkeywords"];
		$webdescriptions = $ourphp_rs["langdescription"];
		$webname = $ourphp_rs["webname2"];
		$webadd = $ourphp_rs["webadd2"];
		$webman = $ourphp_rs["webman2"];
	}

	$fsomd5 = md5('ourphp_wapcontent');
	if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'web_'.$ourphp_Language.$fsomd5.'.txt')){

		include '../../function/ourphp_dz.class.php';
		$rows = array(
			'website' => $website,
			'weburl' => $ourphp_rs["weburl"],
			'weblogo' => $ourphp['webpath'].$ourphp_rs["waplogo"],
			'webname' => $webname,
			'webadd' => $webadd,
			'webtel' => $ourphp_rs["webtel"],
			'webmobi' => $ourphp_rs["webmobi"],
			'webfax' => $ourphp_rs["webfax"],
			'webemail' => $ourphp_rs["webmail"],
			'webzip' => $ourphp_rs["webzip"],
			'webqq' => $ourphp_rs["webqq"],
			'weblinkman' => $webman,
			'webicp' => '<a href="https://beian.miit.gov.cn/" class="icp" target="_blank" rel="nofollow">'.$ourphp_rs["webicp"].'</a>',
			'policeicp' => '<a href="'.$ourphp_rs["policeicpurl"].'" class="policeicp" target="_blank" rel="nofollow"><img src="'.$ourphp['webpath'].'skin/police.png" width="30" />'.$ourphp_rs["policeicp"].'</a>',
			'webtime' => $ourphp_rs["webtime"],
			'webkeywords' => $webkeywords,
			'webdescriptions' => $webdescriptions,
			'webstatistics' => $ourphp_rs["webstatistics"],
			'websitemin' => $ourphp_rs["websitemin"],
			'weixin' => $ourphp_rs["webxin"],
			'erweima' => $ourphp_rs["webxinerweima"],
			'alipayname' => $ourphp_rs["alipayname"],
			'webhttp' => $ourphp_rs["webhttp"],
			'by' => op("OP_Login")
		);
		
		ourphp_file($ourphp_cache.'web_'.$ourphp_Language.$fsomd5.'.txt',json_encode($rows),2);

	}else{

		$arraytojson = json_decode(file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'web_'.$ourphp_Language.$fsomd5.'.txt'));
		$rows = object_array($arraytojson);

	}
	return $rows;
}

function ourphp_usercontrol(){ 
	global $db;
	$ourphp_web = ourphp_web();
	$ourphp_rs = $db -> select("`OP_Userreg`,`OP_Userlogin`,`OP_Userprotocol`,`OP_Usergroup`,`OP_Usermoney`,`OP_Useripoff`,`OP_Regtyle`,`OP_Regcode`,`OP_Userpassgo`,`OP_Withdrawal`","`ourphp_usercontrol`","where `id` = 1"); 
	$rows = array(
		'regoff' => $ourphp_rs[0],
		'loginoff' => $ourphp_rs[1],
		'protocol' => str_replace('[.$ourphp_web.website.]',$ourphp_web['website'],$ourphp_rs[2]),
		'group' => $ourphp_rs[3],
		'money' => explode("|",$ourphp_rs[4]),
		'ipoff' => $ourphp_rs[5],
		'type' => $ourphp_rs[6],
		'code' => $ourphp_rs[7],
		'telsms' => $ourphp_rs[8],
		'withdrawal' => $ourphp_rs[9],
	);
	return $rows;
}
$ourphp_usercontrol = ourphp_usercontrol();

function indexcolumn() { 
    global $db,$ourphp_Language,$ourphp,$Parameterse; 
	$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$ourphp_Language."' order by OP_Sorting asc,id desc");
	$rows=array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
		
		if($ourphp_rs[5] == 'weburl'){
			$weburl = $ourphp_rs[6];
			$wapurl = $ourphp_rs[6];
		}else{
			if($Parameterse['rewrite'] == 1){
				$weburl = $ourphp['webpath'].$ourphp_rs[2].'/'.$ourphp_rs[5].'/'.$ourphp_rs[0].'/';
			}else{
				$weburl = $ourphp['webpath'].'?'.$ourphp_rs[2].'-'.$ourphp_rs[5].'-'.$ourphp_rs[0].'.html';
			}
			$wapurl = $ourphp['webpath'].'client/wap/?'.$ourphp_rs[2].'-'.$ourphp_rs[5].'-'.$ourphp_rs[0].'.html';
		}
		
		if(substr($ourphp_rs[8],0,4) == 'http')
		{
			$minimg = $ourphp_rs[8];
			}elseif($ourphp_rs[8] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[8];
		}
		
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"uid" => $ourphp_rs[1],
			"title" => $ourphp_rs[3],
			"titleto" => $ourphp_rs[4],
			"type" => $ourphp_rs[5],
			"url" => $weburl,
			"briefing" => $ourphp_rs[7],
			"img" => $minimg,
			"wapurl" => $wapurl,
		); 
	$i+=1;
	}
	include '../../function/ourphp_Tree.class.php';
	$op = new Tree($rows);
	$arr = $op -> leaf();
    return $arr;
}

//获取IP等常用参数
function getIP(){
	global $ourphp_Language,$temptype,$listid,$viewid,$Parameterse;
	if (@$_SERVER["HTTP_X_FORWARDED_FOR"]){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
			}elseif (@$_SERVER["HTTP_CLIENT_IP"]){
		$ip = $_SERVER["HTTP_CLIENT_IP"]; 
			}elseif (@$_SERVER["REMOTE_ADDR"]){
		$ip = $_SERVER["REMOTE_ADDR"]; 
			}elseif (@getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
			}elseif (@getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP"); 
			}elseif (@getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR"); 
			}else{
		$ip = "Unknown"; 
	}
	$ip = filter_var($ip, FILTER_VALIDATE_IP);
    if($ip === false){
		$ip = "Unknown";
	}
	$rows = array(
		'ip' => $ip,
		'lang' => $ourphp_Language,
		'type' => $temptype,
		'listid' => $listid,
		'viewid' => $viewid,
		'bookuser' => $Parameterse['bookuser'],
	);
	return $rows; 
}

function columncycle($id=1){
	global $db,$ourphp,$Parameterse;
	$ourphp_rs = $db -> select("`id`,`OP_Lang`,`OP_Columntitle`,`OP_Model`","`ourphp_column`","where id = $id"); 
	if($Parameterse['rewrite'] == 1){
		$url = $ourphp['webpath'].$ourphp_rs[1].'/'.$ourphp_rs[3].'/'.$ourphp_rs[0].'/';
		}else{
		$url = $ourphp['webpath'].'?'.$ourphp_rs[1].'-'.$ourphp_rs[3].'-'.$ourphp_rs[0].'.html';
	}
	$rows = array(
		'title' => $ourphp_rs[2],
		'url' => $url,
	);
	return $rows; 
}

function ourphp_adoverall($type,$temptype){
	global $ourphp,$db,$ourphp_cache;
	if($temptype == 'article' || $temptype == 'articleview'){
	$adclass = '文章';
	}elseif($temptype == 'product' || $temptype == 'productview'){
	$adclass = '商品';
	}elseif($temptype == 'photo' || $temptype == 'photoview'){
	$adclass = '图集';
	}elseif($temptype == 'video' || $temptype == 'videoview'){
	$adclass = '视频';
	}elseif($temptype == 'down' || $temptype == 'downview'){
	$adclass = '下载';
	}elseif($temptype == 'job' || $temptype == 'jobview'){
	$adclass = '招聘';
	}elseif($temptype == 'about'){
	$adclass = '单页面';
	}else{
	$adclass = '首页';
	}
	$fsomd5 = md5($type.$adclass);

	if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'ad_'.$fsomd5.'.txt')){
	switch($type){
	case "head":
		$ourphp_rs = $db -> select("OP_Adcontent,OP_Adclass","`ourphp_ad`","where `id` = 1");
		if(strpos(', '.$ourphp_rs[1],$adclass) > 0){
			$content = $ourphp_rs[0];
		}else{
			$content = '';
		}
		ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$content,1);
	break;
	
	case "foot":
		$ourphp_rs = $db -> select("OP_Adcontent,OP_Adclass","`ourphp_ad`","where `id` = 2");
		if(strpos(', '.$ourphp_rs[1],$adclass) > 0){
			$content = $ourphp_rs[0];
		}else{
			$content = '';
		}
		ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$content,1);
	break;

	case "list":
		$ourphp_rs = $db -> select("OP_Adcontent,OP_Adclass","`ourphp_ad`","where `id` = 3");
		if(strpos(', '.$ourphp_rs[1],$adclass) > 0){
			$content = $ourphp_rs[0];
		}else{
			$content = '';
		}
		ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$content,1);
	break;
	
	case "view":
		$ourphp_rs = $db -> select("OP_Adcontent,OP_Adclass","`ourphp_ad`","where `id` = 4");
		if(strpos(', '.$ourphp_rs[1],$adclass) > 0){
			$content = $ourphp_rs[0];
		}else{
			$content = '';
		}
		ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$content,1);
	break;
	}
	
	}else{
		$content = file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'ad_'.$fsomd5.'.txt');
	}
	return $content;
}

function ourphp_ad($type){
	global $ourphp,$db,$ourphp_cache;
	$fsomd5 = md5($type);
	if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'ad_'.$fsomd5.'.txt')){
	switch($type){
	case "Float":
			$ourphp_rs = $db -> select("OP_Adpiaofui,OP_Adpiaofuu,OP_Adstateo","`ourphp_ad`","where `id` = 5");
			if($ourphp_rs[2] == 1){
				if(substr($ourphp_rs[0],0,4) == 'http')
				{
					$minimg = $ourphp_rs[0];
					}elseif($ourphp_rs[0] == ''){
						$minimg = $ourphp['webpath'].'skin/noimage.png';
						}else{
						$minimg = $ourphp['webpath'].$ourphp_rs[0];
				}
				$ad = '<script src="'.$ourphp['webpath'].'function/plugs/ad/piaofu.js" language="JavaScript"></script>'
					 .'<div id="piaofu" style="z-index:99999;">'
					 .'<a href="'.$ourphp_rs[1].'" target="_blank"><img src="'.$minimg.'" border="0"></a>'
					 .'</div>'
					 .'<script>'
					 .'var piaofurun=new AdMove("piaofu");'
					 .'piaofurun.Run();'
					 .'</script>';
			}else{
				$ad = '';
			}
			ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$ad,1);
	break;
	
	case "Right":
			$ourphp_rs = $db -> select("OP_Adyouxiat,OP_Adyouxiaf,OP_Adstatet","`ourphp_ad`","where `id` = 5");
			if($ourphp_rs[2] == 1){
			$ad = '<div id="msg_win" style="display:block;top:490px;visibility:visible;opacity:1;">'
				 .'<div class="icos"><a id="msg_min" title="最小化" href="javascript:void 0"></a>'
				 .'<a id="msg_close" title="关闭" href="javascript:void 0">×</a></div>'
				 .'<div id="msg_title">'.$ourphp_rs[0].'</div>'
				 .'<div id="msg_content">'.$ourphp_rs[1].'</div>'
				 .'</div>'
				 .'<script src="'.$ourphp['webpath'].'function/plugs/ad/tc.js" language="JavaScript"></script>'
				 .'<LINK href="'.$ourphp['webpath'].'function/plugs/ad/tc.css" type=text/css rel=stylesheet>';
			}else{
				$ad = '';
			}
			ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$ad,1);
	break;
	
	case "Double":
			$ourphp_rs = $db -> select("OP_Adduilianli,OP_Adduilianlu,OP_Adduilianri,OP_Adduilianru,OP_Adstates","`ourphp_ad`","where `id` = 5");
			if($ourphp_rs[4] == 1){
				
			if(substr($ourphp_rs[0],0,4) == 'http')
			{
				$minimg = $ourphp_rs[0];
				}elseif($ourphp_rs[0] == ''){
					$minimg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$minimg = $ourphp['webpath'].$ourphp_rs[0];
			}
			
			if(substr($ourphp_rs[2],0,4) == 'http')
			{
				$maximg = $ourphp_rs[2];
				}elseif($ourphp_rs[2] == ''){
					$maximg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$maximg = $ourphp['webpath'].$ourphp_rs[2];
			}
			
			$ad = '<DIV id="lovexin12" style="left:22px;POSITION:absolute;TOP:69px;z-index:99999;">'
				 .'<a href="'.$ourphp_rs[1].'" target="_blank"><img src="'.$minimg.'" border="0"></a>'
				 .'<br><a href=JavaScript:; onclick="document.getElementById("lovexin12").style.display="none";"><img border="0" src="'.$ourphp['webpath'].'function/plugs/ad/close.gif"></a>'
				 .'</DIV>'
				 .'<DIV id="lovexin14" style="right:22px;POSITION:absolute;TOP:69px;z-index:99999;">'
				 .'<a href="'.$ourphp_rs[3].'" target="_blank"><img src="'.$maximg.'" border="0"></a>'
				 .'<br><a href=JavaScript:; onclick="document.getElementById("lovexin14").style.display="none";"><img border="0" src="'.$ourphp['webpath'].'function/plugs/ad/close.gif"></a>'
				 .'</DIV>'
				 .'<script src="'.$ourphp['webpath'].'function/plugs/ad/duilian.js" language="JavaScript"></script>'
				 .'<script>window.setInterval("heartBeat()",1);</script>';
			}else{
				$ad = '';
			}
			ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$ad,1);
	break;

	case "mpop":
			$ourphp_rs = $db -> select("OP_Adduilianli,OP_Adduilianlu,OP_Adduilianri,OP_Adduilianru,OP_Adstates","`ourphp_ad`","where `id` = 7");
			if($ourphp_rs[4] == 1){
				
			if(substr($ourphp_rs[0],0,4) == 'http')
			{
				$minimg = $ourphp_rs[0];
				}elseif($ourphp_rs[0] == ''){
					$minimg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$minimg = $ourphp['webpath'].$ourphp_rs[0];
			}

			$ad = '
			<script src="'.$ourphp['webpath'].'function/plugs/layer3.1.0/layer.js"></script>
			<script>

			function setcookie() {
			    let d = new Date();

			    d.setTime(d.getTime() + 24 * 60 * 60 * 1000);
			    // ad=popup-ad   键值对形式：name=key   expires 有效期
			    document.cookie = "ad=popup-ad;expires= " + d.toGMTString();

			    let result = document.cookie;
			    return result;
			}

			function mpop(i){
				if(i == 1){
				    layer.open({
				      type: 1,
				      title:false,
				      shadeClose: true,
				      shade: 0.5,
				      area: ["85%", "50%"],
				      skin: "layui-layer-nobg",
				      content: "<div style=\'width:100%; text-align:center;\'><a href=\''.$ourphp_rs[1].'\'><img src=\''.$minimg.'\' style=\'max-width:100%;\'></a></div>"
				  	});
			    }
			}

			$(function(){

				if (!document.cookie.includes("ad=")) {
				    mpop(1);
				    setcookie();
				} else {
				    mpop(2);
				}

			})

			</script>
			';
			}else{
				$ad = '';
			}
			ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$ad,1);
	break;
	
	case "countdown":
			$ourphp_rs = $db -> select("OP_Adduilianli,OP_Adduilianlu,OP_Adduilianri,OP_Adduilianru,OP_Adstates","`ourphp_ad`","where `id` = 8");
			if($ourphp_rs[4] == 1){
				
			if(substr($ourphp_rs[0],0,4) == 'http')
			{
				$minimg = $ourphp_rs[0];
				}elseif($ourphp_rs[0] == ''){
					$minimg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$minimg = $ourphp['webpath'].$ourphp_rs[0];
			}

			$ad = '
			<script src="'.$ourphp['webpath'].'function/plugs/layer3.1.0/layer.js"></script>
			<script>
			
			function setcookie2() {
			    let d = new Date();
				var min = d.getMinutes();
				d.setTime(d.getTime() + 600 * 1000);
			    // ad=popup-ad   键值对形式：name=key   expires 有效期
			    document.cookie = "ad2=popup-ad;expires= " + d.toGMTString();

			    let result = document.cookie;
			    return result;
			}
			
			function ourphpi(){
				var i = $("#countdown").html();
				if(i <= 0){
					layer.closeAll();
				}else{
					i = parseInt(i) - 1;
					$("#countdown").html(i);
				}
			}
			
			function countdown(){
				layer.open({
				  type: 1,
				  title:false,
				  closeBtn: 0,
				  area: ["100%", "100%"],
				  content: "<div style=\'width:100%; text-align:center; position:relative;\'><img src=\''.$minimg.'\' style=\'max-width:100%;\'><span style=\'position:absolute; top:15px; left:15px; background:#9C9C9C; color:#fff; padding:1px 10px; border-radius:4px; font-size:12px;\'>广告</span><span style=\'position:absolute; top:15px; right:15px; color:#000; font-size:14px;\'><font id=\'countdown\'>'.$ourphp_rs[1].'</font> 秒后关闭</span><span style=\'position:absolute; background:#fff; border-radius:20px; padding:5px 15px; bottom:25px; right:15px; color:#333; font-size:14px;\' onclick=\'layer.closeAll();\'>跳过</span></div>"
				});
				
				setInterval("ourphpi()",1000);
			}
			
			$(function(){
				if (!document.cookie.includes("ad2=")) {
					countdown();
					setcookie2();
				}
			})

			</script>
			';
			}else{
				$ad = '';
			}
			ourphp_file($ourphp_cache.'ad_'.$fsomd5.'.txt',$ad,1);
	break;
	}
	
	}else{
		$ad = file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'ad_'.$fsomd5.'.txt');
	}
	return $ad;
}

function ourphp_brand(){
	global $ourphp,$db,$ourphp_Language;
	$query = $db -> listgo("id,OP_Brand,OP_Class,OP_Img,time","`ourphp_productcp`","where OP_Class = 2 order by id desc");
	$rows=array();
	$i=1;
        while($ourphp_rs = $db -> whilego($query)){
			if(substr($ourphp_rs[3],0,4) == 'http')
			{
				$maximg = $ourphp_rs[3];
				}elseif($ourphp_rs[3] == ''){
					$maximg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$maximg=$ourphp['webpath'].$ourphp_rs[3];
			}
			
            $rows[]=array(
				"i" => $i,
				"id" => $ourphp_rs[0],
				"title" => $ourphp_rs[1],
				"class" => $ourphp_rs[2],
				"minimg" => $maximg,
				"time" => $ourphp_rs[4],
				"url" => $ourphp['webpath'].'?'.$ourphp_Language.'-brand-'.$ourphp_rs[0].'.html',
				"wapurl" => $ourphp['webpath'].'client/wap/?'.$ourphp_Language.'-brand-'.$ourphp_rs[0].'.html',
			); 
		$i+=1;
		}
    return $rows;
}

function opcmsbrand($id=0) { 
	global $db,$ourphp;
	if($id == 0){
		return false;
	}else{
		$ourphp_rs = $db -> select("OP_Brand,OP_Img","`ourphp_productcp`","where `id` = ".$id); 
		if(substr($ourphp_rs[1],0,4) == 'http')
		{
			$minimg = $ourphp_rs[1];
			}elseif($ourphp_rs[1] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
				$minimg=$ourphp['webpath'].$ourphp_rs[1];
		}
		$rows = array(
			'title' => $ourphp_rs[0],
			'minimg' => $minimg,
		);
	return $rows;
	}
}


function clubnumber($id='',$class){
	global $db;
	
	if ($id != ''){
		if($class == 'club'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_book`","where `OP_Bookclass` = ".$id);
		}elseif($class == 'zxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersid` = ".$id." && `OP_Orderspay` = 2");
		}elseif($class == 'yxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersid` = ".$id." && DATE_FORMAT(time,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') && `OP_Orderspay` = 2");
		}elseif($class == 'comment'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_comment`","where `OP_Class` = ".intval($id)." && `OP_Type` = 'productview'");
		}
		$ourphptotal = $db -> whilego($ourphptotal);
		return $ourphptotal['tiaoshu'];
	}else{
		return "-1";
	}

}

function ourphp_userproblem(){ 
	global $db,$ourphp; 
	$query = $db -> listgo("`OP_Userproblem`","`ourphp_userproblem`","order by id desc"); 
	$rows=array();
	$i=1;
	while($ourphp_rs = $db -> whilego($query)){
		$rows[] = array(
			'i' => $i,
			'title' => $ourphp_rs[0],
		);
		$i+=1;
	}
	return $rows;
}

function couponlist($cid,$pid)
{
	global $db;
	
	$list = '';
	
	if($cid != 0){
		$list .= '<div class="ourphp_couponlist">';
		$list .= '	<div class="couponlist_fl">领券：</div>';
		$list .= '		<div class="couponlist_fr">';
		$list .= '			<a href="javascript:;" onclick="couponlist('.$pid.');">';
		$a = $db -> listgo("*","ourphp_coupon","where id in (".$cid.") limit 3");
		while($rs = $db -> whilego($a)){
			$list .= '
							<li class="test">'.$rs['OP_Title'].'</li>
			';
		}
		$list .= '			</a>';
		$list .= '		</div>';
		$list .= '</div>';
	}

	return $list;
}

function shoppingiconum($type = ''){
	global $db;
	if(empty($_SESSION['username'])){
		return "0";
	}else{
		
		if($type == 'car'){
			
			$ourphp_rs = $db -> listgo("COALESCE(sum(OP_Shopnum),0) as tiaoshu","`ourphp_shoppingcart`","where `OP_Shopusername` = '".$_SESSION['username']."'");
			
		}else if($type == 'shopping'){
			
			$ourphp_rs = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersemail` = '".$_SESSION['username']."' && `OP_Orderspay` = 1 && `OP_Tuanset` = 1");
			
		}else if($type == 'tuan'){
			
			$ourphp_rs = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersemail` = '".$_SESSION['username']."' && `OP_Orderspay` = 1 && `OP_Tuanset` = 2");
		
		}else if($type == 'msgemail'){
			
			$ourphp_rs = $db -> listgo("count(id) as tiaoshu","`ourphp_usermessage`","where `OP_Usercollect` = '".$_SESSION['username']."' && `OP_Userclass` = 1");
			
		}
		$ourphp_rs = $db -> whilego($ourphp_rs);
		return $ourphp_rs;
	}
}

$smarty->assign('shoppingcart',shoppingiconum('car'));
$smarty->assign('shoppingorder',shoppingiconum('shopping'));
$smarty->assign('msgemail',shoppingiconum('msgemail'));
$smarty->assign('shoptuan',shoppingiconum('tuan'));
$smarty->assign('mobile',isMobile('wap'));
$smarty->assign('ourphp_web',ourphp_web());
$smarty->assign('column',indexcolumn());
$smarty->assign('ip',getIP());
$smarty->assign('ad',array('head'=>ourphp_adoverall('head',$temptype),'foot'=>ourphp_adoverall('foot',$temptype),'list'=>ourphp_adoverall('list',$temptype),'view'=>ourphp_adoverall('view',$temptype)));
$smarty->registerFilter('pre','smartyerror');
$smarty->assign('advert',array('float'=>ourphp_ad('Float'),'right'=>ourphp_ad('Right'),'double'=>ourphp_ad('Double'),'mpop'=>ourphp_ad('mpop'),'countdown'=>ourphp_ad('countdown')));
$smarty->assign('brandclass',ourphp_brand());
$smarty->assign('problem',ourphp_userproblem());
$smarty->assign('usercontrol',$ourphp_usercontrol);
?>