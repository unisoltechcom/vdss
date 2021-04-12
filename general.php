<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$dc = new Database;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	$CostofPackage = mysql_real_escape_string($_POST['COSTOFPACKAGE']);
	$Tax = mysql_real_escape_string($_POST['TAX']);
	$DrivingHours = mysql_real_escape_string($_POST['DRIVINGHOURS']);
	$ClassHours = mysql_real_escape_string($_POST['CLASSHOURS']);
	$array = array('COSTOFPACKAGE'=>$CostofPackage,'TAX'=>$Tax,'DRIVINGHOURS'=>$DrivingHours,'CLASSHOURS'=>$ClassHours);
	$dataserailize = serialize($array );
	if($_POST['ID']==''){
	$query = "INSERT INTO `post_meta` (`ID`, `fid`, `meta_key`, `meta_value`) VALUES (NULL, '2', 'generalinfo', '$dataserailize');";	 
	}else{
		$query = "UPDATE `post_meta` SET `meta_value`='$dataserailize' WHERE ID=".$_POST['ID']."";
		}
	
	$response = $dc->execute_query($query);
	if ($response == 'true') {
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
    <h4>
      <?php if(@isset($_SESSION['recordsave'])) { print $_SESSION['recordsave']; unset($_SESSION['recordsave']); } ?>
    </h4>
  </div>
  <div class="student">General Information</div>
  <div class=" clearfix"></div>
  <br />
  <?php 
   $query = "select * from `post_meta` where meta_key='generalinfo' order by lastUpdate DESC limit 1";
   $responce = $dc->execute_query($query);
   $val = @mysqli_fetch_array($responce);
   $ar = unserialize($val['meta_value']);
   
  ?>
  
  <form class="form-horizontal" name="myForm"  action="" method="post" onsubmit="return validateForm(this);">
    <div class=" clearfix"></div>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Hours:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="CLASSHOURS" value="<?=$ar['CLASSHOURS']?>"   />
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Driving  Hours:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="DRIVINGHOURS"   value="<?=$ar['DRIVINGHOURS']?>" />
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Government Tax :</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="TAX"  value="<?=$ar['TAX']?>" />
            </div>
          </div>
        </div>
        
     
     <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Cost of Package:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="COSTOFPACKAGE"   value="<?=$ar['COSTOFPACKAGE']?>"  />
            </div>
          </div>
        </div>        
     
     
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <div class="col-sm-10 text-right">
          <input type="hidden" name="ID" value="<?=$val['ID']?>"  />
            <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">
          </div>
        </div>
      </div>
    </div>
  <div class=" clearfix"></div>
  </form>
</div>
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
        
<?php include_once('footer.php');?>
</body>
</html>
