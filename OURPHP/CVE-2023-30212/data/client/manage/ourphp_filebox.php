<?php
/*******************************************************************************
* Ourphp - CMS建站系统
* Copyright (C) 2023 www.ourphp.net
* 开发者：哈尔滨伟成科技有限公司
* 
* 在线模板管理系统 v1.1.0 (由哈尔滨伟成科技有限公司开发)
*******************************************************************************/
include '../../config/ourphp_code.php';
include '../../config/ourphp_config.php';
include '../../function/ourphp_function.class.php';

/*
 * 为了系统安全,请在这里设置在线编辑模板的安全口令 (这里的安全口令只对在线编辑模板有效,不是后台的登录口令)
 * 请把 $safecode2 = 'ourphp'; 里面的 ourphp换成你新设置的口令, 为了安全不要用简单的 123456  888888等
 */

$safecode2 = 'ourphp';






























































































if(isset($_GET['path'])){
	if(strpos($_GET['path'],"ourphp_filebox") !== false || strpos($_GET['path'],"ourphp_config") !== false){
		echo '出错';
		exit;
	}
}


function listDirFiles($dir)
{
    $arr = array();
    if (is_dir($dir)) {
        $d = opendir($dir);
        if ($d) {
            while (($file = readdir($d)) !== false) {
                if  ($file != '.' && $file != '..') {
                    if (is_dir($file)) {
                        $arr[$file] = listDirFiles($file);
                    } else {
                        $arr[] = $file;
                    }
                }
            }
        }
        closedir($d);
    }
    return $arr;
}

function format($ourphp)
{
    $format = 'ico_txt.png';
    $formatfont = '不可编辑文件';
    if(strstr($ourphp,".html"))
    {
        $format = 'ico_html.png';
        $formatfont = 'html文件可编辑';
    }
    if(strstr($ourphp,".php"))
    {
        $format = 'ico_php.png';
        $formatfont = 'php文件可编辑';
    }
    if(strstr($ourphp,".css"))
    {
        $format = 'ico_css.png';
        $formatfont = 'css文件可编辑';
    }
    if(strstr($ourphp,".js"))
    {
        $format = 'ico_js.png';
        $formatfont = 'js文件可编辑';
    }

    return array($format,$formatfont);
}

function pw($a,$b)
{
    global $db,$ourphp,$safecode2;
    session_start();
    if ($a == $ourphp['validation'] && $b == $ourphp['safecode']){
         
        $_SESSION['ourphp_out'] = "ourphp"; echo $safecode2;
        
    }else{

        if(empty($_SESSION['ourphp_out']))
        {
            include 'ourphp_checkadmin.php';
			if(strstr($OP_Adminpower,"06")): else: echo "无权限操作"; exit; endif;

        }else{

            session_start();

        }

    }
}

function filearray($ourphp)
{
    $a = array(
        'wap' => '移动端模板',
        'default' => '默认模板',
        'user' => '会员模板',
        'images' => '图片目录',
        'js' => 'JS文件目录',
        'css' => '样式文件目录',
    );

    if(isset($a[$ourphp]))
    {
        return $a[$ourphp];
    }else{

        if(strstr($ourphp,".html") || strstr($ourphp,".css") || strstr($ourphp,".js") || strstr($ourphp,".php") || strstr($ourphp,".jpg") || strstr($ourphp,".jpeg") || strstr($ourphp,".png") || strstr($ourphp,".gif"))
        {
            $format = format($ourphp);
            return $format[1];
        }else{
            return "模板目录";
        }
    }
    
}

$v = (empty($_GET['validation']))? "0" : $_GET['validation'];
$c = (empty($_GET['code']))? "0" : $_GET['code'];
pw($v,$c);
if(isset($_GET['out'])){
    unset($_SESSION['ourphp_out']);
}

