<?php session_start();

require 'includes/functions.php';
include_once 'config.php';

/*if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} */
$s = new StudentForm;
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 


$classarray = str_replace("'","`",$_POST['Class']);
unset($_POST['Class']);

$cararray = str_replace("'","`",$_POST['Car']);
unset($_POST['Car']);

$Installment = serialize(str_replace("'","`",$_POST['Installment']));
unset($_POST['Installment']);

$studentdata =str_replace("'","`",$_POST);
$studentdata['Installment']=$Installment;
$stid = $s->insert_query('student_information',$studentdata);


$classarray['StudentID']=$stid;
$cararray['StudentID']= $stid;

$s->insert_query('car_log',$cararray);
$s->insert_query('class_log',$classarray);
$_SESSION['recordsave'] = 'Record has been saved successfully!';
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
  $("#datepicker-13, #datepicker-14, #datepicker-11, #datepicker-15, #datepicker-16, #datepicker-17, #datepicker-18, #complete ,#datepicker-31,#datepicker-32, #submitdate2,#submitdate").each(function() {
     $(this).datepicker({
       autoclose: true,
       dateFormat: 'dd/mm/yy',
     });
  });
});
</script> 
</head>

<body>
<div class="wrapper"> <center><?php include_once('nav.php'); ?></center>
<!--onsubmit="return validateForm(this);"-->
 <form class="form-horizontal" name="myForm"  action="" id="studentform" method="post"  onsubmit="return validateForm(this);" >
  <div class="row">
    <div class="col-md-12">
    <div class="text-success text-center"><h4><?php if(@isset($_SESSION['recordsave'])) { print $_SESSION['recordsave']; unset($_SESSION['recordsave']); } ?></h4></div>
      <div class="student">Student Information</div><br />
      <div class="col-md-4 col-sm-6">
       <div class="form-group">
            <label class="control-label col-sm-4" >First Name:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="FirstName" name="FirstName" >
              <div class="has-error errormsg"></div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Last Name:</label>
            <div class="col-sm-8">
              <input type="Text" class="form-control" id="LastName" name="LastName" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Email:</label>
            <div class="col-sm-8">
              <input type="Text" class="form-control" id="StudentEmail" name="StudentEmail" >
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >Gender:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="Gender">
                  <option>--Select--</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Location:</label>
            <div class="col-sm-8">
            <select class="form-control" name="Location"  id="Location">
                  <option>--Select--</option>
                  <option value="Hanmer">Hanmer</option>
                  <option value="Sudbury">Sudbury</option>
                </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >Address:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Address" name="Address" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >City:</label>
            <div class="col-sm-8">
            <input type="text" class="form-control" id="City" name="City" >              
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Postal Code:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Pcode" name="PostalCode" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Phone:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Phone" name="Phone">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Date Of Birth:</label>
            <div class="col-sm-8">            
                <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-13" name="DateOfBirth">
              </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >Emergency Contact:</label>
            <div class="col-sm-8">            
                <input type = "text" class="form-control" name="nameEmergency">
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Emergency Phone:</label>
            <div class="col-sm-8">            
                <input type = "text" class="form-control" name="phoneEmergency">
              </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >Notes:</label>
            <div class="col-sm-8">
              <textarea nclass="form-control" id="Notes" name="Notes" ></textarea>
            </div>
          </div>
      </div>
      <div class="col-md-4 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >School:</label>
            <div class="col-sm-8">
              <input type="Text" class="form-control" id="school" name="School">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Licence:</label>
            <div class="col-sm-8">
            <input type="Text" class="form-control" id="Licence" name="Licence">
             
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Obtained:</label>
            <div class="col-sm-8">
               <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-14" name="Obtained">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Expire:</label>
            <div class="col-sm-8">
              <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-11" name="Expire">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Licence Class:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="LicenceClass">
                  <option>--Select--</option>
                  <option value="G">G</option>
                  <option value="G1">G1</option>
                  <option value="G2">G2</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Start:</label>
            <div class="col-sm-8">
   <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-15" name="ClassStart">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class End:</label>
            <div class="col-sm-8">
       <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-16" name="ClassEnd">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Marks:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="marks" name="ClassMarks" >
            </div>
          </div>
      </div>
      <div class="col-md-4 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car Start:</label>
            <div class="col-sm-8">
             <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-17" name="InCarStart">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car End:</label>
            <div class="col-sm-8">
          <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-18" name="InCarEnd">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car Marks:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="carmarks" name="InCarMarks" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Certified Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="complete" name="Completed"  placeholder="dd/mm/yy" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Course Fee:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Cfee" name="CourseFee">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Owing:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="owing" name="Owing" >
            </div>
          </div>
         
          <div class="form-group">
            <label class="control-label col-sm-4" >1st Installment:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="installment" name="Installment[installment]" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">How it was paid:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="Installment[paid1]" >
                  <option value="">--Select--</option>
                  <option value="credit card">Credit card</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >1st Submit Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="submitdate" name="Installment[date]"   placeholder="dd/mm/yy" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >2nd Installment:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="installment2" name="Installment[installment2]" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">How it was paid:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="Installment[paid2]">
                  <option value="">--Select--</option>
                  <option value="credit card">Credit card</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">2nd Submit Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="submitdate2" name="Installment[date2]"   placeholder="dd/mm/yy" />
            </div>
          </div>
          
          
           
        
      </div>
    </div>
  </div>
  
  <br />
  <div class="student">Log Class Hours</div>
  <br /><!--onsubmit="return validateForm(this);"-->
    
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  placeholder="dd/mm/yy" id="datepicker-31" name="Class[InClassDate]" >
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Hours:</label>
            <div class="col-sm-8">
              <select class="form-control" id="Hours" name="Class[Hours]">
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
            <label class="control-label col-sm-4" >Location:</label>
            <div class="col-sm-8">
              <input type = "text" class="form-control" name="Class[Location]">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Instructor:</label>
            <div class="col-sm-8">
              <select class="form-control" name="Class[Instructor]">
                <option>Select Instructor</option>
                <?php 

		  $response = $s->list_instructor();
		  foreach($response as $val)
		  {  ?>               
            <option value="<?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?>"><?=$val['Ins_Last_Name'].' '.$val['Ins_First_Name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  <br />
   <div class="student">Log Car Hours</div>
  <br /><!--onsubmit="return validateForm(this);"-->
    
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  placeholder="dd/mm/yy" id = "datepicker-32" name="Car[InCarDate]" >
              <div class="has-error errormsg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >Hours:</label>
            <div class="col-sm-8">
              <select class="form-control" id="School" name="Car[Hours]">
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
            <label class="control-label col-sm-4" >Instructor:</label>
            <div class="col-sm-8">
              <select class="form-control" name="Car[Instructor]">
                <option>Select Instructor</option>
                <?php 
			 //$response = $s->list_instructor();
			 foreach($response as $val)
			  {  ?>               
               		<option value="<?=$val['Ins_First_Name'].' '.$val['Ins_Last_Name']?>"><?=$val['Ins_Last_Name'].' '.$val['Ins_First_Name']?></option>
                <?php }?>
                
                              
              </select>
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
  </form>
 
</div>
 <?php include_once('footer.php');?>
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
		//  if( document.myForm.Notes.value == "" )
//         {
//			  $('#Notes').closest('div').find('div.errormsg').text("Please Enter Your Notes!").fadeOut(3000);
//			  document.myForm.Notes.focus() ;
//            return false;
//         }
		  if( document.myForm.School.value == "" )
         {
			  $('#School').closest('div').find('div.errormsg').text("Please Enter Your School!").fadeOut(3000);
			  document.myForm.School.focus() ;
            return false;
         }
		 //  if( document.myForm.Licence.value == "" )
//         {
//			  $('#Licence').closest('div').find('div.errormsg').text("Please Enter Your Licence!").fadeOut(3000);
//			  document.myForm.Licence.focus() ;
//            return false;
//         }
		   /* if( document.myForm.Obtained.value == "" )
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
         }*/
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
		// if( document.myForm.ClassMarks.value == "" )
//         {
//			  $('#ClassMarks').closest('div').find('div.errormsg').text("Please Enter Your Class Marks!").fadeOut(3000);
//			  document.myForm.ClassMarks.focus() ;
//            return false;
//         }
		//  if( document.myForm.InCarStart.value == "" )
//         {
//			  $('#datepicker-17').closest('div').find('div.errormsg').text("Please Enter Your In Car Start  Date!").fadeOut(3000);
//			  document.myForm.InCarStart.focus() ;
//            return false;
//         }
// if( document.myForm.HRSInCars.value == "" )
//         {
//			   $('#HRSInCars').closest('div').find('div.errormsg').text("Please Enter Your HRS In Cars!").fadeOut(3000);
//			  document.myForm.HRSInCars.focus() ;
//            return false;
//         }
//		 if( document.myForm.HRSRemaining.value == "" )
//         {
//			$('#HRSRemaining').closest('div').find('div.errormsg').text("Please Enter Your HRS Remaining!").fadeOut(3000);
//			  document.myForm.HRSRemaining.focus() ;
//            return false;
//         }
//		  if( document.myForm.InCarEnd.value == "" )
//         {
//			 $('#datepicker-18').closest('div').find('div.errormsg').text("Please Enter Your In Car End Date!").fadeOut(3000);
//			  document.myForm.InCarEnd.focus() ;
//            return false;
//         }
//		   if( document.myForm.InCarMarks.value == "" )
//         {
//			  $('#InCarMarks').closest('div').find('div.errormsg').text("Please Enter Your In Car Marks!").fadeOut(3000);
//			  document.myForm.InCarMarks.focus() ;
//            return false;
//         }
//		    if( document.myForm.Completed.value == "" )
//         {
//			 $('#Completed').closest('div').find('div.errormsg').text("Please Enter Your Completed!").fadeOut(3000);
//			  document.myForm.Completed.focus() ;
//            return false;
//         }
//		  if( document.myForm.CourseFee.value == "" )
//         {
//			 		  $('#CourseFee').closest('div').find('div.errormsg').text("Please Enter Your In Course Fee!").fadeOut(3000);
//			  document.myForm.CourseFee.focus() ;
//            return false;
//         }
//		   if( document.myForm.Owing.value == "" )
//         {
//			 $('#Owing').closest('div').find('div.errormsg').text("Please Enter Your Owing!").fadeOut(3000);
//			  document.myForm.Owing.focus() ;
//            return false;
//         }
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
