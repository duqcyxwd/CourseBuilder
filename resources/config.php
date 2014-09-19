<?php

$config = array(
	"db" => array(
		"dbname" => "database_name",
		"username" => "root",
		"password" => "",
		"host" => "localhost"
		)
	);


defined("ROOT_PATH")
    or define("ROOT_PATH", "/CourseBuilder/public");

defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
     
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

?>