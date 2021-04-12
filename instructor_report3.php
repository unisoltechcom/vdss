<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$dc = new Database;
$s = new StudentForm;
$totals=0;
$date1 = date("Y-m-d");
$date2 = date("Y-m-d", strtotime("-13 months"));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
    $dateformt2 = explode("/",$_POST['from']);
	$dateformt1 = explode("/",$_POST['to']);	
	$insid = @$_REQUEST['Instructor'];
	$date2 = $dateformt2[2].'-'.$dateformt2[1].'-'.$dateformt2[0];
	$date1 = $dateformt1[2].'-'.$dateformt1[1].'-'.$dateformt1[0];
$query = "SELECT * FROM `Instructor_information` where `isActive`=1 and `Instructor_information`.`Ins_id`= $insid ORDER BY `Instructor_information`.`Ins_id` DESC";
$record = $dc->execute_query($query);
 $totals = @mysqli_num_rows($record);
$response = @mysqli_fetch_array($record);
	
$nameistrct = $response['Ins_First_Name'].' '.$response['Ins_Last_Name'];	
	//SELECT *  FROM `class_log` where STR_TO_DATE(InClassDate, '%m/%d/%Y') Between  '2017-01-01' and '2017-05-01' group by STR_TO_DATE(InClassDate, '%m/%d/%Y')
	
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

<script>
	$( function() {
		var dateFormat = "dd/mm/yy",
			from = $( "#from" )
				.datepicker({
					defaultDate: "-20w",
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
  <div class="student">Instructor Information</div>
  <div class=" clearfix"></div>
  <br />
  <div class="row">
      <div class="col-md-12 reportview">
        <h4 align="left">Select Date Range</h4>
        
        <form action="" method="post" class="instructor"> 
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label col-sm-3 no-padding-right" >Instructor:</label>
            <div class="col-sm-9 inputwidth-90">
              <select class="form-control" name="Instructor" id="Instructor">
                <option>Select Instructor</option>
         <?php $responses = $s->list_instructor();
		  foreach($responses as $val){  ?>               
            <option value="<?=$val['Ins_id']?>"><?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?></option>
                <?php }?>
              </select>
              <script>jQuery('#Instructor  option[value="<?=@$response['Ins_id']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>
        
               
         <div class="col-sm-3">
          <div class="form-group">
            <label class="control-label col-sm-3 no-padding-right" >From :</label>
            <div class="col-sm-9 inputwidth-90">
              <input type="text" class="form-control" value="<?=@$_REQUEST['from']?>"  name="from" id="from"  placeholder="dd/mm/yy"  />
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="control-label col-sm-3 no-padding-right" >To :</label>
            <div class="col-sm-9 inputwidth-90">
              <input type="text" class="form-control" value="<?=@$_REQUEST['to']?>"    name="to"  id="to"  placeholder="dd/mm/yy"  />
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-2">
          <div class="form-group">
            <input type="hidden" value="<?=$_REQUEST['id']?>" name="id"  />
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">            
          </div>
        </div>
         
  <div class=" clearfix"></div>
        </form>
        
       
      </div>
   </div>
  <br />
  
    <div class=" clearfix"></div>
<?php 

if( $totals > 0 ) {
?>    
    
    
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
    
    <br /><br />
 <?php $query = "SELECT `si`.`Location` , `si`.`School` , `si`.`LicenceClass` , `cl`.`InClassDate` , cl.Instructor ,`cl`.`Hours` FROM `class_log` as cl left join `student_information` as si on `cl`.`StudentID` = `si`.`StudentID`  where STR_TO_DATE(InClassDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' and cl.Instructor like '".$nameistrct."' group by `si`.`School` order by STR_TO_DATE(InClassDate, '%d/%m/%Y') ASC";
$record = $dc->execute_query($query); 
$total = mysqli_num_rows($record); 

if($total>0) { 
?>
    <div class="row">
      <div class="col-sm-12">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th scope="col">Location</th>
            <th scope="col">School Name </th>
            <th scope="col">Course Description</th>
            <th scope="col">Lesson Date</th>
            <th scope="col">Hours</th>
            
          </tr>
<?php while($val = mysqli_fetch_array($record))
{?>    <tr align="center">
            <td><?=$val['Location']?></td>
            <td><?=$val['School']?></td>
            <td><?=$val['LicenceClass']?></td>
            <td><?php 
			if($val['InClassDate']) {
	print dateformat($val['InClassDate']); } ?></td>
            <td><?=$val['Hours']?></td>           
          </tr>
<?php } ?>
        </table>

      </div>
    </div>
  
  <?php }  } ?>
  <div class=" clearfix"></div>
</div>

</body>
</html>
