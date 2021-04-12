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

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"

         rel = "stylesheet">

      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>

      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

      

      <!-- Javascript -->

      <script>

     jQuery(document).ready(function($) {

  $("#datepicker-13, #datepicker-14, #datepicker-11, #datepicker-15, #datepicker-16, #datepicker-17, #datepicker-18").each(function() {

     $(this).datepicker({

       autoclose: true

     });

  });

});





</script>

</head>



<body>

<div class="wrapper">

 <form class="form-horizontal" action="action_page.php" method="post">

  <div class="row">

    <div class="col-md-12">

      <div class="student">Student Information</div><br />

      <div class="col-md-4 col-sm-6">

       

          <div class="form-group">

            <label class="control-label col-sm-4" >Last Name:</label>

            <div class="col-sm-8">

              <input type="Text" class="form-control" id="lastName" name="lastName" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >First Name:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="fName" name="firstName" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Gender:</label>

            <div class="col-sm-8">

              <div class="dropdown">

                <select class="form-control" name="stGender">

                  <option>--Select--</option>

                  <option value="male">Male</option>

                  <option value="female">Female</option>

                </select>

              </div>

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Address:</label>

            <div class="col-sm-8">

              <input type="Adress" class="form-control" id="Address" name="Address" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >City:</label>

            <div class="col-sm-8">

              <div class="dropdown">

                <select class="form-control" name="stCity">

                <option>--Select--</option>

                  <option value="sudbury">Sudbury </option>

                   <option value="Hanmer">Hanmer</option>

                </select>

              </div>

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

              <input type="text" class="form-control" id="stPhone" name="stPhone">

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Date Of Birth:</label>

            <div class="col-sm-8">

            

                <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-13" name="dateOfBirth">

              </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Notes:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="Notes" name="Notes">

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

               <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-14" name="Obtained">

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Expire:</label>

            <div class="col-sm-8">

              <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-11" name="Expire">

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

   <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-15" name="ClassStart">

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Class End:</label>

            <div class="col-sm-8">

       <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-16" name="ClassEnd">

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

             <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-17" name="InCarStart">

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >HRS in Cars:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="cars" name="HRSInCars" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >HRS Remaining:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="hrsremain" name="HRSRemaining" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >In Car End:</label>

            <div class="col-sm-8">

          <input type = "text" class="form-control" placeholder="Select Date" id = "datepicker-18" name="InCarEnd">

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >In Car Marks:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="carmarks" name="InCarMarks" >

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-4" >Completed:</label>

            <div class="col-sm-8">

              <input type="text" class="form-control" id="complete" name="Completed">

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

           

            <div class="col-sm-8">

          <input type="submit" value="Submit">

            </div>

          </div>

        

      </div>

    </div>

  </div> </form>

  <div class="row">

    <div class="col-md-6" style="padding:0px !important; border-right:2px solid">

      <table class="table">

        <caption class="caption">

        <b>Classroom Instruction</b>

        </caption>

        <thead>

          <tr>

            <th>Firstname</th>

            <th>Lastname</th>

            <th>Firstname</th>

            <th>Lastname</th>

          </tr>

        </thead>

        <tbody>

          <tr>

            <td>John</td>

            <td>Doe</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

          <tr>

            <td>Mary</td>

            <td>Moe</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

          <tr>

            <td>July</td>

            <td>Dooley</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

        </tbody>

      </table>

    </div>

    <div class="col-md-6 lt" style="padding:0px !important">

      <table class="table">

        <caption class="caption">

        <b>In Car Instruction</b>

        </caption>

        <thead>

          <tr>

            <th>Firstname</th>

            <th>Lastname</th>

            <th>Firstname</th>

            <th>Lastname</th>

          </tr>

        </thead>

        <tbody>

          <tr>

            <td>John</td>

            <td>Doe</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

          <tr>

            <td>Mary</td>

            <td>Moe</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

          <tr>

            <td>July</td>

            <td>Dooley</td>

            <td>John</td>

            <td>Doe</td>

          </tr>

        </tbody>

      </table>

    </div>

  </div>



</div>

<!--<script>

            $(function () {

                $('#datetimepicker1').datetimepicker();

            });

        </script>-->

</body>

</html>

