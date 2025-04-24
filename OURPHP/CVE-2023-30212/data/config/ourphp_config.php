<?php
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

	include 'ourphp_mysqli.php';

	$ourphp = array(
		// 网站路径
		'webpath' => '/',
		// 口令码
		'validation' => '974509',
		// 管理员默认目录
		'adminpath' => 'client/manage',
		// 数据库链接地址
		'mysqlurl' => '127.0.0.1',
		// 数据库登录账号
		'mysqlname' => 'root',
		// 数据库登录密码
		'mysqlpass' => 'tangwei',
		// 数据库表名
		'mysqldb' => 'ourphp',
		// 数据库表前缀
		'prefix' => 'ourphp_',
		// 附件上传最大值
		'filesize' => '5000000',
		// 安全校验码
		'safecode' => 'Ilv5hxxvHtpUQPZM45bFBveMglytYjDpxvHtpU',
		// 数据库连接类型
		'mysqltype' => 'mysqli',
		'diytype' => '1',
	);

	$db = new OurPHP_Mysql(
		$ourphp['mysqlurl'],
		$ourphp['mysqlname'],
		$ourphp['mysqlpass'],
		$ourphp['mysqldb']
	);
?>