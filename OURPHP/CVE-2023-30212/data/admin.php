<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('错误！您的PHP版本不能低于 5.3.0 !');
if (!file_exists("./function/install/ourphp.lock")) {
	header("location: ./function/install/index.php");
	exit;
}

include './config/ourphp_code.php';
include './config/ourphp_config.php';
include './config/ourphp_version.php';
include './config/ourphp_Language.php';
include './function/ourphp_dz.class.php';
include './function/ourphp_function.class.php';
include './function/ourphp/Smarty.class.php';
session_start();
@$ValidateCode = $_SESSION["code"];
$ourphp_update = plugsclass::webupdate();

if (isset($_GET['ourphp_admin']) == "login") {

	if($_POST['admintyle'] == 'admin')
	{
		if ($_REQUEST["ourphp_kouling"] != $ourphp['validation']) {
			exit("<script language=javascript> alert('".$ourphp_adminfont['klerror']."');history.go(-1);</script>");
		}
	}
	
	if($_POST['admintyle'] == 'admin2')
	{
		if ($_REQUEST["ourphp_code"] != $ValidateCode) {
			exit("<script language=javascript> alert('".$ourphp_adminfont['code']."');history.go(-1);</script>");
		}
	}

	$loginname = dowith_sql($_REQUEST["loginname"]);
	$loginpass = dowith_sql(substr(md5(md5($_REQUEST["loginpass"])),0,16));
    $ourphp_rs = $db -> select("id,OP_Admin","ourphp_admin","where OP_Adminname = '".$loginname."' and OP_Adminpass = '".$loginpass."'");
	
    if(!$ourphp_rs){
		
		plugsclass::logs('管理员登录出错',$loginname);
		exit("<script language=javascript> alert('".$ourphp_adminfont['loginerror']."');history.go(-1);</script>");
		
    }else{
		
		plugsclass::logs('管理员登录成功',$loginname);
		session_start();
		$_SESSION['ourphp_adminname'] = $loginname;
		$_SESSION['ourphp_outtime'] = time() + 3600;
		if ($ourphp_rs[1] == 1){
			echo "<script>location.href='".$_SERVER['PHP_SELF']."?ourphp=admin';</script>";
			exit();
				}elseif ($ourphp_rs[1] == 2){
			echo "<script>location.href='".$_SERVER['PHP_SELF']."?ourphp=admin';</script>";
			exit();
		}
    }
}

$ourphp_templates = $ourphp['adminpath']."/templates/";
$ourphp_templates_c = "function/_compile/";
$ourphp_cache = "function/_cache/";
$Encodeurl = "./";

$smarty = new Smarty;
$smarty->caching = false; 
$smarty->setTemplateDir($ourphp_templates);
$smarty->setCompileDir($ourphp_templates_c);
$smarty->setCacheDir($ourphp_cache);
$smarty->addPluginsDir(array('./function/class','./function/data',));
$smarty->assign('ourphp','<h1>hello,ourphp!</h1>');
$smarty->assign('ourphp_access',$ourphp_access);
$smarty->assign('version',$ourphp_version);
$smarty->assign('webpath',$ourphp['webpath']);
$smarty->assign('adminpath',$ourphp['adminpath']);
$smarty->assign('templatepath',$ourphp_templates);
$smarty->assign('ourphp_adminfont',$ourphp_adminfont);