$list = '';
if(!isset($_GET['path']))
{

    if(empty($_SESSION['ourphp_out']))
    {
        $file = listDirFiles('../../templates'); 
    }else{
        $file = listDirFiles('../../'); 
    }
    
    foreach ($file as $op) {
        if(!strstr($op,".")){
        $list .= '
        <li>
                <a href="?path='.$op.'&dir"><img src="../../skin/ico_file.png" width="80"><p>'.$op.'<br /><span>'.filearray($op).'</span></p></a>
        </li>
        ';
        }
    }

}else{

    if(empty($_SESSION['ourphp_out']))
    {
        $file = listDirFiles('../../templates/'.$_GET['path']);
        $file2 = '../../templates/'.$_GET['path'];
		$offedit = 1;
    }else{
        $file = listDirFiles('../../'.$_GET['path']);
        $file2 = '../../'.$_GET['path'];
		$offedit = 2;
    }


    if(isset($_GET['dir']))
    {
        foreach ($file as $op) {
            if(!strstr($op,".")){
                $list .= '
                <li>
                        <a href="?path='.$_GET['path'].'/'.$op.'&dir"><img src="../../skin/ico_file.png" width="80"><p>'.$op.'<br /><span>'.filearray($op).'</span></p></a>
                </li>
                ';
            }
            if(strstr($op,".html") || strstr($op,".css") || strstr($op,".js") || strstr($op,".php") || strstr($op,".htaccess")){

                $format = format($op);
                $list .= '
                <li>
                        <a href="?path='.$file2.'/'.$op.'&edit"><img src="../../skin/'.$format[0].'" width="80"><p>'.$op.'<br /><span>'.filearray($op).'</span></p></a>
                </li>
                ';
            }
            if(strstr($op,".jpg") || strstr($op,".jpeg") || strstr($op,".png") || strstr($op,".gif")){
                $list .= '
                <li>
                        <a href="'.$file2.'/'.$op.'" target="_blank"><img src="'.$file2.'/'.$op.'" width="80" height="70"><p>'.$op.'<br /><span>'.filearray($op).'</span></p></a>
                </li>
                ';
            }
        }
    }

    if(isset($_GET['edit']))
    {
        if(isset($_GET['path'])){
			if($offedit == 1)
			{
				if(!strpos($_GET['path'],'templates') !== false){
					   exit(0);
				}
			}
            $openfile = file_get_contents($_GET['path']);
            $openfile = str_replace("<textarea", "<ourphp_", $openfile);
            $openfile = str_replace("</textarea>", "</ourphp_>", $openfile);
            $list = '
            <form id="form1" name="form1" method="post" action="?path=edit&ok">
                <div class="boxedit">
                        <textarea id="code" name="code">'.$openfile.'</textarea>
                </div>
                <div class="boxok">

                    <p><a href="#"" onClick=window.open("tags.php","go","scrollbars=0,resizable=0,scrollbars=yes,width=1300,height=500,left=150,top=150,screenX=50,screenY=50")>【在新窗口中弹出模板标签】</a></p>
                    <p><a href="#"" onClick=window.open("ourphp_column.php?id=ourphp","go","scrollbars=0,resizable=0,scrollbars=yes,width=1300,height=500,left=150,top=150,screenX=50,screenY=50")>【在新窗口中弹出栏目管理】</a></p>
					<p style="padding-top:220px;">
						<input type="password" name="safecode2" placeholder="输入安全口令" />
                    </p>
					<p style="line-height:20px; font-size:12px; color:#666">安全口令不是系统的口令码，安全口令需要在\client\manage\ourphp_filebox.php中修改和设置。</p>
                    <p>
                    <input type="submit" name="Submit" value="保存代码" class="an" />
                    </p>
                </div>
                <input type="hidden" value="'.$_GET['path'].'|'.MD5($_GET['path'].$ourphp['safecode']).'" name="md">
            </form>
                <script id="script">
                var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                    lineNumbers: true,
                    ineWrapping: true,
                    mode: "markdown"
                });
                </script>
            ';
        }
    }

    if(isset($_GET['ok']))
    {
        if(empty($_POST['code']) || empty($_POST['md'])){
           $list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">不能为空呀！</h1>';
        }
        $md = explode("|", $_POST['md']);
        $md2 = MD5($md[0].$ourphp['safecode']);
        if($md[1] != $md2){
            $list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">验证不通过呀！</h1>';
        }

        $code = $_POST['code'];
		if(get_magic_quotes_gpc())
		{
			$code = stripslashes($code);
		}
        $code = str_replace("<ourphp_", "<textarea", $code);
        $code = str_replace("</ourphp_>", "</textarea>", $code);

        if(empty($_SESSION['ourphp_out']))
        {

            if($safecode2 == 'ourphp' || empty($safecode2) || $safecode2 == ''){
				
				$list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">安全口令不正确！</h1>';
				
			}else{
				
				if($_POST['safecode2'] != $safecode2){
					
					$list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">安全口令不正确！</h1>';
					
				}else{
					
					if(stristr($code,"<?php") || stristr($code,"<%") || stristr($code,"language=\"php\"") || stristr($code,"language='php'") || stristr($code,"language=php") || stristr($code,"<?=") || stristr($md[0],".php") || stristr($md[0],".asp") || stristr($md[0],".aspx") || stristr($md[0],".jsp") || stristr($md[0],".htaccess") || stristr($md[0],".ini") || stristr($md[0],".user.ini"))
					{
						$list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">不要提交违法代码！</h1>';
					}else{

						$filego = fopen($md[0],'w');
						fwrite($filego,$code);
						fclose($filego);

						plugsclass::logs('编辑模板:'.$md[0],$_SESSION['ourphp_adminname']);
						$list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">编辑成功！</h1>';

					}
					
				}
				
			}
			

        }else{
            
			
            if(stristr($md[0],".asp") || stristr($md[0],".aspx") || stristr($md[0],".jsp") || stristr($md[0],".htaccess") || stristr($md[0],".ini") || stristr($md[0],".user.ini"))
            {
                $list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">不要提交违法代码！</h1>';
            }else{

                $filego = fopen($md[0],'w');
                fwrite($filego,$code);
                fclose($filego);

                $list = '<h1 style="float:left; margin-top:30px; padding-bottom:30px; font-size:20px; width:100%; text-align:center;">编辑成功！</h1>';

            }

        }

    }

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OurPHP Filebox 1.0.0</title>
<link href="templates/images/ourphp_login.css?<?php echo time();?>" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../../function/plugs/jquery/1.7.2/jquery-1.7.2.min.js"></script>
<link rel='stylesheet' href='../../function/plugs/codemirror/codemirror.css'>
<script src='../../function/plugs/codemirror/codemirror.js'></script>
<script src='../../function/plugs/codemirror/markdown.js'></script>
<script src='../../function/plugs/codemirror/xml.js'></script>
<style type='text/css'>
.CodeMirror{border:1px solid silver;border-width:1px 2px}
.cm-header{font-family:arial}
.cm-header-1{font-size:150%}
.cm-header-2{font-size:130%}
.cm-header-3{font-size:120%}
.cm-header-4{font-size:110%}
.cm-header-5{font-size:100%}
.cm-header-6{font-size:90%}
.cm-strong{font-size:140%}
</style>
</head>
<body>
<div id="tabs0" class="mt-50">
    <ul class="menu0" id="menu0">
        <li class="hover">在线模板编辑</li>
        <li><a href="javascript:window.history.go(-1);">返回上一层</a></li>
    </ul>
    <div class="main" id="main0" style="border-top:2px #488fcd solid; clear:both;">
        <ul class="block filelist">
            <?php
            echo $list;
            ?>
        </ul>
    </div>
</div>
</body>
</html>