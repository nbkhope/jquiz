/**
 * MySQL database table for the Question Bank
 */

DROP TABLE IF EXISTS `questionbank`;
DROP TABLE IF EXISTS `responses`;
DROP TABLE IF EXISTS `questions`;
DROP TABLE IF EXISTS `lessons`;


CREATE TABLE IF NOT EXISTS `jquiz`.`questionbank` (
	lesson INT(11) NOT NULL,
	question VARCHAR(512) NOT NULL DEFAULT "Unknown Question",
	choice_a VARCHAR(512) NOT NULL DEFAULT "Unknown Answer",
	choice_b VARCHAR(512),
	choice_c VARCHAR(512),
	choice_d VARCHAR(512),
	answer CHAR(1) NOT NULL DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lessons` (
	id INT NOT NULL AUTO_INCREMENT,
	number INT NOT NULL,
	PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `questions` (
	id INT NOT NULL AUTO_INCREMENT,
	prompt VARCHAR(256) NOT NULL,
	lesson_id INT,
	PRIMARY KEY(id),
	FOREIGN KEY(lesson_id) REFERENCES lessons(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `responses` (
	id INT NOT NULL AUTO_INCREMENT,
	prompt VARCHAR(256) NOT NULL,
	is_right_answer BOOLEAN,
	question_id INT,
	PRIMARY KEY(id),
	FOREIGN KEY(question_id) REFERENCES questions(id)	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
