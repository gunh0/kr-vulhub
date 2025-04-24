<?php

/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2019 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function smarty_function_weixinlogin($params, &$smarty){
	global $db,$ourphp;
	extract($params);

	if(isset($_GET['introducer'])){
		$introducer = $_GET['introducer'];
	}else{
		$introducer = 0;
	}
	$from = '
	
	    <script>
        function login() { 
            var msg = "微信登录获取您的头像和名称信息 \n\n确认是否登录"; 
            if (confirm(msg)==true){ 
                return true; 
            }else{ 
                return false; 
            }
        }

	    </script>
		<a href="'.$ourphp['webpath'].'client/plus/weixinlogin/login.php?introducer='.$introducer.'&weburlupgo='.$_SESSION['ourphp_weburlupgo'].'" onclick="javascript:return login()"><span class="btn btn-white radius-7 pt-10 pb-10 pl-50 pr-50 f-16">微信账号登录</span></a>
		
	';
	return $from;
}
?>