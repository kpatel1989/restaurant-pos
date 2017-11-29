<?php
class _database {
	private $link		= false;
	private $result		= false;
	private $row		= false;

	public $settings	= array(
			"servername"=> "localhost",
			"serverport"=> "3306",
			"username"	=> "root",
			"password"	=> "mysql",
			"database"	=> "restaurantpos",
			"persist"	=> false,
			"dieonerror"=> false,
			"showerror"	=> false,
			"error_file"=> true
		);

	function __construct() {
	}
	
	function connect(){
		return mysqli_connect($this->settings["servername"], $this->settings["username"], $this->settings["password"],$this->settings["database"]);
	}
}
?>