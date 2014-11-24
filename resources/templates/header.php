<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/css/main.css" type="text/css">

	<?php if ($pageTitle == "Administrator") : ?>
		<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/css/admin.css" type="text/css">	
	<?php endif; ?>


</head>

<noscript><link rel="stylesheet" type="text/css" href="<?php echo ROOT_PATH . '/css/noJS.css'; ?>"/></noscript>
<script src="<?php echo ROOT_PATH . '/js/course.js'; ?>"></script>
<script src="<?php echo ROOT_PATH . '/js/table.js'; ?>"></script>
<script src="<?php echo ROOT_PATH . '/js/utilities.js'; ?>"></script>

<?php if ($pageTitle == "Home") : ?>
	<script src="<?php echo ROOT_PATH . '/js/homepage.js'; ?>"></script>
<?php elseif ($pageTitle == "Timetable") : ?>
	<script src="<?php echo ROOT_PATH . '/js/timetable.js'; ?>"></script>
<?php endif; ?>

<body>

	<header>
		<a id="course-builder-header" href="<?php echo ROOT_PATH; ?>">
			<h3>Course Builder</h3>
		</a>

		<a id="login" href="<?php echo ROOT_PATH . '/admin.php'; ?>">
			Admin
		</a>
	</header>

	<div id="container">
