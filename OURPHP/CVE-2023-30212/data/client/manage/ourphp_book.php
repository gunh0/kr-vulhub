<?php 
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2014 ourphp.net
* 开发者：哈尔滨伟成科技有限公司
*******************************************************************************/
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';
include '../../function/ourphp_navigation.class.php';

if(isset($_GET["ourphp_cms"]) == ""){
	echo '';
}elseif ($_GET["ourphp_cms"] == "add"){

	plugsclass::logs('创建留言版块',$OP_Adminname);
	$db -> insert("`ourphp_booksection`","`OP_Booksectiontitle` = '".dowith_sql($_POST["OP_Booksectiontitle"])."',`OP_Booksectioncontent` = '".dowith_sql($_POST["OP_Booksectioncontent"])."',`OP_Booksectionlanguage` = '".dowith_sql($_POST["OP_Booksectionlanguage"])."',`OP_Booksectionsorting` = '".dowith_sql($_POST["OP_Booksectionsorting"])."',`time` = '".date("Y-m-d H:i:s")."'","");
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_book.php?id=ourphps';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "del"){

	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除留言',$OP_Adminname);
		$db -> del("ourphp_booksection","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_book.php?id=ourphps';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_book.php?id=ourphps';
		require 'ourphp_remind.php';
	
	}
			
}elseif ($_GET["ourphp_cms"] == "bookdel"){
		
	if (strstr($OP_Adminpower,"35")){

		plugsclass::logs('删除留言版块',$OP_Adminname);
		$db -> del("ourphp_book","where id = ".intval($_GET['id']));
		$ourphp_font = 2;
		$ourphp_class = 'ourphp_book.php?id=ourphp';
		require 'ourphp_remind.php';
	
	}else{
	
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法删除！';
		$ourphp_class = 'ourphp_book.php?id=ourphps';
		require 'ourphp_remind.php';
	
	}

}elseif ($_GET["ourphp_cms"] == "reply"){

	plugsclass::logs('回复留言',$OP_Adminname);
	$db -> update("`ourphp_book`","`OP_Bookreply` = '".dowith_sql($_POST["OP_Bookreply"])."'","where id = ".intval($_GET['id']));
	$ourphp_font = 1;
	$ourphp_class = 'ourphp_book.php?id=ourphp';
	require 'ourphp_remind.php';
			
}elseif ($_GET["ourphp_cms"] == "edit"){

	if (strstr($OP_Adminpower,"34")){

		plugsclass::logs('编辑留言版块',$OP_Adminname);
		$db -> update("`ourphp_booksection`","`OP_Booksectiontitle` = '".dowith_sql($_POST["OP_Booksectiontitle"])."',`OP_Booksectioncontent` = '".dowith_sql($_POST["OP_Booksectioncontent"])."',`OP_Booksectionlanguage` = '".dowith_sql($_POST["OP_Booksectionlanguage"])."',`OP_Booksectionsorting` = '".dowith_sql($_POST["OP_Booksectionsorting"])."',`time` = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['id']));
		$ourphp_font = 1;
		$ourphp_class = 'ourphp_book.php?id=ourphps';
		require 'ourphp_remind.php';
		
	}else{
		
		$ourphp_font = 4;
		$ourphp_content = '权限不够，无法编辑内容！';
		$ourphp_class = 'ourphp_book.php?id=ourphps';
		require 'ourphp_remind.php';
		
	}
			
}

function columncycle($id=1){
	global $db;
	$ourphp_rs = $db -> select("`OP_Booksectiontitle`","`ourphp_booksection`","where id = ".$id);
	return $ourphp_rs[0];
}

function Booklist(){
	global $_page,$db,$smarty;
	$listpage = 40;
	if (intval(isset($_GET['page'])) == 0){
		$listpagesum = 1;
			}else{
		$listpagesum = intval($_GET['page']);
	}
	$start=($listpagesum-1)*$listpage;
	$ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_book`","");
	$ourphptotal = $db -> whilego($ourphptotal);
	$query = $db -> listgo("*","`ourphp_book`","order by id desc LIMIT ".$start.",".$listpage);
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs['id'],
			"content" => $ourphp_rs['OP_Bookcontent'],
			"name" => $ourphp_rs['OP_Bookname'],
			"tel" => $ourphp_rs['OP_Booktel'],
			"ip" => $ourphp_rs['OP_Bookip'],
			"class" => columncycle($ourphp_rs['OP_Bookclass']),
			"lang" => $ourphp_rs['OP_Booklang'],
			"time" => $ourphp_rs['time'],
			"reply" => $ourphp_rs['OP_Bookreply']
		);
	}
	$_page = new Page($ourphptotal['tiaoshu'],$listpage);
	$smarty->assign('ourphppage',$_page->showpage());
	return $rows;
}

function Langlist(){
	global $db;
	$query = $db -> listgo("id,OP_Lang,OP_Font,OP_Default","`ourphp_lang`","order by id asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs[0],
			"lang" => $ourphp_rs[1],
			"font" => $ourphp_rs[2],
			"default" => $ourphp_rs[3]
		);
	}
	return $rows;
}

function Booksection(){
	global $db;
	$query = $db -> listgo("*","`ourphp_booksection`","order by OP_Booksectionsorting asc");
	$rows=array();
	while($ourphp_rs = $db -> whilego($query)){
		$rows[]=array(
			"id" => $ourphp_rs['id'],
			"lang" => $ourphp_rs['OP_Booksectionlanguage'],
			"title" => $ourphp_rs['OP_Booksectiontitle'],
			"content" => $ourphp_rs['OP_Booksectioncontent'],
			"time" => $ourphp_rs['time']
		);
	}
	return $rows;
}

Admin_click('留言管理','ourphp_book.php?id=ourphp');
$smarty->assign("Booklist",Booklist());
$smarty->assign("langlist",Langlist());
$smarty->assign("Booksection",Booksection());
if (isset($_GET["uid"])){
	$ourphp_rs = $db -> select("*","`ourphp_booksection`","where `id` = ".intval($_GET['uid']));
	$smarty->assign('ourphp_booksection',$ourphp_rs);
}
$smarty->display('ourphp_book.html');
?>