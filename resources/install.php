<?php
	require 'Data/sqldata.php';
	class database{
		function __construct($db){
			$host="localhost";
			$user="root";
			$password="";
			if($db!="")
				$this->connection = mysqli_connect($host, $user, $password, $db);
			else
				$this->connection = mysqli_connect($host, $user, $password);
			if ($this->connection->connect_errno) {
			    echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
			}
		}

		function execute($sql){
			return $this->connection->query($sql);
		}

		function multiExe($sql){
			if (!$this->connection->multi_query($sql)) {
			    echo "Multi query failed: (" . $this->connection->errno . ") " . $this->connection->error;
			}
		}
		
		function getError(){
			return mysqli_error($this->connection);
		}
	}

	// require("../resources/config.php");
	/* create the object*/
	$data = new database("");
	
	$sql = "CREATE DATABASE IF NOT EXISTS courseBuilder" ;
	$data->execute($sql);

	$data = new database("courseBuilder");
	echo "Clean old data<br>";

	$sql = "DROP TABLE IF EXISTS CourseCompleted;
			DROP TABLE IF EXISTS Prerequisite;
			DROP TABLE IF EXISTS SpePrereq;
			DROP TABLE IF EXISTS Classes;
			DROP TABLE IF EXISTS Students;
			DROP TABLE IF EXISTS ProgramsRequirement;
			DROP TABLE IF EXISTS Electives;
			DROP TABLE IF EXISTS Courses;";

	// $data->multiExe($sql);
	// if (!$data->connection->multi_query($sql)) {
	//     echo "Drop table failed: (" . $data->connection->errno . ") " . $data->connection->error;
	// }
	echo "<br>";

	echo "importing data";
	if (!$data->connection->multi_query($courseBuilderDataSql)) {
	    echo "Drop table failed: (" . $data->connection->errno . ") " . $data->connection->error;
	}
	echo "<br>";

	echo "done";
?>
