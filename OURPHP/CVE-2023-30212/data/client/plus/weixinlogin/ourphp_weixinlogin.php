<?php

#
#插件配置开始(官方默认配置，必须！)
#

$plugname = '微信绑定数据';					//插件名称(插件的中文名称)
$plugversion = 'v1.0.0';				//插件版本
$plugversiondate = '20191204';			//插件更新日期
$plugauthor = '唐晓伟';				//插件作者
$plugabout = '获取微信用户头像名称等信息绑定系统';				//插件简介
$plugid = 'weixinlogin';					//插件ID(与你的插件文件同名，不能是中文和数字和符号)
$plugclass = '';		//插件类型  共三种类型  'function'  'block'  'modifier' , 不使用请为空
$plugmysql = 'weixinlogin';				//插件所需要的数据库名称(不能是中文和数字和符号) 多个库用@分开，$plugfield[0] 手动增加 如 $plugfield[1]
$plugadminurl = 'weixinloginlist.php';		//后台管理地址(不需要可以为默认的index.htm)
$plugfield[0] = array(
	'`email`|varchar(255)',
	'`code`|varchar(255)',
	'`pic`|varchar(255)',
	'`name`|varchar(255)',
	'`addess`|varchar(255)',
	'`userid`|int(11)',
	'`time`|datetime',
);								//插件所需要的字段，字段名称|字段类型 例如：name|varchar(255)  字段类型: varchar(255)文本类型  text备注类型  int(10)数字类型" 一行一个

#
# $plugadmin 
# 参数为 1 或 2  , 1=不启用 2=启用
# 此变量是开启向后台管理列表增加列表功能数据
# 如果开启后,还需要把 Model_admin.txt 复制到你的插件目录并改成php后缀,然后配置Model_admin.php
#
$plugadmin = 1;




#
#引入插件安装执行文件
#
include '../ourphp_plus.php';
?>