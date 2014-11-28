<?php 

// $requied = ["MATH 1904 or SYSC 1000 and SYSC 1000",
// 			"SYSC 1000, PHYS 1000 and SYSC 5000 or CHEM 1000",
// 			"SYSC 1000, PHYS 1000 and SYSC 5000 and CHEM 2000",
// 			"(SYSC 3600 or SYSC 3500 or (SYSC 3610 and CHEM 1101)) and (ELEC 2507 or ELEC 3605 or SYSC 3203) and SYSC 1104"
// 			];
// // $completed = ["MATH 2003", 'PHYS 1000', 'SYSC 1000', 'CHEM 1000'];
// $completed = ["SYSC 2003", 'PHYS 1000', 'SYSC 2004', 'CHEM 1000'];
// foreach ($requied as $key => $value) {
// 	// s(checkRequirement($value, $completed));
// }

// $value = "SYSC 2002 or SYSC 2006 or permission of the department";
// s(checkRequirement($value, $completed));


class MyCallBack
{
	private $courseCompleted;

	function __construct($variable)
	{
		$this->courseCompleted = $variable;
	}

	// the callback function
	function callbackFunction($matches)
	{
		$res = checkRequirement($matches[0], $this->courseCompleted);
		$res = ($res) ? 'True' : 'False';

		return $res;
	}
}
$callback = new MyCallback('True');

// 'MATH 1104'
// MATH 1003, SYSC 4000 and SYSC 1009
// (SYSC 1003 and SYSC 1005) and SYSC 4000 and SYSC 1009 or (SYSC 1003 and SYSC 1005)
function checkRequirement($requirement, $completedCourses)
{
	$requirement = trimAll($requirement);

	if ($requirement == '') {
		return '';
	}

	while (findBracket($requirement)) {
		$callback = new MyCallback($completedCourses);
		$requirement = preg_replace_callback(BRACKETREG, array($callback, 'callbackFunction'), $requirement);
	}
	//No Bracket from here
	
	if (isCourse($requirement)) {
		//return true for completed this course
		if (in_array($requirement, $completedCourses)) 
			return True;
		else 
			return False;
	}

	$checkResult = checkOrAnd($requirement);
	if($checkResult == ''){
		// TODO; need to think about this..
		// SPecial Requirements
		// Giving a Message
		if (strpos($requirement, 'permission of the department')) { // Special requirement
			$isEligible = true;
			// Message to .....
			// echo "MEssage";
		}

	} else if ($checkResult == 'and none') {
		$req = '/(,|and|AND|And)/';
		$arr = preg_split($req, $requirement);
		foreach ($arr as $a) {
			$a = trim($a);
			if ($a == 'False' or (($a != '') and !checkRequirement($a, $completedCourses))) {
				return False;
			}
		}
		return True;
	} else if ($checkResult == 'or none') {
		$req = '/(,|or|OR|Or)/';
		$arr = preg_split($req, $requirement);
		foreach ($arr as $a) {
			$a = trim($a);
			if ($a == 'True' or checkRequirement($a, $completedCourses)) {
				return True;
			}
		}
		return False;
	} else if ($checkResult == 'or and') {
		$andIndex = strpos($requirement, 'and');
		$orStatement = checkRequirement(substr($requirement, 0, $andIndex), $completedCourses);


		if ($orStatement) 
			return True;
		else 
			return checkRequirement(substr($requirement, $andIndex), $completedCourses);

	} else if ($checkResult == 'and or') {
		$orIndex = strpos($requirement, "or");
		$orStatement = checkRequirement(substr($requirement, 0, $orIndex), $completedCourses);

		if (!$orStatement) 
			return False;

		$orStatement = ($orStatement) ? 'True' : 'False';
		return checkRequirement($orStatement." ".substr($requirement, $orIndex), $completedCourses);
	}
	return True;
}

function isCourse($str){
	return (preg_match("/(^[a-zA-Z]{4} \d{4}$)/", trim($str)) == 1);
}
function findBracket($str)
{
	return (preg_match(BRACKETREG, trim($str)) == 1);
}
function checkOrAnd($str='')
{
	$orIndex = strpos($str, "or");
	$andIndex = strpos($str, 'and');

	if ($orIndex === false) {
		if ($andIndex === false) {
			return 'noResult';
		} else {
			return 'and none';
		}
	} else if ($andIndex === false) {
		return 'or none';
	}

	if ($orIndex < $andIndex)
		return 'or and';
	else
		return 'and or';
}

function trimAll($str=''){
	$str = trim($str);
	if (preg_match(OUTBRACKET, trim($str)) ==1)
		return substr($str, 1, -1);
	else 
		return $str;
}

?>
