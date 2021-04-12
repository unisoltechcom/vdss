<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$s = new StudentForm;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$inc_lectr_log = $_POST;
	$response = $s->insert_query('inc_lectr_log',$inc_lectr_log);
	
	//$response = $s->add_instructor($classdata);
	if ($response) {
		$_SESSION['recordsave'] = 'Record has been saved successfully!';	
	} else {
	  mySqlErrors($response);
	 }
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
    <h4>  <?php if(@isset($_SESSION['recordsave'])) { print $_SESSION['recordsave']; unset($_SESSION['recordsave']); } ?> </h4>
  </div>
  <div class="student">Instructor Lesson log</div>
  <div class=" clearfix"></div>
  <br />
  <form class="form-horizontal" name="myForm"  action="" method="post" onsubmit="return validateForm(this);">
    <div class=" clearfix"></div>
    <div class="row">
      <div class="col-md-12">
      <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Instructor:</label>
            <div class="col-sm-8">
              <select class="form-control" name="Ins_id">
                <option>Select Instructor</option>
                <?php 

		  $response = $s->list_instructor();
		  foreach($response as $val)
		  {  ?>               
            <option value="<?=$val['Ins_id']?>"><?=$val['Ins_Last_Name'].' '.$val['Ins_First_Name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >School Name :</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="schoolName" >
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Course Name:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="course">
                  <option>--Select--</option>
                  <option value="G">G</option>
                  <option value="G1">G1</option>
                  <option value="G2">G2</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Lesson Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="lessonDate" placeholder="dd/mm/yy" id = "datepicker-13">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Hours:</label>
            <div class="col-sm-8">
              <select class="form-control" id="hours" name="hours">
                <option value="">Select Hour(s)</option>
                <option value="1">1:00</option>
                <option value="1.5">1:50</option>
                <option value="2">2:00</option>
                <option value="2.5">2:50</option>
              </select>
            </div>
          </div>
        </div>
     
     <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Sub:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="sub" >
            </div>
          </div>
        </div>
      
          
     
     
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <div class="col-sm-10 text-right">
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">
          </div>
        </div>
      </div>
    </div>
  <div class=" clearfix"></div>
  </form>
</div>

<?php include_once('footer.php');?>
<script>
		function validateForm(){
		if( document.myForm.Ins_First_Name.value == "" )
         {
		  $('#FirstName').closest('div').find('div.errormsg').text("Please Enter Your First Name!").fadeOut(3000);
            document.myForm.Ins_First_Name.focus() ;
            return false;
         }        

         if( document.myForm.Ins_Last_Name.value == "" )
         {
		   $('#LastName').closest('div').find('div.errormsg').text("Please Enter Your Last Name!").fadeOut(3000);
            document.myForm.Ins_Last_Name.focus() ;
            return false;
         }
		 if( document.myForm.ins_email.value == "" )
         {
		   $('#LastName').closest('div').find('div.errormsg').text("Please Enter Your Emale!").fadeOut(3000);
            document.myForm.ins_email.focus() ;
            return false;
         }
		 
		 if( document.myForm.Ins_Gender.value == "" )
         {
		   $('#Gender').closest('div').find('div.errormsg').text("Please Select Gender!").fadeOut(3000);
            document.myForm.Gender.focus() ;
            return false;
         }
          if( document.myForm.Ins_Address.value == "" )
         {  $('#Address').closest('div').find('div.errormsg').text("Please Enter Your Address!").fadeOut(3000);          
            document.myForm.Ins_Address.focus() ;
            return false;
         }
		  if( document.myForm.Ins_Rate.value == "" )
         {   $('#City').closest('div').find('div.errormsg').text("Please Enter Rate!").fadeOut(3000);
            document.myForm.Ins_Rate.focus() ;
            return false;
         }
		  if( document.myForm.Ins_Joining_Date.value == "" )
         {	 $('#PostalCode').closest('div').find('div.errormsg').text("lease Enter Your Joining Date!").fadeOut(3000);
			  document.myForm.Ins_Joining_Date.focus() ;
            return false;
         }         
         return( true );
}

          

        </script>
</body>
</html>
