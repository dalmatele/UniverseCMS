<?php


/**
 * Description of Course
 *
 * @author duc
 */
class Course {
    public $id;
    public $course_name;
    public $instructor_code;
    public $instructor_category;
    public $course_latin_name;
    public $course_code;
    
    public function getCourse_code() {
        return $this->course_code;
    }

    public function setCourse_code($course_code) {
        $this->course_code = $course_code;
    }

        
    public function getId() {
        return $this->id;
    }

    public function getCourse_name() {
        return $this->course_name;
    }

    public function getInstructor_code() {
        return $this->instructor_code;
    }

    public function getInstructor_category() {
        return $this->instructor_category;
    }

    public function getCourse_latin_name() {
        return $this->course_latin_name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCourse_name($course_name) {
        $this->course_name = $course_name;
    }

    public function setInstructor_code($instructor_code) {
        $this->instructor_code = $instructor_code;
    }

    public function setInstructor_category($instructor_category) {
        $this->instructor_category = $instructor_category;
    }

    public function setCourse_latin_name($course_latin_name) {
        $this->course_latin_name = $course_latin_name;
    }


}
