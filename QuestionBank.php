<?php
/**
 * jQuiz main class
 *
 * Author: Renan Martins
 */

/**
 * Keeps track of lessons, questions, and user performance.
 * Also connects to the database to retrieve questions.
 */
class QuestionBank {
	// For the database connection
	private $db_connection = null;

	// To keep track of the lessons available
	private $lessons = array();

	// To keep hold the questions for a specific lesson
	private $questions = array();

	// The number of questions available
	private $total_questions = 0;

	// The lesson the user is currently studying
	public $current_lesson = 0;

	// The user score
	private $score = 0;


	/**
	 * Constructor
	 */
	function __construct() {
		session_start();

		if (isset($_SESSION['current_lesson']) && !empty($_SESSION['current_lesson']))
			$current_lesson = $_SESSION['current_lesson'];

	}

	/**
	 * Must be called after calling fetchLessons();
	 */
	function init() {
		if (!isset($_SESSION['score']))
			$_SESSION['score'] = $this->score;
		else
			$this->score = $_SESSION['score'];

		$this->current_lesson = isset($_GET['lesson']) ? $_GET['lesson'] : $this->current_lesson;

		if (!isset($_SESSION['user_answers'])) {

			$_SESSION['user_answers'] = array();

			//$this->fetchLessons;

			foreach ($this->lessons as $l) {
				$numberOfQuestions = $this->countQuestions($l);

				$answers_array = array();
				for ($i = 0; $i < $numberOfQuestions; $i++)
					$answers_array[] = 'x';

				$_SESSION['user_answers'][$l] = $answers_array;

			}

			if (DEBUG_MODE)
				var_dump($_SESSION['user_answers']);

		}
	}

	/**
	 * Destroys the session
	 */
	 function reset() {
		 $_SESSION = array();
	 }

	/**
	 * Queries from the database and returns the result
	 */
	function query($sql) {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($this->db_connection->connect_errno) {
			echo "Failed to connect to MySQL: (" . $this->db_connection->connect_errno . ") " . $this->db_connection->connect_error;
		}

		// Sets the charset to UTF8
		if (!$this->db_connection->set_charset("utf8")) {
			printf("Error loading character set utf8: %s\n", $this->db_connection->error);
			exit();
		}

		$result = $this->db_connection->query($sql);

		$this->db_connection->close();

		return $result;
	}

	/**
	 * Fetches all the lessons available in the database
	 */
	function fetchLessons() {
		$sql = "SELECT number FROM lessons ORDER BY number;";

		$result = $this->query($sql);

		if ($result) {
			while ($lesson = $result->fetch_array(MYSQLI_ASSOC)) {
				// Add current row information to the lessons array
				$this->lessons[] = $lesson["number"];
			}

			if (DEBUG_MODE) {
				echo "<span class='debug-mode-message'>" . sizeof($this->lessons) . " lessons fetched: [ ";
				foreach ($this->lessons as $l)
					echo "$l ";
				echo "]</span>";
			}
		}
		else {
			echo "Your query did not work: (" . $this->db_connection->errno . ") " . $this->db_connection->error;
		}

	}

	/**
	 * Displays a list links to the lessons available
	 */
	function getLessons() {
		if (sizeof($this->lessons) > 0) {
			echo "<h2>Choose a lesson:</h2>";
			foreach ($this->lessons as $number) {
				echo '<a href="index.php?lesson=' . $number . '">Lesson ' . $number . '</a><br>';
			}
		}
		else {
			echo "There are no lessons available.";
		}

	}

	function countQuestions($lesson) {
		$sql = "SELECT COUNT(lesson) AS numberOfQuestions FROM questionbank WHERE lesson = $lesson;";

		$result = $this->query($sql);

		if ($result) {
			while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
				$count = $question["numberOfQuestions"];
			}

			if (DEBUG_MODE) {
				echo "<span class='debug-mode-message'>" . $count . " question";
				if ($count > 1)
					echo "s";
				echo " counted for $lesson.</span>";
			}
		}
		else {
			echo "Your query did not work: (" . $this->db_connection->errno . ") " . $this->db_connection->error;

			$count = 0;
		}

