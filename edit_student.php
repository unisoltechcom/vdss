<?php session_start();
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
} 
require 'includes/functions.php';
include_once 'config.php';
$s = new StudentForm;
$response = $s->select_student_byid($_REQUEST['id']);

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
  $("#datepicker-13, #datepicker-14, #datepicker-11, #datepicker-15, #datepicker-16, #datepicker-17, #datepicker-18, #complete, #submitdate2,#submitdate").each(function() {
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
 <form class="form-horizontal" name="myForm"  action="action_page.php" method="post" onsubmit="return validateForm(this);">
  <div class="row">
    <div class="col-md-12">
    <div class="text-success text-center"><h4><?php if(@isset($_SESSION['recordsave'])) { print $_SESSION['recordsave']; unset($_SESSION['recordsave']); } ?></h4></div>
      <div class="student">Student Information</div><br />
      <div class="col-md-4 col-sm-6">
       <div class="form-group">
            <label class="control-label col-sm-4" >First Name:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?=$response['FirstName']?>" />
              <div class="has-error errormsg"></div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Last Name:</label>
            <div class="col-sm-8">
              <input type="Text" class="form-control" id="LastName" name="LastName" value="<?=$response['LastName']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">Email:</label>
            <div class="col-sm-8">
              <input type="Text" name="StudentEmail" id="StudentEmail" value="<?=$response['StudentEmail']?>"  class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Gender:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" id="Gender" name="Gender">
                  <option>--Select--</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
                <script>jQuery('#Gender  option[value="<?=$response['Gender']?>"]').prop("selected", true);</script>
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
                <script>jQuery('#Location  option[value="<?=$response['Location']?>"]').prop("selected", true);</script>
              <!--<input type="text" class="form-control" id="Location" name="Location"  value="<?php //$response['Location']?>" >-->
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Address:</label>
            <div class="col-sm-8">
              <input type="Adress" class="form-control" id="Address" name="Address" value="<?=$response['Address']?>"  />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >City:</label>
            <div class="col-sm-8">
            <input type="text" class="form-control" id="City" name="City"  value="<?=$response['City']?>" />
              <!--<div class="dropdown">
                <select class="form-control" name="City">
                <option>--Select--</option>
                  <option value="sudbury">Sudbury </option>
                   <option value="Hanmer">Hanmer</option>
                </select>
              </div>-->
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Postal Code:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Pcode" name="PostalCode"   value="<?=$response['PostalCode']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Phone:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Phone" name="Phone"  value="<?=$response['Phone']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Date Of Birth:</label>
            <div class="col-sm-8">
            
                <input type = "text" class="form-control" placeholder="dd/mm/yy" id="datepicker-13" name="DateOfBirth"  value="<?=$response['DateOfBirth']?>" />
              </div>
          </div>
          
           <div class="form-group">
            <label class="control-label col-sm-4" >Emergency Contact:</label>
            <div class="col-sm-8">            
                <input type = "text" class="form-control" name="nameEmergency" value="<?=$response['nameEmergency']?>" />
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Emergency Phone:</label>
            <div class="col-sm-8">            
                <input type = "text" class="form-control" name="phoneEmergency" value="<?=$response['phoneEmergency']?>" />
              </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >Notes:</label>
            <div class="col-sm-8">
              <textarea nclass="form-control" id="Notes" name="Notes" ><?=$response['Notes']?></textarea>
            </div>
          </div>
      </div>
      <div class="col-md-4 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >School:</label>
            <div class="col-sm-8">
              <input type="Text" class="form-control" id="school" name="School"  value="<?=$response['School']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Licence:</label>
            <div class="col-sm-8">
            <input type="Text" class="form-control" id="Licence" name="Licence"  value="<?=$response['Licence']?>" />
             
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Obtained:</label>
            <div class="col-sm-8">
               <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-14" name="Obtained"  value="<?=$response['Obtained']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Expire:</label>
            <div class="col-sm-8">
              <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-11" name="Expire"  value="<?=$response['Expire']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Licence Class:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="LicenceClass" id="LicenceClass">
                  <option>--Select--</option>
                  <option value="G">G</option>
                  <option value="G1">G1</option>
                  <option value="G2">G2</option>
                </select>
                <script>jQuery('#LicenceClass  option[value="<?=$response['LicenceClass']?>"]').prop("selected", true);</script>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Start:</label>
            <div class="col-sm-8">
   <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-15" name="ClassStart"  value="<?=$response['ClassStart']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class End:</label>
            <div class="col-sm-8">
       <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-16" name="ClassEnd"  value="<?=$response['ClassEnd']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Class Marks:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="marks" name="ClassMarks"   value="<?=$response['ClassMarks']?>" />
            </div>
          </div>
      </div>
      <div class="col-md-4 col-sm-6">
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car Start:</label>
            <div class="col-sm-8">
             <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-17" name="InCarStart"  value="<?=$response['InCarStart']?>" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car End:</label>
            <div class="col-sm-8">
          <input type = "text" class="form-control" placeholder="dd/mm/yy" id = "datepicker-18" name="InCarEnd"  value="<?=$response['InCarEnd']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >In Car Marks:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="carmarks" name="InCarMarks"   value="<?=$response['InCarMarks']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Certified Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="complete" name="Completed"  placeholder="dd/mm/yy"   value="<?=$response['Completed']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Course Fee:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="Cfee" name="CourseFee"  value="<?=$response['CourseFee']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >Owing:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="owing" name="Owing"   value="<?=$response['Owing']?>" />
            </div>
          </div>
        
        <?php $instaments = unserialize ($response['Installment']); ?>
           <div class="form-group">
            <label class="control-label col-sm-4" >1st Installment:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="installment" name="Installment[installment]" value="<?=$instaments['installment']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">How it was paid:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control"  name="Installment[paid1]" id="istallmentpaid1" >
                  <option>--Select--</option>
                  <option value="credit card">Credit card</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
                 <script>jQuery('#istallmentpaid1  option[value="<?=$instaments['paid1']?>"]').prop("selected", true);</script>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >1st Submit Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="submitdate" name="Installment[date]"   placeholder="dd/mm/yy" value="<?=$instaments['date']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4" >2nd Installment:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="installment2" name="Installment[installment2]"  value="<?=$instaments['installment2']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">How it was paid:</label>
            <div class="col-sm-8">
              <div class="dropdown">
                <select class="form-control" name="Installment[paid2]" id="istallmentpaid2">
                  <option value="">--Select--</option>
                  <option value="credit card">Credit card</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
                <script>jQuery('#istallmentpaid2  option[value="<?=$instaments['paid2']?>"]').prop("selected", true);</script>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">2nd Submit Date:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="submitdate2" name="Installment[date2]"   placeholder="dd/mm/yy"  value="<?=$instaments['date2']?>" />
            </div>
          </div>
           
           <div class="form-group">
           <input type="hidden" name="StudentID" value="<?=$response['StudentID']?>"  />
           <input type="hidden" name="update" value="studentupdate"  />
            <div class="col-sm-9 text-right">
              <input type="submit" value="Submit" class="btn btn-primary btn-lg active"  onsubmit="return validateForm(this);">
            </div>
          </div>
        
      </div>
    </div>
  </div> </form>
  <br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top"><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
          <caption class="caption">
          <b>Classroom Instruction</b>
          </caption>
          <thead>
            <tr>
              <th width="40%">Date</th>
              <th width="20%">Hours</th>
              <th width="40%">Instructor</th>
            </tr>
          </thead>
          <tbody>
            <?php $Class = $s->class_log_byid($response['StudentID']); 
		$hour =0;
		foreach($Class as $val) {  $dateformt = explode("/",$val['InClassDate']);	
		$hour = $hour + $val['Hours'];?>
            <tr>
              <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
              <td><?=$val['Hours']?></td>
              <td><?=$val['Instructor']?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><strong>Total Hours</strong></td>
              <td><?=$hour?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
      <td valign="top"><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
          <caption class="caption">
          <b>In Car Instruction</b>
          </caption>
          <thead>
            <tr>
              <th width="25%">Date</th>
              <th width="10%">Hours</th>
              <th width="35%">Instructor</th>
              <th width="30%"></th>
            </tr>
          </thead>
          <tbody>
            <?php $Cars = $s->car_log_byid($response['StudentID']); 
		$hour = 0;
		foreach($Cars as $val) {  $dateformt = explode("/",$val['InCarDate']);	
		$hour = $hour + $val['Hours']; ?>
            <tr>
              <td><?=$months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2]?></td>
              <td><?=$val['Hours']?></td>
              <td><?=$val['Instructor']?></td>
              <td><?php /*$val['Location']*/?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><strong>Total Hours</strong></td>
              <td><?=$hour?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" style="padding:8px;"><strong>Payments</strong></td>
      <td valign="top" style="padding:8px;"><strong>Other</strong></td>
    </tr>
    <tr>
      <td valign="top"><table class="table" width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <th scope="col">Payment</th>
            <th scope="col">Payment Date</th>
            <th scope="col">Income</th>
            <th scope="col">GST</th>
          </tr>
          <tr>
            <td><?=$response['CourseFee']?></td>
            <td>&nbsp;</td>
            <td><?php print (($response['CourseFee'])/((1) + (.13)));?></td>
            <td><?php print ($response['CourseFee']) - (($response['CourseFee'])/((1) + (.13)));?></td>
          </tr>
        </table></td>
      <td valign="top"><table  class="table" width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <th scope="col">Other</th>
            <th scope="col">Other Date</th>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br /><br /><br />
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
		 return true;
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
		 //  if( document.myForm.Licence.value == "" )
//         {
//			  $('#Licence').closest('div').find('div.errormsg').text("Please Enter Your Licence!").fadeOut(3000);
//			  document.myForm.Licence.focus() ;
//            return false;
//         }
		    /*if( document.myForm.Obtained.value == "" )
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
