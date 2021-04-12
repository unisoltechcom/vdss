<style>
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu:hover .dropdown-menu {
    display: block;
    left: 3px;
    margin-top: 0;
    top: 49px;
}
</style>
<div class="navbar navbar-default">
	<ul class="nav navbar-nav">
        <li class="dropdown-submenu"><a href="index.php" title="student list">Home</a></li>        
    	<li class="dropdown-submenu"><a href="studentlist.php" title="student list">Student List</a>
        	<ul class="dropdown-menu">
              <li><a href="add_student.php" title="student">Add Student </a> </li>
			  <li><a href="studentlist.php" title="student">Edit/Delete Student </a> </li>
              <li><a href="trash_student.php" title="student">Trash Student</a> </li>
            </ul>
        </li>
        <li class="dropdown-submenu"><a href="instructorlist.php" title="student list">Instructor List</a>
        	<ul class="dropdown-menu">
              <li><a href="add_instructor.php" title="student">Add Instructor </a> </li>
			  <li><a href="instructorlist.php" title="student">Edit/Delete Instructor </a> </li>
              <li><a href="trash_instructorlist.php" title="student">Trash Instructor </a> </li>
              <li><a href="add_inslectrlog.php" title="student">Add Instructor Log against Class</a> </li>
              <li><a href="instructorloglist.php" title="student">Edit/Delete Instructor Log</a> </li>
              <!--<li><a href="trash_inslectrlog.php" title="student">Trash Instructor Log</a> </li>-->
            </ul>
        </li>
        
        
        <li  class="dropdown-submenu"><a href="inclass.php" title="student list">Log Student's Class Hours</a>
        <ul class="dropdown-menu">
              <li><a href="inclass.php" title="student">Add Log Class Hours </a> </li>
			  <li><a href="classloglist.php" title="student">Edit/Delete Log Class Hours </a> </li> 
              <li><a href="trash_classloglist.php" title="student">Trash Log Class Hours </a> </li>              
            </ul>
        </li>
        <li  class="dropdown-submenu"><a href="incar.php" title="student list">Log Car Hours</a>
        <ul class="dropdown-menu">
              <li><a href="incar.php" title="Add Log Car Hours">Add Log Car Hours </a> </li>
			  <li><a href="carloglist.php" title="Edit/Delete Log Car Hours">Edit/Delete Log Car Hours </a> </li>  
              <li><a href="trash_carloglist.php" title="Trash Log Car Hours">Trash Log Car Hours </a> </li>              
            </ul>
        </li>
        <li class="dropdown-submenu"><a href="" title="student list">User Management</a>
        <ul class="dropdown-menu">
              <li><a href="signup.php" title="student">Add User </a> </li>
			  <li><a href="userlist.php" title="student">Edit/Delete User </a> </li> <!--
              <li><a href="trash_classloglist.php" title="student">Trash Log Class Hours </a> </li>  -->            
            </ul>
        </li>
        <li  class="dropdown-submenu"><a>Reports </a>
        <ul class="dropdown-menu">
              <li><a href="instructor_report.php" title="Reports Instructor">Instructor Report</a></li>
              <li><a href="studentclass_report.php" title="Students in class">Students in Class Report</a></li> 
              <li><a href="studentdriving_report.php" title="Students driving">Students Driving Report</a></li>
              <li><a href="studentinfo_report.php" title="Students driving">Student Hours Report </a></li>            
            </ul>
        </li>
        <li class="dropdown-submenu"><a href="general.php" title="Global Info">Global Info</a></li>
        
        <li><a href="login/logout.php" title="student list">Logout</a></li>        
    </ul>
</div>
<div class=" clearfix"></div>
<div class="banner"><img src="images/banner.jpg" alt="banner"  /></div>
<div class=" clearfix"></div><br />
<script>
  jQuery(document).ready(function() {
        var array = this.location.pathname.split('/');
        jQuery('a[href="' + array[2]+ '"]').parents('li').addClass('active');
    });



var active = 0;
for (var i = 0; i < document.links.length; i++) {
if (document.links[i].href === document.URL) {
active = i;
}
}document.links[active].className = 'active';</script>