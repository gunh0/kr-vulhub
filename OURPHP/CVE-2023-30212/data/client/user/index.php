<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('错误！您的PHP版本不能低于 5.3.0 !');

include '../../config/ourphp_code.php';
include '../../config/ourphp_config.php';
include '../../config/ourphp_version.php';
include '../../config/ourphp_Language.php';
include '../../function/ourphp_dz.class.php';
include '../../function/ourphp_function.class.php';
include '../../function/ourphp/Smarty.class.php';

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

$temptype = (empty($ourphp_weburl[1]))? "cn" : dowith_sql($ourphp_weburl[1]);
$listid = (empty($ourphp_weburl[2])) ? 0 : intval(ourphp_Cut($ourphp_weburl[2]));
$viewid = (empty($ourphp_weburl[3])) ? 0 : intval(ourphp_Cut($ourphp_weburl[3]));

$ourphp_templates = "../../templates/user";
$ourphp_templates_c = "../../function/_compile/";
$ourphp_cache = "../../function/_cache/";
$smarty = new Smarty;
$smarty->caching = false; 
$smarty->setTemplateDir($ourphp_templates);
$smarty->setCompileDir($ourphp_templates_c);
$smarty->setCacheDir($ourphp_cache);
$smarty->addPluginsDir(array('../../function/class','../../function/data',));
$smarty->assign('ourphp','<h1>hello,ourphp!</h1>');
$smarty->assign('ourphp_access',$ourphp_access);
$smarty->assign('version',$ourphp_version);
$smarty->assign('webpath',$ourphp['webpath']);
$smarty->assign('adminpath',$ourphp['adminpath']);
$smarty->assign('tempurl','user');
$smarty->assign('templatepath',$ourphp['webpath'].str_replace('../../','',$ourphp_templates)."/");
$smarty->assign('listid',$listid);


//通用类
function ourphp_web(){
global $ourphp,$db,$ourphp_Language,$temptype,$listid,$viewid,$Parameterse,$ourphp_cache;
$ourphp_rs = $db -> select("a.* ,b.OP_Langtitle,b.OP_Langkeywords,b.OP_Langdescription,b.OP_Webname as webname,b.OP_Webadd as webadd,b.OP_Weblinkman as webman","`ourphp_web` a, `ourphp_lang` b","where a.id = 1 && b.OP_Lang = '".$ourphp_Language."'"); 

	if($ourphp_Language == 'cn'){
		
		$website = $ourphp_rs["OP_Website"];
		$webkeywords = $Parameterse["keywords"];
		$webdescriptions = $Parameterse["descriptions"];
		$webname = $ourphp_rs["OP_Webname"];
		$webadd = $ourphp_rs["OP_Webadd"];
		$webman = $ourphp_rs["OP_Weblinkman"];
		
	}else{
		
		$website = $ourphp_rs["OP_Langtitle"];
		$webkeywords = $ourphp_rs["OP_Langkeywords"];
		$webdescriptions = $ourphp_rs["OP_Langdescription"];
		$webname = $ourphp_rs["webname"];
		$webadd = $ourphp_rs["webadd"];
		$webman = $ourphp_rs["webman"];
	}
	
	$rows = array(
		'website' => $website,
		'weburl' => $ourphp_rs["OP_Weburl"],
		'weblogo' => $ourphp['webpath'].$ourphp_rs["OP_Weblogo"],
		'webname' => $webname,
		'webadd' => $webadd,
		'webtel' => $ourphp_rs["OP_Webtel"],
		'webmobi' => $ourphp_rs["OP_Webmobi"],
		'webfax' => $ourphp_rs["OP_Webfax"],
		'webemail' => $ourphp_rs["OP_Webemail"],
		'webzip' => $ourphp_rs["OP_Webzip"],
		'webqq' => $ourphp_rs["OP_Webqq"],
		'weblinkman' => $webman,
		'webicp' => '<a href="https://beian.miit.gov.cn/" class="icp" target="_blank" rel="nofollow">'.$ourphp_rs["OP_Webicp"].'</a>',
		'policeicp' => '<a href="'.$ourphp_rs["OP_Webpoliceicpurl"].'" class="policeicp" target="_blank" rel="nofollow"><img src="'.$ourphp['webpath'].'skin/police.png" width="30" />'.$ourphp_rs["OP_Webpoliceicp"].'</a>',
		'webtime' => $ourphp_rs["OP_Webtime"],
		'webkeywords' => $webkeywords,
		'webdescriptions' => $webdescriptions,
		'webstatistics' => $ourphp_rs["OP_Webstatistics"],
		'websitemin' => $ourphp_rs["OP_Websitemin"],
		'weixin' => $ourphp_rs["OP_Weixin"],
		'erweima' => $ourphp_rs["OP_Weixinerweima"],
		'alipayname' => $ourphp_rs["OP_Alipayname"],
		'webhttp' => $ourphp_rs["OP_Webhttp"],
		'webofftext' => $Parameterse['webofftext'],
		'by' => op("OP_Login")
	);
	return $rows;
}


