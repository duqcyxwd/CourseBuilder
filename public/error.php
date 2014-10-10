<?php 
	$pageTitle = "Error";
	include("../resources/templates/header.php");

	$errorId = $_GET['errorid'];
	$codes = array(
									100 => array('Database Connection Error', 'Could not connect to the database'),
	    						404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
								);

	if (@$codes[$errorId] == null) {
		$title = "Unknown error";
		$message = "Unrecognized error id: " . $errorId;
	} else {
		$title = $codes[$errorId][0];
		$message = $codes[$errorId][1];
	}

	session_start();
	echo ("
					<h1> Oh No! Something went horribly wrong! </h1>
				  <h2>$_SESSION[error]</h2>

		    	<strong>$title</strong>
		      <p>$message</p>
			");

include("../resources/templates/footer.php"); 

?>