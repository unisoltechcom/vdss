<?php session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
$s = new StudentForm;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	include_once('upload.php');
	
	
	
	$classdata = $_POST;
	$classdata['files'] = serialize($ret);
	
	//print_r($classdata);	
	$response = $s->add_instructor(str_replace("'","`",$classdata));
	//$response = $s->add_instructor($classdata);
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
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
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
  <div class="student">Instructor Information</div>
  <div class=" clearfix"></div>
  <br />
  <form class="form-horizontal" name="myForm"  action="" method="post" onsubmit="return validateForm(this);" enctype="multipart/form-data">
    <div class=" clearfix"></div>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >First Name :</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="Ins_First_Name" >
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Last Name:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="Ins_Last_Name" >
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Email:</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" name="ins_email">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Gender:</label>
            <div class="col-sm-8">
              <select class="form-control" name="Ins_Gender">
                  <option>--Select--</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
            </div>
          </div>
        </div>
     
     <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Address:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="Ins_Address" >
            </div>
          </div>
        </div>
      <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Rate:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="Ins_Rate" >
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Joining Date:</label>
            <div class="col-sm-8">
              <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-13" name="Ins_Joining_Date">
            </div>
          </div>
        </div>  
     
     <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Driving Rate:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="InsDrivingRate" id="InsDrivingRate" >
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="row">
      <div class="col-sm-12">
      <div class="student">Upload Additional Documents (PDF, JPG or DOC)</div>
  <div class=" clearfix"></div><br />
      <div class="repeater-custom-show-hide">
      <div data-repeater-list="document">
        <div data-repeater-item="" class="col-sm-6">
          <div class="form-group box">
            <div class="col-sm-9 "><!--multiple="" -->
					<input type="file"  accept=".png, .jpg, .jpeg, .PDF, .doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" data-multiple-caption="{count} files selected" class="inputfile inputfile-3"  name="myfile">
					
				</div>

            <div class="col-sm-3">
              <span data-repeater-delete="" class="btn btn-danger btn-sm">
                <span class="glyphicon glyphicon-remove"></span> Delete
              </span>
            </div>
          </div>
        </div>
        
      </div>
      <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
          <span data-repeater-create="" class="btn btn-info btn-md">
            <span class="glyphicon glyphicon-plus"></span> Add
          </span>
        </div>
      </div>
      <hr>
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
<script src="js/jquery.repeater.min.js"></script>
<script>
$(document).ready(function () {
  'use strict';
  $('.repeater-custom-show-hide').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function (remove) {
      if(confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });

;( function ( document, window, index )
{/*
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
*/}( document, window, 0 ));

});
</script>
<script>


		function validateForma(){
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
