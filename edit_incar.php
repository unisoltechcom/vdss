<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$s = new StudentForm;
$responses = $s->edit_car_log_byid($_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$logCarID = $_POST['logCarID'];
	unset($_POST['logCarID']);
	$data = $_POST;
	//print_r($data);exit;
	$table='car_log';
	$where = " logCarID = '$logCarID'";
	$response = $s->update_query($table, $data, $where);
	
	if ($response == 'true') {
		$_SESSION['recordsave'] = 'You have successfully saved your form!';	
	} else {
	  mySqlErrors($response);
	 }
	 print "<script>window.location = 'carloglist.php';</script>";
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

       autoclose: true,
       dateFormat: 'dd/mm/yy',
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
  <div class="text-success text-center">
    <h4>
      <?php if(@isset($_SESSION['recordsave'])) { print $_SESSION['recordsave']; unset($_SESSION['recordsave']); } ?>
    </h4>
  </div>
  <div class="student">Log Class Hours</div>
  <br />
  <form class="form-horizontal" name="myForm"  action="" method="post" onsubmit="return validateForm(this);">
    <div class="row">
      <div class="col-md-12">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Student:</label>
            <div class="col-sm-8">
              <select class="form-control" name="StudentID" id="StudentID">
                <option>Select Student</option>
 <?php 

		  $response = $s->list_students();

		  foreach($response as $val)

		  {  ?>
                <option value="<?=$val['StudentID']?>">
                <?=$val['FirstName'].' '.$val['LastName']?>
                </option>
                <?php }?>
              </select>
              <script>jQuery('#StudentID  option[value="<?=$responses['StudentID']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  placeholder="dd/mm/yy" id = "datepicker-17" name="InCarDate" value="<?=$responses['InCarDate']?>" />
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Hours:</label>
            <div class="col-sm-8">
              <select class="form-control" id="Hours" name="Hours">
                <option value="">Select Hour(s)</option>
                <option value="1">1:00</option>
                <option value="1.5">1:50</option>
                <option value="2">2:00</option>
                <option value="2.5">2:50</option>
              </select>
              <script>jQuery('#Hours  option[value="<?=$responses['Hours']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>
        <!--<div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Location:</label>
            <div class="col-sm-8">
              <input type = "text" class="form-control" name="Location" value="<?=$responses['Location']?>">
            </div>
          </div>
        </div>-->
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Instructor:</label>
            <div class="col-sm-8">
              <select class="form-control" name="Instructor" id="Instructor">
                <option>Select Instructor</option>
                <?php 

		  $response = $s->list_instructor();
		  foreach($response as $val)
		  {  ?>               
            <option value="<?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?>"><?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?></option>
                <?php }?>
              </select>
              <script>jQuery('#Instructor  option[value="<?=$responses['Instructor']?>"]').prop("selected", true);</script>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <div class="col-sm-10 text-right">
          <input type="hidden" name="logCarID" value="<?=$responses['logCarID']?>"  />
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>



		function validateForm()

		{ 

		if( document.myForm.FirstName.value == "" )

         {

		  $('#FirstName').closest('div').find('div.errormsg').text("Please Enter Your First Name!").fadeOut(3000);

            document.myForm.FirstName.focus() ;

            return false;

         }

         

         if( document.myForm.LastName.value == "" )

         {

		   $('#LastName').closest('div').find('div.errormsg').text("Please Enter Your Last Name!").fadeOut(3000);

            document.myForm.LastName.focus() ;

            return false;

         }

		 

         if( document.myForm.Gender.value == "" )

         {

		   $('#Gender').closest('div').find('div.errormsg').text("Please Select Gender!").fadeOut(3000);

            document.myForm.Gender.focus() ;

            return false;

         }

		

		 

         if( document.myForm.Address.value == "" )

         {

			  $('#Address').closest('div').find('div.errormsg').text("Please Enter Your Address!").fadeOut(3000);          

            document.myForm.Address.focus() ;

            return false;

         }

          if( document.myForm.City.value == "" )

         {

		   $('#City').closest('div').find('div.errormsg').text("Please Select City!").fadeOut(3000);

            document.myForm.City.focus() ;

            return false;

         }

		

         if( document.myForm.PostalCode.value == "" )

         {

			 $('#PostalCode').closest('div').find('div.errormsg').text("Please Enter Your Postal Code!").fadeOut(3000);

			  document.myForm.PostalCode.focus() ;

            return false;

         }

		   if( document.myForm.Phone.value == "" )

         {

			  $('#Phone').closest('div').find('div.errormsg').text("Please Enter Your Phone Number!").fadeOut(3000);

			  document.myForm.Phone.focus() ;

            return false;

         }

		    if( document.myForm.DateOfBirth.value == "" )

         {

			  $('#datepicker-13').closest('div').find('div.errormsg').text("Please Enter Your Date Of Birth!").fadeOut(3000);

			  document.myForm.DateOfBirth.focus() ;

            return false;

         }

		  if( document.myForm.Notes.value == "" )

         {

			  $('#Notes').closest('div').find('div.errormsg').text("Please Enter Your Notes!").fadeOut(3000);

			  document.myForm.Notes.focus() ;

            return false;

         }

		  if( document.myForm.School.value == "" )

         {

			  $('#School').closest('div').find('div.errormsg').text("Please Enter Your School!").fadeOut(3000);

			  document.myForm.School.focus() ;

            return false;

         }

		   if( document.myForm.Licence.value == "" )

         {

			  $('#Licence').closest('div').find('div.errormsg').text("Please Enter Your Licence!").fadeOut(3000);

			  document.myForm.Licence.focus() ;

            return false;

         }

		    if( document.myForm.Obtained.value == "" )

         {

			  $('#datepicker-14').closest('div').find('div.errormsg').text("Please Enter Your Obtained!").fadeOut(3000);

			  document.myForm.Obtained.focus() ;

            return false;

         }

		     if( document.myForm.Expire.value == "" )

         {

			  $('#datepicker-11').closest('div').find('div.errormsg').text("Please Enter Your Expire!").fadeOut(3000);

			  document.myForm.Expire.focus() ;

            return false;

         }

		  if( document.myForm.LicenceClass.value == "" )

         {

		   $('#LicenceClass').closest('div').find('div.errormsg').text("Please  Select Licence Class!").fadeOut(3000);

            document.myForm.LicenceClass.focus() ;

            return false;

         }

		      if( document.myForm.ClassStart.value == "" )

         {

			  $('#datepicker-15').closest('div').find('div.errormsg').text("Please Enter Your Class Start Date!").fadeOut(3000);

			  document.myForm.ClassStart.focus() ;

            return false;

         }

		 		      if( document.myForm.ClassEnd.value == "" )

         {

			  $('#datepicker-16').closest('div').find('div.errormsg').text("Please Enter Your Class End Date!").fadeOut(3000);



			  document.myForm.ClassEnd.focus() ;

            return false;

         }

		 if( document.myForm.ClassMarks.value == "" )

         {

			  $('#ClassMarks').closest('div').find('div.errormsg').text("Please Enter Your Class Marks!").fadeOut(3000);

			  document.myForm.ClassMarks.focus() ;

            return false;

         }

		  if( document.myForm.InCarStart.value == "" )

         {

			  $('#datepicker-17').closest('div').find('div.errormsg').text("Please Enter Your In Car Start  Date!").fadeOut(3000);

			  document.myForm.InCarStart.focus() ;

            return false;

         }

 if( document.myForm.HRSInCars.value == "" )

         {

			   $('#HRSInCars').closest('div').find('div.errormsg').text("Please Enter Your HRS In Cars!").fadeOut(3000);

			  document.myForm.HRSInCars.focus() ;

            return false;

         }

		 if( document.myForm.HRSRemaining.value == "" )

         {

			$('#HRSRemaining').closest('div').find('div.errormsg').text("Please Enter Your HRS Remaining!").fadeOut(3000);

			  document.myForm.HRSRemaining.focus() ;

            return false;

         }

		  if( document.myForm.InCarEnd.value == "" )

         {

			 $('#datepicker-18').closest('div').find('div.errormsg').text("Please Enter Your In Car End Date!").fadeOut(3000);

			  document.myForm.InCarEnd.focus() ;

            return false;

         }

		   if( document.myForm.InCarMarks.value == "" )

         {

			  $('#InCarMarks').closest('div').find('div.errormsg').text("Please Enter Your In Car Marks!").fadeOut(3000);

			  document.myForm.InCarMarks.focus() ;

            return false;

         }

		    if( document.myForm.Completed.value == "" )

         {

			 $('#Completed').closest('div').find('div.errormsg').text("Please Enter Your Completed!").fadeOut(3000);

			  document.myForm.Completed.focus() ;

            return false;

         }

		  if( document.myForm.CourseFee.value == "" )

         {

			 		  $('#CourseFee').closest('div').find('div.errormsg').text("Please Enter Your In Course Fee!").fadeOut(3000);

			  document.myForm.CourseFee.focus() ;

            return false;

         }

		   if( document.myForm.Owing.value == "" )

         {

			 $('#Owing').closest('div').find('div.errormsg').text("Please Enter Your Owing!").fadeOut(3000);

			  document.myForm.Owing.focus() ;

            return false;

         }

         return( true );

							

							

							

							

							  var fields = ['LastName', 'FirstName', 'Gender', 'Address', 'City', 'PostalCode', 'Phone', 'DateOfBirth', 'Notes', 'School', 'Licence', 'Obtained', 'Expire', 'LicenceClass', 'ClassStart', 'ClassEnd', 'ClassMarks', 'InCarStart', 'HRSInCars', 'HRSRemaining', 'InCarEnd', 'InCarMarks', 'Completed', 'CourseFee', 'Owing']

							

							  var i, l = fields.length;

							  var fieldname;

							  for (i = 0; i < l; i++) {

								fieldname = fields[i];

								if (document.forms["register"][fieldname].value === "") {

								  alert(fieldname + " can not be empty");

								  return false;

								}

							  }

							  return true;

							}

          

        </script>
</body>
</html>
