<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$dc = new Database;
$s = new StudentForm;
$totals=0;
$classhr =  CLASSHOURS;
$carhr =  DRIVINGHOURS;
$datebetween='';
$date1 = date("Y-m-d");
$date2 = date("Y-m-d", strtotime("-13 months"));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
		
    if($_POST['from']){
    $dateformt2 = explode("/",$_POST['from']);
	$date2 = $dateformt2[2].'-'.$dateformt2[1].'-'.$dateformt2[0];
	}
	if($_POST['to']){
	$dateformt1 = explode("/",$_POST['to']);
	$date1 = $dateformt1[2].'-'.$dateformt1[1].'-'.$dateformt1[0];
	}	
	if( $_POST['to'] && $_POST['from'] ){	$datebetween = " and STR_TO_DATE(InClassDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' ";}
	
		
if(@$_REQUEST['Instructor']=='All'){
	 if($date2 && $date1) {$between1 = "  STR_TO_DATE(lessonDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' ";} 
	 if($date2 && $date1) {$between2 = " or STR_TO_DATE(InCarDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' ";} 
	$query = "SELECT si.Ins_id,si.Ins_Rate,si.InsDrivingRate,si.ins_email,si.Ins_Gender, CONCAT_WS(' ', `si`.`Ins_First_Name`, `si`.`Ins_Last_Name`) AS `name` , (SELECT SUM(cl.hours) AS classHours FROM `inc_lectr_log` as cl WHERE `cl`.`Ins_id` = `si`.`Ins_id` GROUP BY cl.Ins_id) as classHours, (SELECT SUM(cr.Hours) AS carHours FROM `car_log` as cr WHERE `cr`.`Ins_id` = `si`.`Ins_id` GROUP BY cr.Ins_id) as carHours FROM `Instructor_information` as si left join `inc_lectr_log` as cl on `cl`.`Ins_id` = `si`.`Ins_id` left join `car_log` as cr on `cr`.`Ins_id` = `si`.`Ins_id`  where  si.`isActive`=1 ".$between1.$between2."  group by `cr`.`Ins_id`,`cl`.`Ins_id`  ";
	$query = "SELECT si.Ins_id,si.Ins_Rate,si.InsDrivingRate,si.ins_email,si.Ins_Gender, CONCAT_WS(' ', `si`.`Ins_First_Name`, `si`.`Ins_Last_Name`) AS `name` , 
(SELECT SUM(cl.hours) AS classHours FROM `inc_lectr_log` as cl WHERE `cl`.`Ins_id` = `si`.`Ins_id` GROUP BY cl.Ins_id) as classHours,
(SELECT SUM(cr.Hours) AS carHours FROM `car_log` as cr WHERE `cr`.`Ins_id` = `si`.`Ins_id` GROUP BY cr.Ins_id) as carHours 
FROM `Instructor_information` as si 
left join `inc_lectr_log` as cl on `cl`.`Ins_id` = `si`.`Ins_id` 
left join `car_log` as cr on `cr`.`Ins_id` = `si`.`Ins_id` 
where  IF(si.`isActive`=1 , " .$between1.$between2. "  ,'false')  
group by `cr`.`Ins_id`,`cl`.`Ins_id` order by `si`.`Ins_Last_Name` asc";
	
	}	
