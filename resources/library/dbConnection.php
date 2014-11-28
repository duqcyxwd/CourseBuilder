<?php
// <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	require "../config.php";
	require "timeTable.class.php";
	require("../library/PreRequisite.class.php");
	// require '/Users/SuperiMan/repo/kint/Kint.class.php';

	// Kint::enabled(true);
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
			$courseCompleted = (isset($variable['courseCompleted']) && $variable['courseCompleted'] != '' ? explode(",", $variable['courseCompleted']) : []);

			$courseObjectArray = $db-> getCourseArray($courseCompleted, $prerequisiteTree);

			if (isset($variable['TimeTableCourse'])) {
				// Generate table from selected course.
				$courseForTable = $variable['TimeTableCourse'];
				$maxNumOfCourse = sizeof($courseForTable);
				$courseForTable = $db->createCourseArrayBySelectCourse($courseForTable);
				$singleTimeTable = getATimeTable($courseForTable, $maxNumOfCourse);
			} else {
				// Generate table from completed courses
				$maxNumOfCourse = (isset($variable['max']) ? $variable['max'] : 5);
				$singleTimeTable = getATimeTable($courseObjectArray, $maxNumOfCourse);
			}

			$avaiableCourses = [];
			foreach ($courseObjectArray as $key => $value) {
				$avaiableCourses[] = [$value->name, $value->courseTitle];
			}

			$result = [];
			$result[] = [$singleTimeTable->toArray()];     // 1-6 Courses that we scheduled
			$result[] = $singleTimeTable->getTablesInArray();;								// Combination of time table that available
			$result[] = [$singleTimeTable->message];		//Message From Backend
			$result[] = $avaiableCourses; // A list of course that available and unCompleted
			$result[] = $elective;  


			echo json_encode($result);

			break;
		default:
			break;
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
			// pprint("find solution");
			// d($timeTable);
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
	function getATimeTable($courseArray, $maxNumOfCourse='')
	{
		if ($maxNumOfCourse == '' && sizeof($courseArray) <=5) {
			$maxNumOfCourse = 5;
		}

		$timeTable = new TimeTable($maxNumOfCourse);
		for ($i=0; $i < sizeof($courseArray); $i++) { 
			$timeTable->addCourse($courseArray[$i]);
			if ($timeTable->isFull()) {
				break;
			}
		}

		return $timeTable;
	}


	function checkPrerequisites($value='')
	{
		// TODO:
		return true;
	}


?>
