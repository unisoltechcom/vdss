<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$dc = new Database;
$s = new StudentForm;
$total=0;
$classhr =  CLASSHOURS;
$carhr =  DRIVINGHOURS;
$date1 = date("Y-m-d");
$date2 = date("Y-m-d", strtotime("-13 months"));
$query = "SELECT `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `cl`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InClassDate` , cl.Instructor ,sum(cl.Hours) as classHours,sum(cr.Hours) as carHours FROM `student_information` as si left join `class_log` as cl on `cl`.`StudentID` = `si`.`StudentID` left join `car_log` as cr on `cr`.`StudentID` = `si`.`StudentID` where `si`.`isActive`=1 group by `cl`.`StudentID` ,`cr`.`StudentID` having sum(cl.Hours) < $classhr or sum(cr.Hours) < $carhr or `si`.`Owing` > 0 order by `si`.`LastName` ASC ";

if ($_REQUEST['studentinfo'] == 'formpost' ) {
	if(@$_REQUEST['from']) {		
		$dateformt2 = explode("/",@$_REQUEST['from']);
		$dateformt1 = explode("/",@$_REQUEST['to']);	
		$date2 = $dateformt2[2].'-'.$dateformt2[1].'-'.$dateformt2[0];
		$date1 = $dateformt1[2].'-'.$dateformt1[1].'-'.$dateformt1[0];
		$datebetween = " and STR_TO_DATE(InClassDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' ";
		$_SESSION['cdateformt2'] = $_REQUEST['from'];
		$_SESSION['cdateformt1'] = $_REQUEST['to'];
		$_SESSION['cdatebetween'] = $datebetween;
		$StudentID = $_REQUEST['StudentID'];
		if($StudentID=='') {$studentid='';} else { $studentid = "and `si`.`StudentID`=$StudentID";}
		$query = "SELECT `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `cl`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InClassDate` , cl.Instructor ,sum(cl.Hours) as classHours,sum(cr.Hours) as carHours FROM `student_information` as si left join `class_log` as cl on `cl`.`StudentID` = `si`.`StudentID` left join `car_log` as cr on `cr`.`StudentID` = `si`.`StudentID` where `si`.`isActive`=1 $studentid group by `cl`.`StudentID` ,`cr`.`StudentID` having sum(cl.Hours) < $classhr or sum(cr.Hours) < $carhr or `si`.`Owing` > 0 order by `si`.`LastName` ASC ";
	} else {	
		$StudentID = $_REQUEST['StudentID'];
		if($StudentID=='') {$studentid='';} else { $studentid = "and `si`.`StudentID`=$StudentID";}
		$query = "SELECT `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `cl`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InClassDate` , cl.Instructor ,sum(cl.Hours) as classHours,sum(cr.Hours) as carHours FROM `student_information` as si left join `class_log` as cl on `cl`.`StudentID` = `si`.`StudentID` left join `car_log` as cr on `cr`.`StudentID` = `si`.`StudentID` where `si`.`isActive`=1 $studentid group by `cl`.`StudentID` ,`cr`.`StudentID` having sum(cl.Hours) < $classhr or sum(cr.Hours) < $carhr or `si`.`Owing` > 0 order by `si`.`LastName` ASC ";
}



$locationQ='';
$querysort = '';

print $query;
//$record = $dc->execute_query($query);  sorby,date,timepending,owing,hours

}
$records_per_page = 50;
$pager = $dc->pager_new($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page');
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record); 
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

<script>
	$( function() {
		var dateFormat = "dd/mm/yy",
			from = $( "#from" )
				.datepicker({
					defaultDate: "-10d",
					changeMonth: true,
					numberOfMonths: 1 , 
					dateFormat: "dd/mm/yy",
				})
				.on( "change", function() {
					to.datepicker( "option", "minDate", getDate( this ) );
				}),
			to = $( "#to" ).datepicker({
				/*defaultDate: "+1w",*/
				changeMonth: true,
				numberOfMonths: 1, 
				dateFormat: "dd/mm/yy",
			})
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			});

		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
	} );
	</script>

