<?php
/*print "<pre>";
print_r($_FILES['newfile']); 
print count($_FILES["document"]['name']);
print_r($_FILES["document"]);
print_r($_FILES);
exit; */
$ret = array(); 
$output_dir = "uploads/"; 
if(count($_FILES["document"]['name']) > 0)
{ 
	
	
//	This is for custom errors;	
/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
function imageExists($image,$dir) {

    $i=1; $probeer=$image;

    while(file_exists($dir.$probeer)) {
        $punt=strrpos($image,".");
        if(substr($image,($punt-3),1)!==("[") && substr($image,($punt-1),1)!==("]")) {
            $probeer=substr($image,0,$punt)."[".$i."]".
            substr($image,($punt),strlen($image)-$punt);
        } else {
            $probeer=substr($image,0,($punt-3))."[".$i."]".
            substr($image,($punt),strlen($image)-$punt);
        }
        $i++;
    }
    return $probeer;
}

if(!is_dir($output_dir)) {mkdir($output_dir , 0777);}
	//$error =$_FILES["document"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["document"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["document"]['name']);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["document"]["name"][$i]['myfile'];
		if(!empty($fileName)) {
		$fileName = imageExists($fileName,$output_dir);
		if($fileName){
		move_uploaded_file($_FILES["document"]["tmp_name"][$i]['myfile'],$output_dir.$fileName);
	  	$ret[]= $fileName; } }
	  }
	
	}
 }

 ?>