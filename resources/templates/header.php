<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/css/main.css" type="text/css">

	<?php if ($pageTitle == "Administrator Login" || $pageTitle == "Administrator") : ?>
		<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/css/admin.css" type="text/css">	
	<?php elseif ($pageTitle == "Timetable") : ?>
		<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/css/tableList.css" type="text/css">	
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
	<script src="<?php echo ROOT_PATH . '/js/tableList.js'; ?>"></script>
<?php endif; ?>

<body>

	<header>
		<a id="course-builder-header" href="<?php echo ROOT_PATH; ?>">
			<h3>Course Builder</h3>
		</a>

		<a id="login" href="<?php echo ROOT_PATH . '/help.php'; ?>">
			Help
		</a>

		<a id="login" href="<?php echo ROOT_PATH . '/admin.php'; ?>">
			Admin
		</a>
	</header>

	<!-- Popup Messages -->
	<div id="light" class="popupContent"></div>
	<div id="fade" class="blackOverlay"></div>

	<div id="container">
