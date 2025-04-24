<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="templates/images/ourphp_login.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../../function/plugs/jquery/1.8.3/jquery-1.8.3.min.js"></script>
<script src="../../function/plugs/layer/layer.min.js"></script>
</head>
 
<body>
<?php
// 商品属性调用
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

if ($_GET['our'] == 'gg'){
echo '<table width="100%" border="0" cellpadding="10">';
echo '	<tr>';
echo '		<td bgcolor="#f4f4f4">';
echo '				 <input type="submit" onclick="isok()" name="Submit" value="确定" style="width:100px; height:25px; line-height:25px; background:#0099CC; color:#FFFFFF; border:0px;" />';
echo '		</td>';
echo '	</tr>';

$query = $db -> listgo("*","`ourphp_coupon`","where OP_Type = 0");
while($ourphp_rs = $db -> whilego($query)){
	echo '	<tr>';
	echo '		<td>';

				echo '<p><input type="checkbox" name="id" value="'.$ourphp_rs['id'].'" />&nbsp;&nbsp;'.$ourphp_rs['OP_Title'].'</p>';
		
	echo '		</td>';
	echo '	</tr>';
}
echo '</table>';
?>
<script>
function isok(){
		var index = parent.layer.getFrameIndex(window.name); 
        var text="";  
        $("input[name=id]").each(function() {  
            if ($(this).attr("checked")) {  
                text += ","+$(this).val();  
            }  
        });
		
           $.get(
                   'Coupon.php',
                   {id:text,our:"cc"},
                   function(data){
						 parent.$('#ourphp_coupon_list').html(data);
						 parent.layer.close(index);
                   }
           );   
};
</script>
<?php
}

if ($_GET['our'] == 'cc'){
	
	
	$id = preg_replace('/,/','',admin_sql($_GET['id']),1);
	if ($id == ''){
		echo '要绑定的优惠券不能为空!<input type="hidden" name="OP_Couponset" value="0" >';
		exit;
	}
	
	echo '<table width="90%" border="1" align="left" cellpadding="5" bordercolor="#E3E3E3" style="border-collapse:collapse;">';
	$query = $db -> listgo("*","`ourphp_coupon`","where `id` in (".$id.")");
	while($ourphp_rs = $db -> whilego($query)){
		echo '
				<tr>
					<td>'.$ourphp_rs['OP_Title'].'</td>
				</tr>
		';
	}
	echo '</table><input type="hidden" name="OP_Couponset" value="'.$id.'" >';
	
}
?>
</body>
</html>