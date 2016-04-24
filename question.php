<?php

class Question {
	/* Attributes */
	private $id;
	private $prompt;
	private $responses;

	/* Constructor */
	function __construct($id, $prompt, $responses = array()) {
		$this->id = $id;
		$this->prompt = $prompt;
		$this->responses = $responses;
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

	function countResponses() {
		return count($this->responses);
	}

	function getAnswer() {
		foreach($this->responses as $response) {
			if ($response->isRightChoice())
				return $response->getDescription();
		}
		return null;
	}

	function addResponse($response) {
		$this->responses[] = $response;
	}
}

// $question = new Question(1, "What is 'where' in Japanese?");
// echo "Question: " . $question->getPrompt() . " (id: " . $question->getId() . ")";

?>