</head>

<body>
<div class="wrapper">
  <center>
    <?php include_once('nav.php'); ?>
  </center>    
  <div class="student">Student Hours Report</div>
  <div class=" clearfix"></div>
  <br />
  <div class="row">
      <div class="col-md-12 reportview">
         <div class="col-sm-12"><h4 align="left">Select Date Range</h4></div>
        
        <form action="" method="get" class="instructor studentdriving"> 
       <div class="col-sm-4 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-4" >Student:</label>
            <div class="col-sm-8">
              <select class="form-control" name="StudentID" id="StudentID">
                <option value="">Select Student</option>
 <?php 

		  $response = $s->list_students();

		  foreach($response as $val)

		  {  ?>
                <option value="<?=$val['StudentID']?>">
                <?=$val['LastName'].' '.$val['FirstName']?>
                </option>
                <?php }?>
              </select>
              <script>jQuery('#StudentID  option[value="<?=$_REQUEST['StudentID']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>  
         <div class="col-sm-3 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-3 no-padding-right" >From :</label>
            <div class="col-sm-9 inputwidth-90">
              <input type="text" class="form-control" value="<?=@$_SESSION['cdateformt2']?>"  name="from" id="from"  placeholder="dd/mm/yy"  />
              <?php @$_SESSION['cdateformt2']="";?>
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-3 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-2 no-padding-right" >To :</label>
            <div class="col-sm-9 inputwidth-90">
              <input type="text" class="form-control" value="<?=@$_SESSION['cdateformt1']?>"    name="to"  id="to"  placeholder="dd/mm/yy"  />
              <?php @$_SESSION['cdateformt1']="";?>
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-2 no-padding">
          <div class="form-group">
          <input type="hidden" value="date" name="date"  /> 
          <input type="hidden" value="formpost" name="studentinfo"  />
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">            
          </div>
        </div>
         
  <div class=" clearfix"></div>
       
      </div>
   </div>
  <br />
  
    <div class=" clearfix"></div>

 <?php 
$_SESSION['cdateformt2'] = "";
$_SESSION['cdateformt1'] = "";
$_SESSION['cdatebetween']="";
if($total > 0 ) {
	

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bordernone">

<tr> 
<td align="left" class="text-success"><strong><?php print $pager["total_records_found_text"]?></strong></td> 
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
    <th scope="col" align="center">Instructor</th>
    <th scope="col" align="center">Name</th>  
    <th scope="col" align="center">Class hrs Completed</th>
    <th scope="col" align="center">Hrs Owing</th>
    <th scope="col" align="center">Car hrs Completed</th>
    <th scope="col" align="center">Hrs Owing</th>
    <th width="10%">Balance Owing</th>
  </tr>
 <?php

while($val = mysqli_fetch_array($record))
{ 
?>
  <tr align="center">
    <td><input type="checkbox" id="chk<?php echo $row_count; ?>" name="chkCustomer<?php echo $row_count; ?>" 
								  value="<?php echo $val['StudentID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?php echo $val['StudentID']; ?><?php //=$rowcount?></td>
    <td><?php 	$arr = explode(" ",$val['Instructor']);
					print $arr[2].' '.$arr[1].' '.$arr[0];
					?></td>
    <td><?=$val['LastName'].' '.$val['FirstName']?></td>       
    <td><?=$val['classHours']?></td>
    <td><?=($classhr-$val['classHours'])?></td>
    <td><?=$val['carHours']?></td>
    <td><?=($carhr-$val['carHours'])?></td>
    <td><?=$val['Owing']?></td>
  </tr>
 <?php  } ?>
</table>
<br />
  
<?php  } else { 
 ?><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">There is no Record against this search criteria.</div></div></div></div><?php  
  }   ?></form>
  <div class=" clearfix"></div><br />
</div>

<?php include_once('footer.php');?>
</body>
</html>
