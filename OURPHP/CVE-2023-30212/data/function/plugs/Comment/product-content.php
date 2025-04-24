<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2016 www.ourphp.net
* 开发者：哈尔滨伟成科技有限公司
* 任何盗版,未授权私自去掉版权的,我们将用法律维护权益.后台自负!!!
*******************************************************************************/

include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_version.php';
include '../../../config/ourphp_Language.php';
include '../../ourphp_function.class.php';

//全局定义
session_start();
date_default_timezone_set('Asia/Shanghai'); //设置时区
$ValidateCode = $_SESSION["code"];

$OP_Class = isset($_GET['id'])?$_GET['id']:"0";
$OP_Type = isset($_GET['type'])?$_GET['type']:"productview";
$OP_Row = isset($_GET['row'])?$_GET['row']:"10";
$userlogin = '游客';

if($OP_Row > 30){
	exit("<script language=javascript> alert('最多加载30条！');history.go(-1);</script>");
}

if($OP_Type == 'productview'){
	$select = "OP_Title";
	$from = "product";
}else{
	echo '参数出错!';
	exit;
}

$ourphp_rs = $db -> select("OP_Webocomment ,OP_Webpcomment ,OP_Pagestype ,OP_Pages ,OP_Pagefont","`ourphp_webdeploy`","where id = 1");
$ourphp_rs2 = $db -> select($select,"`ourphp_".$from."`","where id = ".intval($OP_Class));
if(!$ourphp_rs2){
	echo '参数出错或商品已被下架!';
	exit(0);
}

$OP_Webocomment = $ourphp_rs[0];
$OP_Webpcomment = $ourphp_rs[1];
$OP_Articletitle = $ourphp_rs2[0];
$Parameterse = array(
		'pagetype' => $ourphp_rs[2],
		'pagecss' => $ourphp_rs[3],
		'pagefont' => $ourphp_rs[4],
);