else{
$insid = @$_REQUEST['Instructor'];	
$query = "SELECT * FROM `Instructor_information` where `isActive`=1 and `Instructor_information`.`Ins_id`= $insid ORDER BY `Instructor_information`.`Ins_id` DESC";

$record = $dc->execute_query($query);
 $totals = @mysqli_num_rows($record);
$response = @mysqli_fetch_array($record);	
$nameistrct = $response['Ins_First_Name'].' '.$response['Ins_Last_Name'];	
}
	//SELECT *  FROM `class_log` where STR_TO_DATE(InClassDate, '%m/%d/%Y') Between  '2017-01-01' and '2017-05-01' group by STR_TO_DATE(InClassDate, '%m/%d/%Y')	
}?>

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
					defaultDate: "-4w",
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
                <option value="All">Select All Instructors</option>
         <?php $responses = $s->list_instructor();
		  foreach($responses as $val){  ?>               
            <option value="<?=$val['Ins_id']?>"><?=$val['Ins_Last_Name'].' '.$val['Ins_First_Name']?></option>
                <?php }?>
              </select>
              <?php if(@$_REQUEST['Instructor']=='All'){  ?>
              <script>jQuery('#Instructor  option[value="<?=@$_REQUEST['Instructor']?>"]').prop("selected", true);</script>
              <?php } else {?>
              <script>jQuery('#Instructor  option[value="<?=@$response['Ins_id']?>"]').prop("selected", true);</script>
              <?php } ?>
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
  <?php if(@$_REQUEST['Instructor']=='All'){ 

 $total=0;
  $records_per_page = 50;
$pager = $dc->pager($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page');
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record);
  ?>
  <?php
if($total > 0 ) { ?>
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
    <th scope="col" align="center">#</th>
    <th scope="col" align="center">Name</th>
    <th scope="col" align="center">Email</th>
    <th scope="col" align="center">Gender</th>
    <th scope="col" align="center">Class Rate</th>
    <th scope="col" align="center">Class Hours</th>
    <th scope="col" align="center">Driving Rate</th>
    <th scope="col" align="center">Driving Hours</th> 
	<th scope="col" align="center">Sub Total</th> 
	<th scope="col" align="center">HST</th> 
    <th scope="col" align="center">Instructor's Charges </th>
  </tr>
 <?php
$count = @$_GET['start'] ? @$_GET['start'] : 0; 
 $row_count = 0;
 $rowcount = @$_GET['start'] ? (@$_GET['start']-1)*$records_per_page : 0 ;
/*foreach($response as $val)
{ */
$totalamount = 0;
while($val = mysqli_fetch_array($record))
{ $count++; $rowcount++;
?>
  <tr align="center">    
    <td><?=$val['Ins_id']?></td>
    <td>
	<?php $arr = array(); 
					$arr = explode(" ",$val['name']);
					print $arr[2].' '.$arr[1].' '.$arr[0];
					?>
	<?php //print $val['name']?></td>
    <td><?=$val['ins_email']?></td>
    <td><?=$val['Ins_Gender']?></td>
    <td><?=$val['Ins_Rate']?></td>
    <td><?=$val['classHours']?></td>
    <td><?=$val['InsDrivingRate']?></td>
    <td><?=$val['carHours']?></td>
	<td><?=(($val['Ins_Rate']*$val['classHours'])+($val['InsDrivingRate']*$val['carHours']))?></td>
	<td><?=((($val['Ins_Rate']*$val['classHours'])*(TAX/100))+(($val['InsDrivingRate']*$val['carHours'])*(TAX/100)))?></td>
    <td>
	<?php if($val['Ins_Rate']>0){
			$classcharge = ($val['Ins_Rate']*$val['classHours']);
			$taxamount = (($classcharge)*(TAX/100));
			$classcharge = ($classcharge + $taxamount);	
		}
		
		 if($val['InsDrivingRate']>0){
			 $classdrive = ($val['InsDrivingRate']*$val['carHours']);
			 $taxamount = (($classdrive)*(TAX/100));
			 $classdrive = ($classdrive + $taxamount);		 	 
		 }
		 
		 $totalamount = ($totalamount + ($classcharge+$classdrive));
		 print formatMoney(($classcharge+$classdrive));
		
	 ?></td>
	 
  </tr>
 <?php  $classcharge='';$classdrive=''; $row_count ++; } ?>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="28%">&nbsp;</th>
    <th width="30%">&nbsp;</th>
    <th  width="7%" align="center" style="text-align:right"><strong>Total amount:</strong></th>
    <th  width="11%" align="center"><strong><?=formatMoney($totalamount)?></strong></th>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan="4"></td></tr></table>

<?php } else { ?>
<br /> <br /><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">No Record Found</div></div></div></div>
<?php } ?>
    <div class=" clearfix"></div>
<?php } else {
$intructorname = '';
if( $totals > 0 ) {
$intructorname = $response['Ins_First_Name'].' '.$response['Ins_Last_Name'];
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
            <label class="control-label col-sm-4" >Class Rate:</label>
            <div class="col-sm-8"><?=$response['Ins_Rate']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Joining Date:</label>
            <div class="col-sm-8"><?=$response['Ins_Joining_Date']?></div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4">Driving Rate:</label>
            <div class="col-sm-8"><?=$response['InsDrivingRate']?>
            </div>
          </div>
        </div>  
     
     
      </div>
    </div>
    
    <br /><br />
 <?php /*$query = "SELECT `si`.`Location` , `si`.`School` , `si`.`LicenceClass` , `cl`.`InClassDate` , cl.Instructor ,`cl`.`Hours` FROM `class_log` as cl left join `student_information` as si on `cl`.`StudentID` = `si`.`StudentID`  where STR_TO_DATE(InClassDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."' and cl.Instructor like '".$nameistrct."' group by `si`.`School` order by STR_TO_DATE(InClassDate, '%d/%m/%Y') ASC";*/
 $insid = $response['Ins_id'];
 if($date2 && $date1) {$between = " and STR_TO_DATE(lessonDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."'";} 
 $query = "SELECT * FROM `inc_lectr_log`  where `isActive`=1 and Ins_id=$insid  order by STR_TO_DATE(lessonDate, '%d/%m/%Y') ASC limit 40";
$record = $dc->execute_query($query);
$total=0; 
$total = mysqli_num_rows($record); 

?><div class="student"  style="text-align:left; font-weight:bold">Detail of Class Hours</div>
  <div class=" clearfix"></div> <?php if($total>0) { ?>
    <div class="row">
      <div class="col-sm-12">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th scope="col">School Name </th>
            <th scope="col">Class/Course Description</th>
            <th scope="col">Lesson Date</th>
            <th scope="col">Hours</th>
			<th scope="col">Rate</th>
			<th scope="col">Sub Total</th>
			<th scope="col">HST</th>
            <th scope="col">Instructor's Charges</th>
          </tr>
<?php $hour = 0; 
while($val = mysqli_fetch_array($record))
{ $hour = $hour + $val['hours']; ?>    
<tr align="center">
            <td><?=$val['schoolName']?></td>
            <td><?=$val['course']?></td>
            <td><?php 
			if($val['lessonDate']) {
	print dateformat($val['lessonDate']); } ?></td>
            <td><?=$val['hours']?></td>
			<td><?=$response['Ins_Rate']?></td>
			<td><?=($val['hours']*$response['Ins_Rate'])?></td>
			<td><?=(($val['hours']*$response['Ins_Rate'])*(TAX/100))?></td> 
            <td><?=(($val['hours']*$response['Ins_Rate'])+(($val['hours']*$response['Ins_Rate'])*(TAX/100)))?></td>           
          </tr>
<?php } ?>
<tr>
              <td colspan="3" align="right"><strong>Total Hours:&nbsp;&nbsp;</strong></td>
              <td align="center"><strong><?=$hour?></strong></td>
			  <td align="center"><strong><?=$response['Ins_Rate']?></strong></td>
			   <td align="center"><strong><?=($hour*$response['Ins_Rate'])?></strong></td>
			  <td align="center"><strong><?=(($hour*$response['Ins_Rate'])*(TAX/100))?></strong></td>
              <td align="center"><strong><?php print (($hour*$response['Ins_Rate'])+(($hour*$response['Ins_Rate'])*(TAX/100))); ?></strong></td>
            </tr>
        </table>

      </div>
    </div>
  
  <?php } else { 
 ?><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">No Class hours against this search criteria.</div></div></div></div><?php  
  }   } ?>
  <div class=" clearfix"></div>
  <?php
  $total=0; 
  if($intructorname) {
  if($date2 && $date1) {$between = " and STR_TO_DATE(InCarDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."'";} 
   $query = "SELECT cl.Instructor,cl.InCarDate,cl.Hours,si.FirstName,si.LastName FROM `car_log` as cl left join student_information as si on si.StudentID=cl.StudentID  where cl.`isActive`=1 and  cl.`Instructor` LIKE '".$intructorname."' $between order by STR_TO_DATE(InCarDate, '%d/%m/%Y') ASC  limit 40";
  
$record = $dc->execute_query($query);

$total = mysqli_num_rows($record); 

?><div class="student" style="text-align:left; font-weight:bold">Detail of Driving Hours</div>
  <div class=" clearfix"></div>
  <?php } if($total > 0 ) { ?>
    <div class="row">
      <div class="col-sm-12">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th scope="col">Instructor Name </th>
			<th scope="col">Student Name </th>
            <th scope="col">Lesson Date</th>
            <th scope="col">Hours</th>
			<th scope="col">Rate</th>
			<th scope="col">Sub Total</th>
			<th scope="col">HST</th>
            <th scope="col">Instructor's Charges</th>
          </tr>
<?php $hour = 0; 
while($val = mysqli_fetch_array($record))
{ $hour = $hour + $val['Hours']; ?>    <tr align="center">
            <td>
			<?php $arr = array(); 
					$arr = explode(" ",$intructorname);
					print $arr[1].' '.$arr[0];
					?></td>
            <td><?=$val['LastName'].' '.$val['FirstName'] ?></td>
            <td><?php 
			if($val['InCarDate']) {
	print dateformat($val['InCarDate']); } ?></td>
            <td><?=$val['Hours']?></td>
			
			<td><?=$response['InsDrivingRate']?></td>
			 <td><?=($val['Hours']*$response['InsDrivingRate'])?></td>
			<td><?=(($val['Hours']*$response['InsDrivingRate'])*(TAX/100))?></td> 
            <td><?=(($val['Hours']*$response['InsDrivingRate'])+(($val['Hours']*$response['InsDrivingRate'])*(TAX/100)))?></td>            
          </tr>
<?php } ?>
<tr>             
              <td colspan="3" align="right"><strong>Total Hours:&nbsp;&nbsp;</strong></td>
              <td align="center"><strong><?=$hour?></strong></td>
			  <td align="center"><strong><?=$response['InsDrivingRate']?></strong></td>
			  <td align="center"><strong><?=($hour*$response['InsDrivingRate'])?></strong></td>
			  <td align="center"><strong><?=(($hour*$response['InsDrivingRate'])*(TAX/100))?></strong></td>
			  <td align="center"><strong><?php print (($hour*$response['InsDrivingRate'])+(($hour*$response['InsDrivingRate'])*(TAX/100))); ?></strong></td>
              <!--<td align="center"><strong><?php //print ($hour*$response['InsDrivingRate']); ?></strong></td>-->
            </tr>
        </table>

      </div>
    </div>
  
  <?php } else { 
 ?> <br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger" >No Driving Hours against this search criteria.</div></div></div></div><?php  
  }   ?>
  <div class=" clearfix"></div>
  <?php } ?>
</div>

<?php include_once('footer.php');?>
</body>
</html>
