<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
if(!defined('OURPHPNO')){exit('no!');}
$weburl = $webrs['OP_Webhttp'].$webrs['OP_Weburl'];
$str_tmp .= '<?xml version="1.0" encoding="utf-8"?>';
$str_tmp .= '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
';
$nav = $db -> listgo("*","ourphp_column","where OP_Hide = 0 && OP_Model != 'weburl'");
while($rs = $db -> whilego($nav)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs['OP_Lang'].'-'.$rs['OP_Model'].'-'.$rs['id'].'.html</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>always</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$news = $db -> listgo("*","ourphp_article","where OP_Url = '' order by id desc limit ".$num);
while($rs2 = $db -> whilego($news)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs2['OP_Lang'].'-articleview-'.$rs2['OP_Class'].'-'.$rs2['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs2['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$product = $db -> listgo("*","ourphp_product","where OP_Url = '' order by id desc limit ".$num);
while($rs3 = $db -> whilego($product)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs3['OP_Lang'].'-productview-'.$rs3['OP_Class'].'-'.$rs3['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs3['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$photo = $db -> listgo("*","ourphp_photo","where OP_Url = '' order by id desc limit ".$num);
while($rs4 = $db -> whilego($photo)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs4['OP_Lang'].'-photoview-'.$rs4['OP_Class'].'-'.$rs4['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs4['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$down = $db -> listgo("*","ourphp_down","where OP_Url = '' order by id desc limit ".$num);
while($rs5 = $db -> whilego($down)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs5['OP_Lang'].'-downview-'.$rs5['OP_Class'].'-'.$rs5['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs5['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$job = $db -> listgo("*","ourphp_job","where OP_Url = '' order by id desc limit ".$num);
while($rs6 = $db -> whilego($job)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs6['OP_Lang'].'-jobview-'.$rs6['OP_Class'].'-'.$rs6['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs6['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$video = $db -> listgo("*","ourphp_video","where OP_Url = '' order by id desc limit ".$num);
while($rs7 = $db -> whilego($video)){
$str_tmp .= '<url><loc>'.$weburl.'/?'.$rs7['OP_Lang'].'-videoview-'.$rs7['OP_Class'].'-'.$rs7['id'].'.html</loc><lastmod>'.date("Y-m-d",strtotime($rs7['time'])).'</lastmod><changefreq>daily</changefreq><priority>1.0</priority><mobile:mobile type="htmladapt"/></url>';
}
$str_tmp .= '</urlset>';
$sf = "../../function/api/sitemap/sitemap.xml";
$fp = fopen($sf,"w"); 
fwrite($fp,$str_tmp);
fclose($fp);
	
?>