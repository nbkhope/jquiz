<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="robots" content="index, nofollow">
<meta name="description" content="Practice for the JLPT by taking a quiz">
<meta name="keywords" content="JLPT, test, Japanese, language, practice, Japan">
<meta name="author" content="Renan Martins">
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

//$qbank->fetchQuestionsForLesson();
?>

<header class="site-header">
<span class="site-title">jQuiz</span>
</header>	

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

<section class="site-content">

<?php

if (isset($_GET['lesson']) && !empty($_GET['lesson'])) {

	$qbank->fetchQuestionsForLesson();

	$qbank->current_lesson = $_GET['lesson'];

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
	echo "<h1>Studying for the JLPT</h1>";
	$qbank->getLessons();
}

?>

</section>

<footer class="site-footer">
	<span class="site-copyright">Copyright © 2015 <a href="mailto:ryumemaru@yahoo.com?subject=jQuizWeb">Renan Martins</a></span>
</footer>

</body>
</html>
