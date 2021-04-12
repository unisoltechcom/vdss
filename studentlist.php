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
function getMyRecord () {	

	$.post('cronejob.php', function( data ) {
		if(data.result=='true'){
  		window.location.reload();} 
		else {
		getMyRecord();
			}
		}, "json");	
		
	}
$("#userdataget").click(function(){ getMyRecord (); });

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
//$response = $s->list_students(); desc,asc,nameasc,namedesc
$sort='';
switch (@$_REQUEST['sorby']):
    case 'asc':
        $sort = 'ORDER BY `student_information`.`StudentID`  asc';
        break;
    case 'nameasc':
        $sort = 'ORDER BY `student_information`.`LastName`  asc';
        break;
    case 'namedesc':
        $sort = 'ORDER BY `student_information`.`LastName`  DESC';
        break;
    default:
        $sort = 'ORDER BY `student_information`.`StudentID`  DESC';
endswitch;
if(empty($sort)) {
	 $sort = 'ORDER BY `student_information`.`StudentID`  DESC';
	}
$query = "SELECT * FROM `student_information` where `isActive`=1 $sort";
$records_per_page = 50;
$pager = $dc->pager_new($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page');
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record);
?>
<div class="row">
    <div class="col-sm-10">
<form action="searchstudent.php" method="post" enctype="multipart/form-data">
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
</div>
<!--<div class="col-sm-2"><button class="btn btn-primary active" id="userdataget" >Synchronize users</button></div>-->
</div>
<?php 
if($total > 0 ) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bordernone">
<tr> 
<td align="left" class="text-success"><strong><?=$pager["total_records_found_text"]?></strong></td> 
<td>
<form  action='' method="get" class="text-success" > Sort By
         <select class="text-success"  name="sorby" id="sortby"  onchange='this.form.submit()'>
         <OPTION VALUE='desc'>New Student Descending</OPTION>
         <OPTION VALUE='asc'>New Student  Ascending</OPTION>
      	<OPTION VALUE='nameasc'>Name Ascending</OPTION>
        <OPTION VALUE='namedesc'>Name Descending</OPTION>
        
        
     </select>
     <script>jQuery('#sortby  option[value="<?=$_REQUEST['sorby']?>"]').prop("selected", true);</script>
 </form>

</td>
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
 $count = @$_GET['start'] ? $_GET['start'] : 0; 
 $row_count = 0; 
 $rowcount = @$_GET['start'] ? ($_GET['start']-1)*$records_per_page : 0 ;
/*foreach($response as $val)
{ */
while($val = mysqli_fetch_array($record))
{ $rowcount++;
$count++;
?>
  <tr align="center">
    <td><input type="checkbox" id="chk<?php echo $row_count; ?>" name="chkCustomer<?php echo $row_count; ?>" 
								  value="<?php echo $val['StudentID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?=$val['StudentID']?></td>
    <td><?=$val['LastName'].' '.$val['FirstName']?></td>
    <td><?=$val['Gender']?></td>
    <td><?=$val['City']?></td>
    <td><?=$val['Phone']?></td>
    <td><?=$val['CourseFee']?></td>
    <td><?=$val['Licence']?></td>
    <td><a href="view_student.php?id=<?php echo $val['StudentID']?>" border="0" ><img src="images/view.png" border="0" /></a></td>
    <td><a href="edit_student.php?id=<?php echo $val['StudentID']?>" border="0" ><img src="images/edit.gif" border="0" /></a></td>
    <td><a href="action_page.php?mode=delete&form=student&id=<?php echo ($val['StudentID']);?>" onclick="return checkmode(3)" >
								  <img src="images/delete.png" border="0" /></a></td>
  </tr>
 <?php $row_count ++; } ?>
</table>
<br />
  <div class="row">
  <div class="col-sm-12 text-right">
   <div class="col-sm-7  text-left"><button class="btn btn-default btn-lg active" onclick="javascript: checkAll(this, <?php echo $total; ?> )">All</button> &nbsp; 
								<button class="btn btn-default btn-lg active"  onclick="javascript: uncheckAll(this, <?php echo $total; ?> )">None</button>&nbsp;&nbsp;&nbsp; 
								<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedDelete&form=student&id=',4);" >
									<img src="images/deleteall.png" border=0/>&nbsp;Delete All</a> &nbsp;&nbsp;&nbsp; 
                                     <a href="trash_student.php" class="btn btn-default btn-lg active"  >Trash</a>
                                     
                                     &nbsp;&nbsp;&nbsp; 
                                     </div>  
  		<div class=""><a href="xcel/xcel.php" title="student list"><button type="button" class="btn btn-default btn-lg active" >Export to Excel</button></a></div>
  </div>
  </div>
<?php } else { ?>
<br /> <br /><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">No Record Found</div></div></div></div>
<?php } ?>
</div>
<?php include_once('footer.php');?>
</body>
</html>
