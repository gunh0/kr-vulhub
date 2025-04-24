<?php
/*******************************************************************************
*Ourphp-CMS建站系统
*Copyright(C)2014ourphp.net
*开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
session_start();

if($_GET['ourphp_admin']=="logout"){
	
	plugsclass::logs('退出登录',$_SESSION['ourphp_adminname']);
	unset($_SESSION['ourphp_adminname']);
	unset($_SESSION['ourphp_outtime']);
	unset($_SESSION['ourphp_out']);
	echo"<script>location.href='../..".$_GET['out']."';</script>";
	exit();
		
}elseif($_GET['ourphp_admin'] == "del"){

	if(isset($_POST["radiobutton"]) == ""){

		echo '';
		
	}else{
		function dir_clear($dir){
			$directory = dir($dir);
			while($entry = $directory->read()){
				$filename = $dir.'/'.$entry;
				if(is_file($filename)) {
					@unlink($filename);
				}
			}
			$directory->close();
		}
		dir_clear("../../function/_compile/");
		
		plugsclass::logs('清理缓存',$_SESSION['ourphp_adminname']);
		$ourphp_font = 5;
		$ourphp_content = '清理成功！';
		$ourphp_img = 'ok.png';
		$ourphp_class = 'templates/ourphp_cache.html';
		require 'ourphp_remind.php';
		exit();
	}

	if (!empty($_POST["checkbox"])){
	$checkbox = implode(',',$_POST["checkbox"]);
	}else{
	$checkbox = '';
	}
	$checkbox = explode(',',$checkbox);
	
	$dir = "../../function/_cache";
	if(is_dir($dir)){
		
		$handle = opendir($dir);
		
		if(@chdir($dir)){
		while($file = readdir($handle)){
			$file_arr[]= $file;
		}
		$s_len = sizeof($file_arr);
		$c_len = count($file_arr);
		$i = 0;
		while($i<= $s_len){
			foreach ($checkbox as $op){
				if(@preg_match("/^".$op."/",$file_arr[$i])){
					unlink($file_arr[$i]);
				}
			}
		$i++;
		}
	}
	closedir($handle);
	}

	plugsclass::logs('清理缓存',$_SESSION['ourphp_adminname']);
	$ourphp_font = 5;
	$ourphp_content = '清理成功！';
	$ourphp_img = 'ok.png';
	$ourphp_class = 'templates/ourphp_cache.html';
	require 'ourphp_remind.php';
	exit();

}elseif($_GET['ourphp_admin'] == "c"){
	
	$query = $db -> listgo("`id`,`OP_Model`","`ourphp_column`","where OP_Model != 'weburl' and OP_Model != 'about' order by id asc");
	
	while($rs = $db -> whilego($query)){
		$e = $db -> select("count(id) as TOTAL","`ourphp_".$rs[1]."`","where `OP_Class` = ".$rs[0]);
		$c = $db -> update("`ourphp_column`","`OP_Total` = ".$e['TOTAL'],"where id = ".$rs[0]);
	}

	plugsclass::logs('整理栏目数据',$_SESSION['ourphp_adminname']);
	$ourphp_font = 5;
	$ourphp_content = '清理成功！';
	$ourphp_img = 'ok.png';
	$ourphp_class = 'templates/ourphp_cache.html';
	require 'ourphp_remind.php';
	exit();	
	
}

?>