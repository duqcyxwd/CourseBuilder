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

		function getYearStanding($completedCourses, $program)
		{
			$sql = "SELECT YearRequirement 
							FROM ProgramsRequirement 
							WHERE Program = '$program' 
							AND concat(Subject, ' ', CourseNumber)
							NOT IN (";

			foreach ($completedCourses as $course) {
				$sql .= "'$course',";
			}

			$sql = trim($sql, ",") . ") LIMIT 1";

			$result = $this->execute($sql);

			if ($row = mysqli_fetch_array($result)) {
				$term = $row['YearRequirement'];

				// convert term to year status
				if ($term % 2 == 0) // even
					return ($term + 2) / 2;
				else // odd
					return ($term + 1) / 2;

			} else {
				return 0;
			}

		}


		function getEligibleCourses($completedCourses, $program, $yearStanding) {

			$eligibleCourses = [];

			// first get all the courses of the entire program
			$requirementsTable = "ProgramsRequirement";

			$sql = "SELECT ProgramsRequirement.Subject, ProgramsRequirement.CourseNumber, Requirement 
							FROM ProgramsRequirement
							INNER JOIN Prerequisite
							ON ProgramsRequirement.Subject=Prerequisite.Subject 
							AND ProgramsRequirement.CourseNumber=Prerequisite.CourseNumber
							WHERE YearRequirement >= $yearStanding AND Program = '$program'";

			$result = $this->execute($sql);

			while ($row = mysqli_fetch_array($result)){

				// determine which courses can be taken
				$requirement = $row['Requirement'];

				if ($requirement == '') { // no requirements
					$isEligible = true;
				} else {

					$isEligible = false;

					// Check if year status requirement TODO: INCOMPLETE
					if (strpos($requirement, '-year status')) {
						preg_match('/(\w+)-year status in Engineering/', $requirement, $matches);

						if (count($matches) > 1) {
							// todo
						}
					}

					// split by 'and'
					$requirement = preg_split('/(and)/', $requirement);

					// evaluate each and
					foreach ($requirement as $courses) {
						// split by 'or'
						$courses = preg_split('/(or)/', $courses);

						foreach ($courses as $course) {
							if (in_array(trim($course), $completedCourses)) {
								$isEligible = true;
							}
						}
					}
				}

				if ($isEligible) {
					array_push($eligibleCourses, $row['Subject'] . " " . $row['CourseNumber']);
				}
			}

			return $eligibleCourses;
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
				$courseTitle = $course['Subject'] . " " . $course['CourseNumber'];

				// CLEAN THIS UP

				// if ($course['Subject'] == 'Elective') {
				// 	$courseTitle = $this->getElectives($course['CourseNumber']);
				// }

				if (isset($courseArray[$term])) {
					array_push($courseArray[$term], $courseTitle);
				} else {
					$courseArray[$term] = array($courseTitle);
				}
			}

			return $courseArray;
		}


		function getElectives($electiveType, $coureTitle)
		{
			$electives = $courseTitle . " : [";
			// $electives = array();

			$sql = "SELECT * FROM Electives WHERE ElectiveType='$electiveType'";
			$result = $this->execute($sql);
			while ($row = mysqli_fetch_array($result)){
				$electives .= $row['Subject'] . " " . $row['CourseNumber'] . ",";
				// array_push($electives, $row['Subject'] . $row['CourseNumber']);
			}

			$electives = trim($electives, ",") . "]";
			// print_r($electives);
			return $electives;
		}

		function getElectivesByProgram($program)
		{
			$electives = [];
			// $sql = "SELECT DISTINCT CourseNumber FROM `ProgramsRequirement` where Subject = 'Elective'";
			$sql = "SELECT DISTINCT CourseNumber FROM `ProgramsRequirement` where Subject = 'Elective' and Program ='$program'";
			$result = $this->execute($sql);
			while ($row = mysqli_fetch_array($result)){
				$electiveTypes[] = $row['CourseNumber'];
			}
			foreach ($electiveTypes as $key => $electiveType) {
				$electives = array_merge($electives, ($this->getElectivesByType($electiveType)));
			}
			return $electives;
		}

		function getElectivesByType($electiveType, $trueElectiveType="", $trueElectiveName="", $sql="")
		{
			if ($sql == "") {
				$sql = "SELECT * FROM Electives WHERE ElectiveType='$electiveType'";
			}
			$result = $this->execute($sql);
			$electives = [];
			while ($row = mysqli_fetch_array($result)){
				if ($row['Subject'] != "Elective") {
					if ($trueElectiveType == "") {
						$electives[] = [$row['Subject']." ".$row["CourseNumber"], $row['ElectiveName'], $electiveType];
					} else {
						$electives[] = [$row['Subject']." ".$row["CourseNumber"], $trueElectiveName, $trueElectiveType];
					}
				} else {
					// pprint($row);
					if ($row['CourseNumber'] == 3001) {
						$electives = array_merge($electives, $this->getElectivesByType($row['CourseNumber'], $electiveType, $row['ElectiveName']));
					} else if ($row['CourseNumber'] == 8883 || $row['CourseNumber'] == 7773) {
						if ($row['CourseNumber'] == 8883) {
							// 'SYSC at 3000 or 4000 level'
							$Subject = "SYSC";
						} else  {
							$Subject = "ELEC";
							// 'ELEC at 3000 or 4000 level'
						}
						$sql2 = "SELECT DISTINCT Subject, CourseNumber FROM `Classes` where Subject = '$Subject' and CourseNumber > 3000";
						
						if ($trueElectiveType == "") {
							$electives = array_merge($electives, $this->getElectivesByType($row['CourseNumber'], $electiveType, $row['ElectiveName'], $sql2));
						} else {
							$electives = array_merge($electives, $this->getElectivesByType($row['CourseNumber'], $trueElectiveType, $trueElectiveName, $sql2));
						}
						

					} else if($row['CourseNumber'] == 9993) {
						$electives = array_merge($electives, $this->getElectivesByType($row['CourseNumber'], $electiveType, $row['ElectiveName']));
					}


					// (9993, 'Elective', 8883, 'SYSC at 3000 or 4000 level'),
					// (9993, 'Elective', 7773, 'ELEC at 3000 or 4000 level'),
					// (3002, 'Elective', 3001, 'SE Note B'),
					// (2001, 'Elective', 9993, 'Computer System Engineering Elective B');
				}
			}
			return $electives;
		}

		function getListOfPrograms()
		{
			$result = $this->getDistinctFromTable("Program", "ProgramsRequirement");
			$programList = array();
			while ($row = mysqli_fetch_array($result))
				$programList[] = $row['Program'];
			
			return $programList;
		}


		// return a list of Classes and return a list of classes open in this term
		function getOpeningClasses()
		{
			$term = getCurrentTerm();
			$sql = "SELECT DISTINCT `Subject`, `CourseNumber` FROM Classes WHERE `TERM` = \"".$term."\"";
			$result = mysqli_query($this->mysqli, $sql);
			while ($row = mysqli_fetch_array($result)){
				$classes[] = $row['Subject']." ".$row['CourseNumber'];
			}

			return $classes;
		}

		function getCouseTitleByCourseArray($courseArray) {
			$result = [];
			$term = getCurrentTerm();
			if (sizeof($courseArray) >0) {
				$sql = "SELECT * FROM Courses WHERE( 0 ";
				foreach ($courseArray as $course) {
					$courseTemp = explode(" ", $course);

					if (count($courseTemp) < 2) return; // TODO: NEED TO HANDLE THIS

					$sql .= "OR (`Subject` = \"".$courseTemp[0]."\" AND "
						."`CourseNumber` = \"".$courseTemp[1]."\") ";
				}

				$sql .= ")";

				$queryResult = mysqli_query($this->mysqli, $sql);
				while ($row = mysqli_fetch_array($queryResult)){
					$result[] = [$row['Subject']." ".$row['CourseNumber'], $row['CourseTitle']];
				}
			}
			return $result;
		}


		function getCourseInfoByCourseArray($courseArray) {
			$result = [];
			$term = getCurrentTerm();
			if (sizeof($courseArray) >0) {
				$sql = "SELECT * FROM (SELECT Classes.CourseNumber, Classes.Subject, Classes.Start_Time, Classes.End_Time, Classes.Days, Classes.RoomCap, Classes.Type, Classes.Section, Classes.Term, Courses.CourseTitle FROM Classes INNER JOIN Courses ON Classes.Subject = Courses.Subject AND Classes.CourseNumber = Courses.CourseNumber) AS p WHERE `TERM` = \"".$term."\" AND ( 0 ";
				// $sql = "SELECT * FROM Classes WHERE `TERM` = \"".$term."\" AND ( 0 ";
				foreach ($courseArray as $course) {
					$courseTemp = explode(" ", $course);

					if (count($courseTemp) < 2) return; // TODO: NEED TO HANDLE THIS

					$sql .= "OR (`Subject` = \"".$courseTemp[0]."\" AND "
						."`CourseNumber` = \"".$courseTemp[1]."\") ";
				}

				$sql .= ")";

				$queryResult = mysqli_query($this->mysqli, $sql);
				while ($row = mysqli_fetch_array($queryResult)){
					$result[] = $row;
				}
			}
			return $result;
		}
	}

?>
