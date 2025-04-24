<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include './ourphp_admin.php';
include './ourphp_checkadmin.php'; 
function sql($ourphpstr){
	$ourphpstr = addslashes($ourphpstr);
	$ourphpstr = str_ireplace("../","**",$ourphpstr);
	$ourphpstr = str_ireplace("./","**",$ourphpstr);
	$ourphpstr = str_ireplace("||","**",$ourphpstr);
	$ourphpstr = str_ireplace("<","**",$ourphpstr);
	$ourphpstr = str_ireplace(">","**",$ourphpstr);
	$ourphpstr = str_ireplace("&","**",$ourphpstr);
	$ourphpstr = str_ireplace('--',"**",$ourphpstr);
	$ourphpstr = str_ireplace("%","**",$ourphpstr);
	$ourphpstr = str_ireplace("'","**",$ourphpstr);
	$ourphpstr = str_ireplace("$","**",$ourphpstr);
	return $ourphpstr;
}

function op_file($typefile,$path,$plusname)
{
	$str_f = '$';
	if($typefile == 'function')
	{
		$str_tmp = "<?php
		/*
		 * Ourphp - CMS建站系统
		 * Copyright (C) 2014 ourphp.net
		 * 开发者：哈尔滨伟成科技有限公司
		*/
		if(!defined('OURPHPNO')){exit('no!');}

		function smarty_function_".$plusname."(".$str_f."params, &".$str_f."smarty){
			global ".$str_f."db,".$str_f."ourphp;
			extract(".$str_f."params);
			/*
				你的逻辑代码开始
			*/

		}
		?>";
		$sf = $path."/op_".$plusname.".php";
	}
	if($typefile == 'block')
	{
		$str_tmp = "<?php
		/*
		 * Ourphp - CMS建站系统
		 * Copyright (C) 2014 ourphp.net
		 * 开发者：哈尔滨伟成科技有限公司
		*/
		if(!defined('OURPHPNO')){exit('no!');}

		function smarty_block_".$plusname."(".$str_f."params, ".$str_f."content, ".$str_f."template, &".$str_f."repeat){
			global ".$str_f."db,".$str_f."ourphp;
			extract(".$str_f."params);
			if (is_null(".$str_f."content)) {
				return;
			}
			/*
				你的逻辑代码开始
			*/
			
		}
		?>";
		$sf = $path."/op_".$plusname.".php";
	}
	if($typefile == 'modifier')
	{
		$str_tmp = "<?php
		/*
		 * Ourphp - CMS建站系统
		 * Copyright (C) 2014 ourphp.net
		 * 开发者：哈尔滨伟成科技有限公司
		*/
		if(!defined('OURPHPNO')){exit('no!');}

		function smarty_modifier_".$plusname."(".$str_f."string){
			global ".$str_f."db,".$str_f."ourphp;
			/*
				你的逻辑代码开始
			*/
			
		}
		?>";
		$sf = $path."/op_".$plusname.".php";
	}
	if($typefile == 'modifiercompiler')
	{
		$str_tmp = "<?php
		/*
		 * Ourphp - CMS建站系统
		 * Copyright (C) 2014 ourphp.net
		 * 开发者：哈尔滨伟成科技有限公司
		*/
		if(!defined('OURPHPNO')){exit('no!');}

		function smarty_modifiercompiler_".$plusname."(".$str_f."string, ".$str_f."compiler){
			global ".$str_f."db,".$str_f."ourphp;
			extract(".$str_f."params);
			/*
				你的逻辑代码开始
			*/
			
		}
		?>";
		$sf = $path."/op_".$plusname.".php";
	}

	$fp = fopen($sf,"w"); 
	fwrite($fp,$str_tmp);
	fclose($fp);
}

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "plusadd"){

	if (strstr($OP_Adminpower,"34")){
		

		if($_POST['plusidok'] == 0)
		{
			exit("<script language=javascript> alert('插件ID不可用,请更换!');history.go(-1);</script>");
		}
		
		if(empty($_POST['plusname']) || empty($_POST['plusversion']) || empty($_POST['plusdate']) || empty($_POST['plusauthor']) || empty($_POST['pluscontent']) || empty($_POST['plusid']) || $_POST['plusclass'] == '' || empty($_POST['plusadminurl']) || $_POST['plusmysql'] == '' || $_POST['plusadminlist'] == '' || $_POST['plusidok'] == '')
		{
			exit("<script language=javascript> alert('必填项不能为空!');history.go(-1);</script>");
		}
		$plusid = "../plus/".sql($_POST['plusid']);
		
		/*
			创建插件目录
		*/
		mkdir($plusid,0777,true);
		/*
			创建作者文档
		*/
		$file = fopen($plusid."/Author.tpl",'w');
		$file2 = fopen($plusid."/index.htm",'w');
		fwrite($file,"<h1>".sql($_POST['plusname'])."</h1>\r\n<p>".sql($_POST['pluscontent'])."</p>");
		fwrite($file2,"ourphp！");
		fclose($file);
		fclose($file2);
		/*
			创建函数文件
		*/
		if($_POST['plusclass'] == "0")
		{
			$plusclass = '';
		}else{
			op_file(sql($_POST['plusclass']),$plusid,sql($_POST['plusid']));
			$plusclass = sql($_POST['plusclass']);
		}
		
		/*
			创建安装文件
		*/
		$str_f = '$';
		if($_POST['plusmysql'] == "0")
		{
			$plusmysql = '';
			$ophtml = $str_f."plugfield[0] = array(
							'name|varchar(255)',
							'time|datetime',
					);
					\r\n\r\n
				";
		}else{
			
			$i = 0;
			$table64 = '';
			$ophtml = '';
			$plusmysql = '';
			$plusmysql2 = implode("@",$_POST['mysqltable']);
			$mysqltablehtml = explode("@",sql($plusmysql2));
			foreach($mysqltablehtml as $op)
			{
				
				$ophtml2 = '';
				$sqllisthtml = explode("\r\n",sql($_POST['mysqltablelist'][$i]));
				foreach($sqllisthtml as $op2)
				{
					$ophtml2 .= "'".$op2."',";
				}
				$ophtml .= $str_f."plugfield[".$i."] = array(
						".$ophtml2."
					);
					\r\n\r\n
				";
				
				$i ++;
				
				$plusmysql .= sql($_POST['plusid']).'_'.$op.'@';
				$table64 = $ourphp['prefix']."p_".sql($_POST['plusid']).'_'.$op;
				if(strlen($table64) > 64)
				{
					exit("<script language=javascript> alert('".$table64." 大于64个字符,请修改');history.go(-1);</script>");
				}
			}
			
		}
		if($_POST['plusadminlist'] == 0)
		{
			$plusadminlist = 1;
		}else{
			
			$plusadminlist = 2;
			$adminhtml = '';
			$adminlisthtml = explode("\r\n",sql($_POST['adminfontlist']));
			foreach($adminlisthtml as $op)
			{
				$adminhtml .= "'".$op."',";
			}
			$Model_admin = "<?php
			#
			# 插件配置开始(官方默认配置，必须！)
			# ".$str_f."admin_top_font 后台主菜单文字
			#

			".$str_f."admin_top_font = '".sql($_POST['adminfont'])."';

			#
			# ".$str_f."admin_top_url 后台副菜单链接地址一般为:  /plus/你的插件ID名称/文件.php  
			# 一行一个 用|隔开 例如: 财务|/caiwu/caiwu.php?id=ourphp|1
			#
			".$str_f."admin_top_url = array(

						".$adminhtml."

			);
			?>";
			$fp2 = fopen("../plus/".sql($_POST['plusid'])."/Model_admin.php","w"); 
			fwrite($fp2,$Model_admin);
			fclose($fp2);
			
		}
		if($_POST['plusadminzip'] == '')
		{
			$pluggozip = $str_f."pluggozip = '';";
		}else{
			$pluggozip = $str_f."pluggozip = '".$_POST['plusadminzip']."';";
		}
		$install = "<?php
		#
		#插件配置开始(官方默认配置，必须！)
		#

		//插件名称(插件的中文名称)
		".$str_f."plugname = '".sql($_POST['plusname'])."';

		//插件版本
		".$str_f."plugversion = '".sql($_POST['plusversion'])."';

		//插件更新日期
		".$str_f."plugversiondate = '".sql($_POST['plusdate'])."';

		//插件作者
		".$str_f."plugauthor = '".sql($_POST['plusauthor'])."';

		//插件简介
		".$str_f."plugabout = '".sql($_POST['pluscontent'])."';

		//插件ID(与你的插件文件同名，不能是中文和数字和符号)
		".$str_f."plugid = '".sql($_POST['plusid'])."';

		//插件类型  共四种类型  'function'  'block'  'modifier'  'modifiercompiler', 不使用请为空
		".$str_f."plugclass = '".sql($plusclass)."';

		//插件所需要的数据库名称(不能是中文和数字和符号) 多个库用@分开，".$str_f."plugfield[0] 手动增加 如 ".$str_f."plugfield[1]
		".$str_f."plugmysql = '".sql(rtrim($plusmysql,"@"))."';

		//后台管理地址(不需要可以为默认的index.htm)
		".$str_f."plugadminurl = '".sql($_POST['plusadminurl'])."';

		//插件所需要的字段，字段名称|字段类型 例如：name|varchar(255)  字段类型: varchar(255)文本类型  text备注类型  int(10)数字类型 一行一个
		".$ophtml."	
		
		//用户安装插件时,系统自动向根目录解压插件目录内的zip包并覆盖包内文件(卸载插件时已覆盖的文件不可逆)
		//具体可参考官网插件开发贴子
		".$pluggozip."

		#
		# ".$str_f."plugadmin 
		# 参数为 1 或 2  , 1=不启用 2=启用
		# 此变量是开启向后台管理列表增加列表功能数据
		#

		".$str_f."plugadmin = ".$plusadminlist.";




		#
		#引入插件安装执行文件
		#

		include '../ourphp_plus.php';

		?>";
		
		$fp = fopen("../plus/".sql($_POST['plusid'])."/ourphp_".sql($_POST['plusid']).".php","w"); 
		fwrite($fp,$install);
		fclose($fp);
		
		
		$ourphp_font = 5;
		$ourphp_img = 'ok.png';
		$ourphp_content = '
			<h2>'.$_POST['plusname'].'('.sql($_POST['plusid']).') 插件创建成功!</h2>
			<p>已在 /client/plus/'.sql($_POST['plusid']).'/ 目录生成插件安装文件</p>
			<p>请在 /client/plus/'.sql($_POST['plusid']).'/ 目录中编写您自已的插件功能及逻辑文件</p>
			<p>具体可参考论坛中的插件开发文档</p>
			<p>当插件开发完毕 , 可通过后台 -> 运营 -> 扩展 -> 插件安装中安装自已开发好的插件</p>
			<p style="color:#ff0000">如果是收费插件,请把['.sql($_POST['plusid']).']目录打包成['.sql($_POST['plusid']).'.zip ]提交给官方应用商店</p>
		';
		$ourphp_time = 100;
		$ourphp_class = 'ourphp_developer.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_developer.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}

}elseif ($_GET["ourphp_cms"] == "tempadd"){
	
	if (strstr($OP_Adminpower,"34")){
		

		if($_POST['tempidok'] == 0)
		{
			exit("<script language=javascript> alert('模板ID不可用,请更换!');history.go(-1);</script>");
		}
		
		if(empty($_POST['tempname']) || empty($_POST['tempauthor']) || empty($_POST['tempcontent']) || empty($_POST['tempdate']) || empty($_POST['tempid']) || $_POST['tempidok'] == '')
		{
			exit("<script language=javascript> alert('必填项不能为空!');history.go(-1);</script>");
		}
		
		if($_POST['tempid'] == 'default' || $_POST['tempid'] == 'user' || $_POST['tempid'] == 'wap')
		{
			exit("<script language=javascript> alert('模板ID出错!');history.go(-1);</script>");
		}
		$tempid = "../../templates/".sql($_POST['tempid']);
		/*
			创建模板目录
		*/
		mkdir($tempid,0777,true);
		mkdir($tempid."/images",0777,true);
		mkdir($tempid."/css",0777,true);
		mkdir($tempid."/js",0777,true);
		mkdir($tempid."/cn",0777,true);
		/*
			创建作者文档
		*/
		if($_POST['tempweburl'] == '')
		{
			$tempweburl = '';
		}else{
			$tempweburl = "\r\n<p>作者网站：<a href='".sql($_POST['tempweburl'])."' target='_blank'>www.ourphp.net</a></p>";
		}
		$file = fopen($tempid."/Author.tpl",'w');
		$file2 = fopen($tempid."/cn/cn_index.html",'w');
		fwrite($file,"<h6>".sql($_POST['tempname'])."</h6>\r\n<p>模板作者：".sql($_POST['tempauthor'])."</p>\r\n<p>更新日期：".sql($_POST['tempdate'])."</p>\r\n<p>模板简介：".sql($_POST['tempcontent'])."</p>".$tempweburl);
		fwrite($file2,"<h1>Hello , ".sql($_POST['tempname'])." !</h6>");
		fclose($file);
		fclose($file2);
		
		
		$ourphp_font = 5;
		$ourphp_img = 'ok.png';
		$ourphp_content = '
			<h2>'.$_POST['tempname'].'('.sql($_POST['tempid']).') 模板创建成功!</h2>
			<p>已在 /templates/'.sql($_POST['tempid']).'/ 目录生成模板文件</p>
			<p>请在 /templates/'.sql($_POST['tempid']).'/ 目录中编写您自已的模板及设计模板</p>
			<p>具体可参考论坛中的模板开发文档和后台的模板标签</p>
			<p>当模板开发设计完毕 , 可通过后台 -> 全局 -> 模板安装使用中使用自已设计好的模板</p>
			<p style="color:#ff0000">如果是收费模板,请把['.sql($_POST['tempid']).']目录打包成['.sql($_POST['tempid']).'.zip]提交给官方应用商店</p>
		';
		$ourphp_time = 100;
		$ourphp_class = 'ourphp_developer.php?id=ourphp';
		require 'ourphp_remind.php';
		
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_developer.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}
}


$smarty->display('ourphp_developer.html');
?>