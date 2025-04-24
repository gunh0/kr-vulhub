<?php
namespace userplus;

class regmoney{

	function regaddmoney($name = '',$nameto = '',$money = 0,$integral = 0){
		global $db;
		$db -> insert("ourphp_userregreward","
			`OP_Useremail` = '".dowith_sql($name)."',
			`OP_Useremailto` = '".dowith_sql($nameto)."',
			`OP_Usermoney` = '".dowith_sql($money)."', /*奖励现金*/
			`OP_Userintegral` = '".dowith_sql($integral)."', /*奖励积分*/
			`OP_Userid` = '0', /*被推荐人ID*/
			`time` = '".date("Y-m-d H:i:s")."'
		","");
	}

}

?>