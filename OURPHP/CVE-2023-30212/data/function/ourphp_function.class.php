<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
if(!defined('OURPHPNO')){exit('no!');}

/*
 *	防注入函数
 */
function dowith_sql($ourphpstr){
	$ourphpstr = addslashes($ourphpstr);
	$ourphpstr = str_ireplace(" and ","**",$ourphpstr);
	$ourphpstr = str_ireplace(" or ","**",$ourphpstr);
	$ourphpstr = str_ireplace("&&","**",$ourphpstr);
	$ourphpstr = str_ireplace("||","**",$ourphpstr);
	$ourphpstr = str_ireplace("<script","**",$ourphpstr);
	$ourphpstr = str_ireplace("<iframe","**",$ourphpstr);
	$ourphpstr = str_ireplace("<embed","**",$ourphpstr);
	$ourphpstr = str_ireplace("<","&lt;",$ourphpstr);
	$ourphpstr = str_ireplace(">","&gt;",$ourphpstr);
	$ourphpstr = str_ireplace("&","&amp;",$ourphpstr);
	$ourphpstr = str_ireplace('--',"**",$ourphpstr);
	$ourphpstr = str_ireplace("%","\%",$ourphpstr);
	$ourphpstr = str_ireplace("'","**",$ourphpstr);
	$ourphpstr = str_ireplace("ascii","<span>ascii</span>",$ourphpstr);
	$ourphpstr = str_ireplace("alert","<span>alert</span>",$ourphpstr);
	$ourphpstr = str_ireplace("count","<span>count</span>",$ourphpstr);
	$ourphpstr = str_ireplace("chr","<span>chr</span>",$ourphpstr);
	$ourphpstr = str_ireplace("char","<span>char</span>",$ourphpstr);
	$ourphpstr = str_ireplace("concat","<span>concat</span>",$ourphpstr);
	$ourphpstr = str_ireplace("columns","<span>columns</span>",$ourphpstr);
	$ourphpstr = str_ireplace("declare","<span>declare</span>",$ourphpstr);
	$ourphpstr = str_ireplace("delete","<span>delete</span>",$ourphpstr);
	$ourphpstr = str_ireplace("document","<span>document</span>",$ourphpstr);
	$ourphpstr = str_ireplace("database","<span>database</span>",$ourphpstr);
	$ourphpstr = str_ireplace("execute","<span>execute</span>",$ourphpstr);
	$ourphpstr = str_ireplace("extractvalue","<span>extractvalue</span>",$ourphpstr);
	$ourphpstr = str_ireplace("eval","<span>eval</span>",$ourphpstr);
	$ourphpstr = str_ireplace("from","<span>from</span>",$ourphpstr);
	$ourphpstr = str_ireplace("group_concat","<span>group_concat</span>",$ourphpstr);
	$ourphpstr = str_ireplace("information_schema","<span>information_schema</span>",$ourphpstr);
	$ourphpstr = str_ireplace("insert","<span>insert</span>",$ourphpstr);
	$ourphpstr = str_ireplace("onmouseover","<span>onmouseover</span>",$ourphpstr);
	$ourphpstr = str_ireplace("print","<span>print</span>",$ourphpstr);
	$ourphpstr = str_ireplace("password","<span>password</span>",$ourphpstr);
	$ourphpstr = str_ireplace("substr","<span>substr</span>",$ourphpstr);
	$ourphpstr = str_ireplace("sleep","<span>sleep</span>",$ourphpstr);
	$ourphpstr = str_ireplace("select","<span>select</span>",$ourphpstr);
	$ourphpstr = str_ireplace("truncate","<span>truncate</span>",$ourphpstr);
	$ourphpstr = str_ireplace("tables","<span>tables</span>",$ourphpstr);
	$ourphpstr = str_ireplace("union","<span>union</span>",$ourphpstr);
	$ourphpstr = str_ireplace("updatexml","<span>updatexml</span>",$ourphpstr);
	$ourphpstr = str_ireplace("username","<span>username</span>",$ourphpstr);
	$ourphpstr = str_ireplace("update","<span>update</span>",$ourphpstr);
	$ourphpstr = str_ireplace("where","<span>where</span>",$ourphpstr);
	return $ourphpstr;
}

