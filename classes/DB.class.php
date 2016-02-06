<?php
/*
* database class - only one connection alowed
*/
class DB {
	private static $_connection;
	private static $_instance; //The single instance
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		$config = parse_ini_file('../config/config.ini');
		$conn_string = "pgsql:host={$config['host']} port={$config['port']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}  options='--client_encoding=UTF8'";
		try{
			self::$_connection = new PDO($conn_string)  or die('connection failed');
			//turn on exceptions
			self::$_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException  $e ){
			echo "Error: ".$e;
		}

		
	
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public static function getConnection() {
		return self::$_connection;
	}
}
