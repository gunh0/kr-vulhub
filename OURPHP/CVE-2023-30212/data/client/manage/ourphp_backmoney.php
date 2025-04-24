<?php
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';

if(isset($_GET["type"]) == ""){

	echo '';

}elseif ($_GET["type"] == "ok"){

	plugsclass::logs('处理提现记录',$OP_Adminname);
	$db -> update("ourphp_usermoneyout","`OP_Type` = 2,`OP_Usertime` = '".date("Y-m-d H:i:s")."',`OP_User` = '".$OP_Adminname."'","where id = ".intval($_GET['id']));

}elseif ($_GET["type"] == "del"){

	plugsclass::logs('删除提现记录',$OP_Adminname);
	$db -> del("ourphp_usermoneyout","where id = ".intval($_GET['id']));
	
}

function ourphp_backmoney(){
  global $_page,$db,$smarty;
  $listpage = 25;
  if (intval(isset($_GET['page'])) == 0){
    $listpagesum = 1;
      }else{
    $listpagesum = intval($_GET['page']);
  }
  $start=($listpagesum-1)*$listpage;
  $ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_usermoneyout`","");
  $ourphptotal = $db -> whilego($ourphptotal);

  $rs = array();
  $a = $db -> listgo("*","ourphp_usermoneyout","ORDER BY id DESC LIMIT ".$start.",".$listpage);
  while($r = $db -> whilego($a)){
    $user = $db -> select("*","ourphp_user","where OP_Useremail = '".$r['OP_Useremail']."'");
    $rs[] = array(
      'id' => $r['id'],
      'user' => $r['OP_Useremail'],
      'bank' => $user['OP_Userskype'],
      'banknum' => $user['OP_Useraliww'],
      'money' => $r['OP_Useroutmoney'],
      'type' => $r['OP_Type'],
      'admin' => $r['OP_User'],
      'admintime' => $r['OP_Usertime'],
      'time' => $r['time'],
    );
  }
  $_page = new Page($ourphptotal['tiaoshu'],$listpage);
  $smarty->assign('ourphppage',$_page->showpage());
  return $rs;
}

$smarty->assign("backmoney",ourphp_backmoney());
$smarty->display('ourphp_backmoney.html');
?>
