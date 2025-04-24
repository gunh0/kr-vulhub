<?php
include '../config/ourphp_code.php';
include '../config/ourphp_config.php';
include 'ourphp_function.class.php';

function clubnumber($id = '',$class){
	global $db;
	if ($id != ''){
		if($class == 'club'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_book`","where `OP_Bookclass` = ".intval($id));
		}elseif($class == 'zxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersid` = ".intval($id)." && `OP_Orderspay` = 2");
		}elseif($class == 'yxl'){
			$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_orders`","where `OP_Ordersid` = ".intval($id)." && DATE_FORMAT(time,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') && `OP_Orderspay` = 2");
		}
		$ourphptotal = $db -> whilego($ourphptotal);
		return $ourphptotal['tiaoshu'];
	}else{
		return "-1";
	}
}

$page = intval($_POST['page']);
$model = dowith_sql($_POST['model']);
$id = intval($_POST['id']);
$limit = intval($_POST['limit']);
$lang = dowith_sql($_POST['lang']);
$typeclass = isset($_POST['typeclass']) ? $_POST['typeclass'] : "";
$fontsize = isset($_POST['fontsize']) ? $_POST['fontsize'] : 60;
	
if(empty($page) || empty($model) || !isset($id) || !isset($limit) || !isset($lang)){
	
	echo '参数出错';
	exit;
	
}else{
	
	if($limit == 0){
		
		$rs = $db -> select("OP_Webpage","ourphp_webdeploy","where id = 1");
		$rs = explode(",",$rs[0]);
		switch($model){
			case "article":
				$listpage = $rs[0];
			break;
			case "product":
				$listpage = $rs[1];
			break;
			case "listshop":
				$listpage = $rs[1];
			break;
			case "photo":
				$listpage = $rs[2];
			break;
			case "video":
				$listpage = $rs[3];
			break;
			case "down":
				$listpage = $rs[4];
			break;
			case "job":
				$listpage = $rs[5];
			break;
			case "clubview":
				$listpage = $rs[6];
			break;
		}
		
	}else{
		
		$listpage = $limit;
		
	}
	
	if (intval($page) == 0){
		$listpagesum = 2;
			}else{
		$listpagesum = intval($page);
	}
	$start = ($listpagesum - 1) * $listpage;
	
	if(!isset($typeclass)){
		$bytype = "OP_Sorting asc,id desc";
	}elseif($typeclass == 'OP_Webmarket'){
		$bytype = $typeclass.' asc';
	}elseif($typeclass == 'OP_Click'){
		$bytype = $typeclass.' desc';
	}else{
		$bytype = "OP_Sorting asc,id desc";
	}
	
	if($model == 'clubview'){
		
		$list = $db -> listgo("*","ourphp_book","where `OP_Bookclass` = ".intval($id)." and `OP_Booklang` = '".dowith_sql($lang)."' order by id desc LIMIT ".$start.",".$listpage);
	
	}elseif($model == 'likeyou'){
		
		$list = $db -> listgo("*","ourphp_product","where `OP_Lang` = '".dowith_sql($lang)."' order by ".$bytype." LIMIT ".$start.",".$listpage);
		
	}else{
		
		if($model == 'listshop' || $model == 'listtuan'){ $sql = 'product';}else{ $sql = $model;}
		$list = $db -> listgo("*","ourphp_".dowith_sql($sql),"where `OP_Class` = ".intval($id)." and `OP_Lang` = '".dowith_sql($lang)."' order by ".$bytype." LIMIT ".$start.",".$listpage);
		
	}
	
	if(empty($list)){
		
		$acc = 0;
		
	}else{
		
		$acc = '';
		switch($model){
			case "article":
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Minimg'],0,4) == 'http'){$minimg = $r['OP_Minimg'];}elseif($r['OP_Minimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Minimg'];}
						$acc .= '
						<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Articletitle'],0,$fontsize) .'<span class="f-r">'.date("Y,h,m",strtotime($r['time'])).'</span></a></li>
						';
					}
			break;
			case "listshop":
					$i = 1;
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Minimg'],0,4) == 'http'){$minimg = $r['OP_Minimg'];}elseif($r['OP_Minimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Minimg'];}
						if(substr($r['OP_Maximg'],0,4) == 'http'){$maximg = $r['OP_Maximg'];}elseif($r['OP_Maximg'] == ''){$maximg = $ourphp['webpath'].'skin/noimage.png';}else{$maximg=$ourphp['webpath'].$r['OP_Maximg'];}
						if($i % 2 == 0){$mr = 'class="mr0"';}else{$mr = '';}
						$acc .= '
							<li '.$mr.'><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-productview-'.$id.'-'.$r['id'].'.html"><img src="'.$minimg.'" />
								<p class="prtitle">'. ourphp_mb_substr($r['OP_Title'],0,$fontsize) .'</p>
								<p class="jg">￥'.$r['OP_Webmarket'].'<span class="f-r f-999 f-12">月销'. clubnumber($r['id'],'yxl') .'</span></p>
							</a>
							</li>
						';
						$i ++;
					}
			break;
			case "listtuan":
					$i = 1;
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Minimg'],0,4) == 'http'){$minimg = $r['OP_Minimg'];}elseif($r['OP_Minimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Minimg'];}
						if(substr($r['OP_Maximg'],0,4) == 'http'){$maximg = $r['OP_Maximg'];}elseif($r['OP_Maximg'] == ''){$maximg = $ourphp['webpath'].'skin/noimage.png';}else{$maximg=$ourphp['webpath'].$r['OP_Maximg'];}
						if($i % 2 == 0){$mr = 'class="mr0"';}else{$mr = '';}
						$acc .= '
							<li '.$mr.'><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-productview-'.$id.'-'.$r['id'].'.html"><img src="'.$minimg.'" />
								<p class="prtitle">'. ourphp_mb_substr($r['OP_Title'],0,$fontsize) .'</p>
								<p class="jg">￥'.$r['OP_Webmarket'].'<del class="f-r f-999 f-12">￥'. clubnumber($r['id'],'yxl') .'</del></p>
							</a>
							<div class="tuanico">'.$r['OP_Tuannumber'].'人已团</div>
							</li>
						';
						$i ++;
					}
			break;
			case "likeyou":
					$i = 1;
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Minimg'],0,4) == 'http'){$minimg = $r['OP_Minimg'];}elseif($r['OP_Minimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Minimg'];}
						if(substr($r['OP_Maximg'],0,4) == 'http'){$maximg = $r['OP_Maximg'];}elseif($r['OP_Maximg'] == ''){$maximg = $ourphp['webpath'].'skin/noimage.png';}else{$maximg=$ourphp['webpath'].$r['OP_Maximg'];}
						if($i % 2 == 0){$mr = 'class="mr0"';}else{$mr = '';}
						$acc .= '
							<li '.$mr.'><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-productview-'.$rs['OP_Class'].'-'.$r['id'].'.html"><img src="'.$minimg.'" />
								<p class="prtitle">'. ourphp_mb_substr($r['OP_Title'],0,$fontsize) .'...</p>
								<p class="jg"><span class="f-12">￥'.$r['OP_Webmarket'].'</span><del class="f-r f-999 f-12">￥'. $r['OP_Market'] .'</del></p>
							</a>
							</li>
						';
						$i ++;
					}
			break;
			case "product":
					$i = 1;
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Minimg'],0,4) == 'http'){$minimg = $r['OP_Minimg'];}elseif($r['OP_Minimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Minimg'];}
						if(substr($r['OP_Maximg'],0,4) == 'http'){$maximg = $r['OP_Maximg'];}elseif($r['OP_Maximg'] == ''){$maximg = $ourphp['webpath'].'skin/noimage.png';}else{$maximg=$ourphp['webpath'].$r['OP_Maximg'];}
						if($i % 2 == 0){$mr = 'class="mr0"';}else{$mr = '';}
						$acc .= '
							<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html"><img src="'.$minimg.'"></a>
							<p><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Title'],0,$fontsize) .'</a></p></li>
						';
						$i ++;
					}
			break;
			case "photo":
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Photocminimg'],0,4) == 'http'){$minimg = $r['OP_Photocminimg'];}elseif($r['OP_Photocminimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Photocminimg'];}
						$acc .= '
						<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html"><img src="'.$minimg.'"></a>
						<p><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Phototitle'],0,$fontsize) .'</a></p></li>
						';
					}
			break;
			case "video":
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Videoimg'],0,4) == 'http'){$minimg = $r['OP_Videoimg'];}elseif($r['OP_Videoimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Videoimg'];}
						$acc .= '
						<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html"><img src="'.$minimg.'"></a>
						<p><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Videotitle'],0,$fontsize) .'</a></p></li>
						';	
					}
			break;
			case "down":
					while($r = $db -> whilego($list)){
						if(substr($r['OP_Downimg'],0,4) == 'http'){$minimg = $r['OP_Downimg'];}elseif($r['OP_Downimg'] == ''){$minimg = $ourphp['webpath'].'skin/noimage.png';}else{$minimg=$ourphp['webpath'].$r['OP_Downimg'];}
						$acc .= '
						<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Downtitle'],0,$fontsize) .'<span class="f-r">'.date("Y,h,m",strtotime($r['time'])).'</span></a></li>
						';	
					}
			break;
			case "job":
					while($r = $db -> whilego($list)){
						$acc .= '
						<li><a href="'.$ourphp['webpath'].'client/wap/?'.$lang.'-'.$model.'view-'.$id.'-'.$r['id'].'.html">'. ourphp_mb_substr($r['OP_Jobtitle'],0,$fontsize) .'<span class="f-r">'.date("Y,h,m",strtotime($r['time'])).'</span></a></li>
						';	
					}
			break;
			case "clubview":
					while($r = $db -> whilego($list)){
						
						if($r['OP_Bookreply'] != ''){
							$reply = '
								<table width="100%" border="0" cellpadding="5" bgcolor="#D7E1F9">
								<tr>
								<td style="border-bottom:1px #ffffff dashed;">管理员回复：</td>
								</tr>
								<tr>
								<td valign="top">'.$r['OP_Bookreply'].'</td>
								</tr>
								</table>
							';
						}else{
							$reply = '';
						}
						$acc .= '
						    <tr>
							<td width="20%" rowspan="2" valign="top"><div align="center"><img src="'.$ourphp['webpath'].'skin/userhead_s.jpg" border="0" /></div></td>
							<td width="80%" style="min-height:60px;" valign="top">
							<p style=" height:30px; line-height:30px; border-bottom:1px #CCCCCC dashed;"><span style="float:right; font-size:12px; color:#999999;">('.$r['time'].')</span>'.$r['OP_Bookname'].'</p>
							<p style=" margin-top:20px;">'.$r['OP_Bookcontent'].'</p>
							</td>
							</tr>
							<tr>
							<td>
							
							'.$reply.'
							
							</td>
							</tr>
						';
					}
			break;
		}
		
	}
	
	echo json_encode($acc);
	
}

?>