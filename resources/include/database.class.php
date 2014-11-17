<?php
	class database
	{
		function __construct($db_hostname, $db_username, $db_password, $db_name)
		{
			@$this->mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

			if ($this->mysqli->connect_errno) {
				throw new Exception('Could not connect to database.');
			}
		}

		function getRowsFromTable($table)
		{
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}
		
		function execute($sql){
			return $this->connection->query($sql);
		}
		
		function getError(){
			return mysqli_error($this->connection);
		}
	}
?>
