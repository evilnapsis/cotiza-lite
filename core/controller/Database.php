<?php
class Database {
	public static $db;
	public static $con;

	function __construct(){
//		$this->user="root";$this->pass="";$this->host="localhost";$this->ddbb="lbmin"; // Windows
		$this->user="root";$this->pass="";$this->host="localhost";$this->ddbb="cotizalite"; // Linux
//		$this->user="ymqisyda_cotiza";$this->pass="C0t1z4cion@";$this->host="localhost";$this->ddbb="ymqisyda_cotiza"; // Linux
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
