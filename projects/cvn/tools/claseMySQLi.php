<?php

$phpversion = phpversion();
class db {
	var $link_id	= 0;
	var $query_id	= 0;
	var $record	= array();
	var $errdesc	= '';
	var $errno	= 0;
	var $version	= '';
	var $show_error	= 1;
	
	var $server	= '';
	var $user	= '';
	var $password	= '';
	var $database	= '';
	
	
	function db($server, $user, $password, $database) {
		$this->server = $server;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;			
		$this->connect();		
		$this->password = $password;
		$password = '';
	}    
	
	function connect() {
		$this->link_id = mysqli_connect($this->server, $this->user, $this->password, $this->database);
		if (!$this->link_id) $this->error();
		//if ($this->database != '') $this->select_db($this->database);
	}
	
	function close(){
		 mysqli_close($this->link_id);
    }
	
	function geterrdesc() {
		$this->error = mysqli_error();
		return $this->error;
	}
	
	function geterrno() {
		$this->errno = mysqli_errno();
		return $this->errno;
	}
	
	function getversion() {
		if ($this->link_id) list($this->version) = $this->query_first("SELECT VERSION()", 0, 0, MYSQL_BOTH, 0);
		if (!$this->version) $this->version = "Desconocida";
		return $this->version;
	}
	
	function real_escape_string($cadena) {
		$cadena=mysqli_real_escape_string($cadena);
		return $cadena;
	}
	
	function select_db($database = '') {
		if ($database != '') $this->database = $database;
		if (!mysqli_select_db($this->database, $this->link_id)) $this->error();
	}
	
	function query($query_string, $limit = 0, $offset = 0, $showerror = 1) {
		if ($limit != 0) $query_string .= " LIMIT $offset, $limit";
		$this->query_id = mysqli_query($this->link_id,$query_string);
		if ($showerror == 1 && !$this->query_id) $this->error("SQL no válido: ".$query_string);
		return $this->query_id;
	}
	
	
	function fetch_array($query_id = -1, $type = MYSQL_BOTH) {
		if ($query_id != "-1") $this->query_id = $query_id;
		$this->record = mysqli_fetch_array($this->query_id, $type);
		return $this->record;
	}
	
	function fetch_row($query_id = -1) {
		if ($query_id != -1) $this->query_id = $query_id;
		$this->record = mysqli_fetch_row($this->query_id);
		return $this->record;
	}
	
	function query_first($query_string, $limit = 0, $offset = 0, $type = MYSQL_BOTH, $showerror = 1) {
		$this->query($query_string, $limit, $offset, $showerror = 1);
		$returnarray = $this->fetch_array($this->query_id, $type);
		return $returnarray;
	}
	
	function num_rows($query_id = -1) {
		if ($query_id != "-1") $this->query_id = $query_id;
		return mysqli_num_rows($this->query_id);
	}
	
	function affected_rows() {
		return mysqli_affected_rows($this->link_id);
	}
	
	function insert_id() {
		return mysqli_insert_id($this->link_id);
	}
	
	function error() {
		$this->errdesc = mysqli_error();
		$this->errno = mysqli_errno();
		
		$errormsg = "<b>Error en la base de datos:</b> $errormsg\n<br>";
		$errormsg .= "<b>Error mysql:</b> $this->errdesc\n<br>";
		$errormsg .= "<b>Error mysql número:</b> $this->errno\n<br>";
		$errormsg .= "<b>Versión mysql:</b> ".$this->getversion()."\n<br>";
		$errormsg .= "<b>Versión php:</b> ".phpversion()."\n<br>";
		$errormsg .= "<b>Fecha:</b> ".date("d.m.Y @ H:i")."\n<br>";
		$errormsg .= "<b>Script:</b> ".getenv("REQUEST_URI")."\n<br>";
		$errormsg .= "<b>Referer:</b> ".getenv("HTTP_REFERER")."\n<br><br>";
		
		if ($this->show_error) $errormsg = "$errormsg";
		else $errormsg = "\n<!-- $errormsg -->\n";
		//die("</table><font face=\"Verdana\" size=2><b>SQL-DATABASE ERROR</b><br><br>".$errormsg."</font>");
		die("Parando SQLi de ".$_SERVER['REMOTE_ADDR']);
	}
	
	function data_seek($result, $nr) {
		if (!mysqli_data_seek($result, $nr)) $this->error("No puedo buscar la fila $i en el resultado");
	}
}
?>