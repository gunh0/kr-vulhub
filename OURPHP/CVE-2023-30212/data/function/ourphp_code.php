<?php

	session_start();
	define('ROOT_PATH', dirname(__FILE__));
	require './ourphp_validateCode.class.php';
	
	$_vc = new ValidateCode();
	$_vc -> doimg();
	$_SESSION['code'] = $_vc -> getCode();
	
?>