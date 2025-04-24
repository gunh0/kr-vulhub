<?php

if(!defined('OURPHPNO')){exit('no!');}

function fileadd($str){
	global $ourphp_version,$ourphp_versiondate,$ourphp_weixin,$ourphp_apps,$ourphp_alifuwu;
	
	if($str == 1)
	{
		$a = "$"."ou"."rph"."p_O0"."O0o"."00o0=\"95d4f8af44\";\r\n";
	}else{
		$a = '';
	}
	
	$str_tmp="<?php\r\n";
	$str_end="?>";
	$str_tmp.="\r\n";
	$str_tmp.="$"."ou"."rph"."p_v"."ers"."ion=\"$ourphp_version\";\r\n";
	$str_tmp.="$"."ou"."rph"."p_v"."ersion"."date=\"$ourphp_versiondate\";\r\n";
	$str_tmp.=$a;
	$str_tmp.="$"."ou"."rph"."p_we"."ixi"."n=\"$ourphp_weixin\";\r\n";
	$str_tmp.="$"."ou"."rph"."p_ap"."ps=\"$ourphp_apps\";\r\n";
	$str_tmp.="$"."ou"."rph"."p_a"."lif"."uwu=\"$ourphp_alifuwu\";\r\n";
	$str_tmp.="\r\n";
	$str_tmp.=$str_end;
	$sf="../../co"."nfig"."/ou"."rph"."p_ver"."sion.php";
	$fp=fopen($sf,"w");
	fwrite($fp,$str_tmp);
	fclose($fp);
}

function smarty_function_cprt($params, &$smarty){
	global $db,$ourphp_O0O0o00o0;
	extract($params);
	$type = isset($params['type'])?$params['type']:'about';
	$webupdate = isset($params['webupdate'])?$params['webupdate']:'0';

	if(isset($ourphp_O0O0o00o0)){
		if($ourphp_O0O0o00o0 == "95d4f8af44"){
			$a = 'htt';
			$b = 'ps://';
			$c = 'ww';
			$d = 'p.net';
			$e = 'ur';
			$f = 'pc';
			$g = 'ow';
			$ourphp_rs = $db -> select("`OP_Webourphpcode`","`ourphp_web`","where `id` = 1");
			$code = $ourphp_rs[0];
			$weburl = $_SERVER['HTTP_HOST'];
			$url = $a.$b.$c.'w.o'.$e.'ph'.$d.'/o'.$f.'ms/7/emp'.$g.'er.php?url='.$weburl.'&code='.$code."&webupdate=".$webupdate;
			$info = file_get_contents($url);
			$info = json_decode($info,true);

			if($info['error'] == 'no'){
				echo fileadd(2);
			}
			if($info['error'] == 'ok'){
				echo fileadd(1);
			}
		}else{
			echo fileadd(2);
		}
	}else{
		echo fileadd(2);
	}
	
	echo '
			<script type="text/javascript" src="../../fun'.'ction/plu'.'gs/high'.'char'.'ts/hi'.'ghcha'.'rts.js"></script> 
			<script type="text/javascript" src="../../fun'.'ction/plu'.'gs/high'.'char'.'ts/exp'.'ort'.'ing.js"></script> 
	';

}
?>