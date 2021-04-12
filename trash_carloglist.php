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
$dc = new Database;

$query = "SELECT st.`StudentID`, `FirstName`, `LastName`, `Gender`, `City`, `Licence`,  `logCarID`, `InCarDate`, cl.`Location`, `Hours`, `Instructor` FROM car_log as cl left join `student_information` as st on `st`.`StudentID`= `cl`.`StudentID`   where cl.`isActive`=0  ORDER BY `cl`.`logCarID`  DESC";
$records_per_page = 50;
$pager = $dc->pager_new($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page');
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record);

if($total > 0 ) {
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bordernone">
<tr> 
<td align="center" class="text-success"><strong><?=$pager["total_records_found_text"]?></strong></td> 
  <td align="right" class="text-success" style="text-transform:none;">
      <?php 
          echo($pager["pages_links"]);
      ?>
  </td>
</tr>
</table>
                            
 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center">
    <th scope="col" align="center"><input type="checkbox" name="chkProductTop" id="checkbox" value="selected" 
								  onclick="javascript: selectDeselectAll(this, <?php echo $total; ?>, 1);" /></th>
    <th scope="col" align="center">#</th>
    <th scope="col" align="center">Name</th>
    <th scope="col" align="center">Date</th>
    <th scope="col" align="center">Hours</th>
    <!--<th scope="col" align="center">Location</th>-->
    <th scope="col" align="center">Instructor</th>
    <th width="2%">&nbsp;</th>
    <th width="2%">&nbsp;</th>  
  </tr>
 <?php
 $count = $_GET['start'] ? $_GET['start'] : 0; 
 $row_count = 0;
/*foreach($response as $val)
{ */
while($val = mysqli_fetch_array($record))
{ $dateformt = explode("/",$val['InCarDate']);	
$count++;
?>
  <tr align="center">
    <td><input type="checkbox" id="chk<?php echo $row_count; ?>" name="chkCustomer<?php echo $row_count; ?>" 
								  value="<?php echo $val['logCarID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?=$count?></td>
    <td><?=$val['FirstName'].' '.$val['LastName']?></td>
    <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
    <td><?=$val['Hours']?></td>
    <!--<td><?php /*$val['Location']*/?></td>-->
    <td><?=$val['Instructor']?></td>
    <td>&nbsp;</td>
    <td><a href="action_page.php?mode=restore&form=carlog&id=<?php echo ($val['logCarID']);?>" onclick="return checkmode(5)" >
								  <img src="images/restore.jpg" border="0" /></a></td>
  </tr>
 <?php $row_count ++; } ?>
</table>
<br />
  <div class="row">
  <div class="col-sm-12 text-right">
   <div class="col-sm-7  text-left"><button class="btn btn-default btn-lg active" onclick="javascript: checkAll(this, <?php echo $total; ?> )">All</button> &nbsp; 
								<button class="btn btn-default btn-lg active"  onclick="javascript: uncheckAll(this, <?php echo $total; ?> )">None</button>&nbsp;&nbsp;&nbsp; 
								<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedRestore&form=carlog&id=',5);" >
									<img src="images/recycle_03.png" border=0/>&nbsp;Restore All</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedPDelete&form=carlog&id=', '1');" >
									<img src="images/deleteall.png" border=0/> &nbsp;Empty Trash</a> &nbsp;&nbsp;&nbsp; </div>  
  		<div class=""><!--<a href="xcel/xcel.php" title="student list"><button type="button" class="btn btn-default btn-lg active" >Export to Excel</button></a>--></div>
  </div>
  </div>
<?php } else { ?>
<div class="student alert alert-danger">No Record Found</div>
<?php } ?>
</div>

<?php include_once('footer.php');?>
</body>
</html>
