<?php

require_once(realpath(dirname(__FILE__)) . '/include/database.php');
require_once(realpath(dirname(__FILE__)) . '/include/constants.php');
require_once(realpath(dirname(__FILE__)) . '/library/utilities.php');

// Database connection details
$db_hostname = 'localhost';
$db_name = 'courseBuilder';
$db_username = 'root';
$db_password = '';
$testing = false;

defined("ROOT_PATH")
    or define("ROOT_PATH", "/CourseBuilder/public");

defined("INCLUDE_PATH")
    or define("INCLUDE_PATH", realpath(dirname(__FILE__) . '/include'));
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
defined("LIBRARY_PATH")
		or define("LIBRARY_PATH", "/CourseBuilder/resources/library");
    // or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("RESOURCE_PATH")
    or define("RESOURCE_PATH", realpath(dirname(__FILE__) . '/'));

// Connect to Database
try 
{
	$db = new DataBase($db_hostname, $db_username, $db_password, $db_name);
}
catch (Exception $e) 
{
	if (!strpos($_SERVER['REQUEST_URI'], 'error.php')) {
		errorMessage('error.php', 100, "Looks like we couldn't connect to the database");
	}
}

?>
