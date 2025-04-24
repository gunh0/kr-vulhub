<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 内容操作类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}


/*
** 储存用户ID
*/
if(isset($_SESSION['userid']))
{
	if(!isset($_GET['introducer']))
	{
		
		header("location: ?".$ourphp_Language."-".$temptype."-".$listid."-".$viewid.".html-&introducer=".$_SESSION['userid']);
		exit;
		
	}else{
		
		$_SESSION['introducer_userid'] = intval($_GET['introducer']);
		setcookie("introducer_userid", intval($_GET['introducer']));
		
	}
}


function ourphp_tags($content = ''){
	global $ourphp;
	if($content == ''){
		
		return false;
		
	}else{
		
		$tags = array(
			'tags' => '/\[MP4\](.*?)\[\/MP4\]/s',
			'type' => 'mp4'
		);
		
		preg_match($tags['tags'],$content,$t);
		switch($tags['type']){
			case "mp4":
				@$a = explode(",",$t[1]);
				$rs = '
					<div id="ourphp_video"></div>
					<script type="text/javascript" src="'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.js" charset="utf-8"></script>
					<script type="text/javascript">
					var flashvars={
						f:"'.$a[0].'",
						c:0
					};
					var params={bgcolor:"#FFF",allowFullScreen:true,allowScriptAccess:"always",wmode:"transparent"};
					var video=["'.$a[0].'->video/mp4"];
					CKobject.embed("'.$ourphp['webpath'].'function/plugs/ckplayer/ckplayer/ckplayer.swf","ourphp_video","ckplayer_a1","'.$a[1].'","'.$a[2].'",true,flashvars,video,params);
					</script>
				';
			break;
		}
		$arr = preg_replace($tags['tags'],$rs,$content);
		
		return $arr;
	}
}

