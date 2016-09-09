<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Instructor
 *
 * @author duc
 */
class Instructor {
    public $id;
    public $instructor_short_name;
    public $instructor_long_name;
    public $instructor_code;
    public $instructor_latin_name;
    
    public function getId() {
        return $this->id;
    }

    public function getInstructor_short_name() {
        return $this->instructor_short_name;
    }

    public function getInstructor_long_name() {
        return $this->instructor_long_name;
    }

    public function getInstructor_code() {
        return $this->instructor_code;
    }

    public function getInstructor_latin_name() {
        return $this->instructor_latin_name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setInstructor_short_name($instructor_short_name) {
        $this->instructor_short_name = $instructor_short_name;
    }

    public function setInstructor_long_name($instructor_long_name) {
        $this->instructor_long_name = $instructor_long_name;
    }

    public function setInstructor_code($instructor_code) {
        $this->instructor_code = $instructor_code;
    }

    public function setInstructor_latin_name($instructor_latin_name) {
        $this->instructor_latin_name = $instructor_latin_name;
    }


}
