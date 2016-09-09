<?php

require_once '../models/Database.php';
require_once '../models/Instructor.php';

/**
 * Description of InstructorDB
 *
 * @author duc
 */
class InstructorDB extends Database{
    
    
    public function insert($instructor){
        $query = $this->connection->prepare("INSERT INTO `instructor` (id, instructor_short_name, instructor_long_name, instructor_code, instructor_latin_name) "
                . "VALUES(NULL,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $instructor_short_name = $instructor->getInstructor_short_name();
        $instructor_long_name = $instructor->getInstructor_long_name();
        $instructor_code = $instructor->getInstructor_code();
        $instructor_latin_name = $instructor->getInstructor_latin_name();
        $query->bind_param("ssss", $instructor_short_name, $instructor_long_name, $instructor_code, $instructor_latin_name);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getAllInstructorCode(){
        $query = $this->connection->prepare("SELECT i.instructor_code"
                . " FROM `instructor` as i");
        if(!$query){
            error_log("Error prepare query. ".mysqli_error($this->connection));
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->execute();
        $query->bind_result($instructor_code);
        $instructors = array();
        while($query->fetch()){
            array_push($instructors, $instructor_code);
        }
        return $instructors;
        
    }
}
