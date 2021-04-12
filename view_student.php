<?php session_start();
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
require 'includes/functions.php';
include_once 'config.php';
$s = new StudentForm;
$response = $s->select_student_byid($_REQUEST['id']);

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
  $("#datepicker-13, #datepicker-14, #datepicker-11, #datepicker-15, #datepicker-16, #datepicker-17, #datepicker-18, #complete").each(function() {
     $(this).datepicker({
       autoclose: true
     });
  });
});
</script>
</head>
<style>
.reportview .table {
	margin-bottom:0;
}
</style>
<body class="reportview">
<div class="wrapper">
  <center>
    <?php include_once('nav.php'); ?>
  </center>
  <div class="row">
    <div class="col-md-12">
      <div class="student">Student Information</div>
      <br />
      <div class="col-md-4 col-sm-6 reportview">
        <div class="form-group">
          <label class="control-label col-sm-6" >First Name:</label>
          <div class="col-sm-6">
            <?=$response['FirstName']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Last Name:</label>
          <div class="col-sm-6">
            <?=$response['LastName']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Gender:</label>
          <div class="col-sm-6 text-capitalize">
            <?=$response['Gender']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Address:</label>
          <div class="col-sm-6">
            <?=$response['Address']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >City:</label>
          <div class="col-sm-6">
            <?=$response['City']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Postal Code:</label>
          <div class="col-sm-6">
            <?=$response['PostalCode']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Phone:</label>
          <div class="col-sm-6">
            <?=$response['Phone']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Date Of Birth:</label>
          <div class="col-sm-6">
            <?php  $DateOfBirth = explode("/",$response['DateOfBirth']); ?>
            <?=$months[$DateOfBirth[1]].' '.$DateOfBirth[0].' '.$DateOfBirth[2]?>
          </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-6" >Emergency Contact:</label>
            <div class="col-sm-6">            
                <?=$response['nameEmergency']?>
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" >Emergency Phone:</label>
            <div class="col-sm-6">            
               <?=$response['phoneEmergency']?>
              </div>
          </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Notes:</label>
          <div class="col-sm-6">
            <?=$response['Notes']?>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reportview">
        <div class="form-group">
          <label class="control-label col-sm-6" >School:</label>
          <div class="col-sm-6">
            <?=$response['School']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Licence:</label>
          <div class="col-sm-6">
            <?=$response['Licence']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Obtained:</label>
          <div class="col-sm-6">
            <?php $Obtained = explode("/",$response['Obtained']); ?>
            <?=$months[$Obtained[1]].' '.$Obtained[0].' '.$Obtained[2]?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Expire:</label>
          <div class="col-sm-6">
            <?php $Expire = explode("/",$response['Expire']); ?>
            <?=$months[$Expire[1]].' '.$Expire[0].' '.$Expire[2]?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Licence Class:</label>
          <div class="col-sm-6">
            <?=$response['LicenceClass']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Class Start:</label>
          <div class="col-sm-6">
            <?php $ClassStart = explode("/",$response['ClassStart']); ?>
            <?=$months[$ClassStart[1]].' '.$ClassStart[0].' '.$ClassStart[2]?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Class End:</label>
          <div class="col-sm-6">
            <?php $ClassEnd = explode("/",$response['ClassEnd']); ?>
            <?=$months[$ClassEnd[1]].' '.$ClassEnd[0].' '.$ClassEnd[2]?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Class Marks:</label>
          <div class="col-sm-6">
            <?=$response['ClassMarks']?>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reportview">
        <div class="form-group">
          <label class="control-label col-sm-6" >In Car Start:</label>
          <div class="col-sm-6">
            <?php $InCarStart = explode("/",$response['InCarStart']); ?>
            <?=$months[$InCarStart[1]].' '.$InCarStart[0].' '.$InCarStart[2]?>
          </div>
        </div>
        <!--<div class="form-group">
            <label class="control-label col-sm-6" >HRS in Cars:</label>
            <div class="col-sm-6"><?=$response['HRSInCars']?></div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" >HRS Remaining:</label>
            <div class="col-sm-6"><?=$response['HRSRemaining']?> </div>
          </div>-->
        <div class="form-group">
          <label class="control-label col-sm-6" >In Car End:</label>
          <div class="col-sm-6">
            <?php $InCarEnd = explode("/",$response['InCarEnd']); ?>
            <?=$months[$InCarEnd[1]].' '.$InCarEnd[0].' '.$InCarEnd[2]?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >In Car Marks:</label>
          <div class="col-sm-6">
            <?=$response['InCarMarks']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Certified Date:</label>
          <div class="col-sm-6">
            <?=$response['Completed']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Course Fee:</label>
          <div class="col-sm-6"><?php print $response['CourseFee'] >= 0 ? '$' : ''; ?>
            <?=$response['CourseFee']?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-6" >Owing:</label>
          <div class="col-sm-6"><?php print $response['Owing'] > 0 ? '$' : ''; ?>
            <?=$response['Owing']?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-9 text-right"> </div>
        </div>
      </div>
    </div>
  </div>
  <br />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top"><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
          <caption class="caption">
          <b>Classroom Instruction</b>
          </caption>
          <thead>
            <tr>
              <th width="40%">Date</th>
              <th width="20%">Hours</th>
              <th width="40%">Instructor</th>
            </tr>
          </thead>
          <tbody>
            <?php $Class = $s->class_log_byid($response['StudentID']); 
		$hour =0;
		foreach($Class as $val) {  $dateformt = explode("/",$val['InClassDate']);	
		$hour = $hour + $val['Hours'];?>
            <tr>
              <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
              <td><?=$val['Hours']?></td>
              <td><?=$val['Instructor']?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><strong>Total Hours</strong></td>
              <td><?=$hour?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
      <td valign="top"><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
          <caption class="caption">
          <b>In Car Instruction</b>
          </caption>
          <thead>
            <tr>
              <th width="25%">Date</th>
              <th width="10%">Hours</th>
              <th width="35%">Instructor</th>
              <th width="30%"></th>
            </tr>
          </thead>
          <tbody>
            <?php $Cars = $s->car_log_byid($response['StudentID']); 
		$hour = 0;
		foreach($Cars as $val) {  $dateformt = explode("/",$val['InCarDate']);	
		$hour = $hour + $val['Hours']; ?>
            <tr>
              <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
              <td><?=$val['Hours']?></td>
              <td><?=$val['Instructor']?></td>
              <td><?php /*$val['Location']*/?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><strong>Total Hours</strong></td>
              <td><?=$hour?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" style="padding:8px;"><strong>Payments</strong></td>
      <td valign="top" style="padding:8px;"><strong>Other</strong></td>
    </tr>
    <tr>
      <td valign="top"><table class="table" width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <th scope="col">Payment</th>
            <th scope="col">Payment Date</th>
            <th scope="col">Income</th>
            <th scope="col">GST</th>
          </tr>
          <tr>
            <td><?=$response['CourseFee']?></td>
            <td>&nbsp;</td>
            <td><?php print (($response['CourseFee'])/((1) + (.13)));?></td>
            <td><?php print ($response['CourseFee']) - (($response['CourseFee'])/((1) + (.13)));?></td>
          </tr>
        </table></td>
      <td valign="top"><table  class="table" width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <th scope="col">Other</th>
            <th scope="col">Other Date</th>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br />
  <br />
  <div class="clearfix text-right"> <a href="PDF/pdfload.php?id=<?=$response['StudentID']?>" target="_blank" title="Print PDF" class="btn btn-default btn-primary btn-lg">Print PDF</a> </div>
  <br />
  <br />
</div>
</body>
</html>
