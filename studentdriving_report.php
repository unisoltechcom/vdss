<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$dc = new Database;
$s = new StudentForm;
$drivinghrs = DRIVINGHOURS;
$_SESSION['datebetween']='';
$total=0;
$date1 = date("Y-m-d");
$date2 = date("Y-m-d", strtotime("-13 months"));

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
if(@$_REQUEST['from']) {		
$dateformt2 = explode("/",@$_REQUEST['from']);
$dateformt1 = explode("/",@$_REQUEST['to']);	
$date2 = $dateformt2[2].'-'.$dateformt2[1].'-'.$dateformt2[0];
$date1 = $dateformt1[2].'-'.$dateformt1[1].'-'.$dateformt1[0];
$datebetween = "and STR_TO_DATE(InCarDate, '%d/%m/%Y') Between  '".$date2."' and '".$date1."'";
$_SESSION['dateformt2'] = $_REQUEST['from'];
$_SESSION['dateformt1'] = $_REQUEST['to'];
$_SESSION['datebetween'] = $datebetween;
}
$Inscond='';
if( !empty( @$_REQUEST['Instructor'] )) { $insname = $_REQUEST['Instructor']; $Inscond = "and cl.Instructor like '%".$insname."%'";}
$querysort = '';
if(@$_REQUEST['sorby']=='hours') {	
		$querysort = "GROUP BY cl.StudentID having sum(cl.Hours) = $drivinghrs order by `si`.`LastName` ASC";
		$query = "SELECT `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `si`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InCarDate` , cl.Instructor ,sum(cl.Hours) as Hours  FROM `student_information` as si left join  `car_log` as cl on `cl`.`StudentID` = `si`.`StudentID`  where `cl`.`isActive`=1 ".$Inscond."   ".$_SESSION['datebetween']." ".$querysort."  ";
	} else if (@$_REQUEST['sorby']=='timepending') {
		$querysort = "GROUP BY cl.StudentID having sum(cl.Hours) < $drivinghrs order by `si`.`LastName` ASC";
		$query = "SELECT `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `si`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InCarDate` , cl.Instructor ,sum(cl.Hours) as Hours FROM `student_information` as si left join  `car_log` as cl on `cl`.`StudentID` = `si`.`StudentID`  where `cl`.`isActive`=1 ".$Inscond."   ".$_SESSION['datebetween']." ".$querysort."  ";
	
	} else if (@$_REQUEST['sorby']=='owing') {
		$querysort = "and `si`.`Owing` > 0 order by STR_TO_DATE(InCarDate, '%d/%m/%Y') ASC";
	
	} else   {
		$querysort = "order by `si`.`LastName` ASC";
		$query = "SELECT `cl`.`logCarID`, `si`.`StudentID`,`si`.`FirstName`, `si`.`LastName`, `si`.`Gender`, `si`.`City`, `si`.`Location` , `si`.`Phone`, `si`.`Owing`, `si`.`School` , `si`.`LicenceClass` , `cl`.`InCarDate` , cl.Instructor ,`cl`.`Hours` FROM `student_information` as si left join  `car_log` as cl on `cl`.`StudentID` = `si`.`StudentID`  where `cl`.`isActive`=1 ".$Inscond."   ".$_SESSION['datebetween']." ".$querysort."  ";
	}


