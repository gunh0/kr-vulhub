<?php
include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';

function newqrcode($text = '', $errorCorrectionLevel = 'L', $matrixPointSize = 6){
	
	include './phpqrcode.php';
	
	$QR = QRcode::png($text, false, $errorCorrectionLevel, $matrixPointSize, 2);
	
}

if(!isset($_GET['text']) || !isset($_GET['md'])){
	
	echo '-1';
	exit;
	
}else{
	
	$text = $_GET['text'];
	$l = $_GET['l'];
	$s = $_GET['s'];
	$md2 = $_GET['md'];
	$md = MD5($text.$ourphp['safecode']);
	if($md != $md2)
	{
		echo "-2";
		exit;
		
	}else{
		
		newqrcode($text, $l , $s);
		
	}
	
}



?>