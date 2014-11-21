<?php
	require"../config.php";
	require "timetable.php";


	if (isset($_POST['action'])){
		$variable = $_POST;
	} else {
		$variable = $_GET;
	}

	session_start();
	$action = $variable['action'];
	switch ($action) {
		case 'prereqTree':
			$program = $variable['program'];
			$result = $db->getPrerequisiteTree($program);
			echo json_encode($result);
			break;
		case 'timeTable':

			$prerequisiteTree = $db->getPrerequisiteTree($variable['program']);
			$courseCompleted = explode(";", $variable['courseCompleted']);
			$program = $variable['program'];
			$openingClasses = $db->getOpeningClasses();

			echo "<br>";
			$unCompletedCourses = getUnCompletedCourses($courseCompleted, $prerequisiteTree, $openingClasses);
			$coursesInfo = $db->getCourseInfoByCourseArray(flatArray($unCompletedCourses));
			// d(sizeof($coursesInfo));


			// only problem left here is time conflict!!!!!!!!.....
			// now we have $unCompletedCourses and $coursesInfo

			$timeTables = [];
			$courseArray = createCourseArray($unCompletedCourses, $coursesInfo);

			// getTimeTables($timeTables, $courseArray, 0, new TimeTable());
			$singleTimeTable = getTimeTable($courseArray);
			echo "Result: <br>";
			// d($singleTimeTable);
			// d($singleTimeTable->getATable());


			// redirect to timetable
			// $generatedTimetables = array();
			// $_SESSION['timetables'] = json_encode($generatedTimetables);
			// header('Location: ' . ROOT_PATH . '/timetable.php');
			

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

		foreach ($unCompletedCourses as $term => $coursesOfOneTerm) {
			foreach ($coursesOfOneTerm as $number => $singleCourse) {
				$course = new Course($singleCourse);
				foreach ($classesInfo as $classInfo) {
					if ($singleCourse == $classInfo["Subject"]." ".$classInfo["CourseNumber"]){
						$course->addClass($classInfo);
					} 
				}
				array_push($result, $course);
			}
		}

		return $result;

	}

	/*
		get all the combination of available timeTables
		$timeTables : the final result
		$courseArray : Array of courses Object that we can choice
	 */
	function getTimeTables($timeTables, $courseArray, $startPoint, $timeTable)
	{
		if ($timeTable->isFull()) {
			array_push($timeTables, $timeTable);
			pprint("find solution");
			d($timeTable);
			return true;
		}
		for ($i=$startPoint; $i < sizeof($courseArray); $i++) { 
			$temp = $timeTable->duplicate();
			if ($temp->addCourse($courseArray[$i])) {
				// pprint("called");
				// d($temp);
				getTimeTables($timeTables, $courseArray, $i + 1, $temp);
			}
		}
	}

	// get all the single of timeTable
	function getTimeTable($courseArray)
	{
		$timeTable = new TimeTable();
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
	function getUnCompletedCourses($coursesCompleted, $prerequisiteTree, $openingClasses)
	{

		$requiredCourses = $prerequisiteTree;
		$numOfCourseCompledInTree = 0;

		// get courses that uncompleted in prerequisite Tree
		foreach ($requiredCourses as $term => $coursesOfOneTerm) {
			foreach ($coursesOfOneTerm as $number => $singleCourse) {
				foreach ($coursesCompleted as $c) {

					// TODO: ... 
					$ccourse = explode(" ", $c);
					if (count($ccourse) < 2) return; // WHAT IF THE STUDENT HASN'T TAKEN ANY CLASSES?

					if ($singleCourse == $ccourse[0]." ".$ccourse[1]){
						$numOfCourseCompledInTree++;
						unset($requiredCourses[$term][$number]);
						break;
					} 

				}
			}
			
		}

		// get course that unCompletedCourses open in current term
		// check if this course opening
		foreach ($requiredCourses as $term => $coursesOfOneTerm) {
			foreach ($coursesOfOneTerm as $number => $singleCourse) {

				$courseOpen = false;
				foreach ($openingClasses as $class) {
					if ($singleCourse == $class[0]." ".$class[1]){
						$courseOpen = true;
					}
				}

				if ($courseOpen == false){
					unset($requiredCourses[$term][$number]);
				}

			}
		}

		// filter the courses by prerequisite
		foreach ($requiredCourses as $term => $coursesOfOneTerm) {
			foreach ($coursesOfOneTerm as $number => $singleCourse) {
				if (!checkPrerequisites($singleCourse)) {
					unset($requiredCourses[$term][$number]);
				}
			}
		}
		echo "Completed ".$numOfCourseCompledInTree." course";
		return $requiredCourses;
	}

	function checkPrerequisites($value='')
	{
		// TODO:
		return true;
	}


?>
