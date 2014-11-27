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
		$year = date('Y');
		$month = date('m');
		if($month < 4)
			return $year."w";
		else 
			return $year."f";
	}

	// flat a multi dimension array
	function flatArray($array){
		$result = [];
		if (is_array($array)) {
			foreach ($array as $element) {
				$result = array_merge($result, flatArray($element));
			}
		} else {
			array_push($result, $array);
		}

		return $result;
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


	/**
	 *
	 * get String of classes and return in array format
	 **/
	function stringToClassArray($coursesString='')
	{
		$array = array();
		$courses = explode(";", $coursesString);
		foreach ($courses as $key => $course) {
			$course = explode(" ", $course);
			array_push($array, $course);
		}
		echo "<br>";
		return $array;
	}
	
?>
