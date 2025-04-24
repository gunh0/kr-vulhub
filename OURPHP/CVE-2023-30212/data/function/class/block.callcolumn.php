<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function smarty_block_callcolumn($params, $content, &$smarty, &$repeat){
global $ourphp_access,$listid,$ourphp,$Parameterse;
$id = isset($params['id'])?$params['id']:0;
$limit = isset($params['row'])?$params['row']:1;
$lang = isset($params['lang'])?$params['lang']:'cn';
$type = isset($params['type'])?$params['type']:'td';

	extract($params);  
	if (! isset ( $params['name'] )){
		$return = 'callcolumn';
	}else{
		$return = $params['name'];
	}
    if(!isset($smarty->block_data))
	$smarty->block_data = array();	
	$dataindex = md5(__FUNCTION__ . md5(serialize($params)));  
    $dataindex = substr($dataindex,0,16);

    if (empty($smarty->block_data[$dataindex])){
		global $db;
		if ($type == 'td'){
			$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$lang."' and OP_Uid = ".intval($id)." order by OP_Sorting asc LIMIT 0,".$limit);
		}elseif ($type == 'ty'){
		
			$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$lang."' and OP_Uid = ".intval($listid)." order by OP_Sorting asc LIMIT 0,".$limit);
		
			$rows = $db -> rows($query);
			if (!$rows){
				$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$lang."' and OP_Uid = (select OP_Uid From ourphp_column where id=".$listid.") && OP_Uid != 0 order by OP_Sorting asc LIMIT 0,".$limit);
			}
		
		}elseif ($type == 'dq'){
			$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$lang."' and id in (".$id.") order by OP_Sorting asc LIMIT 0,".$limit);
		
		}else{
			$query = $db -> listgo("id,OP_Uid,OP_Lang,OP_Columntitle,OP_Columntitleto,OP_Model,OP_Url,OP_Briefing,OP_Img","`ourphp_column`","where OP_Hide = 0 and OP_Lang = '".$lang."' and OP_Model= '".$type."' order by OP_Sorting asc LIMIT 0,".$limit);
		}
		
		$rows = $db -> rows($query);
		if(!$rows){
			$rs[] = "";
		}else{
			$rs=array();
			$i = 1;
			while($ourphp_rs = $db -> whilego($query)){
				if($ourphp_rs[5] == 'weburl'){
						$weburl = $ourphp_rs[6];
						$wapurl = $ourphp_rs[6];
				}else{
						if($Parameterse['rewrite'] == 1){
							$weburl = $ourphp['webpath'].$ourphp_rs[2].'/'.$ourphp_rs[5].'/'.$ourphp_rs[0].'/';
						}else{
							$weburl = $ourphp['webpath'].'?'.$ourphp_rs[2].'-'.$ourphp_rs[5].'-'.$ourphp_rs[0].'.html';
						}
						$wapurl = $ourphp['webpath'].'client/wap/?'.$ourphp_rs[2].'-'.$ourphp_rs[5].'-'.$ourphp_rs[0].'.html';
				}
				
				if(substr($ourphp_rs[8],0,4) == 'http')
				{
					$minimg = $ourphp_rs[8];
					}elseif($ourphp_rs[8] == ''){
						$minimg = $ourphp['webpath'].'skin/noimage.png';
						}else{
						$minimg=$ourphp['webpath'].$ourphp_rs[8];
				}
			
				$rs[] = array (
					"i" => $i,
					"id" => $ourphp_rs[0],
					"uid" => $ourphp_rs[1],
					"title" => $ourphp_rs[3],
					"titleto" => $ourphp_rs[4],
					"url" => $weburl,
					"briefing" => $ourphp_rs[7],
					"img" => $minimg,
					"wapurl" => $wapurl,
				);
				$i+=1;
			}
		}
	if(!$rs){
		return str_replace($content,'',$content);
		$repeat = false;
	}
	$smarty->block_data[$dataindex]=$rs;
    }
    if(!$smarty->block_data[$dataindex]){
        $repeat = false;
        return '';  
    }  
    if (list ($key,$item)=each($smarty->block_data[$dataindex] )) {
		$smarty->assign($return, $item);
        $repeat = true;
    }
    if (!$item) {
        $repeat = false;
        reset($smarty->block_data[$dataindex]);
    }
    return $content;
}
?>