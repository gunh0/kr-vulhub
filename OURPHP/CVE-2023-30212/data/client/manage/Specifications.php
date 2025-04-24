<?php
// 商品规格调用
include 'ourphp_admin.php';
include 'ourphp_checkadmin.php';

/* if(isset($_GET["our"]) == ""){
	echo '';
}else{
	$a = implode(',',$_POST["op"]);
	$b = str_replace(',|,','|',$a);
	$c = str_replace(',|','',$b);
	echo $c;
	exit;
}; */


$id = preg_replace('/,/','',$_GET['id'],1);
if (count(explode(",",$id)) > 4 || $id == ''){
	echo $ourphp_adminfont['size_four'];
	exit;
}

//echo '<form id="form1" name="form1" method="post" action="?our=cs">';
echo '<p>&nbsp;</p>';
echo '<p>复制:&nbsp;&nbsp;<a href="javascript:hh()">货号</a>&nbsp;-&nbsp;<a href="javascript:scj()">市场价</a>&nbsp;-&nbsp;<a href="javascript:bzj()">本站价</a><!--&nbsp;-&nbsp;<a href="javascript:kc()">库存</a>-->&nbsp;&nbsp;到规格里</p>';
echo '<p>&nbsp;</p>';
echo '<table width="90%" border="1" align="left" cellpadding="5" bordercolor="#E3E3E3" style="border-collapse:collapse;">';
echo '  <tr>';
echo '    <td bgcolor="#F4F0F4"><div align="center">货号</div></td>';

$query = $db -> listgo("id,OP_Title,OP_Titleto","`ourphp_productspecifications`","where `id` in (".admin_sql($id).") order by OP_Sorting asc,id asc");
while($ourphp_rs = $db -> whilego($query)){
echo '    <td bgcolor="#F4F0F4"><div align="center">'.$ourphp_rs[1].'<span style="color:#999999; font-size:10px;">('.$ourphp_rs[2].')</span></div><input type="hidden" name="optitleid[]" value="'.$ourphp_rs[0].'"><input type="hidden" name="optitle[]" value="'.$ourphp_rs[1].'<span style=color:#999999; font-size:10px;>('.$ourphp_rs[2].')</span>"></td>';
}
echo '    <td bgcolor="#F4F0F4"><div align="center">市场价</div></td>';
echo '    <td bgcolor="#F4F0F4"><div align="center">本站价</div></td>';
echo '    <td bgcolor="#F4F0F4"><div align="center">库存</div></td>';
echo '    <td bgcolor="#F4F0F4"><div align="center">操作</div></td>';
echo '  </tr>';

$query = $db -> listgo("OP_Value","`ourphp_productspecifications`","where `id` in (".admin_sql($id).") order by OP_Sorting asc,id asc");
$rows = array();
while($ourphp_rs = $db -> whilego($query)){
	$rows[] = explode("|",$ourphp_rs[0]);
}
//echo print_r($rows);


function Descartes($d) {
  $r = array_pop($d);
  while($d) {
    $t = array();
    $s = array_pop($d);
    if(! is_array($s)) $s = array($s);
    foreach($s as $x) {
      foreach($r as $y) $t[] = array_merge(array($x), is_array($y) ? $y : array($y));
    }
    $r = $t;
  }
  return $r;
}

$deli = 1;
echo '<tbody id="gglist">';
foreach(Descartes($rows) as $op) {
echo '  <tr id="deli'.$deli.'">';
echo '    <td bgcolor="#FFFFFF"><div style="text-align:center;"><input type="text" name="op[]" class="op_hh"></div></td>';

if (count($rows) == 1){
	if(strstr($op,"uploadfile")){
		echo '    <td bgcolor="#FFFFFF"><img src="'.$ourphp['webpath'].$op.'" width="20" height="20" /><input type="hidden" name="op[]" value="'.$op.'"></td>';
	}else{
		//echo '    <td bgcolor="#FFFFFF">'.$op.'<input type="text" name="op[]" value="'.$op.'"></td>';
		echo '    <td bgcolor="#FFFFFF"><input type="text" name="op[]" value="'.$op.'"></td>';
	}
}else{

	$o = 0;
	for ($p = 1; $p <= count($rows); $p++) {
		if(strstr($op["$o"],"uploadfile")){
			echo '    <td bgcolor="#FFFFFF"><img src="'.$ourphp['webpath'].$op["$o"].'" width="20" height="20" /><input type="hidden" name="op[]" value="'.$op["$o"].'"></td>';
		}else{
			//echo '    <td bgcolor="#FFFFFF">'.$op["$o"].'<input type="text" name="op[]" value="'.$op["$o"].'"></td>';
			echo '    <td bgcolor="#FFFFFF"><input type="text" name="op[]" value="'.$op["$o"].'"></td>';
		}
		$o+=1;
	}

}

echo '    <td bgcolor="#FFFFFF"><div style="text-align:center;"><input type="text" name="op[]" value="0.00" size="10" class="op_scj"></div></td>';
echo '    <td bgcolor="#FFFFFF"><div style="text-align:center;"><input type="text" name="op[]" value="0.00" size="10" class="op_bzj"></div></td>';
echo '    <td bgcolor="#FFFFFF"><div style="text-align:center;"><input type="text" name="op[]" value="100" size="10" class="op_kc"></div><input type="hidden" name="op[]" value="|"></td>';
echo '    <td bgcolor="#FFFFFF"><div style="text-align:center;"><a href="javascript:deli('.$deli.');">删除</a></td>';
echo '  </tr>';

$deli ++;

}
echo '</tbody>';
echo '
				<tr>
					<td colspan="20">
							<a href="javascript:ggadd(1);">自定义增加一条</a>
							<script>
									function ggadd(i){
									    var x = 999999;
									    var y = 111111;
									    var rand = parseInt(Math.random() * (x - y + 1) + y);
										var a = $("#deli"+i).html();
										$("#gglist").append(\'<tr id="deli\'+rand+\'">\'+a.replace("deli(1)","deli("+rand+")")+\'</tr>\');
									}
							</script>
					</td>
				</tr>
';
/* 
$b = count($rows) + 4;
echo '<tr>';
echo '    <td colspan="'. $b .'"><input type="submit" name="Submit" value="提交" /></td>';
echo '  </tr>';
echo '</table>';
echo '</form>'; 
*/
?>