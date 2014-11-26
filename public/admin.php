<?php 
	$pageTitle = "Administrator Login";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php");

	$failedToLogin = false;
	if (isset($_GET['login'])) {
		if ($_GET['login'] == 'failed') {
			$failedToLogin = true;
		}
	}
?>

<?php if ($failedToLogin) : ?>
	<div id="loginFailed">
		Invalid Username and/or Password
	</div>
<?php endif; ?>



	<div id="centered-box">
		<div id="loginHeader">
			Administrator
		</div>
		<form method="post" action="<?php echo LIBRARY_PATH . '/adminlogin.php'; ?>">
			<table id="loginview">
				<tr><td>Username</td></tr>
				<tr><td><input class="text-input" type="text" name="login" maxlength="16"/></td></tr>
				<tr><td>Password</td></tr>
				<tr><td><input class="text-input" type="password" name="password" maxlength="16"/></td></tr>
			</table>

			<input class="loginSubmit" type="submit" value="Login"/>
		</form>
	</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
