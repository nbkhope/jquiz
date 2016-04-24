<?php

class Response {
	/* Attributes */
	private $id;
	private $description;
	private $isRightChoice;

	/* Constructor */
	function __construct($id, $description, $isRightChoice = false) {
		$this->id = $id;
		$this->description = $description;
		$this->isRightChoice = $isRightChoice;
	}

	/* Getter Methods */
	function getId() {
		return $this->id;
	}

	function getDescription() {
		return $this->description;
	}

	function isRightChoice() {
		return $this->isRightChoice;
	}
}

// header('Content-type: text/plain; charset=utf-8');

// $choices = array("どこ", "だれ", "どれ", "なに");
// $responses = array();

// for($i = 0; $i < 4; $i++) {
// 	$response = new Response($i + 1, $choices[$i]);
// 	array_push($responses, $response);
// 	echo "id " . $responses[$i]->getId() . ": " . $responses[$i]->getDescription() . "\n";
// } 

?>
