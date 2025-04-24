<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 列表操作类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

//处理会员权限
function Userpower($listid = 1){
	global $db,$ourphp_adminfont,$Parameterse;
	$power = $ourphp_adminfont['power'];
	$ourphp_rs = $db -> select("`OP_Userright`,`OP_Weight`","`ourphp_column`","where id = ".intval($listid));
	$OP_Userright = $ourphp_rs[0];
	$OP_Weight = $ourphp_rs[1];
	
	if($Parameterse['weight'] == 1){
		
		if($OP_Userright == 0){
			$userright = '';
		}else{
			if(empty($_SESSION['username'])){
				exit("<script language=javascript> alert('".$power."');javascript:window.history.go(-1)</script>");
			}
			$usersql = $db -> select("`OP_Userclass`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
			
			if (!strstr($OP_Userright,$usersql[0])){
				exit("<script language=javascript> alert('".$power."');javascript:window.history.go(-1)</script>");
			}
									
		}
		
	}elseif($Parameterse['weight'] == 2){

		if($OP_Weight == 0){
			echo '';
		}else{
			if(empty($_SESSION['username'])){
				exit("<script language=javascript> alert('".$power."');javascript:window.history.go(-1)</script>");
			}
			$rs = $db -> select("`OP_Userclass`","`ourphp_user`","where `OP_Useremail` = '".$_SESSION['username']."'");
			$rsrs = $db -> select("`OP_Userweight`","`ourphp_userleve`","where `id` = ".$rs[0]);
			
			if($rsrs[0] < $OP_Weight){
				exit("<script language=javascript> alert('".$power."');javascript:window.history.go(-1)</script>");
			}
		}
		
	}
	return ;
}


function Listname(){
	global $ourphp,$db,$ourphp_Language,$temptype,$listid;

	if($temptype == 'club.html'){
		
		$where = "OP_Url = '".$ourphp['webpath']."?".$ourphp_Language."-".$temptype."'";
		
	}elseif($temptype == 'clubview'){
		
		$where = "OP_Url = '".$ourphp['webpath']."?".$ourphp_Language."-club.html'";
		
	}elseif($temptype == 'shop.html'){
		
		$where = "OP_Url = '".$ourphp['webpath']."?".$ourphp_Language."-".$temptype."'";
		
	}else{
		
		$where = "id = ".$listid;
		$db -> update("ourphp_column","`OP_Click` = `OP_Click` + 1","where id = ".intval($listid));
	}
	$ourphp_rs = $db -> select("OP_Columntitle,OP_Columntitleto,OP_Templist,OP_Tempview,OP_Briefing,OP_Img,OP_Userright,OP_Weight","`ourphp_column`","where ".$where);
	
	if(substr($ourphp_rs[5],0,4) == 'http')
	{
		$minimg = $ourphp_rs[5];
		}elseif($ourphp_rs[5] == ''){
			$minimg = $ourphp['webpath'].'skin/noimage.png';
			}else{
			$minimg = $ourphp['webpath'].$ourphp_rs[5];
	}
	
	$rows = array(
		'title' => $ourphp_rs[0],
		'titleto' => $ourphp_rs[1],
		'listtemp' => $ourphp_rs[2],
		'viewtemp' => $ourphp_rs[3],
		'briefing' => $ourphp_rs[4],
		'minimg' => $minimg,
		'userright' => $ourphp_rs[6],
		'weight' => $ourphp_rs[7],
	);
	return $rows;
}

function Crumbs($id = 0){
	if($id != 0){
		global $db,$ourphp,$Parameterse; 
		$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Model,OP_Url","`ourphp_column`","order by id asc");
		static $list = array();
		while($ourphp_rs = $db -> whilego($query)){
			if($ourphp_rs[4] == 'weburl'){
				$weburl = $ourphp_rs[5];
				$wapurl = $ourphp_rs[5];
			}else{
				if($Parameterse['rewrite'] == 1){
				$weburl = $ourphp['webpath'].$ourphp_rs[2].'/'.$ourphp_rs[4].'/'.$ourphp_rs[0].'/';
				}else{
				$weburl = $ourphp['webpath'].'?'.$ourphp_rs[2].'-'.$ourphp_rs[4].'-'.$ourphp_rs[0].'.html';
				}
				$wapurl = $ourphp['webpath'].'client/wap/?'.$ourphp_rs[2].'-'.$ourphp_rs[4].'-'.$ourphp_rs[0].'.html';
			}
			if($ourphp_rs[0]==$id){
				Crumbs($ourphp_rs[1]);
				$list[] = array(
					'id' => $ourphp_rs[0],
					'uid' => $ourphp_rs[1],
					'lang' => $ourphp_rs[2],
					'title' => $ourphp_rs[3],
					'url' => $weburl,
					'wapurl' => $wapurl,
				);
			}
		}
		return $list;
	}else{
		return false;
	}
}

function uptext($params, $smarty){
global $db,$listid,$viewid,$ourphp,$Parameterse; 
$form = isset($params['form'])?$params['form']:'article';
$font = isset($params['font'])?$params['font']:'上一条';
$noacc = isset($params['noacc'])?$params['noacc']:'暂无数据!';
$type = isset($params['type'])?$params['type']:'up';
$wapurl = isset($params['url'])?$params['url']:'web';

if ($form == 'article'){
	$sqltype='OP_Articletitle';
	$urltype='articleview';
}elseif ($form == 'product'){
	$sqltype='OP_Title';
	$urltype='productview';
}elseif ($form == 'photo'){
	$sqltype='OP_Phototitle';
	$urltype='photoview';
}elseif ($form == 'video'){
	$sqltype='OP_Videotitle';
	$urltype='videoview';
}elseif ($form == 'down'){
	$sqltype='OP_Downtitle';
	$urltype='downview';
}elseif ($form == 'job'){
	$sqltype='OP_Jobtitle';
	$urltype='jobview';
}

if($type == 'up'){
	$where = 'id < '.$viewid;
	$order = 'ORDER BY id DESC LIMIT 1';
}else{
	$where = 'id > '.$viewid;
	$order = 'ORDER BY id ASC LIMIT 1';
}

$ourphp_rs = $db -> select("id,".$sqltype.",OP_Class,OP_Lang,OP_Url","`ourphp_".$form."`","where ".$where." and OP_Class = ".intval($listid)." ".$order);

	if ($ourphp_rs[4] == ''){
		if($wapurl == 'web'){
			if($Parameterse['rewrite'] == 1){
				$url = $ourphp['webpath'].$ourphp_rs[3].'/'.$urltype.'/'.$ourphp_rs[2].'/'.$ourphp_rs[0].'/';
			}else{
				$url = $ourphp['webpath'].'?'.$ourphp_rs[3].'-'.$urltype.'-'.$ourphp_rs[2].'-'.$ourphp_rs[0].'.html';
			}
		}elseif($wapurl == 'wap'){
			$url = $ourphp['webpath'].'client/wap/?'.$ourphp_rs[3].'-'.$urltype.'-'.$ourphp_rs[2].'-'.$ourphp_rs[0].'.html';
		}
	}else{
		$url=$ourphp_rs[4];
	}
	
	if ($ourphp_rs == ''){
		return $font.$noacc;
			}else{
		return $font.'<a href="'.$url.'" title="'.$ourphp_rs[1].'">'.$ourphp_rs[1].'</a>';
	}
}

//处理组图
function imgimg($arr = '',$name = ''){
if ($arr != ''){
		$img = explode("|",$arr);
		$name = explode("|",$name);
		$i = 1;
		$ii = 0;
		foreach($img as $op){
			$oparr[] = array(
				'i'=>$i,
				'img'=>$op,
				'name'=>$name[$ii],
			);
			$i += 1;
			$ii += 1;
		}
		return $oparr;
	}else{
		return ;
	}
}

function booksection(){
	global $db,$ourphp,$Parameterse,$ourphp_Language;
	$query = $db -> listgo("id,OP_Booksectiontitle,OP_Booksectioncontent,OP_Booksectionlanguage,time","`ourphp_booksection`","where `OP_Booksectionlanguage` = '".$ourphp_Language."' order by OP_Booksectionsorting asc,id desc"); 
	$rows = array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		if($Parameterse['rewrite'] == 1){
			$url = $ourphp['webpath'].$ourphp_rs[3].'/clubview/'.$ourphp_rs[0].'/';
		}else{
			$url = $ourphp['webpath'].'?'.$ourphp_rs[3].'-clubview-'.$ourphp_rs[0].'.html';
		}
		$wapurl = $ourphp['webpath'].'client/wap/?'.$ourphp_rs[3].'-clubview-'.$ourphp_rs[0].'.html';
		$rows[] = array(
			'i' => $i,
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'content' => $ourphp_rs[2],
			'lang' => $ourphp_rs[3],
			'time' => $ourphp_rs[4],
			'url' => $url,
			'number' => clubnumber($ourphp_rs[0],"club"),
			'wapurl' => $wapurl,
		);
		$i += 1;
	}
	return $rows;
}

