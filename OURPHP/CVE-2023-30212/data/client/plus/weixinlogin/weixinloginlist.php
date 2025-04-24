<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2015 www.ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
include '../ourphp_plus_admin.php';
if(isset($_GET["ourphp_cms"]) == ""){
	
	echo '';
	
}elseif ($_GET["ourphp_cms"] == "del"){

	$query = $db -> del("ourphp_p_weixinlogin","where id = ".intval($_GET['id']));
	header("location: ./weixinloginlist.php");
	exit;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $plusfile['css'];?>
</head>

<body>
<div style="clear:both"></div>
<div id="tabs0">
 <ul class="menu0" id="menu0">
  <li onclick="setTab(0,0)" class="hover">微信登陆绑定列表</li>
 </ul>
 <div class="main" id="main0" style="border-top:2px #488fcd solid; clear:both;">
  <ul class="block">
	  <li>
		<div class="ourphp_newslist">
		        <form id="form2" name="form2" method="post" action="?ourphp_cms=Batch">
		        <table width="100%" border="0" cellpadding="10" class="ourphp_newslist">
                  <tr>
				  	<td width="5%" bgcolor="#EBEBEB"><div align="center">ID</div></td>
                    <td width="20%" bgcolor="#EBEBEB">绑定账号</td>
					<td width="20%" bgcolor="#EBEBEB">APPID</td>
                    <td width="20%" bgcolor="#EBEBEB">名称</td>
                    <td width="6%" bgcolor="#EBEBEB">头像</td>
                    <td width="10%" bgcolor="#EBEBEB">地址</td>
					<td width="10%" bgcolor="#EBEBEB">绑定时间</td>
                    <td width="15%" bgcolor="#EBEBEB">操作</td>
                  </tr>
				<?php
				
				$query = $db -> listgo("*","`ourphp_p_weixinlogin`","order by id desc");
				while($ourphp_rs = $db -> whilego($query)){
				?>
                  <tr>
				  	<td><div align="center"><font style="background:#009933; color:#FFFFFF; padding:2px; text-align:center;"><?php echo $ourphp_rs['id'] ?></font></div></td>
                    <td><?php echo $ourphp_rs['email'] ?></td>
					<td><?php echo $ourphp_rs['code'] ?></td>
                    <td><?php echo $ourphp_rs['name'] ?></td>
                    <td><img src="<?php echo $ourphp_rs['pic'] ?>" width="50" height="50" /></td>
					<td><?php echo $ourphp_rs['addess'] ?></td>
					<td><?php echo $ourphp_rs['time'] ?></td>
                    <td><a href="?ourphp_cms=del&id=<?php echo $ourphp_rs['id'] ?>" onclick="javascript:return confirm('确认删除吗?')">删除</a></td>
                  </tr>
				<?php
				}
				?>
                </table>
                </form>
        </div>
	  </li>
  </ul>
 </div>
</div>
</body>
</html>
