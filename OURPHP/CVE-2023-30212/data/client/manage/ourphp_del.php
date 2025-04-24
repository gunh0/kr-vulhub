<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/

function ourphp_imgdel($minimg = '',$maximg = '',$imgimg = ''){
	global $db;

	$ourphp_rs = $db -> select("`OP_Webfile`","`ourphp_webdeploy`","where id = 1");
	if($ourphp_rs[0] == 2){
		
		if($minimg != ''){
			
			if(strstr($minimg,"/../")){
				exit;
			}
			
			if(strstr($minimg,"function/uploadfile")){
				if (file_exists('../../'.$minimg)){
					unlink('../../'.$minimg);
				}
			}else{
				echo '';
			}
		}
		
		if($maximg != ''){
			
			if(strstr($maximg,"/../") > 0){
				exit;
			}
			
			if(strstr($maximg,"function/uploadfile")){
				if (file_exists('../../'.$maximg)){
					unlink('../../'.$maximg);
				}
			}else{
				echo '';
			}
		}
		
		if($imgimg != ''){
			
			if(strstr($imgimg,"/../") > 0){
				exit;
			}
			
			if(strstr($imgimg,"function/uploadfile")){
				$img = explode('|',$imgimg);
				foreach($img as $op){
					$delimg .= unlink('../../'.$op);
				}
			}else{
				echo '';
			}
		}
		
	}else{
		
		echo '';
		
	}
}
?>