<?php 
	$pageTitle = "Administrator";
	require_once("../config.php");
	
	if (isset($_POST['login'])){
		$username = $_POST['login'];
		$password = $_POST['password'];
	} else {
		header('Location: ' . PUBLIC_PATH . '/admin.php'); // return to login page
	}

	// Verify Login
	$sql = "SELECT * FROM Administrators
					WHERE Username='$username'
					AND Password='$password'";

	$rows = $db->execute($sql);

	if ($rows->num_rows == 0) {
		header('Location: ' . PUBLIC_PATH . '/admin.php?login=failed');
	}

	include(TEMPLATES_PATH . "/header.php");
?>

<div class='adminlogin'>
	<h1>Update Database</h1>
</div>


<div id='input-wrapper'>
	<form id="inputList">
	<div id='db-submit'>Submit Update</div>
		<h3>Select one of the following tabs to edit the database. Hit submit to update database</h3>
		<h4>If anything goes wrong, <a href='<?php echo RESOURCE_PATH . "/install.php" ?>'>click here</a> to reset the database to the original values</h4>
		<table>
			<tr>
				<td class='db-tabs'>Administrators</td>
				<td class='db-tabs'>Classes</td>
				<td class='db-tabs'>Courses</td>
				<td class='db-tabs'>Electives</td>
				<td class='db-tabs'>Prerequisite</td>
				<td class='db-tabs'>Program Requirements</td>
			</tr>
		</table>
		<textarea id='db-text'></textarea>
	</form>
</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
