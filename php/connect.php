<?php

class login_connect {
	private $conn;

	//Connecting to database
	public function connect(){
		include("define.php");

		//Connecting to mysql database
		$this->conn = mysqlI_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		//return database object
		return $this->conn;
	}
}

?>