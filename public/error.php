<?php 
	$pageTitle = "Error";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php"); 

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
					<h2> Oh No! Something went horribly wrong! </h2>
				  <h3>$_SESSION[error]</h3>

		    	<strong>$title</strong><br />
							    $message
			");

include(TEMPLATES_PATH . "/footer.php"); 

?>