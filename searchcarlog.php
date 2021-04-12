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
if (@isset($_REQUEST['search'])) {
//$col = $_REQUEST['searchby']; 
$keyw = mysql_real_escape_string(trim($_REQUEST['search']));
$condition = "and '$keyw' LIKE Concat(Concat('%',FirstName),'%') 
OR '$keyw' LIKE  Concat(Concat('%',LastName),'%') OR cl.Location LIKE '%$keyw%' OR  Instructor LIKE '%$keyw%' ";
$query = "SELECT st.`StudentID`, `FirstName`, `LastName`, `Gender`, `City`, `Licence`,  `logCarID`, `InCarDate`, cl.`Location`, `Hours`, `Instructor` FROM car_log as cl left join `student_information` as st on `st`.`StudentID`= `cl`.`StudentID`   where cl.`isActive`=1 $condition  ORDER BY `cl`.`logCarID`  DESC";
$records_per_page = 50;
$querystring = "&search=$keyw";
$pager = $dc->pager_new($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page', $querystring );
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record);
} else {$total=0;}
?>
<div class="row">
    <div class="col-md-12">
<form action="searchcarlog.php" method="get" enctype="multipart/form-data">
<div class="form-group searchboxcenter">
<!--<div class="col-sm-0"><select  class="form-control" name="searchby"  style="width: 100%;">
<option value="">Select Option</option>
	<option value="FirstName">First Name</option>
    <option value="LastName">Last Name</option>
    <option value="Location">Location</option>
    <option value="ClassStart">Date</option>
    <option value="LicenceClass">Class</option>
        
</select></div>--><div class="col-sm-9">
<input name="search"  class="form-control" type="text" value="<?=@$_REQUEST['search'] ? $_REQUEST['search'] : ''; ?>"  style="width: 100%;" placeholder="Search By Name and Location..." /></div>
<div class="col-sm-3"><input type="submit" value="Search" class="btn btn-primary active"></div>
</div>
</form>
</div></div>
<?php 
if($total > 0 ) {
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bordernone">
<tr> 
<td align="left" class="text-success"><strong><?=$pager["total_records_found_text"]?></strong></td> 
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
$rowcount = $_GET['start'] ? ($_GET['start']-1)*$records_per_page : 0 ;
while($val = mysqli_fetch_array($record))
{ $dateformt = explode("/",$val['InCarDate']);	
$count++; $rowcount++;
?>
  <tr align="center">
    <td><input type="checkbox" id="chk<?php echo $row_count; ?>" name="chkCustomer<?php echo $row_count; ?>" 
								  value="<?php echo $val['logCarID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?=$val['StudentID']?></td>
    <td><?=$val['FirstName'].' '.$val['LastName']?></td>
    <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
    <td><?=$val['Hours']?></td>
    <!--<td><?php /*$val['Location']*/?></td>-->
    <td><?=$val['Instructor']?></td>
    <td><a href="edit_incar.php?id=<?php echo $val['logCarID']?>" border="0" ><img src="images/edit.gif" border="0" /></a></td>
    <td><a href="action_page.php?mode=delete&form=carlog&id=<?php echo ($val['logCarID']);?>" onclick="return checkmode(3)" >
								  <img src="images/delete.png" border="0" /></a></td>
  </tr>
 <?php $row_count ++; } ?>
</table>
<br />
  <div class="row">
  <div class="col-sm-12 text-right">
   <div class="col-sm-5  text-left"><button class="btn btn-default btn-lg active" onclick="javascript: checkAll(this, <?php echo $total; ?> )">All</button> &nbsp; 
								<button class="btn btn-default btn-lg active"  onclick="javascript: uncheckAll(this, <?php echo $total; ?> )">None</button>&nbsp;&nbsp;&nbsp; 
								<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedDelete&form=carlog&id=',4);" >
									<img src="images/deleteall.png" border=0/>&nbsp;Delete All</a> &nbsp;&nbsp;&nbsp; 
                                     <a href="trash_carloglist.php" class="btn btn-default btn-lg active"  >Trash</a></div>  
  		<div class=""><!--<a href="xcel/xcel.php" title="student list"><button type="button" class="btn btn-default btn-lg active" >Export to Excel</button></a>--></div>
  </div>
  </div>
<?php } else { ?>
<br /> <br /><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">No Record Found</div></div></div></div>
<?php } ?>
</div>

</body>
</html>
