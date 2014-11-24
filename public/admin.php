<?php 
	$pageTitle = "Administrator";
	require("../resources/config.php");
	include(TEMPLATES_PATH . "/header.php");
?>

	<div id="centered-box">
		<div id="loginHeader">
			Administrator
		</div>
		<form method="post" action="server.php">
			<table id="loginview">
				<tr>
					<td>Login</td>
				</tr>
				<tr>
					<td><input class="text-input" type="text" name="login"/></td>
				</tr>
				<tr>
					<td>Password</td>
				</tr>
				<tr>
					<td><input class="text-input" type="password" name="password"/></td>
				</tr>
			</table>

			<input type="hidden" value="login" name="typeofrequest"/>
			<input class="loginSubmit" type="submit" value="Login"/>
		</form>
	</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>
