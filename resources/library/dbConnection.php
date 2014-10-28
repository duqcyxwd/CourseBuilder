<?php
	require("../config.php");

	$action = $_POST['action'];
	switch ($action) {
		case 'prereqTree':
			getPrerequisiteTree($db, $_POST['program']);
			break;
		default:
			break;
	}

	function getPrerequisiteTree($db, $program) {
		include(INCLUDE_PATH . "/constants.php");

		$courses = array();
		$result = $db->getCourseInfo($program);

		while ($row = mysqli_fetch_array($result)){
			$courses[] = $row;
		}

		$courseArray = array(array(), array(), array(), array(), array(), array(), array(), array());
		foreach ($courses as $key => $course) {
			$term = $course['term'];
			array_push($courseArray[$term], $course['subject'] . " " . $course['code']);
		}

		echo json_encode($courseArray);
	}
?>