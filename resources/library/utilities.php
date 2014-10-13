<?php 

	function errorMessage($url, $statusCode = 100, $additionMessage = "")
	{
		session_start();
		$_SESSION['error'] = $additionMessage;
		header('Location: ' . $url .'?errorid=' . $statusCode);
		exit();
	}

	function getListOfPrograms($db) 
	{
		$result = $db->getDistinctFromTable("program", "programs");
		$programList = array();
		while ($row = mysqli_fetch_array($result))
			$programList[] = $row['program'];
		
		return $programList;
	}
	
?>