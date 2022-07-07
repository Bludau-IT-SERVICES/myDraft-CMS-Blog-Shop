<?php

class DBi {
    public static $conn;
	static function mysql_escape($string,$conn) { 
	
		return mysqli_real_escape_string($conn,$string);
	}
	static function mysql_num_row($conn) { 
	
		return mysqli_num_rows($conn);
	}
}
?>