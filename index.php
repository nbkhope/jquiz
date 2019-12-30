<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<meta name="robots" content="index, nofollow">
	<meta name="description" content="Practice for the JLPT by taking a quiz">
	<meta name="keywords" content="JLPT, test, Japanese, language, practice, Japan">
	<meta name="author" content="nbkhope">

	<title>jQuiz</title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<script>
	$(document).ready(function() {
		$("#lesson-menu").click(function() {
			$(".lesson-menu").toggle();
		});
	});
	</script>
</head>
<body>

<?php

require_once("settings.php");
require_once("db_auth.php");
require_once("QuestionBank.php");

$qbank = new QuestionBank();

if (isset($_GET['reset']))
	$qbank->reset();

$qbank->fetchLessons();

$qbank->init();

?>

<div class="site-wrapper">

<header class="site-header">
	<div id="site-title"><?php echo SITE_TITLE; ?></div>

	<nav class="site-menu">
		<ul>
			<li><a href="index.php">Home</a></li>
			<?php if (isset($_GET['lesson'])) { ?>
			<li><span id="lesson-menu" style="cursor:pointer;">Lessons</span></li>
			<?php } ?>
			<li><a href="index.php?reset">Reset</a></li>
		</ul>
		<div class="lesson-menu">
		<?php $qbank->getLessons(); ?>
		</div>
	</nav>
</header>

<main class="site-content">
<section>

<?php

if (isset($_GET['lesson']) && !empty($_GET['lesson'])) {

	// If questions are already stored in the session, it will not
	// query from the database. Otherwise, for that or for a different
	// lesson (from the current lesson), the questions will be reloaded
	// from the database
	if (isset($_SESSION['questions']) && !empty($_SESSION['questions'])) {
		if ($_GET['lesson'] !== $_SESSION['current_lesson']) {

			$qbank->current_lesson = $_GET['lesson'];
			$_SESSION['current_lesson'] = $qbank->current_lesson;

			$qbank->fetchQuestionsForLesson();

			$qbank->setSessionQuestionsArray($_SESSION['questions']);
		}
		else {
			$qbank->fetchQuestionsForLessonUseSession();
		}
	}
	else {
		$qbank->current_lesson = $_GET['lesson'];
		$_SESSION['current_lesson'] = $qbank->current_lesson;

		$qbank->fetchQuestionsForLesson();

		$qbank->setSessionQuestionsArray($_SESSION['questions']);
	}

	echo "<h1>Lesson " . $qbank->current_lesson . "</h1>";

	echo '<div class="question-area">';

	if (!isset($_GET['q'])) {
		$q = 0;
	}
	elseif (!empty($_GET['q'])) {
		$q = $_GET['q'];
	}
	else {
		$q = 0;
	}

	$qbank->getQuestion($q);

	echo '</div><!-- .question-area -->';
}
else {
	echo "<h1>" . SITE_SUBTITLE . "</h1>";
	$qbank->getLessons();
}

?>

</section>
</main>

<footer class="site-footer">
	<span id="site-copyright">Copyright © 2015 nbkhope</span>
</footer>

</div><!-- .site-wrapper -->
</body>
</html>
