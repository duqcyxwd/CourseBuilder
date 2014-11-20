<?php 

	function errorMessage($url, $statusCode = 100, $additionMessage = "")
	{
		session_start();
		$_SESSION['error'] = $additionMessage;
		header('Location: ' . $url .'?errorid=' . $statusCode);
		exit();
	}

	function getCurrentTerm()
	{
		// TODO: Add functionality later
		return "2014w";
	}

	// print array in a good way
	function pprint($a) {
		echo '<pre>'.print_r($a,1).'</pre>';
	}	

	function pprint2($a) {
		foreach($a as $child) {
			if(is_array($child)){
				pprint2($child);
			} else {
		   		echo $child . "<br>";
			}
		}
	}
	
?>
