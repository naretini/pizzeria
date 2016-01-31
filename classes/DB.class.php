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
		$conn_string = "host={$config['host']} port={$config['port']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}  options='--client_encoding=UTF8'";


		self::$_connection = pg_connect($conn_string)  or die('connection failed');
	
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return self::$_connection;
	}
}
