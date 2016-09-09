<?php

/* 
 * Sync detail data, such as: instructor, course, advisor
 * Should only run one per day
 */

require_once "../Include/config.php";
require_once './SharepointRequest.php';
require_once '../models/AdvisorDB.php';
require_once '../models/InstructorDB.php';
require_once '../models/CourseDB.php';

$sharepointConnection = new SharepointRequest("minhnv@edumallinternational.onmicrosoft.com",
        "qsysopr@16",
        "https://edumallinternational.sharepoint.com/"
);

$sharepointConnection->setListItem("Advisors");
$items = $sharepointConnection->otherDataMining();
if(count($items) > 0){
    
    $adb = new AdvisorDB();
    $advisor1 = $adb->getAll();
    $advisor2 = array();
    foreach($items as $item){
        if(!in_array($item->Title, $advisor1)){
            array_push($advisor2, $item->Title);
        }
    }
    foreach ($advisor2 as $a){
        $adb->insert($a);
    }
    $adb->dbClose();
}
$sharepointConnection->setListItem("Instructors");
$instructors = $sharepointConnection->otherDataMining();
if(count($instructors) > 0){
    $idb = new InstructorDB();
    $instructor1 = $idb->getAllInstructorCode();
    $instructor2 = array();
    foreach ($instructors as $instructor){
        if(!in_array($instructor->InstructorCode, $instructor1)){
            array_push($instructor2, $instructor);
        }
    }
    foreach ($instructor2 as $i){
        $instructor = new Instructor();
        $instructor->setInstructor_code($i->InstructorCode);
        $instructor->setInstructor_latin_name($i->LatinName);
        $instructor->setInstructor_long_name($i->InstructorName);
        $instructor->setInstructor_short_name($i->Title);
        $idb->insert($instructor);
    }
    $idb->dbClose();
}
$sharepointConnection->setListItem("Courses");
$courses = $sharepointConnection->otherDataMining();
if(count($courses) > 0){
    $cdb = new CourseDB();
    $course1 = $cdb->getAllCourseCode();
    $course2 = array();
    foreach($courses as $course){
        if(!in_array($course->CourseCode, $course1)){
            array_push($course2, $course);
        }
    }
    foreach($course2 as $c){
        echo $c->CourseCode."<br/>";
        $course = new Course();
        $course->setCourse_latin_name($c->CourseEnglishName);
        $course->setCourse_name($c->Title);
        $course->setInstructor_category($c->Category);
        $course->setInstructor_code($c->InstructorCode);
        $course->setCourse_code($c->CourseCode);
        $cdb->insert($course);
    }
    $cdb->dbClose();
}


