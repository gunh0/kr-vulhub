<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="templates/images/ourphp_login.css?1" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../function/plugs/layer3.1.0/layer.js"></script>
<script charset="utf-8" src="templates/images/ourphp.js"></script>
<script>
$(function(){
	var h = window.innerHeight;
	$("#iframepage").attr("height",h);
})
</script>
</head>
<body>
<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

$open = true;

if($OP_Admin != 1)
{
	exit;
}

if(!$open)
{
	echo '未启用应用市场';
	exit;
}

function get_zip_originalsize($filename, $path, $typeclass, $pluszipname, $plusziptitle) {
	//先判断待解压的文件是否存在
	if(!file_exists($filename)){
	 die("文件 $filename 不存在！");
	}
	$starttime = explode(' ',microtime()); //解压开始的时间

	//将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	$filename = iconv("utf-8","gb2312",$filename);
	$path = iconv("utf-8","gb2312",$path);
	//打开压缩包
	//去掉zip之后的所有字符串
	$filename = explode("zip",$filename);
	$filename = $filename[0]."zip";
	$resource = zip_open($filename);
	$i = 1;
	//遍历读取压缩包里面的一个个文件
	while ($dir_resource = zip_read($resource)) {
	 //如果能打开则继续
	 if (zip_entry_open($resource,$dir_resource)) {
		 //获取当前项目的名称,即压缩包里面当前对应的文件名
		 $file_name = $path.zip_entry_name($dir_resource);
		 //以最后一个“/”分割,再用字符串截取出路径部分
		 $file_path = substr($file_name,0,strrpos($file_name, "/"));
		 //如果路径不存在，则创建一个目录，true表示可以创建多级目录
		 if(!is_dir($file_path)){
			 mkdir($file_path,0777,true);
		 }
		 //如果不是目录，则写入文件
		 if(!is_dir($file_name)){
			 //读取这个文件
			 $file_size = zip_entry_filesize($dir_resource);
			 //最大读取6M，如果文件过大，跳过解压，继续下一个
			 if($file_size<(1024*1024*6)){
				 $file_content = zip_entry_read($dir_resource,$file_size);
				 file_put_contents($file_name,$file_content);
			 }else{
				 echo "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312","utf-8",$file_name)." </p>";
			 }
		 }
		 //关闭当前
		 zip_entry_close($dir_resource);
	 }
	}
	//关闭压缩包
	zip_close($resource);
	$ourphp_msg = array();
	$endtime = explode(' ',microtime()); //解压结束的时间
	$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
	$thistime = round($thistime,3); //保留3为小数
	$ourphp_ziptime = '本次解压/安装花费：'.$thistime.' 秒。';
	if($typeclass == 'plus')
	{
		$ourphp_msg['a'] = '插件';
		$ourphp_msg['b'] = '1. 请在后台 -> 运营 -> 扩展 -> 插件管理 -> 安装新插件 -> 安装此插件';
		$ourphp_msg['c'] = '2. 插件已解压到 /client/plus/'. str_replace(".zip","",$pluszipname) .'/ 目录内 , 可二次开发';
		$ourphp_msg['e'] = '3. 插件压缩包已保存到 /client/addons/ 目录内做为备份';
		$ourphp_msg['d'] = 'ourphp_plug.php?id=ourphp';
	}
	if($typeclass == 'temp')
	{
		$ourphp_msg['a'] = '模板';
		$ourphp_msg['b'] = '1. 请在后台 -> 全局 -> 模板安装使用 -> 选择使用此模板';
		$ourphp_msg['c'] = '2. 模板已解压到 /templates/'. str_replace(".zip","",$pluszipname) .'/ 目录内 , 可二次开发';
		$ourphp_msg['e'] = '3. 模板压缩包已保存到 /client/addons/ 目录内做为备份';
		$ourphp_msg['d'] = 'ourphp_template.php?id=ourphp';
	}
	
	$ourphp_font = 5;
	$ourphp_img = 'ok.png';
	$ourphp_content = '
		<h1>【'.$plusziptitle.$ourphp_msg['a'].'】安装成功!</h1>
		<p>'.$ourphp_ziptime.'</p>
		<p>'.$ourphp_msg['b'].'</p>
		<p>'.$ourphp_msg['c'].'</p>
		<p>'.$ourphp_msg['e'].'</p>
	';
	$ourphp_time = 100;
	$ourphp_class = $ourphp_msg['d'];
	require 'ourphp_remind.php';
	
}

function plusno()
{
	$ourphp_font = 5;
	$ourphp_img = 'no.png';
	$ourphp_content = '
		<h1>ZIP模块未开启 , 请联系服务器运营商开启</h1>
	';
	$ourphp_time = 100;
	$ourphp_class = 'ourphp_app.php?id=ourphp';
	require 'ourphp_remind.php';
}

