<?php 
	require_once("../resources/config.php");


	function errorMessage($url, $statusCode = 100, $additionMessage = "")
	{
		session_start();
		$_SESSION['error'] = $additionMessage;
		header('Location: ' . $url .'?errorid=' . $statusCode);
		exit();
	}
?>
