<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2019 www.ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 * 插件执行文件
*/

/*
	当前插件库 版本v1.1.0 校验
*/
if(!isset($plugfield[0])){
	echo '安装失败，插件版本过低！';
	exit;
}

@include '../ourphp_plus_admin.php';


function get_zip_originalsize($filename, $path) {
	//先判断待解压的文件是否存在
	if(!file_exists($filename)){
	 die("文件 $filename 不存在！");
	}
	$starttime = explode(' ',microtime()); //解压开始的时间

	//将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	$filename = iconv("utf-8","gb2312",$filename);
	$path = iconv("utf-8","gb2312",$path);
	//打开压缩包
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
	echo '本次解压/安装花费：'.$thistime.' 秒。';
	
}


if(isset($plugadmin)){
	if($plugadmin == 2){
		
	include 'Model_admin.php';
	$a = $db -> insert("ourphp_adminnav","OP_Title = '".$admin_top_font."',OP_Soft = 10,OP_Ourphp = 2","");
	$a_id = $db -> insertid();
	foreach($admin_top_url as $opcms){
		$b = explode("|",$opcms);
		$c = $db -> insert("ourphp_adminnavlist","OP_Title = '".$b[0]."',OP_Path = '".$b[1]."',OP_Soft = '".$b[2]."',OP_Uid = ".$a_id,"");
	}
	echo "<p>恭喜你，后台列表插件安装成功了!</p>";
	plugsclass::logs('安装插件('.$plugname.')',$_SESSION['ourphp_adminname']);
	
	}
}
	
if($plugmysql != ""){

	$i = 0;
	$explodemysql = explode("@", $plugmysql);
	foreach ($explodemysql as $op) {

		$field = '';
		foreach ($plugfield[$i] as $opcms){
			$field .=str_replace("|"," ",$opcms).",";
		}
		$field = substr($field ,0 ,-1);
		$sqladd = $db -> create("ourphp_p_".$op."(id int unsigned not null auto_increment primary key, ".$field.")default charset=utf8",1);
	$i ++;
	}

	if($sqladd){
		echo "<p>恭喜你，第三方插件安装成功了!</p>";
		plugsclass::logs('安装插件('.$plugname.')',$_SESSION['ourphp_adminname']);
			}else{
		echo "<p>插件数据表创建出错，错误原因：" . $db -> error()."</p>";
		plugsclass::logs('插件安装出错('.$plugname.')',$_SESSION['ourphp_adminname']);
		exit;
	}
}else{
	echo "<p>恭喜你，第三方插件安装成功了!</p>";
	plugsclass::logs('安装插件('.$plugname.')',$_SESSION['ourphp_adminname']);
}

if(isset($a_id)){
	$plugadminid = $a_id;
}else{
	$plugadminid = 0;
}

$db -> insert("`ourphp_plus`","`OP_Name` = '".$plugname."',`OP_Version` = '".$plugversion."',`OP_Versiondate` = '".$plugversiondate."',`OP_Author` = '".$plugauthor."',`OP_Fraction` = '0',`OP_About` = '".$plugabout."',`OP_Pluspath` = '".$plugid.'/'.$plugadminurl."',`OP_Time` = '".date("Y-m-d H:i:s")."',`OP_Off` = 1,`OP_Plugid` = '".$plugid."',`OP_Plugclass` = '".$plugclass."',`OP_Plugmysql` = '".$plugmysql."',`OP_Plugadminid` = ".$plugadminid,"");

if($plugclass != ""){
	$file_q = "op_".$plugid.".php";
	$file_h = WEB_ROOT."/function/data/op_".$plugid.".php";
	$file_c = WEB_ROOT."/function/data/".$plugclass.".".$plugid.".php";
	copy($file_q,$file_h);
	rename($file_h, $file_c);
}

if(isset($pluggozip)){
	if($pluggozip != '')
	{
		$codezip = explode(".",$pluggozip);
		if($codezip[1] == "zip" || $codezip[1] == "ZIP" || $codezip[1] == "Zip")
		{
			 $file = 'Author.tpl';
			 $zipfile = 'ziptest.zip';
			 if (class_exists('ZipArchive')) {
				 
				 $zip = new ZipArchive;
				 if($zip->open($zipfile, ZIPARCHIVE::CREATE) === TRUE) {
					 $zip->addFile($file);
					 $zip->close();
					 
					 get_zip_originalsize('./'.$pluggozip, "../../../");
					 
				 }else{
					 echo '<h1>ZIP模块未开启 , 请联系服务器运营商开启</h1>';
					 exit;
				 }
				 
			 }else{
				 
				 echo '<h1>ZIP模块未开启 , 请联系服务器运营商开启</h1>';
				 exit;
				 
			 }
			 unlink($zipfile);
		}
	}
}

if(!is_file(WEB_ROOT."/client/plus/".$plugid."/index.htm"))
{
	$index_file = fopen(WEB_ROOT."/client/plus/".$plugid."/index.htm",'w');
	fwrite($index_file,"ourphp！");
	fclose($index_file);	
}

echo '<meta http-equiv="Refresh" content="1;URL=../../../'.$ourphp['adminpath'].'/ourphp_plug.php?id=ourphp&ourphp_cms=ok" />';
exit(0);
?>