if($_GET['id'] == 'ourphp')
{
	

	if (isset($ourphp_O0O0o00o0)){
		$array = array(
			"a" => "htt",
			"b" => "://",
			"c" => "ap",
			"d" => ".ou",
			"e" => "hp.",
			"f" => "ne",
			"v" => $ourphp_versiondate,
			"p" => $ourphp_O0O0o00o0,
		);
	}else{
		$array = array(
			"a" => "htt",
			"b" => "://",
			"c" => "ap",
			"d" => ".ou",
			"e" => "hp.",
			"f" => "ne",
			"v" => $ourphp_versiondate,
			"p" => 0,
		);
	}

	$appshopurl =	$array['a']."ps".
					$array['b'].
					$array['c']."p".
					$array['d']."rp".
					$array['e'].
					$array['f']."t/?cn-product-54.html-&v=".
					$array['v']."&p=".
					$array['p'];

	//echo '<iframe id="iframepage" src="'.$appshopurl.'" width="100%" height="0" onload="iFrameHeight()" frameborder="0" scrolling="yes" ></iframe>';
?>
<script>
$(function(){
	layer.open({
	  type: 2, 
	  title:false,
	  closeBtn: 0,
	  anim: 5,
	  offset: 'auto',
	  area: ['100%', '100%'],
	  content: '<?php echo $appshopurl;?>'
	}); 
})

window.onmessage = function(e) {
	//e.data
	if(e.data.msg == "ok")
	{
		layer.confirm('是否安装此应用?', {icon: 3, title:'提示',closeBtn: 0}, function(index){
			layer.load(3);
			window.location.href = "ourphp_app.php?id=zip&plusziptype="+e.data.plusziptype+"&plusziptitle="+e.data.plusziptitle+"&pluszipid="+e.data.pluszipid+"&pluszipname="+e.data.pluszipname+"&plusmd="+e.data.plusmd;
		},function(index){
			layer.close(index);
		});
	}
}
</script>
<?php
}

if($_GET['id'] == 'zip')
{
	 $file = 'ourphp_app.php';
	 $zipfile = 'ziptest.zip';
	 if (class_exists('ZipArchive')) {
		 
		 $zip = new ZipArchive;
		 if($zip->open($zipfile, ZIPARCHIVE::CREATE) === TRUE) {
			 $zip->addFile($file);
			 $zip->close();
		 }else{
			 plusno();
			 exit;
		 }
		 
	 }else{
		 
		 plusno();
		 exit;
		 
	 }
	 unlink($zipfile);

	/**
	 * 将远程文件下载到本地服务器的项目中
	 * 备注说明：
	 * 远程地址包含中文的，必须转换成utf-8的编码格式，然后再urlencode转换成url加密的格式
	 * 例如：$url="http://127.0.0.1/".urlencode(iconv("GB2312","UTF-8","测试.docx"));
	 * @param $file_url     远程文件：$url = "http://www.baidu.com/img/baidu_jgylogo3.gif";
	 * @param $path     存放的目录：$save_dir = "dow";
	 * @param string $save_file_name 文件名：$filename = "test.gif";
	 * @return string
	 */
	 
	$array = array(
		"a" => "htt",
		"b" => "://",
		"c" => "ww",
		"d" => ".ou",
		"e" => "hp.",
		"f" => "ne",
	);

	$plusziptitle = admin_sql($_GET['plusziptitle']);
	$plusziptype = admin_sql($_GET['plusziptype']);
	$pluszipid = intval($_GET['pluszipid']);
	$pluszipname = admin_sql($_GET['pluszipname'].".zip");
	$plusmd = admin_sql($_GET['plusmd']);
	$newdownfile = $plusziptype."_".$pluszipid."_".$pluszipname;
	$plusurl =	$array['a']."ps".
				$array['b'].
				$array['c']."w".
				$array['d']."rp".
				$array['e'].
				$array['f']."t/o"."pc"."ms/ap"."pk"."ey.php?file=".
				$newdownfile."&md=".$plusmd;

	function downloadfile($file_url, $path, $save_file_name = '')
	{
			if ($path) {
				$basepath = $path;
			}
			$dir_path = $path;
			if (!is_dir($dir_path) && !mkdir($dir_path, 0777, true)) {
				return false;
			}

			$newzip = file_get_contents($file_url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $newzip);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_ENCODING,'gzip');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch,CURLOPT_TIMEOUT,60);
			$file = curl_exec($ch);
			curl_close($ch);
			
			if (!$file) {
				return false;
			}

			//传入保存文件的名称
			$filename = $save_file_name ?: pathinfo($file_url, PATHINFO_BASENAME);
			$resource = fopen($dir_path. '/'. $filename, 'a');
			fwrite($resource, $file);
			fclose($resource);
			return $basepath . '/' . $filename;
	}
	
	plugsclass::logs('应用市场在线安装插件('.$plusziptitle.')',$OP_Adminname);
	$res = downloadfile($plusurl, "../addons", $pluszipname);
	if($plusziptype == 'plus')
	{
		$zippath = '../plus/';
	}
	if($plusziptype == 'temp')
	{
		$zippath = '../../templates/';
	}
	$size = get_zip_originalsize('../addons/'.$pluszipname, $zippath, $plusziptype, $pluszipname, $plusziptitle);
	//var_dump($res);
}
?>
</body>
</html>