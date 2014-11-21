<?php
	require("../config.php");

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

			$courses = explode(";", $variable['courseCompleted']);
			$program = $variable['program'];

			$prerequisiteTree = $db->getPrerequisiteTree($program);


			$classes = $db->getClasses();
			// generate TimetablesHere
			$generatedTimetables = array();



			// redirect to timetable
			$_SESSION['timetables'] = json_encode($generatedTimetables);
			header('Location: ' . ROOT_PATH . '/timetable.php');
			
			break;
		default:
			break;
	}


	function stringToClassArray($value='')
	{
		$array = array();
		$courses = explode(";", $value);
		foreach ($courses as $key => $course) {
			$course = explode(" ", $course);
			array_push($array, $course);
		}

		echo "<br>";
		return $array;
	}

?>