function admin_sql($ourphpstr){
	$ourphpstr = str_ireplace("'","",$ourphpstr);
	$ourphpstr = str_ireplace(" and ","<span>and</span>",$ourphpstr);
	$ourphpstr = str_ireplace(" or ","<span>or</span>",$ourphpstr);
	$ourphpstr = str_ireplace("&&","<span>&&</span>",$ourphpstr);
	$ourphpstr = str_ireplace("||","<span>||</span>",$ourphpstr);
	$ourphpstr = str_ireplace("ascii","<span>ascii</span>",$ourphpstr);
	$ourphpstr = str_ireplace("columns","<span>columns</span>",$ourphpstr);
	$ourphpstr = str_ireplace("count","<span>count</span>",$ourphpstr);
	$ourphpstr = str_ireplace("concat","<span>concat</span>",$ourphpstr);
	$ourphpstr = str_ireplace("database","<span>database</span>",$ourphpstr);
	$ourphpstr = str_ireplace("delete","<span>delete</span>",$ourphpstr);
	$ourphpstr = str_ireplace("execute","<span>execute</span>",$ourphpstr);
	$ourphpstr = str_ireplace("from","<span>from</span>",$ourphpstr);
	$ourphpstr = str_ireplace("group_concat","<span>group_concat</span>",$ourphpstr);
	$ourphpstr = str_ireplace("information_schema","<span>information_schema</span>",$ourphpstr);
	$ourphpstr = str_ireplace("insert","<span>insert</span>",$ourphpstr);
	$ourphpstr = str_ireplace("print","<span>print</span>",$ourphpstr);
	$ourphpstr = str_ireplace("password","<span>password</span>",$ourphpstr);
	$ourphpstr = str_ireplace("substr","<span>substr</span>",$ourphpstr);
	$ourphpstr = str_ireplace("sleep","<span>sleep</span>",$ourphpstr);
	$ourphpstr = str_ireplace("select","<span>select</span>",$ourphpstr);
	$ourphpstr = str_ireplace("tables","<span>tables</span>",$ourphpstr);
	$ourphpstr = str_ireplace("union","<span>union</span>",$ourphpstr);
	$ourphpstr = str_ireplace("updatexml","<span>updatexml</span>",$ourphpstr);
	$ourphpstr = str_ireplace("username","<span>username</span>",$ourphpstr);
	$ourphpstr = str_ireplace("update","<span>update</span>",$ourphpstr);
	$ourphpstr = str_ireplace("where","<span>where</span>",$ourphpstr);
	$ourphpstr = str_ireplace("white-space:n<span>or</span>mal;","white-space:normal;",$ourphpstr);
	$ourphpstr = str_ireplace("data-<span>or</span>iginal","data-original",$ourphpstr);
	return $ourphpstr;
}


spl_autoload_register("ourphp_auto");

function ourphp_auto($className)
{
	$file = WEB_ROOT.'/function/'.str_replace('\\', '/', $className).'.php';
	if(file_exists($file)){
		include $file;
	}
}

/*
* 格式化时间
*/
function newtime($t){
		$now_time = date("Y-m-d H:i:s", time());  
		$now_time = strtotime($now_time);  
		$show_time = strtotime($t);  
		$dur = $now_time - $show_time;  
		if ($dur < 0) {  
			return $t;  
		} else {  
			if ($dur < 60) {  
				return $dur . '秒前';  
			} else {  
				if ($dur < 3600) {  
					return floor($dur / 60) . '分钟前';  
				} else {  
					if ($dur < 86400) {  
						return floor($dur / 3600) . '小时前';  
					} else {  
						if ($dur < 259200) { //3天内  
							return floor($dur / 86400) . '天前';  
						} else {  
							return $t;
						}  
					}  
				}  
			}  
		}  
}
/*
* 过虑URL中的ID ?ch-list-article-$id.html
* $str=substr($str,7);//去除前面
*/
function ourphp_Cut($ourphpstr){
	$n = strpos($ourphpstr,'.');
	if ($n) $ourphpstr = substr($ourphpstr,0,$n);
	return $ourphpstr;
}
/*
* 截取$code之后的所有字符并替换空
*/
function ourphp_urlcut($str,$code)
{

	$a = strstr($str, $code);
	$b = strlen($code);
	$c = substr($a, $b);
	return str_replace($c,"",$str);
}

