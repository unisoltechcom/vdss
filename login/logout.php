<?php
    session_start();
    session_destroy();
	setcookie("userlogin", '', time()+60*60*24*365, "/");
    header("location:main_login.php");
