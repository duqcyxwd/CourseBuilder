<?php
	class DataBase
	{
		function __construct($hostname = NULL, $username = NULL, $password = NULL, $name = NULL)
		{
			$this->mysqli = new mysqli($hostname, $username, $password, $name);

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

		function getCourseInfor($program = 'Biomedical and Electrical Engineering')
		{
			return mysqli_query($this->mysqli,"SELECT `subject`,`code`, `term`, `program` FROM `programs` WHERE program = '$program'");
		}
	}
?>
