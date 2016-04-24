<?php

class Question {
	/* Attributes */
	private $id;
	private $prompt;
	private $responses;

	/* Constructor */
	function __construct($id, $prompt) {
		$this->id = $id;
		$this->prompt = $prompt;
		$this->responses = array();
	}

	/* Getter Methods */
	function getId() {
		return $this->id;
	}

	function getPrompt() {
		return $this->prompt;
	}

	function getResponses() {
		return $this->responses;
	}
}

$question = new Question(1, "What is 'where' in Japanese?");
echo "Question: " . $question->getPrompt() . " (id: " . $question->getId() . ")";

?>