/*
 * @param string $str 被截取的字符串 
 * @param integer $start 起始位置 
 * @param integer $length 截取长度(每个汉字为3字节) 
 */
function utf8_strcut($str, $start, $length=null){  
	preg_match_all('/./us', $str, $match);  
	$chars = is_null($length)? array_slice($match[0], $start ) : array_slice($match[0], $start, $length);  
	unset($str);
	return implode('', $chars);  
}

/*
 * @param string $str 被截取的字符串 
 * @param integer $s 起始位置 
 * @param integer $e 截取长度
 */
function ourphp_mb_substr($str = '',$s = 0, $e = ''){
	if(empty($str) || empty($e)){
		return false;
	}else{
		return mb_substr($str, $s, $e, 'utf-8');
	}
}

/*
 * 随机生成一组32位字符，可用于验证
 * 调用方式randomkeys(18)
 */  
function randomkeys($length){
	$key = "";
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
	for($i=0;$i<$length;$i++){
		$key .= $pattern{mt_rand(0,35)};
	}
	return $key.date("YmdHis");
}

/*
 * 压缩html : 清除换行符,清除制表符,去掉注释标记  
 * @param $string  
 * @return  压缩后的$string 
 */
function compress_html($string) {  
    $string = str_replace("\r\n", '', $string); //清除换行符  
    $string = str_replace("\n", '', $string); //清除换行符  
    $string = str_replace("\t", '', $string); //清除制表符  
    $string = str_replace(" ", '', $string); //清除空格
    $pattern = array (  
		"/> *([^ ]*) *</", //去掉注释标记  
		"/[\s]+/",  
		"/<!--[^!]*-->/",  
		"/\" /",  
		"/ \"/",  
		"'/\*[^*]*\*/'"  
    );  
    $replace = array (  
		">\\1<",  
		" ",  
		"",  
		"\"",  
		"\"",  
		""  
    );  
    return preg_replace($pattern, $replace, $string);  
}

/*
 * 替换中间字符为 ***   
 */
function half_replace($str){
    $len = strlen($str)/2;
    return substr_replace($str,str_repeat('*',$len),ceil(($len)/2),$len);
}

/*
 * 插件类
 * plugs静态方法 $a = plugsclass::plugs($id); 获取参数：$a[0];
 */
class plugsclass{
	
	public $db;
	
	public static function webupdate()
	{
		return "7";
	}
	
	public static function plugs($id)
	{
		global $db;
		$rs = $db -> select("`OP_Key`","`ourphp_api`","where `id` = ".intval($id));
		$api = explode('|',$rs[0]);
		switch($api[1]){
			case "1":
				return $api;
			break;
			case "2":
				return false;
			break;
		}
	}
	
	public static function webdeploy($info)
	{
		global $db;
		$rs = $db -> select("OP_Login,OP_Empower,OP_Empowerlist,OP_Empowerright","`ourphp_webdeploy`","where `id` = 1");
		switch($info){
			case "OP_Login":
				return $rs[0];
			break;
			case "OP_Empower":
				return $rs[1];
			break;
			case "OP_Empowerlist":
				return $rs[2];
			break;
			case "OP_Empowerright":
				return $rs[3];
			break;
		}
	}
	
