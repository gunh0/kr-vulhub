<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 *-------------------------------
 * 分页类(2014-10-15)
 *-------------------------------
*/
if(!defined('OURPHPNO')){exit('no!');}

class Page {  
			private $total;      //总记录  
			private $pagesize;    //每页显示多少条  
			private $limit;          //limit  
			private $page;           //当前页码  
			private $pagenum;      //总页码  
			private $url;           //地址  
			private $bothnum;      //两边保持数字分页的量  

			//构造方法初始化  
			public function __construct($_total, $_pagesize) {  
				$this->total = $_total ? $_total : 1;  
				$this->pagesize = $_pagesize;  
				$this->pagenum = ceil($this->total / $this->pagesize);  
				$this->page = $this->setPage();  
				$this->limit = "LIMIT ".($this->page-1)*$this->pagesize.",$this->pagesize";  
				$this->url = $this->setUrl();  
				$this->bothnum = 1;  
			}  

			//获取当前页码  
			private function setPage() {  
				if (!empty($_GET['page'])) {  
				if ($_GET['page'] > 0) {  
					if ($_GET['page'] > $this->pagenum) {  
					return $this->pagenum;  
						} else {  
						return intval($_GET['page']); 
					}  
						} else {  
						return 1;  
					}  
						} else {  
				return 1;  
				}  
			}   

			//获取地址  
			private function setUrl() {  
				$_url = $_SERVER["REQUEST_URI"];  
				$_par = parse_url($_url);  
				if (isset($_par['query'])) {  
					echo parse_str($_par['query'],$_query);  
					unset($_query['page']);  
					$_url = $_par['path'].'?'.http_build_query($_query);  
				}
			$replace = str_replace('_','.',$_url);
			//$replace = str_replace('=','-',$replace);
			//$replace = str_replace('--','-',$replace);
			return $replace;
			}     
  
			//数字目录  
			private function pageList() {
				global $Parameterse;
				$_pagelist = '';
				for ($i=$this->bothnum;$i>=1;$i--) {  
					$_page = $this->page-$i;  
					if ($_page < 1) continue;  
					$_pagelist .= '<a href="'.$this->url.'&page='.$_page.'">'.$_page.'</a>';  
				}  
				$_pagelist .= '<span class="me">'.$this->page.'</span>';  
					for ($i=1;$i<=$this->bothnum;$i++) {  
					$_page = $this->page+$i;  
					if ($_page > $this->pagenum) break;  
					$_pagelist .= '<a href="'.$this->url.'&page='.$_page.'">'.$_page.'</a>';  
				}  
				return $_pagelist;  
			}  

			  //上一页  
			  private function prev(){

			  }

			//下一页  
			  private function next(){

			  }

			//首页  
			private function first() {  
			global $Parameterse;
				if ($this->page > $this->bothnum+1) {  
					return '<a href="'.$this->url.'">1</a><div class="ourphp_dian">...</div>';  
				}  
			}  
			
			//尾页  
			private function last() {  
			global $Parameterse;
				if ($this->pagenum - $this->page > $this->bothnum) {  
					return '<div class="ourphp_dian">...</div><a href="'.$this->url.'&page='.$this->pagenum.'">'.$this->pagenum.'</a>';  
				}  
			}  

			//分页信息  
			public function showpage() { 
				global $Parameterse,$ourphp_Language;
				$font = explode("\r\n",$Parameterse['pagefont']);
				foreach($font as $op)
				{
				  if(strpos($op,$ourphp_Language) !== false)
				  {
					$o = explode('|',str_replace("}","",$op));
				  }
				}
		  
				$_page = '';
				$_page .= $this->prev(); 
				$_page .= $this->first();  
				$_page .= $this->pageList();  
				$_page .= $this->last();
				$_page .= $this->next();
				
				return '
				
				<style type="text/css">
					.ourphp_page {
						margin: 10px 5%;
						float: left;
						clear: both;
						width: 84%;
						background:#f1f1f1;
						padding:6px 3%;
						border-radius:20px;
					}

					.ourphp_page a {
						padding: 5px 11px;
						float: left;
						margin:0 5px;
					}

					.ourphp_page .me {
						padding: 5px 11px;
						float: left;
						background: #D1D1D1;
						color: #fff;
						border-radius:50%;
					}
					
					.ourphp_page .me2 {
						padding: 5px 3px;
						float: left;
						color: #333;
						font-size:12px;
					}

					.ourphp_page a:hover {
						background: #D1D1D1;
						color: #666;
						border-radius:50%;
					}

					.ourphp_dian {
						padding: 5px 11px;
						float: left;
						color: #666;
					}
				</style>
				
				<div class="ourphp_page"><a class="me2">'.$o[2].'</a>'.$_page.'</div>
				
				'; 
			}  
}
?>