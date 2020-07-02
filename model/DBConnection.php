<?php
// This is the DBConnection class which sets the
// db connection. This is required in all model
// classes.

class DBConnection {
	public $pdo;

	public function __construct(){
		$user = 'root';
		$pass = 'root';
		$dsn = 'mysql:host=localhost;dbname=test_php';
		$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
						);
		$this->pdo = new PDO($dsn, $user, $pass, $opt);
	}
}

?>