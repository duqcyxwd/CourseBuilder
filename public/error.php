<?php 

	require_once("../resources/config.php");
	require_once(TEMPLATES_PATH . "/header.php"); 
?>

<?php 
	$errorId = $_GET['errorid'];
	$codes = array(
		100 => array('100 DataBase Unconnect', 'DataBase is not ready.'),
	    404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
	);

	print_r($errorId);

	$title = $codes[$errorId][0];
	$message = $codes[$errorId][1];
	if ($title == false || strlen($errorId) != 3) {
		$message = 'Please supply a valid errorId.';
		die();
	}
	$pageTitle = $title;

	echo '<h1>'.$title.'</h1>
	<p>'.$message.'</p>';

	session_start();
	echo "<p style='color: #AA1111;'>".$_SESSION['error']."</a>";

?>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>
