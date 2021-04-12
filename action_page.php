<?php
session_start();
require 'includes/functions.php';
include_once 'config.php';
$s = new StudentForm;

if (@$_POST['add']=='add') {
	unset($_POST['add']);
	$studentdata = $_POST;
//	print_r($studentdata);
//	print("<br>aa");
//	print_r(str_replace("'","`",$studentdata));
//	exit;
	$response = $s->createStudent(str_replace("'","`",$studentdata));
//$response = $s->createStudent($studentdata);
	
	
	
	if ($response == 'true') {
	$_SESSION['recordsave'] = 'Record has been saved successfully!';
	ob_clean();
			 print "<script>window.location = './';</script>";
	} else {
	  mySqlErrors($response);
	 } 
 }
 if (@$_POST['update']=='studentupdate') {
	unset($_POST['update']);
	$StudentID = $_POST['StudentID'];
	unset($_POST['StudentID']);
	$Installment = serialize(str_replace("'","`",$_POST['Installment']));
     unset($_POST['Installment']);
	$studentdata = $_POST;
	$studentdata['Installment']=$Installment;
	$response = $s->update_student($StudentID , str_replace("'","`",$studentdata));
    //$response = $s->update_student($StudentID , $studentdata);
	

	
	if ($response == 'true') {
	$_SESSION['recordsave'] = 'Record has been updated successfully!';
	ob_clean();
			 print "<script>window.location = 'studentlist.php';</script>";
	} else {
	  mySqlErrors($response);
	 } 
 }

if (@$_REQUEST['mode']=='delete' && @$_REQUEST['form']=='student' ) { 
	  $s->delete_student(mysql_real_escape_string($_GET['id']));
	   print "<script>window.location = 'studentlist.php';</script>";
}


if (@$_REQUEST['mode']=='selectedDelete' && @$_REQUEST['form']=='student' ) { 
      $record =$_GET['id'];
			$StudentID = explode(',', $record);
			
			foreach($StudentID as $val)
			{
				$s->delete_student(mysql_real_escape_string($val));
			}
	 print "<script>window.location = 'studentlist.php';</script>";
  }
  
  
  
  if (@$_REQUEST['mode']=='restore' && @$_REQUEST['form']=='student' ) { 
	  $s->restore_student(mysql_real_escape_string($_GET['id']));
	   print "<script>window.location = 'trash_student.php';</script>";
}


if (@$_REQUEST['mode']=='selectedRestore' && @$_REQUEST['form']=='student' ) {  
        $record =$_GET['id'];
			$StudentID = explode(',', $record);
			
			foreach($StudentID as $val)
			{
				$s->restore_student(mysql_real_escape_string($val));
			}
	 print "<script>window.location = 'trash_student.php';</script>"; 
 }
 if (@$_REQUEST['mode']=='selectedPDelete' && @$_REQUEST['form']=='student' ) {  
        $record =$_GET['id'];
			$StudentID = explode(',', $record);
			foreach($StudentID as $val)
			{
				$s->delete_student_permanent(mysql_real_escape_string($val));
			}
	 print "<script>window.location = 'trash_student.php';</script>"; 
 }
  
  /*student area end*/
  
  
  /*class log Start*/
  //$where = array( "StudentID" => $StudentID);
  if (@$_REQUEST['mode']=='delete' && @$_REQUEST['form']=='classLog' ) { 
      $where = array( "logClassID" => mysql_real_escape_string($_GET['id']));
	  $s->delete_query('class_log',$where);
	   print "<script>window.location = 'classloglist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedDelete' && @$_REQUEST['form']=='classLog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "logClassID" => mysql_real_escape_string($val));
				$s->delete_query('class_log', $where);
			}
	 print "<script>window.location = 'trash_classloglist.php';</script>";
  }
if (@$_REQUEST['mode']=='selectedPDelete' && @$_REQUEST['form']=='classLog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);	 
			foreach($StudentID as $val)
			{   $where = array( "logClassID" => mysql_real_escape_string($val));
				$s->delete_query_permanent($where , 'class_log' );
			}
	 print "<script>window.location = 'trash_classloglist.php';</script>";
  }
