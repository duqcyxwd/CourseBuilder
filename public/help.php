<?php 
	$pageTitle = "About Us";
	require_once("../resources/config.php");
	require_once(TEMPLATES_PATH . "/header.php"); 
?>


<div id="help-container">
	<div class="help-wrapper">
		<div class="help-instructions">
			<h1>Course Builder Instructions</h1>
			<h3>Completed Courses Selection</h3>
			<h2>Program Selection</h2>
			<p>Select the program you are currently enrolled in from the dropdown located at the top of the homepage.
				 Upon selecting a course from the dropdown menu, a table should appear underneath containing all of the
				 courses required for that program.</p>
			<h2>Course Number</h2>
			<p>Specify the number of courses you wish to take for the upcoming semester by setting a value within
			   the input field located between the program selection dropdown and the submit button. The default 
			   value is set to 5. The maximum number of courses that can be set is 6. Any value that does is not 
			   within 1 and 6 will generate the default number of courses.</p>
			<h2>Course Table</h2>
			<p>From the table that appears, select all the courses that you have completed. Courses highlighted in red
				 indicate that you have completed the course. If you are <b>on pattern</b> within your program, you can specify
				 your year status and the table will automatically fill the table with the coures that you've completed.
				 <b>Note</b>: the on pattern selector is based on the current time of year. That is, if you are using
				 this application between November and January, the on pattern selector will assume that you have completed
				 the fall term and are building a timetable for the upcoming Winter term.</p>
			 <h2>Submit</h2>
			 <p>Once you have selected all the courses you've completed, hit the <b>Submit</b> button. The application
				  will then analyze your selection and generate all the possible timetables for the upcoming term.</p>
			<br/>
		  <h3>Timetable Configuration</h3>
		  <h2>Removing a Course</h2>
		  <p>If a course appears in the timetable that you do not wish to register for, simply select the course in the
		  	sidebar, below the <b>Register</b> button. This will generate a new timetable without that course.</p>
	  	<h2>Add Elective</h2>
	  	<p>The sidebar contains a <em>"Click to Add Electives"</em> button which brings up a list of electives which
  			 you can take. All the electives that appear in the list are offered during the term and are
  			 part of the selected program. The type of elective is also indicated in parentheses.</p>
		  <h2>Add a Course</h2>
		  <p>The sidebar also contains a <em>"Click to Add Course"</em> button which brings up a list of courses you
			   can take. Similar to the electives button, this option provides a list of courses related to your selected
			   program which are available during the given term.</p>
	    <h2>Alternate Tables</h2>
	    <p>If several timetables can be generated according to your specifications, they will appear in the list located
		     at the bottom of the sidebar on the right side. The first alternate table is the initial table displayed. Selecting 
		     any subsequent option will update the visible timetable</p>
		</div>
	</div>
</div>


<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>