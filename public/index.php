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
					<?php 
						$courses = array();
						$con=mysqli_connect("localhost","root","","courseBuilder");
						$result=mysqli_query($con,"SELECT `code`, `subject`,`program` FROM `programs` WHERE term = '0' AND program = 'Biomedical and Electrical Engineering'");
						while ($row = mysqli_fetch_array($result)){
							$courses[] = $row;
						}
					foreach ($courses as $key => $value): ?>
						<tr>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
							<td><?php echo $value['subject'] . ' ' . $value['code'] ?></td>
						</tr>
					<?php endforeach ?>
					
				</tbody>
				<tfoot>
					
				</tfoot>
			</table>
		</section>
	</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
