<?php 

$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
       404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
       $message = 'Please supply a valid status code.';
}
$pageTitle = $title;
require_once("../resources/config.php");
require_once(TEMPLATES_PATH . "/header.php"); 

echo '<h1>'.$title.'</h1>
<p>'.$message.'</p>';

?>
