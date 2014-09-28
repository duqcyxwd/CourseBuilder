<?php 
	$pageTitle = "Home";
	require_once("../resources/config.php");
	require_once(TEMPLATES_PATH . "/header.php"); 
?>

	<div id="container">
		<section class="program-select">
			<div id="program-select-title">
				Please select a program and year from the dropdown menus
			</div>
			<div id="program-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
				<div id="program-select-subtitle">
					Select a program
				</div>
				<ul class="dropdown">
					<li><a href="#bio">Biomedical and Electrical Engineering</a></li>
					<li><a href="#comm">Communications Engineering</a></li>
					<li><a href="#comp">Computer Systems Engineering</a></li>
					<li><a href="#soft">Software Engineering</a></li>
				</ul>
			</div>
			<div id="year-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
				<div id="year-select-subtitle">Select a year</div>
				<ul class="dropdown">
					<li><a href="#first">1st Year</a></li>
					<li><a href="#second">2nd Year</a></li>
					<li><a href="#third">3rd Year</a></li>
					<li><a href="#fourth">4th Year</a></li>
				</ul>
			</div>
	  	<div id="program-select-onpattern">
				<div class="checkbox">
		  		<input type="checkbox" value="1" id="checkboxInput" name="" />
			  	<label for="checkboxInput"></label>
		  	</div>
		  	On Pattern
				
			</div>
		</section>
	</div>


<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>