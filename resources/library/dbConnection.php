<?php
	require"../config.php";
	require "timeTable.class.php";
	require '/Users/SuperiMan/repo/kint/Kint.class.php';

	if (isset($_POST['action'])){
		$variable = $_POST;
	} else {
		$variable = $_GET;
	}

	session_start();
	
	if (isset($variable['max'])) {
		$maxNumOfCourse=$variable['max'];
	} else {
		$maxNumOfCourse = 5;
	}

	$program = $variable['program'];
	$prerequisiteTree = $db->getPrerequisiteTree($program);
	$elective = $db->getElectivesByProgram($program);
	
	$action = $variable['action'];
	switch ($action) {
		case 'prereqTree':
			echo json_encode([$prerequisiteTree, $elective]);
			break;

		case 'timeTable2':
			$program = $variable['program'];
			$courseSelect = $variable['course'];

			$coursesInfo = $db->getCourseInfoByCourseArray($courseSelect);
			$courseArray = createCourseArray($courseSelect, $coursesInfo);



			$singleTimeTable = getATimeTable($courseArray, $maxNumOfCourse);
			$table = $singleTimeTable->getTablesInArray();


			$result = [];
			$result[] = [$singleTimeTable->toString()];
			$result[] = $table;
			$result[] = [$singleTimeTable->message];
			$result[] = $unCompletedCourses;
			$result[] = $elective;  // 

			echo json_encode($result);
			break;
		case 'timeTable':

			$courseCompleted = explode(",", $variable['courseCompleted']);

			$openingClasses = $db->getOpeningClasses();
			$unCompletedAndAvaiableCourses = getUnCompletedCourses($courseCompleted, $prerequisiteTree, $openingClasses);
			$coursesInfo = $db->getCourseInfoByCourseArray($unCompletedAndAvaiableCourses);
			
			
			$courseArray = createCourseArray($unCompletedAndAvaiableCourses, $coursesInfo);



			$singleTimeTable = getATimeTable($courseArray, $maxNumOfCourse);
			$table = $singleTimeTable->getTablesInArray();
			
			$result = [];
			$result[] = [$singleTimeTable->toString()];     // 1-6 Courses that we scheduled
			$result[] = $table;								// Combination of time table that available
			$result[] = [$singleTimeTable->message];		//Message From Backend
			$result[] = $db->getCouseTitleByCourseArray($unCompletedAndAvaiableCourses); // A list of course that available and unCompleted
			$result[] = $elective;  						// A list of Avaiable Elective

			echo json_encode($result);


			// $timeTables = [];
			// getTimeTables($timeTables, $courseArray, 0, new TimeTable($maxNumOfCourse));
			// echo json_encode($timeTables);



			// $openingClasses = $db->getOpeningClasses();
			$eligibleCourses = $db->getEligibleCourses($courseCompleted, $program, 3); // change the 3


			// echo "<br>";
			// $unCompletedCourses = getUnCompletedCourses($courseCompleted, $prerequisiteTree, $openingClasses);
			// $coursesInfo = $db->getCourseInfoByCourseArray(flatArray($unCompletedCourses));
			// d(sizeof($coursesInfo));


			// only problem left here is time conflict!!!!!!!!.....
			// now we have $unCompletedCourses and $coursesInfo

			// $timeTables = [];
			// $courseArray = createCourseArray($unCompletedCourses, $coursesInfo);

			// getTimeTables($timeTables, $courseArray, 0, new TimeTable());
			// $singleTimeTable = getATimeTable($courseArray);
			// echo "Result: <br>";

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
	function getUnCompletedCourses($coursesCompleted, $prerequisiteTree, $openingClasses)
	{

		$requiredCourses = $prerequisiteTree;
		$numOfCourseCompledInTree = 0;

		// get courses that uncompleted in prerequisite Tree
		foreach ($requiredCourses as $term => $coursesOfOneTerm) {
			foreach ($coursesOfOneTerm as $number => $singleCourse) {
				foreach ($coursesCompleted as $c) {
					// TODO: ... 
					if ($c != "") {
						if ($singleCourse == $c){
							$numOfCourseCompledInTree++;
							unset($requiredCourses[$term][$number]);
							break;
						} 
					}
				}
			}
			
		}

		// get course that unCompletedCourses open in current term
		// check if this course open or not
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
		return flatArray($requiredCourses);
	}

	function checkPrerequisites($value='')
	{
		// TODO:
		return true;
	}


?>
