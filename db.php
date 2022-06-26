<?php

class DB {
  
	private static $connection = null;
	private $dbh;

  private function __construct() {
    
		try {
			$this->dbh = new PDO("mysql:host=".HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
			$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$db_config = null;
		} catch (Exception $e) {
			die("Error establishing a database connection.");
		}

  }

  public static function get_instance() {
    if (empty(self::$connection) || self::$connection == null || !is_a(self::$connection, 'DB')) {
      self::$connection = new DB();
    }
    return self::$connection->dbh;
  }

}

?>