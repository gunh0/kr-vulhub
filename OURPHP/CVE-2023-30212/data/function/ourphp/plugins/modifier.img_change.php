<?php

/**
 * www.ourphp.net OURPHP傲派 smarty 图片地址转换插件
 * 原创插件 2021-12-10
**/
function smarty_modifier_img_change($string = '', $encoding='UTF-8',$break_words = false, $middle = false)
{

	global $ourphp;
	
	if(substr($string,0,4) == 'http')
	{
		$minimg = $string;
		
		}elseif($string == ''){
			
			$minimg = $ourphp['webpath'].'skin/noimage.png';
			
		}else{
			
			$minimg = $ourphp['webpath'].$string;
			
	}
	
	return $minimg;

}
