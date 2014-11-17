<?php
	require("../config.php");

	$action = $_POST['action'];
	switch ($action) {
		case 'prereqTree':
			$result = $db->getPrerequisiteTree($_POST['program']);
			echo json_encode($result);
			break;
		default:
			break;
	}
?>
