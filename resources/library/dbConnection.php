<?php require '/Users/SuperiMan/repo/kint/Kint.class.php';?>
<?php
// <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	require "../config.php";
	require "timeTable.class.php";
	require("../library/PreRequisite.class.php");
	

	Kint::enabled(true);
	session_start();

	$variable = (isset($_POST['action']) ? $_POST: $_GET);

	$program = $variable['program'];
	$prerequisiteTree = $db->getPrerequisiteTree($program);

	// Need another Ajax for Elective when select form prerequisite table
	$elective = $db->getElectivesByProgram($program);

	$action = $variable['action'];
	switch ($action) {
		case 'prereqTree':
			// pprint([$prerequisiteTree, $elective]);
			echo json_encode([$prerequisiteTree, $elective]);
			break;

		case 'timeTable':
			//TODO: add eligibleCourses to our function
			// $program = $variable['program'];
			// $eligibleCourses = $db->getEligibleCourses($courseCompleted, $program, 3); // change the 3

			// $courseCompleted = explode(",", $variable['courseCompleted']);
			$courseCompleted = (isset($variable['courseCompleted']) && $variable['courseCompleted'] != '' ? explode(",", $variable['courseCompleted']) : []);
			$openingClasses = $db->getOpeningClasses();

			$maxNumOfCourse = (isset($variable['max']) ? $variable['max'] : 5);


			if (isset($variable['TimeTableCourse'])) {
				// Generate table from selected course.
				$courseForTable = $variable['TimeTableCourse'];
			} else {
				// Generate table from completed courses
				$courseForTable = unCompletedOpeningCourses($courseCompleted, $prerequisiteTree, $openingClasses);

				// $eligibleCourses = $db->getEligibleCourses($courseCompleted, $program, 1); // change the 3

				// $courseForTable = unCompletedOpeningCoursestwo($courseCompleted, $openingClasses);
			}


			$coursesInfo = $db->getCourseInfoByCourseArray($courseForTable);
			$courseArray = createCourseArray($courseForTable, $coursesInfo);
			$singleTimeTable = getATimeTable($courseArray, $maxNumOfCourse);


			$result = [];
			$result[] = [$singleTimeTable->toString()];     // 1-6 Courses that we scheduled
			$result[] = $singleTimeTable->getTablesInArray();;								// Combination of time table that available
			$result[] = [$singleTimeTable->message];		//Message From Backend
			$result[] = $db->getCouseTitleByCourseArray($courseForTable); // A list of course that available and unCompleted
			$result[] = $elective;  


			echo json_encode($result);

			break;
		default:
			break;
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

	/*
		get all the combination of available timeTables
		$timeTables : the final result
		$courseArray : Array of courses Object that we can choice
	 */
	function getTimeTables(&$timeTables, $courseArray, $startPoint, $timeTable)
	{
		if (sizeof($timeTables) > 2) {
			return true;
		}
		if ($timeTable->isFull()) {
			array_push($timeTables, $timeTable);
			pprint("find solution");
			d($timeTable);
			return true;
		}
		for ($i=$startPoint; $i < sizeof($courseArray); $i++) { 
			// $temp = $timeTable->duplicate();
			if ($timeTable->addCourse($courseArray[$i])) {
				getTimeTables($timeTables, $courseArray, $i + 1, $timeTable);
			}
		}
	}

	// get all the single of timeTable
	function getATimeTable($courseArray, $maxNumOfCourse)
	{
		$timeTable = new TimeTable($maxNumOfCourse);
		for ($i=0; $i < sizeof($courseArray); $i++) { 
			$timeTable->addCourse($courseArray[$i]);
			if ($timeTable->isFull()) {
				break;
			}
		}

		return $timeTable;
	}


	/**
	 * Taking a list of completed list, prerequisiteTree, and openingClasses
	 *
	 * @return course that not completed and open in current term
	 * @author 
	 **/
	function unCompletedOpeningCourses($coursesCompleted, $prerequisiteTree, $openingClasses)
	{

		$requiredCourses = flatArray($prerequisiteTree);

		// get courses that uncompleted in prerequisite Tree
		$unCompletedCourse = array_diff($requiredCourses, $coursesCompleted);

		// get course that unCompletedCourses open in current term
		// check if this course open or not
		$unCompletedOpeningCourses = array_diff($unCompletedCourse, array_diff($unCompletedCourse, $openingClasses));

		// filter the courses by prerequisite
		foreach ($unCompletedOpeningCourses as $term => $singleCourse) {
			if (!checkPrerequisites($singleCourse)) {
				unset($unCompletedOpeningCourses[$term]);
			}
		}

		// $courseForTable = getEligibleCourses($coursesCompleted, "Communication Engineering", 1)
		return $unCompletedOpeningCourses;
	}


	/**
	 * Taking a list of completed list, prerequisiteTree, and openingClasses
	 *
	 * @return course that not completed and open in current term
	 * @author 
	 **/
	function unCompletedOpeningCoursestwo($avaiableInprerequisiteTree, $openingClasses)
	{

		// get course that unCompletedCourses open in current term
		// check if this course open or not
		$unCompletedOpeningCourses = array_diff($avaiableInprerequisiteTree, array_diff($avaiableInprerequisiteTree, $openingClasses));

		// filter the courses by prerequisite
		foreach ($unCompletedOpeningCourses as $term => $singleCourse) {
			if (!checkPrerequisites($singleCourse)) {
				unset($unCompletedOpeningCourses[$term]);
			}
		}

		// $courseForTable = getEligibleCourses($coursesCompleted, "Communication Engineering", 1)
		return $unCompletedOpeningCourses;
	}

	function checkPrerequisites($value='')
	{
		// TODO:
		return true;
	}


?>
