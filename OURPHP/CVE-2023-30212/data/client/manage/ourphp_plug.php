<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include './ourphp_admin.php';
include './ourphp_checkadmin.php'; 
include './ourphp_page.class.php';

function plusok($id,$i)
{
		echo '
			<script src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
			<script src="../../function/plugs/layer/layer.min.js"></script>
			<script>$(function(){
				parent.plusdel(\''.$id.'\','.$i.');
			})</script>
		';	
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"35")){
		
		$ourphp_rs = $db -> select("`OP_Plugid`,`OP_Plugclass`,`OP_Plugmysql`,`OP_Pluspath`,`OP_Plugadminid`,`OP_Name`","`ourphp_plus`","where id = ".intval($_GET['id']));
		$file = '../../function/data/'.$ourphp_rs[1].'.'.$ourphp_rs[0].'.php';
		@$result = unlink($file);
		$explode = explode("@", $ourphp_rs[2]);
		foreach ($explode as $op) {
			$retval = $db -> drop("ourphp_p_".$op);
		}
		
		plugsclass::logs('卸载插件('.$ourphp_rs[5].')',$OP_Adminname);
		$db -> del("ourphp_plus","where id = ".intval($_GET['id']));
		
		if(strstr($ourphp_rs[3],"index.htm")){
			$db -> del("ourphp_adminnav","where id = ".$ourphp_rs[4]);
			$db -> del("ourphp_adminnavlist","where OP_Uid = ".$ourphp_rs[4]);
		}
		
		plusok(intval($_GET['id']),2);
		
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_plug.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_plug.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}

}elseif ($_GET["ourphp_cms"] == "ok"){
	
		$plist = '';
		$query = $db -> listgo("id,OP_Name,OP_Version,OP_Versiondate,OP_Author,OP_Fraction,OP_About,OP_Pluspath,OP_Time,OP_Off","`ourphp_plus`","order by id desc");
		while($ourphp_rs = $db -> whilego($query)){
			$plist .= '<ul id="plusdel'.$ourphp_rs[0].'"><li><a href="'.$ourphp['webpath'].'client/plus/'.$ourphp_rs[7].'" target="main">'.$ourphp_rs[1].'</a></li></ul>';
		}
		plusok($plist,1);
		
}

function Pluslist(){
	global $_page,$db,$smarty;
	$listpage = 25;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_plus`","order by id desc");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("id,OP_Name,OP_Version,OP_Versiondate,OP_Author,OP_Fraction,OP_About,OP_Pluspath,OP_Time,OP_Off","`ourphp_plus`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	$i = 1;
	while($ourphp_rs = $db -> whilego($query)){
		if(strstr($ourphp_rs[7],"#ourphp#")){
			$purl = '#';
		}else{
			$purl = $ourphp_rs[7];
		}
		$rows[]=array(
			"i" => $i,
			"id" => $ourphp_rs[0],
			"name" => $ourphp_rs[1],
			"version" => $ourphp_rs[2],
			"versiondate" => $ourphp_rs[3],
			"author" => $ourphp_rs[4],
			"fraction" => $ourphp_rs[5],
			"about" => $ourphp_rs[6],
			"pluspath" => $purl,
			"time" => $ourphp_rs[8],
			"off" => $ourphp_rs[9],
		);
		$i = $i + 1;
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}


function myscandir($path){
	$mydir=dir($path);		
	$rows=array();
	while($file=$mydir->read()){
		$p=$path.'/'.$file;
		if(($file!=".") AND ($file!="..")){
			if($p != '../plus/ourphp_plus.php' AND $p != '../plus/Model.txt' AND $p != '../plus/Model_admin.txt' AND $p != '../plus/ourphp_plus_index.php' AND $p != '../plus/ourphp_plus_admin.php' AND $p != '../plus/index.htm'){
			if(file_exists($p.'/Author.tpl')){
				$author = file_get_contents($p.'/Author.tpl');
					}else{
				$author = '无名称，无介绍';
			}
			$rows[] = array('url'=>mb_convert_encoding($p,"utf-8","gb2312"),'name'=>$file,'author'=>$author,);
			}
		}
	}  
	return $rows;
}
	
$smarty->assign("Pluslist",Pluslist());
$smarty->assign("Addpluslist",myscandir('../plus'));
$smarty->display('ourphp_pluslist.html');
?>