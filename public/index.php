<?php 
	$pageTitle = "Home";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php"); 
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
				<?php 
					$result= $db->getProgramList();

					$programList = array();
					while ($row = mysqli_fetch_array($result)) {
						$programList[] = $row['program'];
					}
				?>
				<?php foreach ($programList as $program) { ?>

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
				<tbody id="course-classes">
					<?php 
						$courses = array();
						$result= $db->getCourseInfor();

						while ($row = mysqli_fetch_array($result)){
							$courses[] = $row;
						}
						$courseArray = array(array(), array(), array(), array(), array(), array(), array(), array(), );
						foreach ($courses as $key => $course) {
							$term = $course['term'];
							array_push($courseArray[$term], array($course['subject'], $course['code']));
						}
						// calculate max course in a term
						$temp = array();

						foreach ($courseArray as $key => $value) {
							array_push($temp, sizeof($value));
						}
						$maxNumOfCourseInterm = max($temp);
						for ($i=0; $i < $maxNumOfCourseInterm; $i++) { 
							echo "<tr>";
							for ($j=0; $j < sizeof($courseArray); $j++) { 
								
								if (array_key_exists($i, $courseArray[$j])) {
									echo "<td> " . $courseArray[$j][$i][0] . " " .$courseArray[$j][$i][1] . " </td>";
								} else {
									echo "<td>  </td>";

								}
							}

							echo "</tr>";
						}
					?>


				</tbody>
				<tfoot>
					
				</tfoot>
			</table>
		</section>
	</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
