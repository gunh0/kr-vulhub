<?php  
header("Content-Type:text/html; varcharset=UTF-8"); 
header("Cache-Control:no-cache");
date_default_timezone_set('Asia/Shanghai');

function compress_html1($string) {  
	$string = str_replace("\r\n", '', $string); //清除换行符  
	$string = str_replace("\n", '', $string); //清除换行符  
	$string = str_replace("\t", '', $string); //清除制表符  
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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; varcharset=utf-8" />
<title>OURPHP - 安装程序</title>
<script language="JavaScript" type="text/javascript" src="../plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../plugs/layer/layer.min.js"></script>
<style type="text/css">
	*{ margin: 0 auto; padding:0px; font-size:12px; font-family:Arial, Helvetica, sans-serif;}
	body { background:#f4f4f4;}
	#ourphp_install { width:720px; height: auto; overflow:hidden; border:1px #E8E8E8 solid; padding:30px 20px 30px 20px; margin-top:80px; background:#FFFFFF; border-radius: 4px;}
	#ourphp_logo { width:720px; height:40px; margin-bottom:20px;}
	#ourphp_font { width:678px; height:350px; border:1px #CCCCCC solid; background:#FFFFFF;}
	.btnGray { width:150px; height:40px; background: #CCCCCC; border:0px; color:#333333; font-size: 18px; }
	.btn { width:150px; height:40px; background:#1382b3; border:0px; color:#FFFFFF; font-size: 18px; }
	#ourhp_er h1 { width:100%; height:35px; line-height:35px; font-size:16px; color:#999999; border-bottom:1px #CCCCCC solid; margin-bottom:10px; font-weight:400;}
	h1 { width:100%; height:35px; line-height:35px; font-size:16px; color:#999999; border-bottom:1px #CCCCCC solid; margin-bottom:10px; font-weight:400;}
	#ourhp_er p { height:25px; line-height:25px; font-size:14px; color: #666666;}
	#ourphp_table td { height:40px; line-height:40px; font-size:16px; color:#666666;}
	#ourphp_table #input { width:282px; height:18px; line-height:18px; border:1px #ccc solid;padding:6px;}
</style>
<script language="javascript">
function sqlload(){
    $.layer({
        type: 3,
        border: [5, 0.8,'#1083B2']
    });
}
function agree(){
  if (document.getElementById('btn_license').checked){
    document.getElementById('submit').disabled=false;
    document.getElementById('submit').className='btn';  
		}else{
    document.getElementById('submit').disabled='disabled';  
    document.getElementById('submit').className='btnGray';  
	}
}  
</script>
</head>

<body>
<div id="ourphp_install">
	<div id="ourphp_logo" style="padding-left: 8px;"><img src="images/logo.jpg?8" border="0" /></div>
<?php
if(version_compare(PHP_VERSION,'5.3.0','<')) die('错误！您的PHP版本不能低于 5.3.0 !');
if (file_exists("ourphp.lock")) {

	echo "<p align='center' style='padding-top:100px;'><img src='../../skin/no.png' border='0' width='180'></p>";
	echo "<p>&nbsp;</p>";
	echo "<p align='center'>请先删除 ourphp.lock 文件在安装系统！</p>";
	exit();
	
}
if (intval(isset($_GET['ourphp'])) == 0) {
?>
<form action="" method="post">
<table width='100%' border='0' cellpadding="0" cellspacing="10">
  <tr>
    <td><textarea name="request" readonly="readonly" id="ourphp_font" style="padding:10px;">OURPHP网站管理系统最终用户授权许可协议

感谢您选择OURPHP傲派建站系统（以下简称OURPHP），OURPHP提供一个企业级+电商网站的跨平台解决方案，基于 PHP + MySQL 的技术开发，源码开源。(开源不等于随意用,未获取授权禁止去除软件版权信息。强行去除后果自负，如不同意本协议请删除本软件!)

为了您正确并合法的使用本软件，请您在使用前务必阅读清楚下面的协议条款： 
一、本授权协议适用于 OURPHP 所有版本，OURPHP官方对本授权协议拥有最终解释权。

二、协议许可的权利
1.您可以在完全遵守本最终用户授权协议的基础上(即必须保留页面版权的情况下)，将本软件应用于商业用途，而不必支付软件版权授权费用。
2.您可以在协议规定的约束和限制范围内修改 OURPHP 源代码或界面风格以适应您的网站要求。
3.您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
4.获得商业授权之后，您可以去除OURPHP的版权信息，同时依据所购买的授权类型中确定的技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。

三、协议规定的约束和限制
1.未获商业授权之前，不得删除网站底部或网站标题及相应的官方版权信息和链接。OURPHP著作权已在中华人民共和国国家版权局注册(中国国家版权局著作权登记号 2015SR078193)，著作权受到法律和国际公约保护 。购买商业授权请登陆www.ourphp.net了解最新说明。
2.未经官方许可，不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。
3.不管您的网站是否整体使用 OURPHP ，还是部份栏目使用 OURPHP，在您网站页面页脚处的 Powered by OURPHP 名称和 www.ourphp.net 的链接都必须保留且不能修改。
4.未经官方许可，禁止在 OURPHP 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。
5.如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。

四、有限担保和免责声明
1.本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
2.用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。
3.电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装OURPHP，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
4.如果本软件带有其它软件的整合API示范例子包，这些文件版权不属于本软件官方，并且这些文件是没经过授权发布的，请参考相关软件的使用许可合法的使用。

版权所有 &copy; <?php echo date("Y");?> www.ourphp.net 保留所有权利。

协议发布时间：2016年1月29日

</textarea></td>
  </tr>
  <tr>
    <td style="font-size:16px; color:#ff0000; font-weight:bold;"><input name="confirm" type="checkbox" onclick="agree();" align="absMiddle" id="btn_license"/>&nbsp;<label for="btn_license">我已认真阅读并接受以上协议。</label></td>
  </tr>
  <tr>
    <td><input type="button" class="btnGray" name="submit" value="开始安装" disabled="disabled" id="submit" onclick="location.href='index.php?ourphp=1'"/></td>
  </tr>
</table>
</form>
<?php

}elseif (intval($_GET['ourphp']) == 1) {

	function check_writeable($file){
		if (file_exists($file)){
			if (is_dir($file)){
				$dir = $file;
				if ($fp = @fopen("$dir/test.txt", 'w')){
					@fclose($fp);
					@unlink("$dir/test.txt");
					$writeable = 1;
				}else{
					$writeable = 0;
				}
			}else{
				if ($fp = @fopen($file, 'a+')){
					@fclose($fp);
					$writeable = 1;
				}else{
					$writeable = 0;
				}
			}
		}else{
			$writeable = 2;
		}
	
		return $writeable;
	}

$sys_info['mysql_ver']		= extension_loaded('mysql') ? 'OK' : 'NO';
$sys_info['mysqli_ver']		= extension_loaded('mysqli') ? 'OK' : 'NO';
$sys_info['zlib']			= function_exists('gzclose') ? 'OK' : 'NO';
$sys_info['gd']				= extension_loaded("gd") ? 'OK' : 'NO';
$sys_info['socket']			= function_exists('fsockopen') ? 'OK' : 'NO';
$sys_info['curl_init']		= function_exists('curl_init') ? 'OK' : 'NO';

echo "<form id='form1' name='form1' method='post' action='index.php?ourphp=2'>";
echo '<div id="ourhp_er">';
echo '<h1>系统环境</h1>';
echo '<p>服务器操作系统:&nbsp;....................................................................&nbsp;'.PHP_OS.'</p>';
echo '<p>Web 服务器:&nbsp;....................................................&nbsp;'.$_SERVER['SERVER_SOFTWARE'].'</p>';
echo '<p>PHP 版本:&nbsp;....................................................................&nbsp;'.PHP_VERSION.'</p>';
echo '<p >MySQL 支持:&nbsp;....................................................................&nbsp;'.$sys_info['mysql_ver'].'</p>';
echo '<p >MySQLi 支持:&nbsp;....................................................................&nbsp;'.$sys_info['mysqli_ver'].'</p>';
echo '<p>Zlib 支持:&nbsp;....................................................................&nbsp;'.$sys_info['zlib'].'</p>';
echo '<p>GD2 支持:&nbsp;....................................................................&nbsp;'.$sys_info['gd'].'</p>';
echo '<p>Socket 支持:&nbsp;....................................................................&nbsp;'.$sys_info['socket'].'</p>';
echo '<p>curl 支持:&nbsp;....................................................................&nbsp;'.$sys_info['curl_init'].'</p>';
echo '<h1>目录权限</h1>';

	/* 检查目录 */
	$check_dirs = array (
		'../../config',
		'../../function',
		'../../function/_compile',
		'../../function/_cache',
		'../../function/uploadfile',
		'../../function/backup'
	);
	
	$i = 0;
	foreach ($check_dirs AS $dir){
		$full_dir = $dir;
		$check_writeable = check_writeable($full_dir);
		if ($check_writeable == '1'){
			
			echo "<p>".$check_dirs[$i]."&nbsp;...................................................................&nbsp;<font color='#00CC33'>可写</font></p>";
			
		}elseif ($check_writeable == '0'){
			
			echo "<p>".$check_dirs[$i]."&nbsp;...................................................................&nbsp;<font color='#ff0000'>不可写</font></p>";
			$no_write = true;
			
		}elseif ($check_writeable == '2'){
			
			echo "<p>".$check_dirs[$i]."&nbsp;...................................................................&nbsp;<b>不存在</b></p>";
			$no_write = true;
			
		}
	$i = $i + 1;
	}
	echo "<h1></h1>";
	if($sys_info['gd'] == 'NO' || $sys_info['curl_init'] == 'NO'){
		
		exit('上面的主要组件不支持，无法安装使用！');
		
	}else{
		
		if ($check_writeable == '0' || $check_writeable == '2'){
			echo '<input type="button" class="btnGray" name="submit" value="下一步" disabled="disabled" id="submit"/>';
		}else{
			echo '<input type="submit" class="btn" name="Submit" value="下一步" />';
		}
		
	}
echo '</div>';
echo '</form>';
exit;
	
}elseif (intval($_GET['ourphp']) == 2) {
	
	  if(function_exists("mysqli_connect")){
		$checked = array('','checked');
	  }else{
		$checked = array('checked','');
	  }

	echo "<form id='form1' name='form1' method='post' action='index.php?ourphp=3' class='registerform'>";
	echo "<table width='100%' border='0' align='center' cellpadding='10' id='ourphp_table'>";
	echo "<tr>";
	echo "<td colspan='2'><h1>数据库配置</h1></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>数据库连接类型：</div></td>";
	echo "<td><label style='font-size:18px;'><input name='type' type='radio' value='mysql' ".$checked[0]." />&nbsp;MySQL</label>&nbsp;&nbsp;<label style='font-size:18px;'><input name='type' type='radio' value='mysqli' ".$checked[1]." />&nbsp;MySQL<font style='color:#ff0000;font-size:18px;'>i</font>(推荐)</label></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>数据库连接地址：</div></td>";
	echo "<td><input name='ourphp_dburl' type='text' id='input' value='127.0.0.1' datatype='*' /> * <span style='font-size:12px;'>本地用localhost 或 127.0.0.1</span></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>数据库登录名：</div></td>";
	echo "<td><input name='ourphp_dbname' type='text' id='input' datatype='*' /> *</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>数据库登录密码：</div></td>";
	echo "<td><input name='ourphp_dbpass' type='password' id='input' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>数据库名称：</div></td>";
	echo "<td><input name='ourphp_mydb' type='text' id='input' datatype='*' /> *</td> ";
	echo "</tr>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td>(如果本地安装，请手动创建一个数据库)</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td><input type='submit' class='btn' name='Submit' value='下一步' onclick=\"sqlload();\" /></td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";
	
}elseif (intval($_GET['ourphp']) == 3) {

	if ($_POST["ourphp_dburl"] == "" || $_POST["ourphp_dbname"] == "" || $_POST["ourphp_mydb"] == ""){
		exit(' * 号是必填项！ <a href="index.php?ourphp=2">重新来过</a>');
	}
	
	$ourphp_mydb = $_POST["ourphp_mydb"];
	$ourphp_dburl = $_POST["ourphp_dburl"];
	$ourphp_dbname = $_POST["ourphp_dbname"];
	$ourphp_dbpass = $_POST["ourphp_dbpass"];

	if($_POST["type"] == 'mysql'){
		include '../../config/ourphp_mysql.php';
		$mysql_file = 'ourphp_mysql.php';
		$mysql_type = 'mysql';
			}else{
		include '../../config/ourphp_mysqli.php';
		$mysql_file = 'ourphp_mysqli.php';
		$mysql_type = 'mysqli';
	}
	
	$db = new OurPHP_Mysql($ourphp_dburl,$ourphp_dbname,$ourphp_dbpass,$ourphp_mydb);
	
	//导入数据
	$sql = compress_html1(file_get_contents("install.sql"));
	$sql = rtrim($sql, ";");
	$a = explode(";",$sql);
	foreach($a as $b){
		$c = $b.";";
		$db -> create($c,2);
	}
	$db -> close();
	
	function getRandomString($len, $chars=null){
		if (is_null($chars)){ $chars = "bcdefghijklmnpqrtuvwxyzBCDEFGHIJKLMNPQRTUVWXYZ12345679"; }  
		mt_srand(10000000*(double)microtime());
		for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
			$str .= $chars[mt_rand(0, $lc)];  
		}
		return $str;
	}
	
$ourphp_safecode = getRandomString(32);
$safecode6 = substr($ourphp_safecode , 6 , 6);
$randcode = rand(111111,999999);
$str_f = '$';
$str_tmp = "<?php
	/*
	 * Ourphp - CMS建站系统
	 * Copyright (C) 2014 ourphp.net
	 * 开发者：哈尔滨伟成科技有限公司
	 * -------------------------------
	 * 网站配置文件 (2016-10-22)
	 * -------------------------------
	 */

	define('OURPHPNO', true);
	define('WEB_ROOT',substr(dirname(__FILE__), 0, -7));

	include '".$mysql_file."';

	".$str_f."ourphp = array(
		// 网站路径
		'webpath' => '/',
		// 口令码
		'validation' => '".$randcode."',
		// 管理员默认目录
		'adminpath' => 'client/manage',
		// 数据库链接地址
		'mysqlurl' => '".$ourphp_dburl."',
		// 数据库登录账号
		'mysqlname' => '".$ourphp_dbname."',
		// 数据库登录密码
		'mysqlpass' => '".$ourphp_dbpass."',
		// 数据库表名
		'mysqldb' => '".$ourphp_mydb."',
		// 数据库表前缀
		'prefix' => 'ourphp_',
		// 附件上传最大值
		'filesize' => '5000000',
		// 安全校验码
		'safecode' => '".$ourphp_safecode.$safecode6."',
		// 数据库连接类型
		'mysqltype' => '".$mysql_type."',
		'diytype' => '1',
	);

	".$str_f."db = new OurPHP_Mysql(
		".$str_f."ourphp['mysqlurl'],
		".$str_f."ourphp['mysqlname'],
		".$str_f."ourphp['mysqlpass'],
		".$str_f."ourphp['mysqldb']
	);
?>";
	
$sf = "../../config/ourphp_config.php";
$fp = fopen($sf,"w"); 
fwrite($fp,$str_tmp);
fclose($fp);
echo "<script>location.href='index.php?ourphp=4&code=".$randcode."';</script>"; 
exit;
	
}elseif (intval($_GET['ourphp']) == 4) {

	echo "<form id='form1' name='form1' method='post' action='index.php?ourphp=5' class='registerform'>";
	echo "<table width='100%' border='0' align='center' cellpadding='10' id='ourphp_table'>";
	echo "<tr>";
	echo "<td colspan='2'><h1></h1></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>网站标题：</div></td>";
	echo "<td><input name='ourphp_website' type='text' id='input' datatype='*' /> *</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>网站地址：</div></td>";
	echo "<td><input name='ourphp_weburl' type='text' id='input' value='".$_SERVER['HTTP_HOST']."' datatype='*' /> *&nbsp;(不要以http(s)://开头，也不要以 / 结尾)</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>管理员登录账号：</div></td>";
	echo "<td><input name='ourphp_adminname' type='text' id='input' datatype='*' /> *</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>管理员登录密码：</div></td>";
	echo "<td><input name='ourphp_adminpass' type='text' id='input' datatype='*' /> *</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>口令码：</div></td>";
	echo "<td><span style='color:#ff0000; font-size:22px;'>".$_GET['code']."</span>&nbsp;&nbsp;&nbsp;(安装成功后，打开/config/ourphp_config.php&nbsp;修改口令码！)</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><div align='right'>导入测试数据：</div></td>";
	echo "<td><label><input type='radio' name='demodata' value='ok' checked />&nbsp;导入（推荐）</label>&nbsp;&nbsp;<label><input type='radio' name='demodata' value='no' />&nbsp;不导入</label></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan='2'><h1></h1></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td><input type='submit' class='btn' name='Submit' value='下一步' onclick=\"sqlload();\" /></td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";

}elseif (intval($_GET['ourphp']) == 5) {

	if ($_POST["ourphp_website"] == "" || $_POST["ourphp_weburl"] == "" || $_POST["ourphp_adminname"] == "" || $_POST["ourphp_adminpass"] == ""){
		exit('必填项不能为空！ <a href="index.php?ourphp=4">重新来过</a>');
	}

	$ourphpcode = "utf-8";
	include '../../config/ourphp_config.php';
	include '../../function/ourphp_function.class.php';
	
	$ourphp_website = dowith_sql($_POST["ourphp_website"]);
	$ourphp_weburl = dowith_sql($_POST["ourphp_weburl"]);
	$ourphp_adminname = dowith_sql($_POST["ourphp_adminname"]);
	$ourphp_adminpass = dowith_sql(substr(md5(md5($_POST["ourphp_adminpass"])),0,16));
	$ourphp_date = date("Y-m-d");
	$ourphp_update = plugsclass::webupdate();
	$db -> insert("`ourphp_web`","`OP_Website` = '".$ourphp_website."',`OP_Weburl` = '".$ourphp_weburl."',`OP_Webtime` = '".$ourphp_date."',`OP_Webstatistics` = '',`OP_Webourphpcode` = '',`OP_Webourphpu` = '',`OP_Webourphpp` = '',`OP_Weblogo` = 'function/uploadfile/ourphp888/logo.png',`OP_Webname` = '哈尔滨伟成科技有限公司', `OP_Webadd` = '黑龙江省哈尔滨市双城区天宏广场2层E05室', `OP_Webtel` = '400-626-0451', `OP_Webmobi` = '13199509559',`OP_Webfax` = '0451-83209995', `OP_Webemail` = '77701950@qq.com', `OP_Webzip` = '150100', `OP_Webqq` = '77701950', `OP_Weblinkman` = '岁月无声', `OP_Websitemin` = '【OURPHP】验证码：[.regcode.]，5分钟内有效，您正在进行注册，请勿泄露给他人！'","");
	$db -> insert("`ourphp_admin`","`OP_Adminname` = '".$ourphp_adminname."',`OP_Adminpass` = '".$ourphp_adminpass."',`OP_Adminpower` = '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60',`OP_Admin` = 1,`time` = '".date("Y-m-d H:i:s")."'","");
	$db -> update("`ourphp_webdeploy`","`OP_Pages` = '<style type=\"text/css\">.ourphp_page { font-size:14px; margin:20px auto 0 auto; float:left; clear:both;}.ourphp_page a { padding:8px 15px; float:left; margin-right:10px; border:1px #D1D1D1 solid; background:#f4f4f4; color:#666;}.ourphp_page .me { padding:8px 15px; float:left; margin-right:10px; border:1px #f4f4f4 solid; background:#D1D1D1; color:#666; font-weight:bold;}.ourphp_page a:hover {background:#D1D1D1; color:#666;}.ourphp_dian{ padding:8px 15px; float:left; margin-right:10px; color:#666;}.ourphp_page .disabled{ padding:8px 15px; float:left; margin-right:10px; border:1px #f4f4f4 solid; background:#D1D1D1; color:#666;}.ourphp_page .disabled2{background:#f4f4f4; color:#666;}</style>',`OP_Webupdate` = '".$ourphp_update."'","where id = 1");
	
	//导入默认数据
	if($_POST["demodata"] == 'ok')
	{
		$sqldata = compress_html1(file_get_contents("data.sql"));
		$sqldata = rtrim($sqldata, ";");
		$aa = explode(";",$sqldata);
		foreach($aa as $bb){
			$cc = $bb.";";
			$db -> create($cc,2);
		}
		
		if($ourphp_update == 0){ $db -> del("`ourphp_column`","where id = 2"); }
	}
	
	$close = $db -> close();
	$file = fopen("ourphp.lock",'w');
	fwrite($file," 只有删除这个文件，才可以重新安装系统。如果不需要重新安装系统，不要删除此文件！\r\n (系统安装时间 ".date("Y-m-d H:i:s").")");
	fclose($file);
	
	echo "<p align='center'><img src='../../skin/ok.png' border='0' width='200'></p>";
	echo "<p align='center' style='font-size:20px;'>恭喜您，网站安装成功！</p>";
	echo "<p align='center' style='padding-top:20px; font-size:16px;'>[ <a href='../../' target='_blank' style='font-size:16px;'>登录前台</a> ]&nbsp;&nbsp;&nbsp;&nbsp;[ <a href='../../admin.php' style='font-size:16px;'>进入后台</a> ]</p>";
	
	exit;
}
?>
</div>
<link rel="stylesheet" href="../plugs/Validform/style.css" type="text/css" />
<script type="text/javascript" src="../plugs/Validform/Validform_v5.3.2.js"></script>
<script type="text/javascript">
$(function(){
	$(".registerform").Validform();
})
</script>
</body>
</html>