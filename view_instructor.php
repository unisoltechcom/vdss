<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$s = new StudentForm;
$response = $s->select_instructor_byid($_REQUEST['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$classdata = $_POST;
	//print_r($classdata);
	//SELECT *  FROM `class_log` where STR_TO_DATE(InClassDate, '%m/%d/%Y') Between  '2017-01-01' and '2017-05-01' group by STR_TO_DATE(InClassDate, '%m/%d/%Y')
	$response = $s->add_instructor($classdata);
	if ($response == 'true') {
		$_SESSION['recordsave'] = 'You have successfully saved your form!';	
	} else {
	  mySqlErrors($response);
	 }
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Student Information</title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"     rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Javascript -->

<script>
 jQuery(document).ready(function($) {
  $("#datepicker-13, #datepicker-14, #datepicker-11, #datepicker-15, #datepicker-16, #datepicker-17, #datepicker-18").each(function() {
     $(this).datepicker({
       autoclose: true
     });
  });
});
</script>
</head>

<body>
<div class="wrapper">
  <center>
    <?php include_once('nav.php'); ?>
  </center>  
  <div class="student">Instructor Information</div>
  <div class=" clearfix"></div>
  <br />
  
    <div class=" clearfix"></div>
    <div class="row">
      <div class="col-md-12 reportview">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >First Name :</label>
            <div class="col-sm-8"><?=$response['Ins_First_Name']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Last Name:</label>
            <div class="col-sm-8"><?=$response['Ins_Last_Name']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Email:</label>
            <div class="col-sm-8"><?=$response['ins_email']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Gender:</label>
            <div class="col-sm-8"><?=$response['Ins_Gender']?></div>
          </div>
        </div>
     
     <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Address:</label>
            <div class="col-sm-8"><?=$response['Ins_Address']?></div>
          </div>
        </div>
      <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Rate:</label>
            <div class="col-sm-8"><?=$response['Ins_Rate']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Joining Date:</label>
            <div class="col-sm-8"><?=$response['Ins_Joining_Date']?></div>
          </div>
        </div>  
     
     
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <div class="col-sm-10 text-right">
          </div>
        </div>
      </div>
    </div>
  <div class=" clearfix"></div>
</div>

</body>
</html>
