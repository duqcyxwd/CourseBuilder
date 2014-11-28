<?php 
	$pageTitle = "Administrator";
	require_once("../config.php");
	
	if (isset($_POST['login'])){
		$username = $_POST['login'];
		$password = $_POST['password'];
	} else {
		header('Location: ' . ROOT_PATH . '/admin.php'); // return to login page
	}

	// Verify Login
	$sql = "SELECT * FROM Administrators
					WHERE Username='$username'
					AND Password='$password'";

	$rows = $db->execute($sql);

	if ($rows->num_rows == 0) {
		header('Location: ' . ROOT_PATH . '/admin.php?login=failed');
	}

	include(TEMPLATES_PATH . "/header.php");
?>

<h1>Update Database</h1>

<div id='input-wrapper'>
	<form id="inputList">
		<table>
			<tr>
				<td>Administrator</td>
				<td>Classes</td>
				<td>Courses</td>
				<td>Electives</td>
				<td>Prerequisite</td>
				<td>Program Requirements</td>
			</tr>
		</table>
		<!-- <textarea rows='10' cols='20'></textarea> -->
		<textarea></textarea>
	</form>
</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
