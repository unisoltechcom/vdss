<?php
session_start();
require '../includes/functions.php';
include_once '../config.php';
$table = $_REQUEST['tablename'];
$table_id = $_REQUEST['tableid'];
$ids = $_REQUEST['ids'];

 $query = "SELECT Ins_First_Name,Ins_Last_Name,schoolName,course,lessonDate,hours,sub FROM `inc_lectr_log` as incl left join Instructor_information as inc on `incl`.`Ins_id`=`inc`.`Ins_id` where incl.`isActive`=1  and `incl`.`incLectrID` IN ($ids) ORDER BY `incl`.`incLectrID` DESC";
$response = execute_query($query);
$new_array = array();
foreach($response as $array){
  foreach ($array as $key => $val){
	  
    if (is_numeric($key)){  // only numbers, a point and an `e` like in 1.1e10
        unset($array[$key]);
		 }
  }
  $data[] = $array;
  }
       


  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "website_data_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;
?>