<?php 
	$pageTitle = "Home";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php");
	$isRequiredPage = array_key_exists('program', $_GET);
	$requiredProgram = ($isRequiredPage) ? $_GET['program'] : "Biomedical and Electrical Engineering";
	$programList = getListOfPrograms($db);
?>
	
	<section class="program-select">
		<div id="program-select-title">
			Please select a program and year from the dropdown menus
		</div>
		<div id="program-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
			<div id="program-select-subtitle">
				<?= ($isRequiredPage) ? "$requiredProgram" : "Select a program"; ?>
			</div>
			<ul class="dropdown">
			<?php foreach ($programList as $program): ?>

				<li>
					<div class="gen-tree"><?= $program; ?></div>
				</li>

			<?php endforeach; ?>
			</ul>
		</div>

		<div id="year-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
			<div id="year-select-subtitle">Select a year</div>
			<ul class="dropdown">
			<?php foreach ($UNIVERSITY_YEARS as $year): ?>

				<li>
					<div><?= $year; ?> Year</div>
				</li>

			<?php endforeach ?>
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

		<h1 id="program-name">
			<?= $requiredProgram; ?> Prerequisite Tree
		</h1>

		<div id="course-table">
			<!-- generate by JavaScript -->
		</div>
	</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
