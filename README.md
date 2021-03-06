# jquiz

A quiz web application to help study for the JLPT Japanese proficiency test.

![jQuiz Screenshot](screenshot.png "jQuiz")

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

## Character Encoding Issues

If you are having problems with character encoding, first use the following
command to determine whether MySQL is using utf8:

```
mysql> SHOW VARIABLES LIKE 'char%';
```

You will get a listing with the type of encoding using by the client, database,
server, etc. Make sure they are all using utf8, otherwise you will run into
problems with character encoding.

```
+--------------------------+----------------------------+
| Variable_name            | Value                      |
+--------------------------+----------------------------+
| character_set_client     | utf8                       |
| character_set_connection | utf8                       |
| character_set_database   | latin1                     |
| character_set_filesystem | binary                     |
| character_set_results    | utf8                       |
| character_set_server     | latin1                     |
| character_set_system     | utf8                       |
| character_sets_dir       | /usr/share/mysql/charsets/ |
+--------------------------+----------------------------+
```

If your database has encoding other than utf8 (like latin1), use the following
command to alter its default character set:

```
mysql> ALTER SCHEMA `jquiz` DEFAULT CHARACTER SET utf8;
```