//处理关健词
function keywords_tag($keywords = ''){
	global $Parameterse;
	if($Parameterse['keywordsk'] == 1){
		if($keywords == ''){
			$rows = $Parameterse['keywords'];
		}else{
			$rows = $keywords;
		}
	}elseif($Parameterse['keywordsk'] == 2){
		$rows = $keywords;
	}elseif($Parameterse['keywordsk'] == 3){
		$rows = $keywords.','.$Parameterse['keywords'];
	}
	return $rows;
}

function videoplay($videourl = '', $videowidth = '', $videoheight = '', $videotype = ''){ 
	global $ourphp;
	$videowidth = (strpos($videowidth,'%') == false)? $videowidth.'px' : $videowidth;
	$videoheight = (strpos($videoheight,'%') == false)? $videoheight.'px' : $videoheight;
	
	if ($videotype == 'SWF'){
		
		$rows = '<embed src="'.$videourl.'" type="application/x-shockwave-flash" width="'.$videowidth.'" height="'.$videoheight.'" quality="high" />';
		
	}elseif ($videotype == 'FLV'){
		
		$rows = '<embed src="'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.swf" flashvars="f='.$videourl.'&p=2" quality="high" width="'.$videowidth.'" height="'.$videoheight.'" align="middle" allowScriptAccess="always" allowFullscreen="true" type="application/x-shockwave-flash"></embed>';
		
	}elseif ($videotype == 'MP4'){
		
		$rows = '
				<div id="ourphp_video"></div>
				<script type="text/javascript" src="'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.js" charset="utf-8"></script>
				<script type="text/javascript">
				var flashvars={
					f:"'.$videourl.'",
					c:0
				};
				var params={bgcolor:"#FFF",allowFullScreen:true,allowScriptAccess:"always",wmode:"transparent"};
				var video=["'.$videourl.'->video/mp4"];
				CKobject.embed("'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.swf","ourphp_video","ckplayer_a1","'.$videowidth.'","'.$videoheight.'",true,flashvars,video,params);
				</script>
		';
		
	}
	return $rows;
}

Userpower($listid);
$Listname = listname();
$smarty->assign('listname',$Listname);
$smarty->assign('crumbs',Crumbs($listid));
$smarty->registerPlugin("function","uptext", "uptext");
$smarty->assign('club',booksection());
?>