if (@$_REQUEST['mode']=='restore' && @$_REQUEST['form']=='classLog' ) { 
       $where = array( "logClassID" => mysql_real_escape_string($_GET['id']));
	  $s->restore_query('classLog',$where);
	   print "<script>window.location = 'trash_classloglist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedRestore' && @$_REQUEST['form']=='classLog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "logClassID" => mysql_real_escape_string($val));
				$s->restore_query('classLog',$where);
			}
	 print "<script>window.location = 'trash_classloglist.php';</script>";
  }  
  
  /*class log end*/
  
    /*car log Start*/
  if (@$_REQUEST['mode']=='delete' && @$_REQUEST['form']=='carlog' ) { 
      $where = array( "logCarID" => mysql_real_escape_string($_GET['id']));
	  $s->delete_query('car_log',$where);
	   print "<script>window.location = 'carloglist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedDelete' && @$_REQUEST['form']=='carlog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "logCarID" => mysql_real_escape_string($val));
				$s->delete_query('car_log', $where);
			}
	 print "<script>window.location = 'trash_carloglist.php';</script>";
  }
if (@$_REQUEST['mode']=='selectedPDelete' && @$_REQUEST['form']=='carlog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);	 
			foreach($StudentID as $val)
			{   $where = array( "logCarID" => mysql_real_escape_string($val));
				$s->delete_query_permanent($where , 'car_log' );
			}
	 print "<script>window.location = 'trash_carloglist.php';</script>";
  }
if (@$_REQUEST['mode']=='restore' && @$_REQUEST['form']=='carlog' ) { 
       $where = array( "logCarID" => mysql_real_escape_string($_GET['id']));
	  $s->restore_query('car_log',$where);
	   print "<script>window.location = 'trash_carloglist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedRestore' && @$_REQUEST['form']=='carlog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "logCarID" => mysql_real_escape_string($val));
				$s->restore_query('car_log',$where);
			}
	 print "<script>window.location = 'trash_carloglist.php';</script>";
  }
  
 /*car log end*/
 /*instructorlist log*/  
  if (@$_REQUEST['mode']=='delete' && @$_REQUEST['form']=='inslog' ) { 
	  $where = array( "incLectrID" => mysql_real_escape_string($_GET['id'])); 
	  $s->delete_query('inc_lectr_log',$where);
	   print "<script>window.location = 'instructorloglist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedDelete' && @$_REQUEST['form']=='inslog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "incLectrID" => mysql_real_escape_string($val));
				$s->delete_query('inc_lectr_log', $where);
			}
	 print "<script>window.location = 'trash_instructorlog.php';</script>";
  }
if (@$_REQUEST['mode']=='selectedPDelete' && @$_REQUEST['form']=='inslog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);	 
			foreach($StudentID as $val)
			{   $where = array( "incLectrID" => mysql_real_escape_string($val));
				$s->delete_query_permanent($where , 'inc_lectr_log' );
			}
	 print "<script>window.location = 'trash_instructorlog.php';</script>";
  }
if (@$_REQUEST['mode']=='restore' && @$_REQUEST['form']=='inslog' ) { 
       $where = array( "incLectrID" => mysql_real_escape_string($_GET['id']));
	  $s->restore_query('inc_lectr_log',$where);
	   print "<script>window.location = 'trash_instructorlog.php';</script>";
}
if (@$_REQUEST['mode']=='selectedRestore' && @$_REQUEST['form']=='inslog' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{	$where = array( "incLectrID" => mysql_real_escape_string($val));
				$s->restore_query('inc_lectr_log',$where);
			}
	 print "<script>window.location = 'trash_instructorlog.php';</script>";
  }

  
  
/*instructorlist*/  
  
if (@$_REQUEST['mode']=='delete' && @$_REQUEST['form']=='ins' ) { 
	  $s->delete_instructor(mysql_real_escape_string($_GET['id']));
	   print "<script>window.location = 'instructorlist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedDelete' && @$_REQUEST['form']=='ins' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{
				$s->delete_instructor(mysql_real_escape_string($val));
			}
	 print "<script>window.location = 'instructorlist.php';</script>";
  }
if (@$_REQUEST['mode']=='selectedPDelete' && @$_REQUEST['form']=='ins' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);	 
			foreach($StudentID as $val)
			{   $where = array( "Ins_id" => mysql_real_escape_string($val));
				$s->delete_query_permanent($where , 'Instructor_information' );
			}
	 print "<script>window.location = 'trash_instructorlist.php';</script>";
  }
if (@$_REQUEST['mode']=='restore' && @$_REQUEST['form']=='ins' ) { 
	  $s->restore_instructor(mysql_real_escape_string($_GET['id']));
	   print "<script>window.location = 'trash_instructorlist.php';</script>";
}
if (@$_REQUEST['mode']=='selectedRestore' && @$_REQUEST['form']=='ins' ) { 
            $record =$_GET['id'];
			$StudentID = explode(',', $record);			
			foreach($StudentID as $val)
			{
				$s->restore_instructor(mysql_real_escape_string($val));
			}
	 print "<script>window.location = 'trash_instructorlist.php';</script>";
  }
  
 

?>