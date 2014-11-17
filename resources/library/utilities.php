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
		$result = $db->getDistinctFromTable("Name", "ProgramsRequirement");
		$programList = array();
		while ($row = mysqli_fetch_array($result))
			$programList[] = $row['Name'];
		
		return $programList;
	}
	
?>
