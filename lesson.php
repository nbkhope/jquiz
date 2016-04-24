<?php

class Lesson {
	/* Attributes */
	private $id;
	private $number;
	private $questions;

	/* Constructor */
	function __construct($id, $number, $questions = array()) {
		$this->id = $id;
		$this->number = $number;
		$this->questions = $questions;
	}

	/* Getter Methods */
	function getId() {
		return $this->id;
	}

	function getNumber() {
		return $this->number;
	}

	function getQuestions() {
		return $this->questions;
	}

	function getQuestion($index) {
		if ($index < countQuestions())
			return $this->questions[$index];
		//else
			// invalid index
	}

	function countQuestions() {
		return count($this->questions);
	}

	function addQuestion($question) {
		# Pushes a question into the questions array
		$this->questions[] = $question;
	}
}

// $lesson = new Lesson(1, 25);
// echo "Lesson " . $lesson->getNumber() . " (id: " . $lesson->getId() . ")";

?>
