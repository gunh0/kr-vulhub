<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if(strstr($OP_Adminpower,"36")): else: echo "无权限操作"; exit; endif;

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){
	
		if (empty($_POST["plus"])){
			exit("<script language=javascript> alert('参数不能为空');history.go(-1);</script>");
		}
		
		plugsclass::logs('修改API接口',$OP_Adminname);
		$apicontent = implode("|",$_POST["plus"]);
		$apicontent = str_replace("||","|0|0",$apicontent);
		$apicontent = str_replace("|||","|0|0|0",$apicontent);
		$apicontent = str_replace("||||","|0|0|0|0",$apicontent);
		$apicontent = str_replace("|||||","|0|0|0|0|0",$apicontent);
		$apicontent = str_replace("||||||","|0|0|0|0|0|0",$apicontent);
		$db -> update("`ourphp_api`","`OP_Key` = '".compress_html(admin_sql($apicontent))."'","where id = ".intval($_GET['id']));
		echo "<p style='color:green'>编辑成功!</p>";
		
	}else{
		
		echo "<script language=javascript>layer.msg('您暂时无权限操作!');</script>";
		exit;
	}			
}


$apid = intval($_GET['id']);
$apiinfo = $db -> select("OP_Key","ourphp_api","where id = ".$apid);
$apiinfo = explode("|",$apiinfo[0]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API</title>
<link href="templates/images/ourphp_login.css" rel="stylesheet" type="text/css">
<link href="../../function/plugs/YIQI-UI/YIQI-UI.min.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<script src="../../function/plugs/layer3.1.0/layer.js"></script>
<script>
<!--
/*第一种形式 第二种形式 更换显示样式*/
function setTab(m,n){
 var tli=document.getElementById("menu"+m).getElementsByTagName("li");
 var mli=document.getElementById("main"+m).getElementsByTagName("ul");
 for(i=0;i<tli.length;i++){
  tli[i].className=i==n?"hover":"";
  mli[i].style.display=i==n?"block":"none";
 }
}
//-->
function handleClick(e)
{
	if(e.checked){
		$("#checkbox").val(1);
	}else{
		$("#checkbox").val(2);
	}
}
</script>
<style type="text/css">
table {width:100%; text-align:left;}
.input {background:#f3f3f3; width:78%; height:30px; line-height:30px; padding-left:2%;}
.switch-button{
	display: none;
}
.switch-button+label{
	display: inline-block;
	position: relative;
	transition: all .3s;
	width: 30px;
	height: 15px;
	border: 1px solid #999;
	border-radius: 15px;
	background-color: #ccc;
}
.switch-button:checked+label{
	background-color: lightgreen;
}
.switch-button+label::before{
	content: '';
	display: block;
	height: 12px;
	width: 12px;
	position: absolute;
	border-radius: 25px;
	left: 2px;
	top: 1px;
	background-color: #fff;
	box-shadow: 0 1px 3px rgba(0, 0, 0, .4);
	transition: all .3s;
}
.switch-button:checked+label::before{
	left: 15px;
	transition:  all .2s linear;
}
</style>
</head>
<body id="YIQI-UI">
<form id="form1" name="form1" method="POST" action="?ourphp_cms=edit&id=<?php echo $apid;?>" class="registerform">

  <table class="table table-border table-bg text-l">
    <thead>
      <tr>
        <th colspan="10"><?php echo $apiinfo[0];?>在PHP中调用 $api = plugsclass::plugs(<?php echo $apid;?>); </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="120">插件名称</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[0];?>" class="input" />
			<span class="f-r">$api[0]</span>
		</td>
      </tr>
      <tr>
        <td width="120">插件状态</td>
        <td>
			<input type="checkbox" class="switch-button" id="switch-button" onclick="handleClick(this)" <?php if($apiinfo[1] == 1){echo 'checked';}?> />
			<label for="switch-button"></label>
			<input name="plus[]" type="hidden" id="checkbox" value="<?php echo $apiinfo[1];?>" />
			<span class="f-r">(值：1=开，2=关) $api[1]</span>
		</td>
      </tr>
	  <?php
	  if($apid == 1){
	  ?>
      <tr>
        <td>AppCode(常用)</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>AppKey</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>AppSecret</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 2){
	  ?>
      <tr>
        <td>AppID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>AppSecret</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 3){
	  ?>
      <tr>
        <td>PID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>KEY</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>收款账户</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 4){
	  ?>
      <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>参数三</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>参数四</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 5){
	  ?>
      <tr>
        <td>账号</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>密码</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>URL地址</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 6){
	  ?>
      <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>发送模式</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>注册模式</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 7){
	  ?>
      <tr>
        <td>appID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>AppSecret</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 8){
	  ?>
      <tr>
        <td>合作商家ID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>收款账户</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 9){
	  ?>
      <tr>
        <td>APPID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>MCHID(商户号)</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>KEYS</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>APPSECRET</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 10){
	  ?>
      <tr>
        <td>APPID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>MCHID(商户号)</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>KEYS</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>APPSECRET</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 11){
	  ?>
      <tr>
        <td>APPID</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>APPSECRET</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <?php
	  }else if($apid == 12){
	  ?>
      <tr>
        <td>高德地图KEY</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>百度地图KEY</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>腾讯地图KEY</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <?php
	  }else{
	  ?>
      <tr>
        <td>参数一</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[2];?>" class="input" />
			<span class="f-r">$api[2]</span>
		</td>
      </tr>
	  <tr>
        <td>参数二</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[3];?>" class="input" />
			<span class="f-r">$api[3]</span>
		</td>
      </tr>
	  <tr>
        <td>参数三</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[4];?>" class="input" />
			<span class="f-r">$api[4]</span>
		</td>
      </tr>
	  <tr>
        <td>参数四</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[5];?>" class="input" />
			<span class="f-r">$api[5]</span>
		</td>
      </tr>
	  <tr>
        <td>参数五</td>
        <td>
			<input name="plus[]" type="text" value="<?php echo $apiinfo[6];?>" class="input" />
			<span class="f-r">$api[6]</span>
		</td>
      </tr>
	  <?php
	  }
	  ?>
	  <tr>
        <td colspan="2"><input type="submit" name="submit" value="提交" class="ourphp-anniu"/></td>
      </tr>
	  
    </tbody>
  </table>
		
</form>
</body>
</html>