		return $count;
	}

	/**
	 * Retrieves all questions from the question bank
	 */
	function fetchQuestions() {
		$sql = "SELECT * FROM questionbank;";

		$result = $this->query($sql);

		if ($result) {
			while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
				$this->questions[] = $question;
				$this->total_questions++;
			}

			if (DEBUG_MODE)
				echo "<span class='debug-mode-message'>" . $this->total_questions . " questions fetched.</span>";
		}
		else {
			echo "Your query did not work: (" . $this->db_connection->errno . ") " . $this->db_connection->error;
		}
	}

	/**
	 * Fetches all questions from the question
	 * bank for a specific lesson
	 */
	function fetchQuestionsForLesson() {
		$sql = "SELECT * FROM questionbank WHERE lesson = " . $this->current_lesson . ";";

		$result = $this->query($sql);

		if ($result) {
			$this->total_questions = 0;

			while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
				$this->questions[] = $question;
				$this->total_questions++;
			}

			if (DEBUG_MODE)
				echo "<span class='debug-mode-message'>" . $this->total_questions . " questions fetched for lesson " . $this->current_lesson . ".</span>";

		}
		else {
			echo "Your query did not work: (" . $this->db_connection->errno . ") " . $this->db_connection->error;
		}
	}

	/**
	 * Fetches all questions from the session
	 * It assumes the session variable is already set
	 */
	function fetchQuestionsForLessonUseSession() {
		$this->questions = $_SESSION['questions'];

		if (DEBUG_MODE) {
			//var_dump($this->questions);
			var_dump($this->getQuestionsArrayByVal());
		}

		foreach ($this->questions as $q)
			$this->total_questions++;
		//$this->total_questions = count($this->questions, COUNT_RECURSIVE);

		if (DEBUG_MODE)
			echo "<span class='debug-mode-message'>" . $this->total_questions . " questions fetched (from session) for lesson " . $this->current_lesson . ".</span>";
	}

	/**
	 * Retrieves all questions from the question bank and displays them in a table
	 */
	function getQuestions() {
		$sql = "SELECT * FROM questionbank;";

		$result = $this->query($sql);

		if ($result) {
			while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
				$this->questions[] = $question;
			}
		}
		else {
			echo "Your query did not work: (" . $this->db_connection->errno . ") " . $this->db_connection->error;
		}

		echo "<table>";
		echo "<thead>";
		echo "<tr><th>Lesson</th><th>Question</th><th>a</th><th>b</th><th>c</th><th>d</th><th>Answer</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach ($this->questions as $qarray) {
			$q_lesson = $qarray["lesson"];
			$q_question = $qarray["question"];
			$q_choice_a = $qarray["choice_a"];
			$q_choice_b = $qarray["choice_b"];
			$q_choice_c = $qarray["choice_c"];
			$q_choice_d = $qarray["choice_d"];
			$q_answer = $qarray["answer"];

			echo "<tr>";
			echo "<td>$q_lesson</td><td>$q_question</td>";
			echo "<td>$q_choice_a</td><td>$q_choice_b</td><td>$q_choice_c</td><td>$q_choice_d</td>";
			echo "<td>$q_answer</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}

	/**
	 * Retrieves a specific question from the array of questions,
	 * given id
	 *
	 * This function is responsible for rendering the question as well
	 */
	function getQuestion($id) {

		if (isset($this->questions[$id])) {

			$active = true;

			$q_lesson = $this->questions[$id]["lesson"];
			$q_question = $this->questions[$id]["question"];
			$q_choice_a = $this->questions[$id]["choice_a"];
			$q_choice_b = $this->questions[$id]["choice_b"];
			$q_choice_c = $this->questions[$id]["choice_c"];
			$q_choice_d = $this->questions[$id]["choice_d"];
			$q_answer = $this->questions[$id]["answer"];

			if (isset($_GET['choice']) && !empty($_GET['choice'])) {

				switch ($q_answer) {
					case 'a':
						$user_answer = $q_choice_a;
						break;
					case 'b':
						$user_answer = $q_choice_b;
						break;
					case 'c':
						$user_answer = $q_choice_c;
						break;
					case 'd':
						$user_answer = $q_choice_d;
						break;
					default:
						//
				}

				if ($_GET['choice'] === $q_answer) {
					$user_answer = "<span style='color:green;'>$user_answer</span>";

					$this->updateScore(1);

					$_SESSION['user_answers'][$q_lesson][$id] = $q_answer;

					$got_it = true;
				}
				else {
					$user_answer = "<span style='color:blue;'>$user_answer</span>";

					$this->updateScore(-1);

					$_SESSION['user_answers'][$q_lesson][$id] = $_GET['choice'];

					$got_it = false;
				}

				$q_question = str_replace("＿＿＿", $user_answer, $q_question);

				$active = false;
			}


			echo "<div class='question-prompt'>" . ($id+1) . ": $q_question</div>";
			echo "<div class='question-choices'>";

			echo $this->itemLink($id, 'a', $q_choice_a, $active);

			if (!empty($q_choice_b)) {
				echo $this->itemLink($id, 'b', $q_choice_b, $active);

				if (!empty($q_choice_c)) {
					echo $this->itemLink($id, 'c', $q_choice_c, $active);

					if (!empty($q_choice_d))
						echo $this->itemLink($id, 'd', $q_choice_d, $active);
				}

			}

			echo "<span style='display:block;text-align:right;padding:6px;'>Score: " . $this->score . "</span>";

			if (isset($got_it)) {
				if ($got_it) {
					echo "<span class='response-message' style='color:green;'>You got it right!</span>";
				}
				else {
					echo "<span class='response-message' style='color:red;'>You got it wrong :/</span>";
				}



				if (++$id < $this->total_questions) {
					echo "<a href='index.php?lesson=$q_lesson&q=$id'><span class='continue-button'>Continue</span></a>";
				}
				else {
					echo "<span style='display:block;float:right;'>There are no more questions left.</span>";

					$this->getResults();
				}
			}

			echo "</div><!-- .question-choices -->";

		}
		else {
			echo "<span class='.question-missing'>That question does not exist.</span>";
		}
	}

	/**
	 * Assigns the array of questions to the given
	 * argument, the session array of questions
	 */
	function setSessionQuestionsArray(&$session_array) {
		$session_array = $this->questions;
	}

	/**
	 * Returns the array of questions
	 */
	function getQuestionsArrayByVal() {
		return $this->questions;
	}

	/**
	 * Returns a formatted question item
	 */
	function itemLink($q_id, $item, $choice, $active = true) {
		$q_answer = $this->questions[$q_id]["answer"];

		/*
		 * 1: check mark
		 * 2: x mark
		 * 3: finger pointing left
		 */
		$symbol = 0;

		if (!$active)
			$user_item_choice = $_GET['choice'];

		$format = "<span class='question-item'";

		if ($active) {
			$format .= ">";
			$format .= "<a href='index.php?lesson=";
			$format .= $this->current_lesson . "&q=$q_id&choice=$item'>";
		}
		else {
			$format .= " style='background-color: inherit;";

			if ($item === $user_item_choice && $item === $q_answer) {
				$format .= "color: green;font-size:larger;";
				$symbol = 1; // check mark
			}
			elseif ($item !== $user_item_choice && $item === $q_answer) {
				$format .= "color: blue;font-size:larger;";
				$symbol = 3; // finger pointing left
			}
			elseif ($item === $user_item_choice && $item !== $q_answer) {
				$format .= "color: red;";
				$symbol = 2; // x mark
			}

			$format .= "'>";
		}

		$format .= "$item) $choice";

		if ($active)
			$format .= "</a>";

		if ($symbol == 1)
			$format .= " ✔";
		elseif ($symbol == 2)
			$format .= " ✘";
		elseif ($symbol == 3)
			$format .= " ☜";

		$format .= "</span>";

		return $format;
	}

	/**
	 * Updates the user score, given the amount to increase/decrease
	 */
	function updateScore($amount) {
		$this->score += $amount;
		$_SESSION['score'] = $this->score;
	}

	/**
	 * Evaluates the user performance
	 * Must be called after all questions have been answered
	 */
	function getResults() {
		$questions = $this->total_questions;
		$lesson = $this->current_lesson;

		$correct = 0;
		$wrong = 0;

		foreach ($_SESSION['user_answers'][$lesson] as $index => $a)
		{
			if ($a !== 'x' && $a === $this->questions[$index]['answer'])
				$correct++;
			elseif ($a !== 'x' && $a !== $this->questions[$index]['answer'])
				$wrong++;
			else
				; // unaswered
		}

		$questions_answered = $correct + $wrong;

		echo "<br><div class='results-panel'><h4>Results</h4>Answered $questions_answered questions.<br>Got $correct questions right and $wrong questions wrong.<br>Success Ratio: $correct/$questions" . " (" . number_format($correct/$questions*100, 2) . " %)</div>";
	}
}

?>
