<?php

class ourphpsms{
	
	public function uuid()  
	{  
	    $chars = md5(uniqid(mt_rand(), true));  
	    $uuid = substr ( $chars, 0, 8 ) . '-'
	            . substr ( $chars, 8, 4 ) . '-' 
	            . substr ( $chars, 12, 4 ) . '-'
	            . substr ( $chars, 16, 4 ) . '-'
	            . substr ( $chars, 20, 12 );  
	    return $uuid ;  
	}

	public function smsconfig($m='',$c='',$s='',$t=1,$regcode){
		global $db;
		$rs = $db -> select("OP_Key","`ourphp_api`"," where id = 6");
		$rs = explode('|',$rs[0]);
		if($rs[1] == 2){

			return false;

		}else{

			    $host = "https://miitangs09.market.alicloudapi.com";
			    $path = "/v1/tools/sms/code/sender";
			    $method = "POST";
			    $appcode = $rs[2];
			    $headers = array();
			    array_push($headers, "Authorization:APPCODE " . $appcode);
			    //需要自行安装UUID,需要给X-Ca-Nonce的值生成随机字符串，每次请求不能相同
			    $uuidStr = $this -> uuid();
			    array_push($headers, "X-Ca-Nonce:" . $uuidStr);
			    //根据API的要求，定义相对应的Content-Type
			    array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
			    $querys = "";
			    $bodys = "phoneNumber=".$m."&reqNo=".date("Y-m-d H:i:s")."&smsSignId=".$rs[3]."&smsTemplateNo=".$rs[4]."&verifyCode=".$regcode;
			    $url = $host . $path;

			    $curl = curl_init();
			    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
			    curl_setopt($curl, CURLOPT_URL, $url);
			    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			    curl_setopt($curl, CURLOPT_FAILONERROR, false);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($curl, CURLOPT_HEADER, false);
			    if (1 == strpos("$".$host, "https://"))
			    {
			        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			    }
			    curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
			    return curl_exec($curl);

		}
	}
	

}

$smskey = new ourphpsms();
?>