if($OP_Webpcomment == 4){
		if(isset($_SESSION['username'])){
			$username = $_SESSION['username'];
		}else{
			$username = '0';
		}
		$query = $db -> select("`id`","`ourphp_orders`","where `OP_Ordersid` = ".intval($OP_Class)." && `OP_Ordersemail` = '".$username."'");
		if($query){
			$userbuy = 1;
		}else{
			$userbuy = 2;
		}
}else{
		$userbuy = 1;
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

			//验证
			if($OP_Webpcomment == 2){
				exit("评论被关闭:(");
			}elseif($OP_Webpcomment == 3){
				if(!isset($_SESSION['username'])){
					exit("请先登录:(");
				}
			}
			
			if($userbuy == 2){
				exit("<script language=javascript> alert('只有购买过此商品才可以评论！');history.go(-1);</script>");
			}

			if (!empty($_POST["content"]) == '' || $_POST["code"] == ''){
				exit("<script language=javascript> alert('评论或验证码不能为空！');history.go(-1);</script>");
			}elseif ($_POST["code"] != $ValidateCode){
				exit("<script language=javascript> alert('验证码错误！');history.go(-1);</script>");
			}
			
			if(!isset($_SESSION['username'])){
				$OP_Name = $userlogin;
			}else{
				$OP_Name = $_SESSION['username'];
			}
			
			$OP_Ip = $_SERVER["REMOTE_ADDR"];

			if (!empty($_POST["dafen"])){
				$OP_Scoring = implode('|',$_POST["dafen"]);
			}else{
				$OP_Scoring = '0';
			}
			
			if (!empty($_POST["OP_Vote"])){
				$OP_Vote = $_POST["OP_Vote"];
			}else{
				$OP_Vote = 0;
			}
			
			
			if($OP_Webpcomment == 4){
				$query = $db -> select("`id`","`ourphp_comment`","where OP_Class = ".intval($_POST["OP_Class"])." && OP_Name = '".$OP_Name."'");
				if ($query){
					exit("<script language=javascript> alert('您已经评论过了，不可以重复评论！');history.go(-1);</script>");
				}
			}
			
			$user = $db -> select("OP_Userhead,OP_Username","ourphp_user","where OP_Useremail = '".$OP_Name."'");
			if($user)
			{
				if($user[0] == '')
				{
					$touxiang = '../../../skin/userhead_s.jpg';
				}else{
					$touxiang = $user[0];
				}
				$username = $user[1];
			}else{
				$touxiang = '../../../skin/userhead_s.jpg';
				$username = $userlogin;
			}
			
			$query = $db -> insert("`ourphp_comment`","`OP_Content` = '".dowith_sql(ourphp_sensitive($_POST["content"]))."',`OP_Class` = '".dowith_sql($_POST["OP_Class"])."',`OP_Type` = '".dowith_sql($_POST["OP_Type"])."',`OP_Name` = '".$OP_Name."',`OP_Ip` = '".$OP_Ip."',`OP_Vote` = '".intval($OP_Vote)."',`OP_Scoring` = '".dowith_sql($OP_Scoring)."',`time` = '".date("Y-m-d H:i:s")."',`OP_Usernick` = '".$username."',`	OP_Userimg` = '".$touxiang."'","");
			exit("<script language=javascript> alert('OK!:)');history.go(-1);</script>");
}

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
<!-- 声明文档使用的字符编码 -->
<meta charset="utf-8">
<!-- 优先使用 IE 最新版本和 Chrome -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<!-- 网页作者 -->
<meta name="author" content="www.ourphp.net"/>
<!-- 为移动设备添加 viewport -->
<meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
<!-- `width=device-width` 会导致 iPhone 5 添加到主屏后以 WebApp 全屏模式打开页面时出现黑边 http://bigc.at/ios-webapp-viewport-meta.orz -->
<meta name="apple-mobile-web-app-title" content="我的网站">
<!-- 添加到主屏后的标题（iOS 6 新增） -->
<meta name="apple-mobile-web-app-capable" content="yes"/>
<!-- 是否启用 WebApp 全屏模式，删除苹果默认的工具栏和菜单栏 -->
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<!-- 设置苹果工具栏颜色 -->
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- 不让百度转码 -->
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
<!-- 微软的老式浏览器 -->
<meta name="MobileOptimized" content="320">
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<!-- UC强制全屏 -->
<meta name="full-screen" content="yes">
<!-- QQ强制全屏 -->
<meta name="x5-fullscreen" content="true">
<!-- UC应用模式 -->
<meta name="browsermode" content="application">
<!-- QQ应用模式 -->
<meta name="x5-page-mode" content="app">
<!-- windows phone 点击无高光 -->
<meta name="msapplication-tap-highlight" content="no">
<title><?php echo $OP_Articletitle; ?> 的相关评论</title>
<link rel="stylesheet" type="text/css" href="../../plugs/YIQI-UI/YIQI-UI.min.css" />
</head>
<body>
<div id="YIQI-UI">
	<table class="table table-border">
	  <tr>
		<td colspan="2"><h1 class="f-16"><?php echo $OP_Articletitle; ?> [相关评价]</h1></td>
	  </tr>
	<?php
	include '../../ourphp_page.class.php';

	$listpage = intval($OP_Row);
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_comment`","where OP_Class = ".intval($OP_Class)."");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_comment`","where OP_Class = ".intval($OP_Class)." && OP_Type = '".dowith_sql($OP_Type)."' order by time desc LIMIT ".$start.",".$listpage);

	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		
	$userip = preg_replace('/((?:\d{1,3}\.){3})\d{1,3}/','$1*',$ourphp_rs['OP_Ip']);
	if($ourphp_rs['OP_Name'] == $userlogin){
		$username = $ourphp_rs['OP_Name'];
	}else{
		$username = half_replace($ourphp_rs['OP_Usernick']);
	}
	$scoring = explode('|',$ourphp_rs['OP_Scoring']);
	$touxiang = $ourphp_rs['OP_Userimg'];
	?>
	  <tr>
	   <td width="20%" rowspan="2" valign="top">
			<div align="center">
			<img src="<?php echo $touxiang;?>"  border="0" width="70" class="border radius-75" />
			<?php if($ourphp_rs['OP_Vote'] != 0){ ?>
				<?php if($ourphp_rs['OP_Vote'] == 1){ ?><img src="../../../skin/1.gif" border="0" />好评<?php }elseif($ourphp_rs['OP_Vote'] == 2){ ?><img src="../../../skin/2.gif" border="0" />中评<?php }elseif($ourphp_rs['OP_Vote'] == 3){ ?><img src="../../../skin/3.gif" border="0" />差评<?php } ?>
			<?php } ?>
			</div>
	   </td>
		<td width="80%" height="20">
			<span style="float:right;"><?php echo newtime($ourphp_rs['time']); ?></span><?php echo $username; ?> <font color="#CCCCCC" size="2">(<?php echo $userip; ?>)</font>
		</td>
	  </tr>
	  <tr>
		<td height="98" valign="top">
		 <?php if($OP_Webpcomment == 4){ ?>
			 <table width="100%" border="0" cellpadding="0">
			 <?php if($scoring[0] != 0){ ?>
			   <tr>
				 <td>
					宝贝描述相符度 <img src="lib/img/<?php echo $scoring[0] ?>.png" border="0" /><br />
					服务态度 <img src="lib/img/<?php echo $scoring[1] ?>.png" border="0" /><br />
					发货速度 <img src="lib/img/<?php echo $scoring[2] ?>.png" border="0" />
				 </td>
			   </tr>
			 <?php } ?>
			 </table> 
		<?php } ?> 
		<?php 
				echo $ourphp_rs['OP_Content']; 
				if($ourphp_rs['OP_Gocontent'] != ''){
					echo '<div style="clear:both; height:20px;"></div>';
					echo '<p>管理员回复：'.$ourphp_rs['OP_Gocontent'].'&nbsp;&nbsp;&nbsp;&nbsp;('.$ourphp_rs['OP_Gotime'].')</p>';
				}
		?>
		</td>
	  </tr>
	  <tr>
		<td colspan="2" bgcolor="#eeeeee"></td>
	  </tr>
	<?php
	$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	?>
	  <tr>
		<td colspan="2"><?php echo $_page->showpage(); ?></td>
	  </tr>
	</table>
	<form id="form1" name="form1" method="post" action="?ourphp_cms=add&id=<?php echo $OP_Class;?>&type=<?php echo $OP_Type;?>&row=<?php echo $OP_Row;?>">
	<table class="table">
	<?php if($OP_Webpcomment == 4){ ?>
	  <tr>
		<td>
		<div class="pl-10 pr-10 pt-5 pb-5">
			<input type="radio" name="OP_Vote" value="1" class="radio" /><img src="../../../skin/1.gif" border="0" />好评&nbsp;&nbsp;<input name="OP_Vote" type="radio" value="2" checked="checked" class="radio" /><img src="../../../skin/2.gif" border="0" />中评&nbsp;&nbsp;<input type="radio" name="OP_Vote" value="3" class="radio" /><img src="../../../skin/3.gif" border="0" />差评
		</div>
		</td>
	  </tr>
	  <tr>
		<td>



		<input type="hidden" name="dafen[]" value="3" id="baobei" />
		<input type="hidden" name="dafen[]" value="3" id="taidu" />
		<input type="hidden" name="dafen[]" value="3" id="fahuo" />

		<script type="text/javascript" src="lib/jquery.min.js"></script>
		<script type="text/javascript" src="lib/jquery.raty.min.js"></script>
		<style type="text/css">
		.dafen { width:100%;}
		.font { width:70px; float:left; text-align:right; height:25px; line-height:25px; padding-right:15px;}
		.target-demo { width:300px; float:left; height:25px; line-height:25px; padding:0px; margin:0px;}
		.hint { width:80px; float:left; height:25px; line-height:25px;}
		.radio {
		-webkit-appearance: radio; 
		}
		</style>

		<div class=" pl-10 pr-10 pt-5 pb-5">
			<div class="dafen">
				<div class="font">描述相符度</div>
				<div id="targetKeep-baobei" class="target-demo"></div>
				<div id="targetKeep-hintbaobei" class="hint"></div>
			  </div>
				<div style=" clear:both"></div>
			  <div class="dafen">
				<div class="font">服务态度</div>
				<div id="targetKeep-fuwu" class="target-demo"></div>
				<div id="targetKeep-hintfuwu" class="hint"></div>
			  </div>
				<div style=" clear:both"></div>
			  <div class="dafen">
				<div class="font">发货速度</div>
				<div id="targetKeep-fahuo" class="target-demo"></div>
				<div id="targetKeep-hintfahuo" class="hint"></div>
			  </div>
		</div>

		  <script type="text/javascript">
			$(function() {
			  $.fn.raty.defaults.path = 'lib/img';
			  $('#targetKeep-baobei').raty({
				cancel    : false,
				target    : '#targetKeep-hintbaobei',
				targetKeep: true,
				score: 3,
						click: function(score) {
							$("#baobei").val(score);
						  }
			  });
			  
			  $('#targetKeep-fuwu').raty({
				cancel    : false,
				target    : '#targetKeep-hintfuwu',
				targetKeep: true,
				score: 3,
						click: function(score) {
							$("#taidu").val(score);
						  }
			  });
			  
			  $('#targetKeep-fahuo').raty({
				cancel    : false,
				target    : '#targetKeep-hintfahuo',
				targetKeep: true,
				score: 3,
						click: function(score) {
							$("#fahuo").val(score);
						  }
			  });
			});
		  </script>	
		</td>
	  </tr>
	<?php } ?>
	  <tr>
		<td>
			<div class="pl-10 pr-10 pt-5 pb-5">

			<?php if($OP_Webpcomment == 2){ ?>
				<textarea name="content" style="width:100%; height:80px; text-align:center; line-height:110px; border:1px #ccc solid;" disabled="disabled" >评论被关闭:(</textarea>

					<?php }elseif($OP_Webpcomment == 3){ 
								if(isset($_SESSION['username'])){
					?>

				<textarea name="content" style="width:100%; height:80px;" placeholder="发表您的评价" ></textarea>
				<?php }else{ ?>
				<textarea name="content" style="width:100%; height:80px; text-align:center; line-height:110px; border:1px #ccc solid;" disabled="disabled" >请登录后在评论！</textarea>
				<?php } ?>
				
				<?php }elseif($OP_Webpcomment == 4){ 
				if($userbuy == 2){
				?>
				<textarea name="content" style="width:100%; height:80px; text-align:center; line-height:110px; border:1px #ccc solid;" disabled="disabled" >您未购买过该商品，无权评价:(</textarea>
				<?php
				}elseif($userbuy == 1){
				?>
				<textarea name="content" style="width:100%; height:80px; border:1px #ccc solid;" placeholder="发表您的评价" ></textarea>
				<?php
				}
				?>
		</div>
	</td>
	<?php }else{ ?>
		<div class="pl-10 pr-10 pt-5 pb-5"><textarea name="content" placeholder="发表您的评价" style="width:100%; height:80px; border:1px #ccc solid;" ></textarea></div>
	<?php } ?>
	  </tr>
	  <tr>
		<td>
			<div class="pl-10 pr-10 pt-5 pb-5">
				<input type="text" name="code" style="width:100px; height:23px; border:1px #cccccc solid; line-height:23px;" onfocus="document.getElementById('checkcode2').src+='?'" placeholder="验证码" />&nbsp;&nbsp;<img title="点击刷新" id="checkcode2" src="<?php echo $ourphp['webpath']; ?>function/ourphp_code.php" align="absbottom" onclick="this.src='<?php echo $ourphp['webpath']; ?>function/ourphp_code.php?'+Math.random();" width="80" height="25"></img>
			</div>
		</td>
	  </tr>
	  <tr>
		<td>

		<input type="hidden" name="OP_Class" value="<?php echo $OP_Class ?>" /><input type="hidden" name="OP_Type" value="<?php echo $OP_Type ?>" />
		<input type="submit" name="Submit" value="提交评论" class="btn btn-danger radius-5 pt-10 pb-10 pl-20 pr-20 mt-10" />

		</td>
	  </tr>
	</table>
	</form>
</div>
</body>
</html>