<?php
	class database
	{
		function __construct($mysqli)
		{
			$this->mysqli = $mysqli;
		}

		function getRowsFromTable($table)
		{
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}
	}
?>
