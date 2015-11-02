/**
 * MySQL database table for the Question Bank
 */

DROP TABLE IF EXISTS `questionbank`;

CREATE TABLE IF NOT EXISTS `jquiz`.`questionbank` (
	id INT NOT NULL AUTO_INCREMENT,
	level INT(11) NOT NULL,
	lesson INT(11) NOT NULL,
	question VARCHAR(512) NOT NULL DEFAULT "Unknown Question",
	choice_a VARCHAR(512) NOT NULL DEFAULT "Unknown Answer",
	choice_b VARCHAR(512),
	choice_c VARCHAR(512),
	choice_d VARCHAR(512),
	answer CHAR(1) NOT NULL DEFAULT 'a',
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

