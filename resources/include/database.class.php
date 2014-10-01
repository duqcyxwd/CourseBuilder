<?php
	class database
	{
		function __construct($db_hostname, $db_username, $db_password, $db_name)
		{
			$this->mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

			if ($this->mysqli->connect_errno) {
			    die("Failed to connect to MySQL: " . $this->mysqli->connect_error);
			}
		}

		function getRowsFromTable($table)
		{
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}
	}
?>
