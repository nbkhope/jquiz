<?php

class Lesson {
	/* Attributes */
	private $id;
	private $number;
	private $questions;

	/* Constructor */
	function __construct($id, $number) {
		$this->id = $id;
		$this->number = $number;
		$this->questions = array();
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
}

$lesson = new Lesson(1, 25);
echo "Lesson " . $lesson->getNumber() . " (id: " . $lesson->getId() . ")";

?>
