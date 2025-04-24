<?php
namespace userplus;

class group{

	function grouppay($id = 0, $set = 1, $tuanid = 0, $username = '', $buyid = 0, $num = 0){
		global $db;
		
		if($set == 2)
		{
			
			if($tuanid == 0){
				
				//new
				$db -> insert("ourphp_tuan","
					`OP_Tuanpid` = '".$id."',
					`OP_Tuanoid` = '".$buyid."',
					`OP_Tuanuser` = '".$username."',
					`OP_Tuannum` = '".$num."',
					`OP_Tuanznum` = '1',
					`time` = '".date("Y-m-d H:i:s")."'
				","");
				$newid = $db -> insertid();
				
				$db -> insert("ourphp_tuanuserlist","
					`OP_Tuanid` = '".$newid."',
					`OP_Tuanpid` = '".$id."',
					`OP_Tuanoid` = '".$buyid."',
					`OP_Tuanuser` = '".$username."',
					`time` = '".date("Y-m-d H:i:s")."'
				","");
				
			}else{
				
				$db -> update("ourphp_tuan","`OP_Tuanznum` = `OP_Tuanznum` + 1","where id = ".intval($tuanid));
				
				$db -> insert("ourphp_tuanuserlist","
					`OP_Tuanid` = '".$tuanid."',
					`OP_Tuanpid` = '".$id."',
					`OP_Tuanoid` = '".$buyid."',
					`OP_Tuanuser` = '".$username."',
					`time` = '".date("Y-m-d H:i:s")."'
				","");
			}
			
		}else{
			
			return false;
			
		}
	}

}

?>