//$record = $dc->execute_query($query);  sorby,date,timepending,owing,hours
$records_per_page = 50;
$pager = $dc->pager_new($query, $records_per_page, 1, ">", "<", "FIRST", "LAST", 'paging_footer', 'css_page');
$record = $dc->execute_query($pager['query']);
$total = mysqli_num_rows($record);
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
					defaultDate: "-1d",
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
  <div class="student">Student Driving Report Information</div>
  <div class=" clearfix"></div>
  <br />
  <div class="row">
      <div class="col-md-12 reportview">
        <div class="col-sm-12"><h4 align="left">Select Date Range</h4></div>
        
        <form action="" method="get" class="instructor studentdriving"> 
          <div class="col-sm-3 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-4" >Instructor:</label>
            <div class="col-sm-8 inputwidth-96 no-padding-right no-padding-left">
               <select class="form-control" name="Instructor" id="Instructor">
                <option value="">Select Instructor</option>
                <?php 
			 $response = $s->list_instructor();
			 foreach($response as $val)
			  {  ?>               
               		<option value="<?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?>"><?=$val['Ins_Last_Name'].' '.$val['Ins_First_Name']?></option>
                <?php }?>
                
                              
              </select><script>jQuery('#Instructor  option[value="<?=$_REQUEST['Instructor']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>     
         <div class="col-sm-3 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-3 no-padding-right" >From :</label>
            <div class="col-sm-9 inputwidth-96 no-padding-right no-padding-left">
              <input type="text" class="form-control" value="<?=@$_SESSION['dateformt2']?>"  name="from" id="from"  placeholder="dd/mm/yy"  />
              <?php @$_SESSION['dateformt2']="";?>
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-3 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-2 no-padding-right" >To :</label>
            <div class="col-sm-9 inputwidth-96 no-padding-right no-padding-left">
              <input type="text" class="form-control" value="<?=@$_SESSION['dateformt1']?>"    name="to"  id="to"  placeholder="dd/mm/yy"  />
              <?php @$_SESSION['dateformt1']="";?>
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-2 no-padding">
          <div class="form-group">
          <input type="hidden" value="date" name="date"  /> 
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">            
          </div>
        </div>
         
  <div class=" clearfix"></div>
        
        
       
      </div>
   </div>
  <br />
  
    <div class=" clearfix"></div>
<br />
 <?php 
$_SESSION['dateformt2'] = "";
$_SESSION['dateformt1'] = "";
$_SESSION['datebetween'] = "";
if($total > 0 ) {
?>



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bordernone">
<tr> 
<td align="left" class="text-success"></td> 
  <td align="right" class="text-success" style="text-transform:none;">
     
     Sort By:<select name="sorby" id="sortby"  onchange="this.form.submit()">
     	<option value="date">Date</option>
        <option value="timepending">Time Pending</option>
        <option value="hours"> Hours Completed  </option>   
    </select>
    <div style="height:10px;"></div>
     <script>jQuery('#sortby  option[value="<?=$_REQUEST['sorby']?>"]').prop("selected", true);</script>

  </td>
</tr>
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
    <th scope="col" align="center">Instructor</th>
    <th scope="col" align="center">Name</th>  
    <th scope="col" align="center">Phone</th>
    <th scope="col" align="center">Owing</th> 
     <?php if( @$_REQUEST['sorby']=='date' ) { ?><th scope="col" align="center"> Hours</th><?php } else if(@$_REQUEST['sorby']=='timepending' || @$_REQUEST['sorby']=='hours' ) { ?>   
    <th scope="col" align="center">Completed Hours</th>
    <th scope="col" align="center">Owing Hours</th>
    <?php } else  { ?>
    <th scope="col" align="center"> Hours</th>
    <?php } ?>  
    <th scope="col" align="center">School</th>
    <th scope="col" align="center">Location</th>
    <th scope="col" align="center">In  Car Date</th>
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
								  value="<?php echo $val['logCarID']; ?>" onclick="selectDeselectAll(this, <?php echo $total; ?>, 2);" /></td>
    <td><?php echo $val['StudentID']; ?><?php //=$rowcount?></td>
    <td><?php 	$arr = explode(" ",$val['Instructor']);
					print $arr[2].' '.$arr[1].' '.$arr[0];
					?></td>
    <td><?=$val['LastName'].' '.$val['FirstName']?></td>
    <td><?=$val['Phone']?></td>
    <td><?=$val['Owing']?></td> 
    <?php if( @$_REQUEST['sorby']=='date' ) { ?><td  align="center"> <?=$val['Hours']?></td><?php } else if(@$_REQUEST['sorby']=='timepending' || @$_REQUEST['sorby']=='hours' ) { ?>   
    <td  align="center"><?=$val['Hours']?></td>
    <td  align="center"><?php  print ($drivinghrs-@$val['Hours']);  ?></td>
    <?php } else  { ?>
    <td scope="col" align="center"> <?=$val['Hours']?></td>
    <?php } ?>  
    
    

   
    <td><?=$val['School']?></td>
    <td><?=$val['Location']?></td>
    <td><?php print dateformat($val['InCarDate']);?></td>    
    <td><?=$val['Hours']?></td>   
    <td><a href="view_student.php?id=<?php echo $val['StudentID']?>" border="0" ><img src="images/view.png" border="0" /></a></td>
    <td><a href="edit_incar.php?id=<?php echo $val['StudentID']?>" border="0" ><img src="images/edit.gif" border="0" /></a></td>
    <td><a href="action_page.php?mode=delete&form=carlog&id=<?php echo ($val['StudentID']);?>" onclick="return checkmode(3)" >
								  <img src="images/delete.png" border="0" /></a></td>
  </tr>
 <?php $row_count ++; } ?>
</table>
<br />
  <div class="row">
  <div class="col-sm-12 text-right">
   <div class="col-sm-5  text-left"><span class="btn btn-default btn-lg active" onclick="javascript: checkAll(this, <?php echo $total; ?> )">All</span> &nbsp; 
								<span class="btn btn-default btn-lg active"  onclick="javascript: uncheckAll(this, <?php echo $total; ?> )">None</span>&nbsp;&nbsp;&nbsp; 
								<a class="btn btn-default btn-lg active" href="javascript:void(0);" 
								    onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'action_page.php?mode=selectedDelete&form=carlog&id=',4);" >
									<img src="images/deleteall.png" border=0/>&nbsp;Delete All</a> &nbsp;&nbsp;&nbsp; 
                                     <a href="trash_student.php" class="btn btn-default btn-lg active"  >Trash</a></div>  
  		<div class=""><a  href="javascript:void(0);" 
        onclick="javascript: doCheckBoxAction(this, <?php echo $total; ?>, 'xcel/carreportxcelsheet.php?tableid=incLectrID&tablename=inc_lectr_log&ids=',0);" 
        title="student list"><button type="button" class="btn btn-default btn-lg active" >Export to Excel</button></a></div>
  </div>
  </div>
<?php } else { 
 ?><br /> <div class="row"><div class="col-sm-12"><div class="col-sm-12">
<div class="student alert alert-danger">There is no Record against this search criteria.</div></div></div></div><?php  
  }   ?></form>
  <div class=" clearfix"></div>
  <br />
</div>

<?php include_once('footer.php');?>
</body>
</html>
