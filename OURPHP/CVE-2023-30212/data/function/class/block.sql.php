<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}

function smarty_block_sql($params, $content, &$smarty, &$repeat){
global $_page,$db,$Parameterse;
$sql = isset($params['mysql'])?$params['mysql']:"";
$font = isset($params['font'])?$params['font']:"未找到数据！";
			
	extract($params);  
	if (!isset ($params['name'])){
		$return = 'sql';
	}else{
		$return = $params['name'];
	}

    if(!isset($smarty->block_data))
	$smarty->block_data = array();	

	$dataindex = md5(__FUNCTION__ . md5(serialize($params)));  
    $dataindex = substr($dataindex,0,16);

    if (empty($smarty->block_data[$dataindex])){
		$query = $db -> create($sql,2);
		$rs = array();
		$i = 1;
		while($ourphp_rs = $db -> whilego($query)){
			$ourphp_rsrs = $ourphp_rs;
			$ourphp_rsrs['i'] = $i;
			$rs[] = $ourphp_rsrs;
			$i+=1;
		}
		if(!$rs){
			return str_replace($content,$font,$content);
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