<?php 
	$pageTitle = "Home";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php"); 
?>
	
	<section class="program-select">
		<div id="program-select-title">
			Please select a program and year from the dropdown menus
		</div>
		<div id="program-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
			<div id="program-select-subtitle">
				Select a program
			</div>
			<ul class="dropdown">
			<?php foreach ($UNIVERSITY_PROGRAMS as $program) { ?>

				<li><a href="#"><?php echo $program; ?></a></li>

			<?php } ?>
			</ul>
		</div>
		<div id="year-select-dd" class="wrapper-dropdown" tabindex="1" onclick="selectProgram(this);">
			<div id="year-select-subtitle">Select a year</div>
			<ul class="dropdown">
			<?php foreach ($UNIVERSITY_YEARS as $year) { ?>

				<li><a href="#"><?php echo $year; ?> Year</a></li>

			<?php } ?>
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
	<section class="course-table">
		
		<table id="schedule-table" onclick="something(this);">
			<thead>
				<tr id="course-header-year">
				<?php for ($i=1; $i <= $NUMBER_OF_YEARS; $i++) { ?>

					<th colspan='2'>YEAR <?php echo $i ?></th>

				<?php	} ?>
				</tr>
				<tr id="course-header-term">
				<?php for ($i=0; $i < $NUMBER_OF_YEARS ; $i++) {?>
					
					<th>FALL</th>
					<th>WINTER</th>
					
				<?php } ?>
				</tr>
			</thead>
			<!-- TEST DATA - TO BE REMOVED -->
			<tbody id="course-classes">
				<tr>
					<td class="MATH">MATH 1104</td>
					<td class="MATH">MATH 1005</td>
					<td class="MATH">MATH 2004</td>
					<td class="MATH">MATH 1805</td>
					<td class="COMP">COMP 3005</td>
					<td class="STAT">STAT 3502</td>
					<td class="ELEC">ELEC 4507</td>
					<td class="ECOR">ECOR 4995</td>
				</tr>
				<tr>
					<td class="MATH">MATH 1004</td>
					<td class="PHYS">PHYS 1004</td>
					<td class="ELEC">ELEC 2501</td>
					<td class="ELEC">ELEC 2607</td>
					<td class="ECOR">ECOR 3800</td>
					<td class="SYSC">SYSC 3101</td>
					<td class="SYSC">SYSC 4101</td>
					<td class="SYSC">SYSC 4005</td>
				</tr>
				<tr>
					<td class="PHYS">PHYS 1003</td>
					<td class="ECOR">ECOR 1101</td>
					<td class="SYSC">SYSC 2001</td>
					<td class="SYSC">SYSC 2003</td>
					<td class="SYSC">SYSC 3110</td>
					<td class="SYSC">SYSC 3120</td>
					<td class="SYSC">SYSC 4120</td>
					<td class="SYSC">SYSC 4507</td>
				</tr>
				<tr>
					<td class="SYSC">SYSC 1005</td>
					<td class="SYSC">SYSC 2006</td>
					<td class="SYSC">SYSC 2004</td>
					<td class="SYSC">SYSC 2100</td>
					<td class="SYSC">SYSC 4001</td>
					<td class="SYSC">SYSC 3303</td>
					<td class="ETIV">ELECTIVE</td>
					<td class="SYSC">SYSC 4806</td>
				</tr>
				<tr>
					<td class="ECOR">ECOR 1010</td>
					<td class="CHEM">CHEM 1101</td>
					<td class="CCDP">CCDP 2100</td>
					<td class="ETIV">ELECTIVE</td>
					<td class="ETIV">ELECTIVE</td>
					<td class="SYSC">SYSC 4106</td>
					<td class="ETIV">ELECTIVE</td>
					<td class="SYSC">SYSC 4927</td>
				</tr>
			</tbody>
			<tfoot>
				
			</tfoot>
		</table>
	</section>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
