<?php
	class DataBase
	// TODO: factory Queries code to handle sql error
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


		function getDistinctFromTable($rows, $table) 
		{
			return mysqli_query($this->mysqli, "SELECT DISTINCT $rows FROM $table");
		}

		function getRowsFromTableWithParms($rows="*", $table, $parms="1") 
		{
			return mysqli_query($this->mysqli, "SELECT $rows FROM $table WHERE $parms");
		}

		function getPrerequisiteTree($program) {
			$courses = array();
			$result = mysqli_query($this->mysqli,"SELECT `Subject`,`CourseNumber`, `yearRequirement`, `program` FROM `ProgramsRequirement` WHERE program = '$program'");

			while ($row = mysqli_fetch_array($result)){
				$courses[] = $row;
			}
			// TODO: simplify this....
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

		// Giving a list of Classes and return a list of classes open in this term
		function getOpeningClasses()
		{
			$term = getCurrentTerm();
			$sql = "SELECT DISTINCT `Subject`, `CourseNumber` FROM Classes WHERE `TERM` = \"".$term."\"";
			$result = mysqli_query($this->mysqli, $sql);
			while ($row = mysqli_fetch_array($result)){
				$classes[] = $row;
			}
			return $classes;
		}


		function getCourseInfoByCourseArray($courseArray) {
			$result = [];
			if (sizeof($courseArray) >0) {
				$sql = "SELECT * FROM Classes WHERE 0 ";
				foreach ($courseArray as $course) {
					$courseTemp = explode(" ", $course);
					$sql .= "OR (`Subject` = \"".$courseTemp[0]."\" AND "
						."`CourseNumber` = \"".$courseTemp[1]."\") ";
				}
				$queryResult = mysqli_query($this->mysqli, $sql);
				while ($row = mysqli_fetch_array($queryResult)){
					$result[] = $row;
				}
			}
			return $result;
		}
	}

?>
