<?php
	require("../config.php");

	if (isset($_POST['action'])){
		$variable = $_POST;
	} else {
		$variable = $_GET;
	}

	$action = $variable['action'];
	switch ($action) {
		case 'prereqTree':
			$result = $db->getPrerequisiteTree($variable['program']);
			echo json_encode($result);
			break;
		case 'timeTable':
			$courses = explode(";", $variable['courseCompleted']);
			$program = $variable['program'];
			$prerequisiteTree = $db->getPrerequisiteTree($variable['program']);
			// $avaiableCourse = $db->getAvaiableCourse

			print_r($courses);
			echo "<br>$program"; 

			$db->getClasses();
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
