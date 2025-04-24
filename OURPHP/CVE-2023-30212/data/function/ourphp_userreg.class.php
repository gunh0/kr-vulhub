<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/
if(!defined('OURPHPNO')){exit('no!');}
@session_start();

// 注册类
class OurPHP_Reg{
	
	function __construct(){
		
	}
	
	public function email_code($id,$path){
		global $db;
		$ourphp_mail = 'regcode';
		$OP_Useremail = $id;
		$OP_Regcode = rand(1000,9999);
		$_SESSION['vcode'] = $OP_Regcode;
		include $path.'function/ourphp_mail.class.php';
		return "200";
	}
	
	public function tel_code($id,$path){
		global $db;
		$ourphp_rs = $db -> select("`OP_Websitemin`","`ourphp_web`","where `id` = 1");
		include $path.'function/api/telcode/user_regcode.class.php';
		$OP_Usertel = $id;
		$OP_Regcode = rand(1000,9999);
		$_SESSION['vcode'] = $OP_Regcode;
		$c = str_replace("[.regcode.]", $OP_Regcode, $ourphp_rs[0]);
		$s = '';
		$smskey -> smsconfig($OP_Usertel,$c,$s,1,$OP_Regcode);
		return "200";
	}
	
	public function reg_code(){
		global $ourphp_usercontrol;
		if($ourphp_usercontrol['code'] == 0){
			$table = '';
		}elseif($ourphp_usercontrol['code'] == 1){
			$table = '
			  <tr>
				<td>
					<div class="border radius-10 pl-10 pr-10 pt-5 pb-5 ml-15">
						<span class="fromleft">验证码</span><input type="text" name="vcode" placeholder="验证码" class="input3" datatype="*" /><input onclick="reg_code();" id="btn" type="button" class="btn btn-success radius-5 ml-10" value="获取验证码" /> <font class="ml-10 f-f00">*</font>
					</div>
				</td>
			  </tr>
			';
		}
		return $table;
	}
	
	public function reg_table(){
		global $ourphp_usercontrol;
		if($ourphp_usercontrol['type'] == 'email'){
			$table = '
			  <tr>
				<td>
					<div class="border radius-10 pl-10 pr-10 pt-5 pb-5 ml-15">
						<span class="fromleft">E-MAIL</span><input type="text" id="zh" name="OP_Useremail" class="input" datatype="e" placeholder="格式 12345@qq.com" onblur="regselect();" /><font class="ml-10 f-f00">*</font>
					</div>
				</td>
			  </tr>
			'.$this -> reg_code();
		}elseif($ourphp_usercontrol['type'] == 'tel'){
			$table = '
			  <tr>
				<td>
					<div class="border radius-10 pl-10 pr-10 pt-5 pb-5 ml-15">
						<span class="fromleft">手机号</span><input type="text" id="zh" name="OP_Usertel" class="input" datatype="m" placeholder="格式 13888888888" onblur="regselect();" /><font class="ml-10 f-f00">*</font>
					</div>
				</td>
			  </tr>
			'.$this -> reg_code();
		}
		return $table;
	}
	
	public function reg_select($id){
		global $db;
		$query = $db -> select("id","ourphp_user","where `OP_Useremail` = '".dowith_sql($id)."' or `OP_Usertel` = '".dowith_sql($id)."'");
		if($query){
			$arr = array("error" => 0,"msg" => "用户已存在，换个账号试试！");
		}else{
			$arr = array("error" => 1,"msg" => "恭喜您，账号可以使用！");
		}
		return $arr;
	}
	
	public function reg_vcode($id,$path = '../../'){
		global $ourphp_usercontrol;

		if($ourphp_usercontrol['type'] == 'email'){
			$arr = $this -> email_code($id,$path);
		}elseif($ourphp_usercontrol['type'] == 'tel'){
			$arr = $this -> tel_code($id,$path);
		}
		
		return $arr;
	}
	
}
$reg = new OurPHP_Reg();

?>