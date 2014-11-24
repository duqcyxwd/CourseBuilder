<?php 
	$pageTitle = "Administrator";
	require_once("../config.php");
	
	if (isset($_POST['login'])){
		$username = $_POST['login'];
		$password = $_POST['password'];
	} else {
		// return to login page
		header('Location: ' . ROOT_PATH . '/admin.php');
	}

	// Verify Login
	$sql = "SELECT * FROM Administrators
					WHERE Username='$username'
					AND Password='$password'";

	$rows = $db->execute($sql);

	if ($rows->num_rows == 0) {
		header('Location: ' . ROOT_PATH . '/admin.php?login=failed');
	}

	include(TEMPLATES_PATH . "/header.php");
?>

<p>ADD FUNCTIONALITY TO ADMIN</p>


<?php include(TEMPLATES_PATH . "/footer.php"); ?>