if (isset($_GET['ourphp']) == "") {

		$smarty->assign('OP_Login',op('OP_Login'));
		$smarty->display('ourphp_login.html');
	
		}elseif ($_GET['ourphp'] == "admin") {
		
		include './'.$ourphp['adminpath'].'/ourphp_checkadmin.php';
		
		$smarty->assign('id',$id);
		$smarty->assign('OP_Adminname',$OP_Adminname);
		$smarty->assign('OP_Adminpass',$OP_Adminpass);
		$smarty->assign('OP_Adminpower',$OP_Adminpower);
		$smarty->assign('OP_Empower',op('OP_Empower'));
		$smarty->assign('OP_Empowerlist',op('OP_Empowerlist'));
		$smarty->assign('ourphp_out',$_SERVER['PHP_SELF']);
		$smarty->assign('ourphp_switch',array('weixin'=>$ourphp_weixin,'apps'=>$ourphp_apps,'alifuwu'=>$ourphp_alifuwu));
		
		function Pluslist(){
			global $smarty,$ourphp,$db;
			$query = $db -> listgo("OP_Name,OP_Pluspath,id","`ourphp_plus`","order by id desc");
			$rows = array();
			$i = 1;
			while($ourphp_rs = $db -> whilego($query)){
				$rows[]=array(
					"i" => $i,
					"id" => $ourphp_rs[2],
					"name" => $ourphp_rs[0],
					"pluspath" => $ourphp['webpath'].'client/plus/'.$ourphp_rs[1],
					"pluspath2" => $ourphp_rs[1],
				);
				$i = $i + 1;
			}
			return $rows;
		}
		
		function Ourphp_Admin_click(){
			global $db;
			$query = $db -> listgo("`id`,`OP_Title`,`OP_Url`,`OP_Click`","`ourphp_adminclick`","order by OP_Click desc LIMIT 0,15");
			$rows = array();
			while($ourphp_rs = $db -> whilego($query)){
				$rows[]=array(
					"id" => $ourphp_rs[0],
					"Click_title" => $ourphp_rs[1],
					"Click_url" => $ourphp_rs[2]
				);
			}
			return $rows;
		}
		
		function Ourphp_Admin_nav(){
			global $db,$homedeploy;
			$webmajor = array(3,9);
			$query = $db -> listgo("`id`,`OP_Title`,`OP_Soft`,`OP_Ourphp`","`ourphp_adminnav`","order by OP_Soft asc LIMIT 0,30");
			$rows = array();
			while($ourphp_rs = $db -> whilego($query)){
				if($homedeploy[1] == 7)
				{
					$rows[]=array(
						"id" => $ourphp_rs[0],
						"title" => $ourphp_rs[1],
						"soft" => $ourphp_rs[2],
						"ourphp" => $ourphp_rs[3],
					);
				}else{
					if(!in_array($ourphp_rs[0],$webmajor))
					{
						$rows[]=array(
							"id" => $ourphp_rs[0],
							"title" => $ourphp_rs[1],
							"soft" => $ourphp_rs[2],
							"ourphp" => $ourphp_rs[3],
						);
					}
				}
			}
			return $rows;
		}
		
		function Ourphp_Admin_navlist(){
			global $db,$ourphp,$homedeploy;
			$webmajor = array(40,41,42,43,44,45,69);
			$query = $db -> listgo("`id`,`OP_Title`,`OP_Soft`,`OP_Ourphp`","`ourphp_adminnav`","order by OP_Soft asc LIMIT 0,30");
			$rows = array();
			$html = '';
			while($ourphp_rs = $db -> whilego($query)){
				$html .= '<div id="con_nav_'.$ourphp_rs[0].'"style="display:none;">';
				$query2 = $db -> listgo("`id`,`OP_Title`,`OP_Path`,`OP_Soft`,`OP_Uid`","`ourphp_adminnavlist`","where `OP_Uid` = ".$ourphp_rs[0]." order by OP_Soft asc LIMIT 0,30");
				while($ourphp_rs2 = $db -> whilego($query2)){
					if($homedeploy[1] == 7)
					{
						if($ourphp_rs2[2] == 'ourphp')
						{
							$html .= '<h1>'.$ourphp_rs2[1].'</h1><div class="cc"></div>';
						}else{
							$html .= '<ul><li><a href="'.$ourphp['webpath'].$ourphp['adminpath'].$ourphp_rs2[2].'" target="main">'.$ourphp_rs2[1].'</a></li></ul>';
						}
						
					}else{
						if(!in_array($ourphp_rs2[0],$webmajor))
						{
							if($ourphp_rs2[2] == 'ourphp')
							{
								$html .= '<h1>'.$ourphp_rs2[1].'</h1><div class="cc"></div>';
							}else{
								$html .= '<ul><li><a href="'.$ourphp['webpath'].$ourphp['adminpath'].$ourphp_rs2[2].'" target="main">'.$ourphp_rs2[1].'</a></li></ul>';
							}
						}
					}
					$uid = $ourphp_rs2[4];
				}
				
				if($uid == 5)
				{
					$html .= '<div id="plusnewlist">';
					$plusinfo = Pluslist();
					foreach($plusinfo as $op)
					{
						if(!empty($op['pluspath2']))
						{
							$html .= '<ul id="plusdel'.$op['id'].'"><li><a href="'.$op['pluspath'].'" target="main">'.$op['name'].'</a></li></ul>';
						}
					}
					$html .= '</div>';
				}
				$html .= '</div>';
			}
			return $html;
		}

		if($ourphp_update == 0): $v = '企业版'; else: $v = '专业版'; endif;
		$smarty->assign('ourphp_click',Ourphp_Admin_click());
		$smarty->assign('ourphp_adminnav',Ourphp_Admin_nav());
		$smarty->assign('ourphp_adminnavlist',Ourphp_Admin_navlist());
		$smarty->assign('Pluslist',Pluslist());
		$smarty->assign('v',$v);
		
		if ($OP_Admin == 1){
				$smarty->display('ourphp_index.html');
			}elseif ($OP_Admin == 2){
				$smarty->display('ourphp_assist.html');
		}else{
			echo $ourphp_adminfont['accessno'];
			exit(0);
		}
}
?>