<?php  
//DATABASE CONNECTION VARIABLES
/*$host = "localhost"; // Host name
$username = "root"; // Mysql username
$password = ""; // Mysql password
$db_name = "drivingschool"; // Database name
define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD','');
define('DATABASENAME','drivingschool');
define('TABLEPREFIX','');
define('EMAIL','');
*/
//DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

$tbl_prefix = ""; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
$tbl_members = $tbl_prefix."members";
 $student_information = $tbl_prefix."student_information";
$tbl_attempts = $tbl_prefix."loginAttempts";
