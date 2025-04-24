<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function smarty_function_info($params, &$smarty){
global $db,$ourphp_access,$ourphp,$ourphp_cache;
extract($params);
$type = isset($params['type'])?$params['type']:'about';
$id = isset($params['id'])?$params['id']:0;
$html = isset($params['html'])?$params['html']:1;
$size = isset($params['size'])?$params['size']:150;
$width = isset($params['width'])?$params['width']:200;
$height = isset($params['height'])?$params['height']:200;
$auto = isset($params['auto'])?$params['auto']:2;
$url = isset($params['url'])?$params['url']:'pc';
$fsomd5 = md5($id.$html.$size.$width.$height);

	switch($type){
	
	case "about":
	if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'about_'.$fsomd5.'.txt')){
	$ourphp_rs = $db -> select("id,OP_Columntitle,OP_About,OP_About_wap","`ourphp_column`","where OP_Model = 'about' and id = ".$id); 
		if ($html == 1){
			
			if($url == 'pc')
			{
				$content = strip_tags($ourphp_rs[2]);
			}
			if($url == 'wap')
			{
				$content = strip_tags($ourphp_rs[3]);
			}
			
			$content = mb_substr($content,0,$size,'utf-8');
			
		}elseif ($html == 2){
			
			if($url == 'pc')
			{
				$content = $ourphp_rs[2];
			}
			if($url == 'wap')
			{
				$content = $ourphp_rs[3];
			}
			
		}
		ourphp_file($ourphp_cache.'about_'.$fsomd5.'.txt',$content,1);
		return $content;
		
	}else{
		
		return file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'about_'.$fsomd5.'.txt');
	}
	break;
	
	case "video":
	if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'video_'.$fsomd5.'.txt')){
	$ourphp_rs = $db-> select("OP_Videovurl,OP_Videoformat","`ourphp_video`","where id = ".$id);
	$width = (strpos($width,'%') == false)? $width.'px' : $width;
	$height = (strpos($height,'%') == false)? $height.'px' : $height;
	
	if ($ourphp_rs[1] == 'SWF'){
		
		$content = '<embed src="'.$ourphp_rs[0].'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" quality="high" />';
		
	}elseif ($ourphp_rs[1] == 'FLV'){
		
		$content = '<embed src="'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.swf" flashvars="f='.$ourphp_rs[0].'&p=2" quality="high" width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always" allowFullscreen="true" type="application/x-shockwave-flash"></embed>';
		
	}elseif ($ourphp_rs[1] == 'MP4'){
		
		$content = '
				<div id="ourphp_video"></div>
				<script type="text/javascript" src="'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.js" charset="utf-8"></script>
				<script type="text/javascript">
				var flashvars={
					f:"'.$ourphp_rs[0].'",
					c:0
				};
				var params={bgcolor:"#FFF",allowFullScreen:true,allowScriptAccess:"always",wmode:"transparent"};
				var video=["'.$ourphp_rs[0].'->video/mp4"];
				CKobject.embed("'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.swf","ourphp_video","ckplayer_a1","'.$width.'","'.$height.'",true,flashvars,video,params);
				</script>
		';
	}
		ourphp_file($ourphp_cache.'video_'.$fsomd5.'.txt',$content,1);
		return $content;
		
	}else{
		
		return file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'video_'.$fsomd5.'.txt');
	}
	break;
	
	}
}
?>