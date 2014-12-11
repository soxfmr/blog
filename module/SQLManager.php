<?php
	class SQLManager {
		private $conn = null;

		function __construct($host, $port, $user, $pwd, $dbname)
		{
			$this->conn = $this->connect($host, $port, $user, $pwd, $dbname);
		}

		private function connect($host, $port, $user, $pwd, $dbname)
		{
			$conn = false;
			if($dbname) {
				$conn = @mysql_connect($host . ":" . $port, $user, $pwd);
				if($conn) {
					$this->query("set names utf8");
					@mysql_select_db($dbname, $conn);
				}
			}			
			return $conn;
		}

		function query($sqlStr)
		{
			$Ret = false;
			if($this->conn) 
				$Ret = @mysql_query($sqlStr, $this->conn);
			return $Ret;
		}

		function close() {
			if($this->conn) mysql_close($this->conn);
		}

		function is_connect() {
			return $this->conn ? true : false;
		}
	}
?>