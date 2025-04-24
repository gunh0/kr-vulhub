<?php
/*
 * Ourphp - CMS建站系统
 * Copyright (C) 2014 ourphp.net
 * 开发者：哈尔滨伟成科技有限公司
 * -------------------------------
 * Mysql 数据库类 (2016-10-22)
 * -------------------------------
 */
 
class OurPHP_Mysql{
	
	private $dburl;
	private $dbname;
	private $dbpass;
	private $dbtabel;
	private $dblang = "utf8";
	private $conn;
	
	function __construct($dburl = '',$dbname = '',$dbpass = '',$dbtabel = ''){
		$this -> conn = mysql_connect($dburl,$dbname,$dbpass);
		mysql_select_db($dbtabel,$this -> conn) or die('数据库链接出错: ' . $this -> error());
		mysql_query('set names '.$this -> dblang,$this -> conn);
	}
	
	//读取一条记录
	public function select($o = '',$u = '',$r = ''){
		$Query = mysql_query("select ".$o." from ".$u." ".$r);
		$Rs = mysql_num_rows($Query);
		if($Rs < 1){
			return false;
		}else{
			return mysql_fetch_array($Query);
		}
		mysql_free_result($Query);
		$this -> close();
	}
	
	//插入记录方式一
	public function insert($o = '',$u = '',$r = ''){
		$Query = mysql_query("insert into ".$o." set ".$u." ".$r);
		return $Query;
		$this -> close();
	}
	
	/*
		插入记录方式二(支持一次插入多条)
		组合记录:
		$info = array(
			"table" => "OP_Class,OP_Lang,OP_Title",
			"data" => array(
							  array(13,"cn","插入测试"),
							  array(14,"cn","插入测试"),
							  array(15,"cn","插入测试"),
					  )
		);
		插入记录:
		$db -> insertarray($db->datatable("product"),$info,"");
	*/
	public function insertarray($o = '',$u = '',$r = ''){
		if($u == '')
		{
			return false;
		}
		
		$field = $u['table'];
		$sqllist = '';
		foreach($u["data"] as $op)
		{
			$value = '';
			$sqllist .= '(';
			foreach($op as $opop)
			{
				$value .= "'".$opop."',";
			}
			
			$sqllist .= arrend($value,",").'),';
		}
		
		$Query = mysql_query("INSERT INTO ".$o." (".$field.") VALUES ".arrend($sqllist,",")." ".$r);
		return $Query;
		$this -> close();
	}
	
	//删除记录
	public function del($o = '',$u = ''){
		$Query = mysql_query("delete from ".$o." ".$u);
		return $Query;
		$this -> close();
	}
	
	//更新记录
	public function update($o = '',$u = '',$r = ''){
		$Query = mysql_query("update ".$o." set ".$u." ".$r);
		return $Query;
		$this -> close();
	}
	
	//记录集
	public function listgo($o = '',$u = '',$r = ''){
		$Query = mysql_query("select ".$o." from ".$u." ".$r);
		return $Query;
		mysql_free_result($Query);
		$this -> close();
	}
	
	//循环记录集
	public function whilego($o = '',$u = 1){
		if($u == 1){
			return mysql_fetch_array($o);
		}
		if($u == 2){
			return mysql_fetch_row($o);
		}
		if($u == 3){
			return mysql_fetch_assoc($o);
		}
	}
	
	//取记录数 and 取前一次MYSQL记录行数
	public function rows($o = '', $u = 1){
		return mysql_num_rows($o);
	}
	
	//创建表 and 执行SQL
	public function create($o = '',$u = 1){
		if($o == '' || $o == ';')
		{
			return false;
		}
		if($u == 1){
			$Query = mysql_query("create table ".$o);
		}
		if($u == 2){
			$Query = mysql_query($o,$this -> conn);
		}
		return $Query;
		$this -> close();
	}
	
	//删除表
	public function drop($o = ''){
		$Query = mysql_query("DROP TABLE ".$o);
		return $Query;
	}
	
	//其它函数
	public function other($o = '',$u = '',$r = ''){
		if($o == '' || $u == ''){
			return false;
		}else{
			switch($o){
				case "escape_string":
					return mysql_escape_string($u);
				break;
				case "num_fields":
					return mysql_num_fields($u);
				break;
				case "field_flags":
					return mysql_field_flags($u,$r);
				break;
			}
		}
	}
	
	//返回上一步 INSERT 操作产生的 ID
	public function insertid(){
		return mysql_insert_id($this -> conn);
		$this -> close();
	}
	
	//自动填充数据表名
	public function datatable($o = ''){
		global $ourphp;
		return $ourphp['prefix'].$o;
		$this -> close();
	}
	
	//错误提示
	public function error(){
		return mysql_error($this -> conn);
	}
	
	//关闭数据库
	public function close(){
		//mysql_close($this -> conn);
	}
}
?>