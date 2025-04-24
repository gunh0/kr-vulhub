<?php
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';
include 'ourphp_page.class.php';



if(isset($_GET["type"]) == ""){

  echo '';

}elseif ($_GET["type"] == "ok"){

  $a = $db -> select("*","ourphp_orders","where id = ".intval($_GET['id']));
  if($a['OP_Usermoneyback'] == 3){
    echo "<script language=javascript> alert('此笔订单已完成退款，无需重复操作');history.go(-1);</script>";
    exit;
  }
  if(!$a){
    echo "<script language=javascript> alert('订单不存在！');history.go(-1);</script>";
    exit;
  }

	plugsclass::logs('同意退款操作',$OP_Adminname);
	
    $db -> update("ourphp_orders","OP_Usermoneyback = 3","where id = ".intval($_GET['id']));
    $db -> update("ourphp_user","OP_Usermoney = OP_Usermoney + ".$a['OP_Ordersusermarket'],"where id = ".intval($_GET['id']));
	$db -> update("ourphp_product","`OP_Stock` = `OP_Stock` + ".$a['OP_Ordersnum'],"where id = ".intval($a['OP_Ordersid']));
	$db -> update("ourphp_usermoneyback","OP_Adminname = '".$_SESSION['ourphp_adminname']."',OP_Admintime = '".date("Y-m-d H:i:s")."'","where id = ".intval($_GET['rid']));
	 
    $db -> insert("`ourphp_userpay`","`OP_Useremail` = '".$a['OP_Ordersemail']."',`OP_Usermoney` = '".$a['OP_Ordersusermarket']."',`OP_Userintegral` = '0',`OP_Usercontent` = '后台退款',`OP_Useradmin` = 'admin',`OP_Uservoucherone` = '0',`OP_Uservouchertwo` = '0',`time` = '".date("Y-m-d H:i:s")."',`OP_Userpaytype`='a'","");

    echo "<script language=javascript> alert('退款成功');</script>";
  

}

function ourphp_outmoney(){
  global $_page,$db,$smarty;
  $listpage = 25;
  if (intval(isset($_GET['page'])) == 0){
    $listpagesum = 1;
      }else{
    $listpagesum = intval($_GET['page']);
  }
  $start=($listpagesum-1)*$listpage;
  $ourphptotal = $db -> listgo("count(id) as tiaoshu","`ourphp_usermoneyback`","");
  $ourphptotal = $db -> whilego($ourphptotal);

  $rs = array();
  $a = $db -> listgo("*","ourphp_usermoneyback","ORDER BY id DESC LIMIT ".$start.",".$listpage);
  while($r = $db -> whilego($a)){
    $o = $db -> select("*","ourphp_orders","where id = ".$r['OP_Orderid']);
    $rs[] = array(
      'id' => $r['id'],
      'user' => $r['OP_Useremail'],
      'buytitle' => $o['OP_Ordersname'],
      'buyuser' => $o['OP_Ordersusername'],
      'buyusertel' => $o['OP_Ordersusertel'],
      'buymoney' => $o['OP_Ordersusermarket'],
      'buytype' => $o['OP_Usermoneyback'],
      'posnum' => $r['OP_Userposnum'],
      'posname' => $r['OP_Userposname'],
      'admin' => $r['OP_Adminname'],
      'admintime' => $r['OP_Admintime'],
      'time' => $r['time'],
      'buyid' => $r['OP_Orderid'],
    );
  }
  $_page = new Page($ourphptotal['tiaoshu'],$listpage);
  $smarty->assign('ourphppage',$_page->showpage());
  return $rs;
}

$smarty->assign("outmoney",ourphp_outmoney());
$smarty->display('ourphp_outmoney.html');
?>
