<?php  session_start();
require 'includes/functions.php';
include_once 'config.php';
if (!isset($_SESSION['username'])) {
    return header("location:login/main_login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Valley Driving School Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/main.css" rel="stylesheet" media="screen">
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>
  <div class="wrapper">
  
    <div class="container"><center>
    <?php include_once('nav.php'); ?>
  </center>
      <div class=" text-center"><br><br>
        <h1>Driving School Management System</h1>
<br>     
      </div>
    </div> <!-- /container -->
    </div>
    <?php include_once('footer.php');?>
	<div align="center">
Powered by <a href="http://www.navigatormarketing.ca" target="_blank" title="navigatormarketing">www.navigatormarketing.ca</div>
  </body>
</html>
