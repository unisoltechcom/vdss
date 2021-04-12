<?php
class StudentForm extends DbConn
{
    public function createStudent($studentdata)
    {
        try {

            $db = new DbConn;
            $tbl_members = $db->tbl_student_information;
			
			$str = implode (", ", array_keys($studentdata));
			$str1 = implode (", :", array_keys($studentdata));
			$str1 = ':'.$str1;
			
			/*print_r("INSERT INTO ".$tbl_members." (".$str.")
            VALUES (".$str1.")");exit;*/
            // prepare sql and bind parameters
            $stmt = $db->conn->prepare("INSERT INTO ".$tbl_members." (".$str.")
            VALUES (".$str1.")");
            
            $stmt->bindParam(':username', $usr);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $pw);
            $stmt->execute();

            $err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }
        //Determines returned value ('true' or error code)
        if ($err == '') {

            $success = 'true';

        } else {

            $success = $err;

        };

        return $success;

    }
}
