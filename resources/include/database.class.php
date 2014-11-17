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
			return mysqli_query($this->mysqli,"SELECT `Subject`,`CourseNumber`, `yearRequirement`, `program` FROM `ProgramsRequirement` WHERE program = '$program'");
		}

		function getPrerequisiteTree($program) {
			include(INCLUDE_PATH . "/constants.php");

			$courses = array();
			$result = $this->getCourseInfo($program);

			while ($row = mysqli_fetch_array($result)){
				$courses[] = $row;
			}

			$courseArray = array(array(), array(), array(), array(), array(), array(), array(), array());
			foreach ($courses as $key => $course) {
				$term = $course['yearRequirement'];
				array_push($courseArray[$term], $course['Subject'] . " " . $course['CourseNumber']);
			}

			return $courseArray;
		}

		function getListOfPrograms() 
		{
			$result = $this->getDistinctFromTable("program", "ProgramsRequirement");
			$programList = array();
			while ($row = mysqli_fetch_array($result))
				$programList[] = $row['program'];
			
			return $programList;
		}

	}

?>