	/*
	 * 日志
	 */
	public static function logs($content = '', $account = '', $time = '')
	{
		global $db;
		if($content != '')
		{
			if(getenv('HTTP_CLIENT_IP'))
			{ 
				$ip = getenv('HTTP_CLIENT_IP');
			}elseif(getenv('HTTP_X_FORWARDED_FOR')){ 
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			} elseif(getenv('REMOTE_ADDR')){ 
				$ip = getenv('REMOTE_ADDR');
			} else { 
				$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
			}
			
			if($time == '')
			{
				$time = date("Y-m-d H:i:s");
			}else{
				$time = $time;
			}
			
			$db -> insert("ourphp_logs","
				`OP_Logscontent` = '".admin_sql(compress_html($content))."',
				`OP_Logsaccount` = '".admin_sql(compress_html($account))."',
				`OP_Logsip` = '".admin_sql(compress_html($ip))."',
				`time` = '".admin_sql($time)."'
			","");
			
			return true;
		}else{
			return false;
		}
	}
	
}


/*
 * 判断手机或电脑   
 */
function isMobile($type = ''){
	global $ourphp,$db;
	$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
	$useragent_commentsblock = preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';  	  
	function CheckSubstrs($substrs,$text){  
		foreach($substrs as $substr)
			if(false !== strpos($text,$substr)){  
				return true;
			}
			return false;
	}
	$mobile_os_list = array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
	$mobile_token_list = array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
	$found_mobile = CheckSubstrs($mobile_os_list,$useragent_commentsblock) || CheckSubstrs($mobile_token_list,$useragent);
	if($type == 'pc'){
		if ($found_mobile){
			return "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?".$_SERVER["QUERY_STRING"]."');</script>";
		}else{
			return false;
		}
	}elseif($type == 'wap'){
		if (!$found_mobile){
			$rs = $db -> select("OP_Weburl","ourphp_wap","where id = 1");
			if($rs[0] == 'yes'){
				return false;
			}else{
				return "<script language=javascript>location.replace('".$ourphp['webpath']."?".$_SERVER["QUERY_STRING"]."');</script>";
			}
		}else{
			return false;
		}
	}
}

/*
 * 处理缓存文件 
 */
function ourphp_file($file,$content,$class){
	
	$of = fopen(WEB_ROOT.'/'.$file,'w');
	if($of){
		$con = fwrite($of,$content);
	}
	return $con;
	fclose($of);
	
}

/*
 * 处理json传递的数组 
 */
function object_array($array){
	if(is_object($array)){
		$array = (array)$array;
	}
	if(is_array($array)){
		foreach($array as $key => $value){
			$array[$key] = object_array($value);
		}
	}
	return $array;
}

/*
 * 处理敏感字 
 */
function ourphp_sensitive($content = ''){
	global $db;
	$sensitive = $db -> select("`OP_Sensitive`","`ourphp_webdeploy`","where `id` = 1");
	$var = explode("|",$sensitive[0]);
	$vartwo = array_combine($var,array_fill(0,count($var),'*'));
	return strtr($content, $vartwo);
}

function op($info = ''){
	global $ourphp_O0O0o00o0;
	if($info){
		$api = plugsclass::webdeploy($info);
		$md = authcode($api, $operation = 'DECODE', $key = 'tangwei', $expiry = 0);
	}
	if (!isset($ourphp_O0O0o00o0)){
		return $md;
	}else{
		if($ourphp_O0O0o00o0 == "95d4f8af44"){
			return ;
		}else{
			return $md;
		}
	}
}

/*
 * 处理电脑与移动端返回路径 
 */
function ourphp_pcwapurl($type = '',$pcurl = '',$wapurl = '',$goback = 0,$font = ''){
	global $ourphp;
	if($goback == 0){
		if($type == '' || $type == 'pc'){
			$url = $ourphp['webpath'].'client/user/'.$pcurl;
		}elseif($type == 'wap'){
			$url = $ourphp['webpath'].'client/wap/'.$wapurl;
		}
		if($font != ''){
			$alert = 'alert(\''.$font.'\');';
		}
		return "<script language=javascript>".$alert."location.replace('".$url."');</script>";
	}else{
		return "<script language=javascript>alert('".$font."');history.go(-1);</script>";
	}
}

function quota($num = 0)
{
	global $ourphp_adminfont;
	$f = explode("|",$ourphp_adminfont['quota']);
	$quota = '<span style="color: #dd514c; background-color: #fff; border: 1px solid #dd514c; padding:0 5px; font-size:14px;">'.$f[0].$num.$f[1].'</span>';
	return $quota;
}

function arrend($arr, $op)
{
	return rtrim($arr,$op);
}
 
/*
 * 404跳转
 */
function no404()
{
	global $ourphp;
	return '<meta http-equiv="refresh" content="0;url='.$ourphp['webpath'].'?cn-404.html">';
}

$homedeploy = $db -> select("`OP_Home`,`OP_Webupdate`","`ourphp_webdeploy`","where `id` = 1");
$homelang = explode('|',$homedeploy[0]);
?>