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
		
		function execute($sql)
		{
			return mysqli_query($this->mysqli, $sql);
		}
		
		function getError()
		{
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
			return mysqli_query($this->mysqli,"SELECT `Subject`,`CourseNumber`, `YearRequirement`, `Program` FROM `ProgramsRequirement` WHERE `Program` = '$program'");
		}

		function getPrerequisiteTree($program)
		{
			$courses = array();
			$result = $this->getCourseInfo($program);
			while ($row = mysqli_fetch_array($result)){
				$courses[] = $row;
			}

			foreach ($courses as $key => $course) {
				$term = '' . $course['YearRequirement'];
				if (isset($courseArray[$term]))
					array_push($courseArray[$term], $course['Subject'] . " " . $course['CourseNumber']);
				else
					$courseArray[$term] = array($course['Subject'] . " " . $course['CourseNumber']);
			}

			return $courseArray;
		}

		function getElectives($program)
		{
			// todo
		}

		function getListOfPrograms()
		{
			$result = $this->getDistinctFromTable("Program", "ProgramsRequirement");
			$programList = array();
			while ($row = mysqli_fetch_array($result))
				$programList[] = $row['Program'];
			
			return $programList;
		}

		// Giving a list of Classes and return a list of classes open in this term
		function getClasses()
		{
			$sql = "SELECT * FROM `Classes`";
			$classes = $this->execute($sql);
			return $classes;
		}

	}

?>
