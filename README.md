# jquiz
A simple quiz website to help study for the JLPT Japanese proficiency test.

This web application is still under development, but as of now is already in a usable state.

## Requirements
To run jQuiz, you will need a server with support for 
* PHP
* MySQL

## Installation
Create a database called "jquiz". Then, with that database active, perform the following commands:
```
source questionbank.sql
LOAD DATA LOCAL INFILE 'QuestionBank.csv' INTO TABLE questionbank;
```
Note that you need to be within the sql/ directory before using the mysql command to connect to the server. Also, make sure to enable loading local file using the following command
```
mysql --local-infile --default-character-set=utf8 -u your_db_user -p jquiz 
```
After creating the database and creating and populating the questionbank table, you need to adjust your PHP database connection settings. Create a file named **db_auth.php** within jquiz/ and define four variables for database authentication:
```
<?php
/**
 * Database settings
 */
define("DB_HOST", "localhost");
define("DB_USER", "your_db_user");
define("DB_PASS", "your_password");
define("DB_NAME", "jquiz");
?>
```
Modify the definitions to conform with your system settings.

## Adding More Questions
Just go to **sql/** and modify the file **QuestionBank.csv**. You can open this with a spreadsheet software like Microsoft Excel or LibreOffice Calc. Make sure to maintain the settings that it is a CSV file using a TAB as the delimiter. Use **\N** for question items that are not used. After making changes to QuestionBank.csv, recreate the database table using the commands presented above in the Installation section.

## Security
The directory sql/ is just initially used to set up the database and should not be available to the public. Also be careful with db_auth.php, as its contents should not be publicly displayed.

