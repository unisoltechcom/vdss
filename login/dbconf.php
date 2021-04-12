<?php
//DATABASE CONNECTION VARIABLES
$host = "localhost"; // Host name
$username = "valleydr_valley2"; // Mysql username
$password = "hT8Wru5VD2"; // Mysql password
$db_name = "valleydr_driving"; // Database name

/*$link = mysqli_connect($host, $username, $password) or die('Could not connect the database : Username or password incorrect');
mysqli_select_db($link, $db_name) or die("Could not open the db '$dbname'");
$test_query = "SHOW TABLES FROM $db_name";
$result = mysqli_query($link, $test_query);
$tblCnt = 0;
while($tbl = mysqli_fetch_array($result)) {
$tblCnt++;
#echo $tbl[0]."<br />\n";
}
if (!$tblCnt) {
echo "There are no tables<br />\n";
} else {
echo "There are $tblCnt tables<br />\n";
}  */
//DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

$tbl_prefix = ""; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
$tbl_members = $tbl_prefix."members";
$tbl_student_information = $tbl_prefix."student_information";
$tbl_attempts = $tbl_prefix."loginattempts";
