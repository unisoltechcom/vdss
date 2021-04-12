<?php
class StudentForm extends Database
{
	private $dc;
	function __CONSTRUCT() { $this->dc  = new Database(); }
	
	function run_query($query) {
		return $result = $this->dc->fetch_array_all($query);
	}
	function update_query($table, $data, $where) {
		return $this->dc->query_update($table, $data, $where);
	}
	function insert_query($table, $data) {
		return $this->dc->query_insert($table, $data);
	}
	function delete_query($table , $where)
	{
		return $this->dc->query_delete($table, $where);
	}
	function delete_query_permanent($where, $table,$tables=array())
	{
		
		$this->dc->query_delete_permanent($table,$tables, $where);
	}
	function restore_query($table , $where)
	{
		//$where = array( "StudentID" => $StudentID);
		$this->dc->query_restore($table, $where);
	}
	
	
	
	
	function update_student($StudentID, $data ){		
	    $where = "StudentID = '$StudentID'"; 
		return $this->dc->query_update("student_information", $data, $where);
	}
    public function createStudent($studentdata)
    {
	  $this->dc->query_insert("student_information", $studentdata); 
	  return true;
    }
	public function addCarLog($cardata)
    {
	  $this->dc->query_insert("car_log", $cardata); 
	  return true;
    }
	public function addClassLog($classdata)
    {
	  $this->dc->query_insert("class_log", $classdata); 
	  return true;
    }
	
	///SELECT st.`StudentID`, `firstName`, `lastName`, `stGender`, `stCity`, `Licence`,  `logClassID`, `InClassDate`, `Location`, `Hours`, `Instructor` FROM `student_information` as st left join class_log as cl on `st`.`StudentID`= `cl`.`StudentID` where `cl`.`logClassID` =2
	function list_classLog()
	{
		$query = "SELECT st.`StudentID`, `firstName`, `lastName`, `stGender`, `stCity`, `Licence`,  `logClassID`, `InClassDate`, cl.`Location`, `Hours`, `Instructor` FROM class_log as cl left join `student_information` as st on `st`.`StudentID`= `cl`.`StudentID`  ORDER BY `st`.`lastName`  asc";		
		return $result = $this->dc->fetch_array_all($query);
	}
	function edit_class_log_byid($ID){
		$query = "SELECT * FROM `class_log` where logClassID=$ID";
		$result = $this->dc->execute_query($query);
		return $row = @mysqli_fetch_array($result);
	}
	function edit_car_log_byid($ID){
		$query = "SELECT * FROM `car_log` where logCarID=$ID";
		$result = $this->dc->execute_query($query);
		return $row = @mysqli_fetch_array($result);
	}
	
	
	function list_students()
	{
		$query = "SELECT * FROM `student_information` where `isActive`=1 ORDER BY `student_information`.`lastName`  asc";
		return $result = $this->dc->fetch_array_all($query);
	}
	function list_students_inclass()
	{
		$query = "SELECT st.StudentID,st.firstName,st.lastName, SUM(cl.Hours) AS TodaysOrders FROM `student_information` as st left join `class_log` as cl on st.StudentID=cl.StudentID  GROUP BY st.StudentID having ifNull(sum(cl.Hours),0)<20   order by st.lastName asc ";
		return $result = $this->dc->fetch_array_all($query);
	}
	function list_students_incar()
	{
		$query = "SELECT st.StudentID,st.firstName,st.lastName, SUM(cl.Hours) AS TodaysOrders FROM `student_information` as st left join `car_log` as cl on st.StudentID=cl.StudentID  GROUP BY st.StudentID having ifNull(sum(cl.Hours),0)<10  order by st.lastName asc ";
		return $result = $this->dc->fetch_array_all($query);
	}
	function trash_students()
	{
		$query = "SELECT * FROM `student_information` where `isActive`=0 ORDER BY `student_information`.`lastName`  asc";
		return $result = $this->dc->fetch_array_all($query);
	}
	function select_student_byid($ID) {
		$query = "SELECT * FROM `student_information` WHERE StudentID = $ID ";
	    $result = $this->dc->execute_query($query);
		return $row = @mysqli_fetch_array($result);		
     }
	 function class_log_byid($ID){
		$query = "SELECT * FROM `class_log` where StudentID=$ID";
		return $result = $this->dc->fetch_array_all($query);
	}
	function car_log_byid($ID){
		 $query = "SELECT * FROM `car_log` where StudentID= $ID";
		return $result = $this->dc->fetch_array_all($query);
	}
	 function delete_student($StudentID)
	{
		$where = array( "StudentID" => $StudentID);
		$this->dc->query_delete("student_information", $where);
	}
	function delete_student_permanent($StudentID)
	{
		$tables=array("car_log", "class_log");
		$where = array( "StudentID" => $StudentID);
		$this->dc->query_delete_permanent("student_information",$tables, $where);
	}
	function restore_student($StudentID)
	{
		$where = array( "StudentID" => $StudentID);
		$this->dc->query_restore("student_information", $where);
	}
	 
	 
	function add_instructor ($insdata) {
		$this->dc->query_insert("Instructor_information", $insdata); 
	  return true;
	}
	function update_instructor($Ins_id, $data ){		
	    $where = "Ins_id = '$Ins_id'"; 
		return $this->dc->query_update("Instructor_information", $data, $where);
	}
	function list_instructor()
	{
		$query = "SELECT * FROM `Instructor_information` where `isActive`=1 ORDER BY `Instructor_information`.`Ins_Last_Name` asc";
		return $result = $this->dc->fetch_array_all($query);
	}
	function trash_instructor()
	{
		$query = "SELECT * FROM `Instructor_information` where `isActive`=0 ORDER BY `Instructor_information`.`Ins_Last_Name` asc";
		return $result = $this->dc->fetch_array_all($query);
	}	
	function select_instructor_byid($ID) {
		$query = "SELECT * FROM `Instructor_information` WHERE Ins_id = $ID ";
	    $result = $this->dc->execute_query($query);
		return $row = @mysqli_fetch_array($result);		
     }
	 function delete_instructor($Ins_id)
	{
		$where = array( "Ins_id" => $Ins_id);
		$this->dc->query_delete("Instructor_information", $where);
	}
	function delete_instructor_permanent($Ins_id)
	{
		$tables=array("car_log", "class_log");
		$where = array( "Ins_id" => $Ins_id);
		$this->dc->query_delete_permanent("Instructor_information",$tables, $where);
	}
	function restore_instructor($Ins_id)
	{
		$where = array( "Ins_id" => $Ins_id);
		$this->dc->query_restore("Instructor_information", $where);
	}
	
	
}