function indexcolumn() { 
    global $db,$ourphp_Language,$ourphp,$Parameterse; 
	$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$ourphp_Language."' order by OP_Sorting asc,id desc");
	$rows=array();
	$i=1;
        while($ourphp_rs = $db -> whilego($query)){
			if($ourphp_rs[5] == 'weburl'){
				$weburl = $ourphp_rs[6];
			}else{
				if($Parameterse['rewrite'] == 1){
				$weburl = $ourphp['webpath'].$ourphp_rs[2].'/'.$ourphp_rs[5].'/'.$ourphp_rs[0].'/';
				}else{
				$weburl = $ourphp['webpath'].'?'.$ourphp_rs[2].'-'.$ourphp_rs[5].'-'.$ourphp_rs[0].'.html';
				}
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
				"url" => $weburl,
				"briefing" => $ourphp_rs[7],
				"img" => $minimg,
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
	global $ourphp_Language,$temptype,$listid,$viewid;
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
	);
	return $rows; 
}

function columncycle($id = 1){
	global $conn,$db,$Parameterse;
	$ourphp_rs = $db -> select("`id`,`OP_Lang`,`OP_Columntitle`,`OP_Model`","`ourphp_column`","where id = ".intval($id)); 
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
	if($temptype == 'login.html'){
		$adclass = '会员登录左侧';
	}else{
		$adclass = '首页';
	}

	$fsomd5 = md5($type.$adclass);
	
	if(!is_file(WEB_ROOT.'/function/_cache/'.'ad_'.$fsomd5.'.txt')){
	switch($type){
		case "login":
			$ourphp_rs = $db -> select("OP_Adcontent,OP_Adclass","`ourphp_ad`","where `id` = 6");
			if(strpos(', '.$ourphp_rs[1],$adclass) > 0){
				$content = $ourphp_rs[0];
			}else{
				$content = '';
			}
			ourphp_file($ourphp['webpath'].'function/_cache/ad_'.$fsomd5.'.txt',$content,1);
		break;
	}
	
	}else{
		$content = file_get_contents(WEB_ROOT.'/function/_cache/'.'ad_'.$fsomd5.'.txt');
	}
	return $content;
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

function clubnumber($id = '',$class){
	if ($id != ''){
		if($class == 'club'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_book`","where `OP_Bookclass` = ".intval($id));
		}elseif($class == 'zxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersid` = ".intval($id)." && `OP_Orderspay` = 2");
		}elseif($class == 'yxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","`OP_Ordersid` = ".intval($id)." && DATE_FORMAT(time,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') && `OP_Orderspay` = 2");
		}
		$ourphptotal = $db -> whilego($ourphptotal);
		return $ourphptotal['tiaoshu'];
	}else{
		return "-1";
	}
}

$smarty->assign('mobile',isMobile());
$smarty->assign('ourphp_web',ourphp_web());
$smarty->assign('column',indexcolumn());
$smarty->assign('ip',getIP());
$smarty->assign('ad',array('login'=>ourphp_adoverall('login',$temptype)));
$smarty->registerFilter('pre','smartyerror');
$smarty->assign('shoppingcart',shoppingiconum('car'));
$smarty->assign('shoppingorder',shoppingiconum('shopping'));
$smarty->assign('msgemail',shoppingiconum('msgemail'));
$smarty->assign('shoptuan',shoppingiconum('tuan'));

include 'ourphp_user.class.php';
include 'ourphp_page.class.php';
include 'ourphp_template.class.php';
?>