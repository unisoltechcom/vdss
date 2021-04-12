<?php 
require_once("dompdf_config.inc.php");
include_once '../config.php';
include_once '../includes/database.php';
include_once '../includes/studentform.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   if ( get_magic_quotes_gpc() )
    $_POST["html"] = stripslashes($_POST["html"]);
	$studentname =$_POST['nameStudent'];
	$dompdf = new DOMPDF();
  $dompdf->load_html($_POST["html"]);
  $dompdf->set_paper('a4', 'landscape');
  $dompdf->render();
  $dompdf->stream("$studentname.pdf", array("Attachment" => true));

  print "control here";
}
$s = new StudentForm;
$response = $s->select_student_byid($_REQUEST['id']);
?>  
<form style="display:none" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" name="pdfCreate">

<input type="hidden" name="nameStudent" value="<?=$response['firstName']?> <?=$response['lastName']?>" />
<textarea name="html" cols="60" rows="20"><html>
<head>
<title>PDF Student Report</title>
</head>
<style>.centertxt td {text-align:center;}</style>
<body>
<div class="student" style="text-align:center; border-top:1px solid; border-bottom:1px solid; font-size:20px;">Student <strong><?=$response['firstName']?> <?=$response['lastName']?></strong> Information</div>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>First Name:</strong></td>
    <td><?=$response['firstName']?></td>
    <td><strong>School:</strong></td>
    <td><?=$response['School']?></td>
    <td><strong>In Car Start:</strong></td>
    <td><?=dateformat($response['InCarStart'])?></td>
  </tr>
  <tr>
    <td><strong>Last Name:</strong></td>
    <td><?=$response['lastName']?></td>
    <td><strong>Licence:</strong></td>
    <td><?=$response['Licence']?></td>
    <td><strong>In Car End:</strong></td>
    <td><?=dateformat($response['InCarEnd'])?></td>
  </tr>
  <tr>
    <td><strong>Gender:</strong></td>
    <td><?=$response['stGender']?></td>
    <td><strong>Obtained:</strong></td>
    <td><?=dateformat($response['Obtained'])?></td>
    <td><strong>In Car Marks:</strong></td>
    <td><?=$response['InCarMarks']?></td>
  </tr>
  <tr>
    <td><strong>Address:</strong></td>
    <td><?=$response['Address']?></td>
    <td><strong>Expire:</strong></td>
    <td><?=dateformat($response['Expire'])?></td>
    <td><strong>Certified Date:</strong></td>
    <td><?=$response['Completed']?></td>
  </tr>
  <tr>
    <td><strong>City:</strong></td>
    <td><?=$response['stCity']?></td>
    <td><strong>Licence Class:</strong></td>
    <td><?=$response['LicenceClass']?></td>
    <td><strong>Course Fee:</strong></td>
    <td><?=$response['CourseFee']?></td>
  </tr>
  <tr>
    <td><strong>Postal Code:</strong></td>
    <td><?=$response['PostalCode']?></td>
    <td><strong>Class Start:</strong></td>
    <td><?=dateformat($response['ClassStart'])?></td>
    <td><strong>Owing:</strong></td>
    <td><?=$response['Owing']?></td>
  </tr>
  <tr>
    <td><strong>Phone:</strong></td>
    <td><?=$response['stPhone']?></td>
    <td><strong>Class End:</strong></td>
    <td><?=dateformat($response['ClassEnd'])?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Date Of Birth:</strong></td>
    <td><?=dateformat($response['dateOfBirth'])?></td>
    <td><strong>Class Marks:</strong></td>
    <td><?=$response['ClassMarks']?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Emergency Contact:</strong></td>
    <td><?=$response['nameEmergency']?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Emergency Phone:</strong></td>
    <td><?=$response['phoneEmergency']?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Notes:</strong></td>
    <td><?=$response['Notes']?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="centertxt">
  <tr>
    <td valign="top"><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
        <caption class="caption">
        <b>Classroom Instruction</b>
        </caption>
        <thead>
          <tr>
            <th width="25%">Date</th>
            <th width="12%">Hours</th>
			<th width="33%">Instructor</th>
            <th  width="30%">Location</th>
          </tr>
        </thead>
        <tbody>
        <?php $Class = $s->class_log_byid($response['StudentID']); 
		$hour =0;
		foreach($Class as $val) {
			$hour = $hour + $val['Hours']; ?>
          <tr>
            <td><?=dateformat($val['InClassDate'])?></td>
            <td><?=$val['Hours']?></td>
			<td><?=$val['Instructor']?></td>
            <td><?=$val['Location']?></td>
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
    <td  valign="top" ><table class="table" width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
        <caption class="caption">
        <b>In Car Instruction</b>
        </caption>
        <thead>
          <tr>
            <th width="40%">Date</th>
            <th width="20%">Hours</th>
            <th width="40%">Instructor</th>
          </tr>
        </thead>
        <tbody>
        <?php $Cars = $s->car_log_byid($response['StudentID']); 
		$hour = 0;
		foreach($Cars as $val) { 
		$hour = $hour + $val['Hours'];
		?> 
          <tr>
            <td><?=dateformat($val['InCarDate'])?></td>
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
  </tr>
  <tr>
      <td valign="top" style="padding:8px;"><strong>Payments</strong></td>
      <td valign="top" style="padding:8px;"><strong>Other</strong></td>
    </tr>
    <tr>
      <td valign="top"><table class="table" width="100%" border="1" cellspacing="0" cellpadding="8">
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
      <td valign="top"><table  class="table" width="100%" border="1" cellspacing="0" cellpadding="8">
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
</body>
</html>
</textarea>

<button type="submit">Download</button>

</form> 
<script>window.onload = function(){
  document.pdfCreate.submit();
};</script>
  