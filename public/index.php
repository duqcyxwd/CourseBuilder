<?php 
	$pageTitle = "Home";
	require_once("../resources/config.php");
	require_once(TEMPLATES_PATH . "/header.php"); 
?>

	<div id="container">
		<section>
			<div id="program-select-title"></div>
			<div id="program-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
				<div id="program-select-subtitle">Select a program</div>
				<ul class="dropdown">
					<li><a href="#bio">Biomedical and Electrical Engineering</a></li>
					<li><a href="#comm">Communications Engineering</a></li>
					<li><a href="#comp">Computer Systems Engineering</a></li>
					<li><a href="#soft">Software Engineering</a></li>
				</ul>
			</div>
		</section>

		<p>TODO:</p>
		<ul>
		    <li>Add Checkbox for [On pattern] and dropdown with which year</li>
		    <li>Table fades in with all the course info about that program (hard code for now)</li>
		    <li>Add JavaScript so that the courses are auto-selected when user chooses on-pattern</li>
		    <li>CLEAN UP CSS BEFORE PUSHING</li>
		</ul>
	</div>


<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>