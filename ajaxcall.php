<?php 
require 'includes/functions.php';
include_once 'config.php';
if($_POST['classlog']=='classlog') {
	$dc = new Database;
	$stdid = $_POST['StudentID'];
	$query = "select sum(Hours) as hr from class_log where StudentID=$stdid GROUP BY StudentID";
	$record = $dc->fetch_array_all($query);
	$total = $record[0][0] ? $record[0][0] : 0;
	
		
  	$array = array();
	
	if($total < 20){
			$array['msg'] =  "<b>Remaining Hours: ".(20-$total)."</b>"; $array['disable'] = 1 ;
		}
		
     else if ($total >= 20){ 		
			$array['msg'] =  "<b>Remaining Hours: ".(20-$total)."</b>";
			$array['disable'] = 2 ;		
		}
	
	print json_encode($array);
	}
	
	if($_POST['classlog']=='carlog') {
	$dc = new Database;
	$stdid = $_POST['StudentID'];
	$query = "select sum(Hours) as hr from car_log where StudentID=$stdid  GROUP BY StudentID";
	$record = $dc->fetch_array_all($query);
	$total = $record[0][0] ? $record[0][0] : 0;
		
  	$array = array();
	
	if($total < 10){
			$array['msg'] =  "<b>Remaining Hours: ".(10-$total)."</b>"; $array['disable'] = 1 ;
		}
		
     else if ($total >= 10){ 		
			$array['msg'] =  "<b>Remaining Hours: ".(10-$total)."</b>";
			$array['disable'] = 2 ;		
		}
	
	print json_encode($array);
	}

?>