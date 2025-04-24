<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

$ourphp_rs = $db -> select("OP_Webfenci","`ourphp_webdeploy`","where id = 1");
if ($ourphp_rs[0] == 1){

	$word = compress_html($word);
	$url = 'http://api.pullword.com/get.php?source='.$word.'&param1=0.7&param2=2';
	$info = file_get_contents($url);
	
	//过滤替换
	$wordtag = preg_replace('/\s/',',',$info);
	$wordtag = preg_replace('/\s/',',',$info);
	$wordtag = str_replace(",,",",",$wordtag);
	$wordtag = str_replace(",,,",",",$wordtag);
	$wordtag = str_replace(",,,,",",",$wordtag);
	$wordtag = str_replace(",,,,,",",",$wordtag);
	$wordtag = str_replace(",,,,,,",",",$wordtag);
	$wordtag = substr($wordtag,0,100);

}else{

	$wordtag = $tag;

}
?>