$db -> update("`ourphp_".str_replace("view","",$temptype)."`","OP_Click = OP_Click + 1","where `id` = ".$viewid);
switch($temptype){

	case "articleview":
		$ourphp_rs = $db -> select("id,OP_Articletitle,OP_Articleauthor,OP_Articlesource,`time`,OP_Articlecontent,OP_Class,OP_Tag,OP_Url,OP_Click,OP_Description,OP_Minimg,OP_Articlecontent_wap","`ourphp_article`","where `id` = ".$viewid); 
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		if(substr($ourphp_rs[11],0,4) == 'http')
		{
			$minimg = $ourphp_rs[11];
			}elseif($ourphp_rs[11] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
			}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[11];
		}

		$rows = array(
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'author' => $ourphp_rs[2],
			'source' => $ourphp_rs[3],
			'time' => $ourphp_rs[4],
			'content' => @ourphp_tags($ourphp_rs[5]),
			'content_wap' => @ourphp_tags($ourphp_rs[12]),
			'class' => $ourphp_rs[6],
			'tag' => keywords_tag($ourphp_rs[7]),
			'url' => $ourphp_rs[8],
			'click' => $ourphp_rs[9],
			'description' => $ourphp_rs[10],
			"minimg" => $minimg,
		);
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

	case "productview":
		$ourphp_rs = $db -> select("id,OP_Title,OP_Number,OP_Goodsno,OP_Brand,OP_Market,OP_Webmarket,OP_Stock,OP_Specificationsid ,OP_Specifications,OP_Pattribute,OP_Minimg,OP_Maximg,OP_Content,OP_Img,OP_Url,OP_Description,OP_Click,time,OP_Class,OP_Tag,OP_Usermoney,OP_Freight,OP_Integral,OP_Integralexchange,OP_Suggest,OP_Productimgname,OP_Tuanset,OP_Tuanusernum,OP_Tuantime,OP_Tuannumber,OP_Couponset,`OP_Buyoffnum`,`OP_Buytotalnum`,OP_Content_wap","`ourphp_product`","where `id` = ".$viewid); 
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		if(substr($ourphp_rs[11],0,4) == 'http')
		{
			$minimg = $ourphp_rs[11];
			}elseif($ourphp_rs[11] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
			}else{
				$minimg=$ourphp['webpath'].$ourphp_rs[11];
		}
		
		if(substr($ourphp_rs[12],0,4) == 'http')
		{
			$maximg = $ourphp_rs[12];
			}elseif($ourphp_rs[12] == ''){
				$maximg = $ourphp['webpath'].'skin/noimage.png';
				}else{
					$maximg = $ourphp['webpath'].$ourphp_rs[12];
		}
		
		if($ourphp_rs[22] == 1){
			$freight = '<span style="padding:1px 5px 1px 5px; background:#F90; color:#FFF; border-radius:5px;">包邮</span>';
		}else{
			$freight = '';
		}
		
		/*
			此处的日期给前台拼团使用 并减去1个月 m
		*/
		if($ourphp_rs[29] != ''){
			$ymd = explode(" ", $ourphp_rs[29]);
			$ymd2 = date('Y-m-d',strtotime("$ymd[0] - 1 month"));
			$his = explode(":", $ymd[1]);
		}else{
			$ymd2 = 0;
			$his = array(0,0,0);
		}
		
		if(isset($_GET['tuan'])){
			$tuan = $db -> select("`OP_Tuannum`,`OP_Tuanznum`","ourphp_tuan","where id = ".intval($_GET['tuanid']));
			if($tuan){
				$tuannum = $tuan[0] - $tuan[1];
			}else{
				$tuannum = '0';
			}
		}else{
			$tuannum = '0';
		}
		
		if($ourphp_rs[32] != 0){
			$prxg = quota($ourphp_rs[32]);
		}else{
			$prxg = '';
		}

		$rows = array(
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'number' => $ourphp_rs[2],
			'goodsno' => $ourphp_rs[3],
			'brand' => opcmsbrand($ourphp_rs[4]),
			'market' => $ourphp_rs[5],
			'webmarket' => $ourphp_rs[6],
			'stock' => $ourphp_rs[7],
			'specificationsid' => $ourphp_rs[8],
			'specifications' => $ourphp_rs[9],
			'attribute' => Attribute($ourphp_rs[10]),
			'minimg' => $minimg,
			'maximg' => $maximg,
			'content' => @ourphp_tags($ourphp_rs[13]),
			'content_wap' => @ourphp_tags($ourphp_rs[34]),
			'img' => imgimg($ourphp_rs[14],$ourphp_rs[26]),
			'url' => $ourphp_rs[15],
			'description' => $ourphp_rs[16],
			'click' => $ourphp_rs[17],
			'time' => $ourphp_rs[18],
			'class' => $ourphp_rs[19],
			'tag' => keywords_tag($ourphp_rs[20]),
			'usermoney' => $ourphp_rs[21],
			'freight' => $ourphp_rs[22],
			'integral' => $ourphp_rs[23],
			'integralexchange' => $ourphp_rs[24],
			"total" => $ourphp_rs[33],
			"totalday" => clubnumber($ourphp_rs[0],"yxl"),
			"freight-tag" => $freight,
			"suggest" => $ourphp_rs[25],
			"tuanset" => $ourphp_rs[27],
			"tuanusernum" => $ourphp_rs[28],
			"tuantime" => $ourphp_rs[29],
			"tuannumber" => $tuannum,
			"tuantime2" => array($ymd2,$his[0],$his[1],$his[2]),
			"coupon" => couponlist($ourphp_rs[31],$ourphp_rs[0]),
			"quota" => $prxg
		);
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

	case "photoview":
		$ourphp_rs = $db -> select("id,OP_Phototitle,time,OP_Photocminimg,OP_Photoimg,OP_Photocontent,OP_Class,OP_Tag,OP_Url,OP_Description,OP_Click,OP_Photoname,OP_Photocontent_wap","`ourphp_photo`","where `id` = ".$viewid);
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		if(substr($ourphp_rs[3],0,4) == 'http')
		{
			$minimg = $ourphp_rs[3];
			}elseif($ourphp_rs[3] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
			}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[3];
		}
		
		$rows = array(
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'time' => $ourphp_rs[2],
			'minimg' => $minimg,
			'img' => imgimg($ourphp_rs[4],$ourphp_rs[11]),
			'content' => @ourphp_tags($ourphp_rs[5]),
			'content_wap' => @ourphp_tags($ourphp_rs[12]),
			'class' => $ourphp_rs[6],
			'tag' => keywords_tag($ourphp_rs[7]),
			'url' => $ourphp_rs[8],
			'description' => $ourphp_rs[9],
			'click' => $ourphp_rs[10],
		);
		
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

	case "videoview":
		$ourphp_rs = $db -> select("id,OP_Videotitle,time,OP_Videoimg,OP_Videovurl,OP_Videoformat,OP_Videowidth,OP_Videoheight,OP_Videocontent,OP_Class,OP_Tag,OP_Url,OP_Description,OP_Click,OP_Videocontent_wap","`ourphp_video`","where `id` = ".$viewid);
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		if(substr($ourphp_rs[3],0,4) == 'http')
		{
			$minimg = $ourphp_rs[3];
			}elseif($ourphp_rs[3] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[3];
		}
		
		$rows = array(
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'time' => $ourphp_rs[2],
			'minimg' => $minimg,
			'playurl' => $ourphp_rs[4],
			'format' => $ourphp_rs[5],
			'width' => $ourphp_rs[6],
			'height' => $ourphp_rs[7],
			'content' => @ourphp_tags($ourphp_rs[8]),
			'content_wap' => @ourphp_tags($ourphp_rs[14]),
			'class' => $ourphp_rs[9],
			'tag' => keywords_tag($ourphp_rs[10]),
			'url' => $ourphp_rs[11],
			'description' => $ourphp_rs[12],
			'click' => $ourphp_rs[13],
			'player' => videoplay($ourphp_rs[4],$ourphp_rs[6],$ourphp_rs[7],$ourphp_rs[5]),
		);
		
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

	case "about":
		if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'aboutview_'.md5($listid).'.txt')){
		$ourphp_rs = $db -> select("id,OP_Columntitle,OP_Url,OP_About,OP_Userright,OP_About_wap","`ourphp_column`","where `id` = ".$listid);
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		$rows = array(
			'id' => $ourphp_rs[0],
			'title' => $ourphp_rs[1],
			'url' => $ourphp_rs[2],
			'content' => @ourphp_tags($ourphp_rs[3]),
			'content_wap' => @ourphp_tags($ourphp_rs[5]),
		);
		
		ourphp_file($ourphp_cache.'aboutview_'.md5($listid).'.txt',json_encode($rows),2);
		
		}else{
			
			$arraytojson = json_decode(file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'aboutview_'.md5($listid).'.txt'));
			$rows=object_array($arraytojson);
			
		}
	break;

	case "clubview":
		$listpage = $Parameterse['page'][6];
		if (intval(isset($_GET['page'])) == 0){
			$listpagesum = 1;
				}else{
			$listpagesum = intval($_GET['page']);
		}
		$start=($listpagesum-1)*$listpage;
		$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_book`","where `OP_Bookclass` = ".$listid);
		$ourphptotal = $db -> whilego($ourphptotal);
		
		$query = $db -> listgo("id,OP_Bookcontent,OP_Bookname,OP_Booktel,OP_Bookip,OP_Bookreply,time","`ourphp_book`","where `OP_Bookclass` = ".$listid." order by id desc LIMIT ".$start.",".$listpage); 
		$rows = array();
		$i = 1;
		while($ourphp_rs = $db -> whilego($query)){
			$ip = explode('.',$ourphp_rs[4]);
			$rows[] = array(
				'id' => $ourphp_rs[0],
				'content' => ourphp_sensitive($ourphp_rs[1]),
				'name' => ourphp_sensitive($ourphp_rs[2]),
				'tel' => ourphp_sensitive($ourphp_rs[3]),
				'ip' => $ip[0].'.'.$ip[1].'.'.$ip[2].'.**',
				'reply' => $ourphp_rs[5],
				'time' => $ourphp_rs[6],
			);
			$i+=1;
		}
		$_page = new Page($ourphptotal['tiaoshu'],$listpage);
		$smarty->assign('ourphppage',$_page->showpage());
	break;

	case "downview":
		$ourphp_rs = $db -> select("id,OP_Downtitle,time,OP_Downimg,OP_Downdurl,OP_Downcontent,OP_Downempower,OP_Downtype,OP_Downlang,OP_Downsize,OP_Downmake,OP_Lang,OP_Url,OP_Description,OP_Click,OP_Class,OP_Downsetup,OP_Random,OP_Tag,OP_Downcontent_wap","`ourphp_down`","where `id` = ".$viewid);
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		if(substr($ourphp_rs[3],0,4) == 'http'){
			$minimg = $ourphp_rs[3];
			}elseif($ourphp_rs[3] == ''){
				$minimg = $ourphp['webpath'].'skin/noimage.png';
				}else{
				$minimg = $ourphp['webpath'].$ourphp_rs[3];
		}
		
		$rows = array(
			"id" => $ourphp_rs[0],
			"title" => $ourphp_rs[1],
			"time" => $ourphp_rs[2],
			"minimg" => $minimg,
			"downurl" => $ourphp['webpath'].'function/ourphp_play.class.php?ourphp_down='.$ourphp_rs[0].'&code='.$ourphp_rs[17],
			"downurltrue" => $ourphp_rs[4],
			"content" => @ourphp_tags($ourphp_rs[5]),
			"content_wap" => @ourphp_tags($ourphp_rs[19]),
			"empower" => $ourphp_rs[6],
			"type" => $ourphp_rs[7],
			"lang" => $ourphp_rs[8],
			"size" => $ourphp_rs[9],
			"make" => $ourphp_rs[10],
			"url" => $ourphp_rs[12],
			"description" => $ourphp_rs[13],
			"click" => $ourphp_rs[14],
			"class" => $ourphp_rs[15],
			"setup" => $ourphp_rs[16],
			"tag" => keywords_tag($ourphp_rs[18]),
		);
		
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

	case "jobview":
		$ourphp_rs = $db -> select("`id`, `OP_Jobtitle`, `time`, `OP_Jobwork`, `OP_Jobadd`, `OP_Jobnature`, `OP_Jobexperience`, `OP_Jobeducation`, `OP_Jobnumber`, `OP_Jobage`, `OP_Jobwelfare`, `OP_Jobwage`, `OP_Jobcontact`, `OP_Jobtel`, `OP_Jobcontent`, `OP_Class`, `OP_Lang`, `OP_Url`, `OP_Description`, `OP_Click`,`OP_Tag`, `OP_Jobcontent_wap`","`ourphp_job`","where `id` = ".$viewid);
		
		if(!$ourphp_rs){
			echo no404();
			exit;
		}
		
		$rows = array(
			"id" => $ourphp_rs[0],
			"title" => $ourphp_rs[1],
			"time" => $ourphp_rs[2],
			"work" => $ourphp_rs[3],
			"add" => $ourphp_rs[4],
			"nature" => $ourphp_rs[5],
			"experience" => $ourphp_rs[6],
			"education" => $ourphp_rs[7],
			"number" => $ourphp_rs[8],
			"age" => $ourphp_rs[9],
			"welfare" => $ourphp_rs[10],
			"wage" => $ourphp_rs[11],
			"contact" => $ourphp_rs[12],
			"tel" => $ourphp_rs[13],
			"content" => @ourphp_tags($ourphp_rs[14]),
			"content_wap" => @ourphp_tags($ourphp_rs[21]),
			"class" => $ourphp_rs[15],
			"url" => $ourphp_rs[17],
			"description" => $ourphp_rs[18],
			"click" => $ourphp_rs[19],
			"tag" => keywords_tag($ourphp_rs[20]),
		);
		
		if ($rows['url'] != ''){header("location: ".$rows['url']);}
	break;

}

if(isset($_GET['introducer'])){
	$introducer = "&introducer=".intval($_GET['introducer']);
}else{
	$introducer = "";
}

if(isset($_GET['tuan'])){
    $_SESSION['ourphp_weburlupgo'] = "?".$ourphp_Language."-".$temptype."-".$listid."-".$viewid.".html-&tuan&tuanid=".intval($_GET['tuanid']).$introducer;
}else{
   $_SESSION['ourphp_weburlupgo'] = "?".$ourphp_Language."-".$temptype."-".$listid."-".$viewid.".html-".$introducer; 
}

$smarty->assign('opcms',$rows);
$smarty->assign('bookform',$ourphp['webpath'].'function/ourphp_play.class.php?ourphp_cms=add');
?>