<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} ?>

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
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"    rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src = "js/check.checkbox.js"></script>
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
<?php include_once('nav.php'); ?> </center>
<?php 
 $count='0';
$s = new StudentForm;
$response = $s->trash_students();
 $total = count($response); 
if($total > 0 ) {
?>

 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center">
    <th scope="col" align="center"><input type="checkbox" name="chkProductTop" id="checkbox" value="selected" 
								  onclick="javascript: selectDeselectAll(this, <?php echo $total; ?>, 1);" /></th>
    <th scope="col" align="center">#</th>
    <th scope="col" align="center">Name</th>
    <th scope="col" align="center">Gender</th>
    <th scope="col" align="center">City</th>
    <th scope="col" align="center">Phone</th>
    <th scope="col" align="center">Course Fee</th>
    <th scope="col" align="center">Licence#</th>
    <th width="2%">&nbsp;</th>
    <th width="2%">&nbsp;</th>
    <th width="2%">&nbsp;</th>  
  </tr>
 <?php
 $row_count = 0; 
foreach($response as $val)
{ $count++;
?>
  <tr align="center">
    <td><input type="checkbox" id="chk<?php echo $row_count; ?>" name="chkCustomer<?php echo $row_count; ?>" 
								  value="<?php echo $val['StudentID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?=$val['StudentID']?></td>
    <td><?=$val['FirstName'].' '.$val['LastName']?></td>
    <td><?=$val['Gender']?></td>
    <td><?=$val['City']?></td>
    <td><?=$val['Phone']?></td>
    <td><?=$val['CourseFee']?></td>
    <td><?=$val['Licence']?></td>
    <td></td>
    <td></td>
    <td><a href="action_page.php?mode=restore&form=student&id=<?php echo ($val['StudentID']);?>" onclick="return checkmode(5)" >
								  <img src="images/restore.jpg" border="0" /></a></td>
  </tr>
 <?php $row_count ++; } ?>
</table>
<br />
  <div class="row">
  <div class="col-sm-12 text-right">
   <div class="col-sm-8 text-left"><button class="btn btn-default btn-lg active" onclick="javascript: checkAll(this, <?php echo $total; ?> )">All</button> &nbsp; 
								<button class="btn btn-default btn-lg active"  onclick="javascript: uncheckAll(this, <?php echo $total; ?> )">None</button>&nbsp;&nbsp;&nbsp; 
								<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedRestore&form=student&id=', '5');" >
									<img src="images/recycle_03.png" border=0/>&nbsp;Restore All</a> &nbsp;&nbsp;&nbsp;<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedPDelete&form=student&id=', '1');" >
									<img src="images/deleteall.png" border=0/>&nbsp;Empty Trash</a> &nbsp;&nbsp;&nbsp; 
                                     <a href="studentlist.php" class="btn btn-default btn-lg active"  >Student List</a></div>  
  		
  </div>
  </div>
<?php } else { ?>
<div class="student alert alert-danger">No Record Found</div>
<?php } ?>
</div>
<?php include_once('footer.php');?>
</body>
</html>
