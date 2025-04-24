<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/

	if ($ourphp_font == 3){ //错误
		echo "<link href='templates/images/ourphp_login.css' rel='stylesheet' type='text/css'> ";
		echo "<div class='ourphp-ok'>";
		echo "<p><img src='templates/images/no.png' border='0' /></p>";
		echo "<p style='padding-top:15px;'>" . $ourphp_adminfont['upexist'] . "</p>";
		echo "<meta http-equiv='Refresh' content='2;URL=$ourphp_class' />";
		echo "</div>";
		exit();
			}elseif ($ourphp_font == 1){ //加载
		echo "<link href='templates/images/ourphp_login.css' rel='stylesheet' type='text/css'> ";
		echo "<div class='ourphp-ok'>";
		echo "<p><img src='templates/images/ajax_loader.gif' border='0' /></p>";
		echo "<p style='padding-top:15px;'>" . $ourphp_adminfont['upok'] . "</p>";
		echo "<meta http-equiv='Refresh' content='2;URL=$ourphp_class' />";
		echo "</div>";
		exit();
			}elseif ($ourphp_font == 2){ //成功
		echo "<link href='templates/images/ourphp_login.css' rel='stylesheet' type='text/css'> ";
		echo "<div class='ourphp-ok'>";
		echo "<p><img src='templates/images/ok.png' border='0' /></p>";
		echo "<p style='padding-top:15px;'>" . $ourphp_adminfont['updel'] . "</p>";
		echo "<meta http-equiv='Refresh' content='2;URL=$ourphp_class' />";
		echo "</div>";
		exit();
			}elseif ($ourphp_font == 4){ //临时提醒
		echo "<link href='templates/images/ourphp_login.css' rel='stylesheet' type='text/css'> ";
		echo "<div class='ourphp-ok'>";
		echo "<p><img src='templates/images/no.png' border='0' /></p>";
		echo "<p style='padding-top:15px;'>" . $ourphp_content . "</p>";
		echo "<meta http-equiv='Refresh' content='2;URL=$ourphp_class' />";
		echo "</div>";
		exit();
			}elseif ($ourphp_font == 5){ //自定义提醒
			if(empty($ourphp_time))
			{
				$ourphp_time = 2;
			}
		echo "<link href='templates/images/ourphp_login.css' rel='stylesheet' type='text/css'> ";
		echo "<div class='ourphp-ok' style='width:90%;'>";
		echo "<p><img src='templates/images/" . $ourphp_img . "' border='0' /></p>";
		echo "<p style='padding-top:15px;'>" . $ourphp_content . "</p>";
		echo "<meta http-equiv='Refresh' content='$ourphp_time;URL=$ourphp_class' />";
		echo "</div>";
		exit();
	}
?>
</body>
</html>