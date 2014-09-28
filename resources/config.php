<?php

require_once(realpath(dirname(__FILE__)) . '/include/database.class.php');

// Database connection details
$db_hostname = 'localhost';
$db_name = 'CourseBuilder'; // TODO_KR replace this with future database name
$db_username = 'root';
$db_password = '';


defined("ROOT_PATH")
    or define("ROOT_PATH", "/CourseBuilder/public");

defined("INCLUDE_PATH")
    or define("INCLUDE_PATH", realpath(dirname(__FILE__) . '/include'));
     
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));



// Connect to Database
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$db = new database($mysqli);


// TODO_KR basic format for getting database rows:
// $result = $db->getRowsFromTable("test");
// while ($row = mysqli_fetch_array($result)) {
// 	echo $row["courses"];
// }

?>