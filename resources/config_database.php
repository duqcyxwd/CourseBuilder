<?php 


// Connect to Database
// $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// if ($mysqli->connect_errno) {
//     echo "Failed to connect to MySQL: " . $mysqli->connect_error;
// }
// else {
// 	$db = new database($mysqli);	
// }
$db = new database($db_hostname, $db_username, $db_password, $db_name);

// TODO_KR basic format for getting database rows:
$result = $db->getRowsFromTable("test");
while ($row = mysqli_fetch_array($result)) {
	echo $row["courses"];
}

?>
