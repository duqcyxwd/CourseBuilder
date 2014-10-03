<?php
	class DataBase
	{
		function __construct($db_hostname = NULL, $db_username = NULL, $db_password = NULL, $db_name = NULL)
		{
			$this->mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

			if ($this->mysqli->connect_errno) {
				throw new Exception('Could not connect to database.');
			}
		}

		function getRowsFromTable($table)
		{
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}

		function getProgramList()
		{
			return mysqli_query($this->mysqli,"SELECT DISTINCT program FROM programs");
		}

		function getCourse($program = 'Biomedical and Electrical Engineering')
		{
			return mysqli_query($this->mysqli,"SELECT `subject`,`code`, `term`, `program` FROM `programs` WHERE program = 'Biomedical and Electrical Engineering'");
		}
	}
?>
