<?php
	class DataBase
	// TODO: factory Queries code to handle sql error
	{

		function __construct($hostname = NULL, $username = NULL, $password = NULL, $name = NULL) {
			$this->mysqli = new mysqli($hostname, $username, $password, $name);

			if ($this->mysqli->connect_errno) {
				throw new Exception('Could not connect to database.');
			}
		}


		function getAllRowsFromTable($table) {
			return mysqli_query($this->mysqli, "SELECT * FROM $table");
		}

		function execute($sql) {
			return mysqli_query($this->mysqli, $sql);
		}
		

		function getError() {
			return mysqli_error($this->connection);
		}


		function getDistinctFromTable($rows, $table) {
			return mysqli_query($this->mysqli, "SELECT DISTINCT $rows FROM $table");
		}


		function getRowsFromTableWithParms($rows="*", $table, $parms="1") {
			return mysqli_query($this->mysqli, "SELECT $rows FROM $table WHERE $parms");
		}


		function getCourseInfo($program) {
			return mysqli_query($this->mysqli,"SELECT `Subject`,`CourseNumber`, `YearRequirement`, `Program` FROM `ProgramsRequirement` WHERE `Program` = '$program'");
		}

		function getYearStanding($completedCourses, $program) {
			$first_year = [];
			$second_year = [];
			$third_year = [];

			$yearStanding = 1;

			$sql = "SELECT * 
					FROM ProgramsRequirement 
					WHERE Program = '$program' 
					AND Subject != 'Elective'";

			$result = $this->execute($sql);
			
			while($row = mysqli_fetch_array($result)){
				$term = $row['YearRequirement'];

				$course = $row['Subject']." ".$row['CourseNumber'];

				if($row['YearRequirement'] < 2 ){
					array_push($first_year, $course);
				}
				elseif($row['YearRequirement'] < 4){
					array_push($second_year, $course);
				}
				elseif($row['YearRequirement'] < 6){
					array_push($third_year, $course);
				}
				else{}

			}
		
			if($this->yearCompleted($first_year,$completedCourses) == true){
				$yearStanding = 2;
			}
			else{ 
				return $yearStanding; 
			}

			//for second year specifications
			$count = 0;
			foreach ($second_year as $course) {
				if(in_array($course, $completedCourses)){
					$count++;
				}
			}
			if($count >= 8){
				$yearStanding = 3;
			}else{

				return $yearStanding;
			}

			if($this->yearCompleted($second_year,$completedCourses)){
				$count = 0;
				foreach ($third_year as $course) {
					if(in_array($course, $completedCourses)){
						$count++;
					}
				}
				if($count >= 7){
					$yearStanding = 4;
				}else{

					
					return $yearStanding;
				}
			}

			return $yearStanding;
		}

		function yearCompleted($requiredCourses,$completedCourses) {
			foreach ($requiredCourses as $course) {
				if(in_array($course, $completedCourses)){
					continue;
				}
				else{
					return false;
				}
			}

			return true;
		}


		function getPrerequisiteTree($program) {
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


		function getElectives($electiveType, $coureTitle) {
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

		function getElectivesByProgram($program) {
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

		function getElectivesByType($electiveType, $trueElectiveType="", $trueElectiveName="", $sql="") {
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

		function getListOfPrograms() {
			$result = $this->getDistinctFromTable("Program", "ProgramsRequirement");
			$programList = array();
			while ($row = mysqli_fetch_array($result))
				$programList[] = $row['Program'];
			
			return $programList;
		}


		// return a list of Classes and return a list of classes open in this term
		function getOpeningClasses() {
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


		/*
		* registers a student for classes in bulk. If a student can register
		* in all classes it returns TRUE. if there is an error registering for
		* one of the classes it return FALSE.
		*/

		function registerForClasses($courseList){

			$array = $this->checkCourseAvailability($courseList);
			if(count($array) == 0){
			
				$courses = explode(";", $courseList);
				foreach ($courses as $course) {

					if ($course != "") { // ignore empty strings
						$course_info = explode(" ", $course);

						$sql = "UPDATE Classes
					        SET RoomCap = RoomCap - 1
					        WHERE Subject = '$course_info[0]' AND CourseNumber = '$course_info[1]' AND Section='$course_info[2]'";

					    $this->execute($sql);
					}
				}
				return [];
			}
			else{
				return $array;
			}
		}
		


		/*
			return an array of Course Object
		 */
		function getCourseArray($courseCompleted, $prerequisiteTree, $program) {

			$yearStanding = $this->getYearStanding($courseCompleted, $program);
			// function checkRequirement($requirement, $completedCourses)

			$openingClasses = $this->getOpeningClasses();

			$requiredCourses = flatArray($prerequisiteTree);

			// get courses that uncompleted in prerequisite Tree
			$unCompletedCourse = array_diff($requiredCourses, $courseCompleted);

			// get course that unCompletedCourses open in current term
			// check if this course open or not
			$unCompletedOpeningCourses = array_diff($unCompletedCourse, array_diff($unCompletedCourse, $openingClasses));
			// TODO: RECOMMENT BELOW
			// $unCompletedOpeningCourses = $this->filterCourseListByPrerequisite($courseCompleted, $yearStanding, $unCompletedOpeningCourses);

			// return $unCompletedOpeningCourses;

			$classInfo = $this->getCourseInfoByCourseArray($unCompletedOpeningCourses);

			$courseArray = createCourseArray($unCompletedOpeningCourses, $classInfo);

			return $courseArray;
		}


		function filterCourseListByPrerequisite($courseCompleted, $yearStanding, $unCompletedCourses)
		{
			$sql = "SELECT `Subject`, `CourseNumber`, `Requirement`, `YearReq` FROM Prerequisite";
			$result = mysqli_query($this->mysqli, $sql);
			$filteredResult = [];
			while ($row = mysqli_fetch_array($result)){
				foreach ($unCompletedCourses as $key => $course) {
					if ($row['Subject']." ".$row['CourseNumber'] == $course) {
						if ($row['YearReq'] > $yearStanding) {
							break;
						} {
							$filteredResult[] = [$course, $row['Requirement']];
							break;
						}
					}
				}
			}


			$result = [];
			foreach ($filteredResult as $key => $coursRequiremnt) {
				$requirement = $coursRequiremnt[1];
				if (!checkRequirement($requirement, $courseCompleted)) {
					unset($filteredResult[$key]);
				} else {
					$result[] = $coursRequiremnt[0];
				}
			}

			return $result;
		}

		/*
			return an array of Course Object
		 */
		function createCourseArrayBySelectCourse($courseForTable) {
			$result = [];
			$classesInfo = $this->getCourseInfoByCourseArray($courseForTable);
			if (count($courseForTable) == 0) return []; // TODO: WHAT IF NO COURSES SELECTED?

			foreach ($courseForTable as $number => $singleCourse) {
				$course = new Course($singleCourse);
				foreach ($classesInfo as $classInfo) {
					if ($singleCourse == $classInfo["Subject"]." ".$classInfo["CourseNumber"]){
						$course->addClass($classInfo);
					} 
				}
				array_push($result, $course);
			}

			return $result;
		}


		/**
		 * checkCourseAvailability
		 *
		 * determine if list of courses are not full
	 	 *
	 	 * @param courseList: list of courses to check
	 	 * @return array containing courses that are
	 	 *				 no longer available
		 */
		function checkCourseAvailability($courseList) {
			
			$coursesNoLongerAvailable = [];

			$courses = explode(";", $courseList);
			foreach ($courses as $course) {

				if ($course != "") { // ignore empty strings
					$course_info = explode(" ", $course);

					$sql = "SELECT * FROM Classes
					        WHERE Subject = '$course_info[0]' AND CourseNumber = '$course_info[1]' AND Section='$course_info[2]'
					        AND RoomCap = 0 LIMIT 1";

					$queryResult = $this->execute($sql);
					if (mysqli_num_rows($queryResult) != 0) {
						$coursesNoLongerAvailable[] = $course; // add course
					}
				}
			}

			return $coursesNoLongerAvailable;
		}

		function getAllAdmins() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('Administrators');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['Username']  . "','" . $row['Password'] .  "'\n";
			}

			return $retVal;
		}

		function getAllClasses() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('Classes');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['Subject']  . "','" . $row['CourseNumber']  . "','" 
								. $row['Start_Time']  . "','" .$row['End_Time']  . "','" 
								. $row['Days']  . "','" . $row['RoomCap']  . "','" . $row['Professor']  . "','" 
								. $row['Type']  . "','" .$row['Section']  . "','" . $row['Term'] . "'\n";
			}

			return $retVal;
		}

		function getAllCourses() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('Courses');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['Subject']  . "','" . $row['CourseNumber'] . "','" 
								. $row['CourseTitle'] . "\n";
			}

			return $retVal;
		}

		function getAllElectives() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('Electives');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['ElectiveType']  . "','" . $row['Subject'] . "','" 
								. $row['CourseNumber'] . "','" . $row['ElectiveName'] . "'\n";
			}

			return $retVal;
		}

		function getAllPrerequisites() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('Prerequisite');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['CourseNumber']  . "',' " . $row['Requirement'] . "',' " 
								. $row['YearReq'] . "'\n";
			}

			return $retVal;
		}

		function getAllProgramsRequirements() {
			$retVal = "";
			$result = $this->getAllRowsFromTable('ProgramsRequirement');
			while($row = mysqli_fetch_array($result)){
				$retVal .= "'" . $row['Program']  . "',' " . $row['Subject'] . "',' " 
								. $row['CourseNumber'] . "','" . $row['YearRequirement'] . "'\n";
			}

			return $retVal;
		}

		function setAllTableValues($table, $data) {

			$tableLayout = array(
												'Administrators' => array('Username', 'Password'),
												'Classes' => array('Subject', 'CourseNumber', 'Section', 'Type', 'Days', 'Start_Time', 'End_Time', 'RoomCap', 'Term'),
												'Courses' => array('Subject', 'CourseNumber', 'CourseTitle'),
												'Electives' => array('ElectiveType', 'Subject', 'CourseNumber', 'ElectiveName'),
												'Prerequisite' => array('Subject', 'CourseNumber', 'Requirement', 'YearReq'),
												'ProgramsRequirement' => array('Program', 'Subject', 'CourseNumber', 'YearRequirement')
										  );

			$table = ($table == 'Program Requirements') ? 'ProgramsRequirement' : $table;
			if (isset($tableLayout[$table])) {

				$rowNames = $tableLayout[$table];
				$rows = split("\n", $data);

				$sqlFront = "INSERT INTO $table (";
				foreach ($rowNames as $name) {
					$sqlFront .= "$name,";
				}

				$sqlFront = rtrim($sqlFront, ",") . ") VALUES (";

				$sql = "";
				foreach ($rows as $row) {
					if ($row != "")
						$sql .= $sqlFront . $row . ");";
				}

				// clear table and re-add all values
				$sql = "DELETE FROM $table; " . $sql;
				$result = mysqli_multi_query($this->mysqli, $sql);
				if (mysqli_error($this->mysqli) != "") {
					return "There is an error in the file - errormsg: " . mysqli_error($this->mysqli);
				} else {
					return "Successfully Updated the database! " . $sql;
				}

			}

			return "Could not successfully update the database";
		}


	}

	/*
		return an array of Course Object
	 */
	function createCourseArray($unCompletedCourses, $classesInfo){
		$result = [];

		if (count($unCompletedCourses) == 0) return []; // TODO: WHAT IF NO COURSES SELECTED?

		foreach ($unCompletedCourses as $number => $singleCourse) {
			$course = new Course($singleCourse);
			foreach ($classesInfo as $classInfo) {
				if ($singleCourse == $classInfo["Subject"]." ".$classInfo["CourseNumber"]){
					$course->addClass($classInfo);
				} 
			}
			array_push($result, $course);
		}

		return $result;
	}

?>
