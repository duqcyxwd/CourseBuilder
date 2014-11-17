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

		function getAllRowsFromTable($table)
		{
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}
		
		function execute($sql){
			return $this->connection->query($sql);
		}
		
		function getError(){
			return mysqli_error($this->connection);
		}

		function getDistinctFromTable($rows, $table) 
		{
			return mysqli_query($this->mysqli, "SELECT DISTINCT $rows FROM $table");
		}

		function getRowsFromTableWithParms($rows="*", $table, $parms="1") 
		{
			return mysqli_query($this->mysqli, "SELECT $rows FROM $table WHERE $parms");
		}

		function getCourseInfo($program)
		{
			return mysqli_query($this->mysqli,"SELECT `subject`,`code`, `term`, `program` FROM `programs` WHERE program = '$program'");
		}
	}
?>
