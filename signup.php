<?php
  session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
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
    <div class="container">
    <div class="col-sm-2">&nbsp;</div>
     <div class="col-sm-7">
      <form class="form-signup" id="usersignup" name="usersignup" method="post" action="createuser.php">
        <h2 class="form-signup-heading">Add User</h2>
         
        <input name="newuser" id="newuser" type="text" class="form-control" placeholder="Username" autofocus> <br />
        <input name="email" id="email" type="text" class="form-control" placeholder="Email"> <br />

        <input name="password1" id="password1" type="password" class="form-control" placeholder="Password"> <br />
        <input name="password2" id="password2" type="password" class="form-control" placeholder="Repeat Password"> <br />
        <select class="form-control">
        <option>--Select Role--</option>
        <option>Admin</option>
        <option>CFO</option
        ><option>Manager</option>
        <option>Instructor</option>
        <option>Staff</option>
        <option>Student</option>
        <option></option>
        </select><br />
       <div class="col-sm-8 text-right"> <button name="Submit" id="submit" class="btn btn-primary btn-lg active" type="submit">Sign up</button></div>

        <div id="message"></div>
      </form>
</div>

    </div></div> <!-- /container -->

<?php include_once('footer.php');?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="login/js/bootstrap.js"></script>

    <script src="login/js/signup.js"></script>


    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<script>

$( "#usersignup" ).validate({
  rules: {
	email: {
		email: true,
		required: true
	},
    password1: {
      required: true,
      minlength: 4
	},
    password2: {
      equalTo: "#password1"
    }
  }
});
</script>

  </body>
</html>
