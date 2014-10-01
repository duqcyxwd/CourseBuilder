<?php 
	$pageTitle = "Home";
	require_once("../resources/config.php");
	require_once("../resources/config_database.php");
	require_once(TEMPLATES_PATH . "/header.php"); 
	require_once(LIBRARY_PATH ."/templateFunction.php")
?>

	<div id="container">
		<div id="content">
			
		</div>
	</div>

<?php 
	// Testing for error Message
	errorMessage('error.php', 100, "Testing");
?>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
