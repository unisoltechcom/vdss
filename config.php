<?php

$_SESSION['username'] = $_COOKIE['userlogin'];

//Pull '$base_url' and '$signin_url' from this file

include 'globalcon.php';

//Pull database configuration from this file

include 'dbconf.php';
$link = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASENAME);
if ($link) {
	$query = "select * from `post_meta` where meta_key='generalinfo' order by lastUpdate DESC limit 1 ";
	$result = $link->query($query);

if ($result->num_rows > 0) { 
$generalinfo = unserialize( $result->fetch_assoc()['meta_value'] );
foreach($generalinfo as $key=>$val) {
define($key, $val);
}
//print_r($generalinfo);
}
	
	
	}
mysqli_close($link);


function execute_query($query){
	$link = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASENAME);
	$rsult=array();
	if ($link) {
	    //$query = "select * from `post_meta` where meta_key='generalinfo' order by lastUpdate DESC limit 1 ";
		$result = $link->query($query);
			if ($result->num_rows > 0) { 
				 while ($row = $result->fetch_assoc()) {
				 		
						$rsult[]=$row;				 
				 }				 
			}
		
		}
	mysqli_close($link);
	return $rsult;
	}