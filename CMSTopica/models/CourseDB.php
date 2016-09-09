<?php

require_once '../models/Database.php';
require_once '../models/Course.php';

/**
 * Description of CourseDB
 *
 * @author duc
 */
class CourseDB extends Database{
    
    public function insert($course){
        $query = $this->connection->prepare("INSERT INTO `course` (id, course_name, course_code, instructor_code, instructor_category, course_latin_name) "
                . "VALUES(NULL,?,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $course_name = $course->getCourse_name();
        $course_code = $course->getCourse_code();
        $instructor_code = $course->getInstructor_code();
        $instructor_category = $course->getInstructor_category();
        $course_latin_name = $course->getCourse_latin_name();
        $query->bind_param("sssss", $course_name, $course_code, $instructor_code, $instructor_category, $course_latin_name);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getAllCourseCode(){
        $query = $this->connection->prepare("SELECT c.course_code"
                . " FROM `course` as c");
        if(!$query){
            error_log("Error prepare query. ".mysqli_error($this->connection));
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->execute();
        $query->bind_result($course_code);
        $course = array();
        while($query->fetch()){
            array_push($course, $course_code);
        }
        return $course;
    }
}
