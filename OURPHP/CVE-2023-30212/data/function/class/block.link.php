<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function smarty_block_link($params, $content, &$smarty, &$repeat){
global $ourphp_access,$ourphp_cache,$ourphp;
$limit = isset($params['row'])?$params['row']:1;
$type = isset($params['type'])?$params['type']:1;
$fsomd5 = md5($limit.$type);

  
	extract($params);  
	if (! isset ( $params['name'] )){
		$return = 'link';
	}else{
		$return = $params['name'];
	}
    if(!isset($smarty->block_data))
	$smarty->block_data = array();	
	$dataindex = md5(__FUNCTION__ . md5(serialize($params)));  
    $dataindex = substr($dataindex,0,16);
	

    if (empty($smarty->block_data[$dataindex])){
		if(!is_file(WEB_ROOT.'/'.$ourphp_cache.'link_'.$fsomd5.'.txt')){
		global $db;
		if ($type == 1){
			$query = $db -> listgo("`OP_Linkname`,`OP_Linkurl`,`OP_Linkimg`","`ourphp_link`","where OP_Linkstate = 1 and OP_Linkclass = 'font' order by OP_Linksorting asc LIMIT 0,".$limit);
		}elseif ($type == 2){
			$query = $db -> listgo("`OP_Linkname`,`OP_Linkurl`,`OP_Linkimg`","`ourphp_link`","where OP_Linkstate = 1 and OP_Linkclass = 'img' order by OP_Linksorting asc LIMIT 0,".$limit);
		}
		$rs = array();
		$i = 1;
		while($ourphp_rs = $db -> whilego($query)){
			if(substr($ourphp_rs[2],0,4) == 'http')
			{
				$minimg = $ourphp_rs[2];
				}elseif($ourphp_rs[2] == ''){
					$minimg = $ourphp['webpath'].'skin/noimage.png';
					}else{
					$minimg = $ourphp['webpath'].$ourphp_rs[2];
			}
			$rs[] = array (
				'i' => $i,
				'title' => $ourphp_rs[0],
				'url' => $ourphp_rs[1],
				'img' => $minimg,
			);
			$i+=1;
		}
		
		if(!$rs){
			return str_replace($content,$ourphp_access,$content);
			$repeat = false;
		}
        $smarty->block_data[$dataindex]=$rs;
		ourphp_file($ourphp_cache.'link_'.$fsomd5.'.txt',json_encode($rs),2);
		
		}else{
			$arraytojson = json_decode(file_get_contents(WEB_ROOT.'/'.$ourphp_cache.'link_'.$fsomd5.'.txt'));
			$smarty->block_data[$dataindex]=object_array($arraytojson);
		}
		
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