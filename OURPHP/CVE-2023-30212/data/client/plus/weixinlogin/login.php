<?php

/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2019 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
*/


session_start();
date_default_timezone_set('Asia/Shanghai');

include '../../../config/ourphp_code.php';
include '../../../config/ourphp_config.php';
include '../../../config/ourphp_version.php';
include '../../../config/ourphp_Language.php';
include '../../../function/ourphp_function.class.php';




//调用插件 把plugs()中的数字改成插件ID
$app = plugsclass::plugs(11);











class WxService
{
    protected $appid;
    protected $appKey;

    public $data = null;

    public function __construct($appid, $appKey)
    {
        $this->appid = $appid; //微信支付申请对应的公众号的APPID
        $this->appKey = $appKey; //微信支付申请对应的公众号的APP Key
    }

    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *
     * @return 用户的openid
     */
    public function GetOpenid($diy)
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = $this->getCurrentUrl();
            $url = $this->__CreateOauthUrlForCode($baseUrl,$diy);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code,$diy);
            return $openid;
        }
    }

    public function getCurrentUrl()
    {
        $scheme = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
        $uri = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
        if($_SERVER['REQUEST_URI']) $uri = $_SERVER['REQUEST_URI'];
        $baseUrl = urlencode($scheme.$_SERVER['HTTP_HOST'].$uri);
        return $baseUrl;
    }

    /**
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * @return openid
     */
    public function GetOpenidFromMp($code,$diy)
    {
        $url = $this->__CreateOauthUrlForOpenid($code,$diy);        
        $res = self::curlGet($url);
        $data = json_decode($res,true);
        $this->data = $data;
        return $data;
    }

    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code,$diy)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appKey;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString."&introducer=".$diy;
    }

    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl,$diy)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_userinfo";
        $urlObj["state"] = "STATE";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString."&introducer=".$diy;
    }

    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 获取用户信息
     * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
     * @return string
     */
    public function getUserInfo($openid,$access_token)
    {

        $response = self::curlGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');
        return json_decode($response,true);
        
    }

    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}



$appid = $app[2];
$appKey = $app[3];
$wxPay = new WxService($appid,$appKey);
$data = $wxPay->GetOpenid($_GET["introducer"]);
if(!$data['openid']) exit('获取openid失败');
$user = $wxPay->getUserInfo($data['openid'],$data['access_token']);

$webinfo = $db -> select("a.OP_Weburl as url, b.OP_Usergroup as usergroup, b.OP_Usermoney as money","ourphp_web a,ourphp_usercontrol b","where a.id = 1 && b.id = 1");

$newemail = date("ymdHis").rand(11,99).'@'.$webinfo['url'];
$a = $db -> select("*","ourphp_p_weixinlogin","where `code` = '".$data['openid']."'");
if($a){


	$_SESSION['userid'] = $a['userid'];
	$_SESSION['username'] = $a['email'];
	$_SESSION['name'] = $a['name'];

	$db -> update("ourphp_user","
		`OP_Username` = '".$user['nickname']."',
		`OP_Userip` = '".$user['province']."-".$user['city']."',
		`OP_Userhead` = '".$user['headimgurl']."'
	","where id = ".$a['userid']);

	$db -> update("ourphp_p_weixinlogin","
		`pic` = '".$user['headimgurl']."',
		`name` = '".$user['nickname']."',
		`addess` = '".$user['province']."'-'".$user['city']."'
	","where `userid` = ".$a['userid']);

	if(isset($_SESSION['ourphp_weburlupgo'])){
	    echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/".$_SESSION['ourphp_weburlupgo']."');</script>";
	}else{
	    echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?cn-usercenter.html');</script>";
	}
	
	exit;

}else{

	if($_GET["introducer"] == 0){

		$introducer = '';

	}else{

		$ourphp_usercontrol = explode("|",$webinfo['money']);
		$ourphp_rs = $db -> select("`OP_Useremail`","`ourphp_user`","WHERE `id` = ".intval($_GET["introducer"]));
		if ($ourphp_rs){

			$query = $db -> update("`ourphp_user`","`OP_Usermoney` = `OP_Usermoney` + ".$ourphp_usercontrol['money'][2].",`OP_Userintegral` = `OP_Userintegral` + ".$ourphp_usercontrol['money'][3],"where id = ".intval($_GET["introducer"]));

			$finance = new userplus\regmoney();
			$finance -> regaddmoney($ourphp_rs[0],$newemail,$ourphp_usercontrol['money'][2],$ourphp_usercontrol['money'][3]);
			$introducer = $ourphp_rs[0];

		}else{

			$introducer = '';

		}

	}

	$db -> insert("ourphp_user","
		`OP_Useremail` = '".$newemail."',
		`OP_Userpass` = '',
		`OP_Usertel` = '',
		`OP_Username` = '".$user['nickname']."',
		`OP_Userclass` = '".$webinfo['usergroup']."',
		`OP_Usersource` = '".$introducer."',
		`OP_Usermoney` = '0.00',
		`OP_Userintegral` = '0.00',
		`OP_Userip` = '".$user['province']."-".$user['city']."',
		`OP_Userstatus` = 1,
		`OP_Userhead` = '".$user['headimgurl']."',
		`OP_Usercode` = '".randomkeys(18)."',
		`time` = '".date("Y-m-d H:i:s")."'
	","");
	$newid = $db -> insertid();
	
	$db -> update("ourphp_user","`OP_Userregcode` = '".$newid.rand(1111,9999)."'","where id = ".$newid);
	$db -> insert("ourphp_p_weixinlogin","
		`email` = '".$newemail."',
		`code` = '".$data['openid']."',
		`pic` = '".$user['headimgurl']."',
		`name` = '".$user['nickname']."',
		`addess` = '".$user['province']."'-'".$user['city']."',
		`userid` = '".$newid."',
		`time` = '".date("Y-m-d H:i:s")."'
	","");

	$_SESSION['userid'] = $newid;
	$_SESSION['username'] = $newemail;
	$_SESSION['name'] = $user['nickname'];
	
	if(isset($_SESSION['ourphp_weburlupgo'])){
	    echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/".$_SESSION['ourphp_weburlupgo']."');</script>";
	}else{
	    echo "<script language=javascript>location.replace('".$ourphp['webpath']."client/wap/?cn-usercenter.html');</script>";
	}
	
	exit;

}


exit;
//$user['headimgurl']
//$user['nickname']
//$user['province'].' / '.$user['city']
?>