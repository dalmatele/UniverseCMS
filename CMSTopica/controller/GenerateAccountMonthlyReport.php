<?php

require_once '../Include/config.php';
require_once '../libs/PHPExcel.php';
require_once '../models/OrderDB.php';
require_once '../models/AccountReportMonthlyDB.php';
require_once './Utilities.php';

$isNow = htmlspecialchars($_GET["now"]);
date_default_timezone_set("Asia/Ho_Chi_Minh");
if(empty($isNow)){
    $date = new DateTime();
    $t_status_date = $date->format('Y-m-d');
    $date->sub(new DateInterval("P1M"));
    $f_status_date = $date->format('Y-m-d');
    error_log($t_status_date);
    error_log($f_status_date);
    $odb = new OrderDB($db_host, $db_username, $db_password, $db_dbname);
    $account_reports = $odb->accountReport($f_status_date, $t_status_date);
    $odb->dbClose();
    error_log("This report has: ".count($account_reports)." records");
    if(count($account_reports) > 0){
        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $output = array();
        array_push($output, ["Completed", "BuyerName",
            "Latin Name", "Phone Number", "User", "Course", "Course Code",
            "Course English name", "Category", "Payment", "Tracking Code", "Revenue (BAHT)", "Advisor",
            "Note"]);
        foreach($account_reports as $account_report){
            array_push($output, [$account_report["status_date"], $account_report["r_name"],
                "", $account_report["r_phonenumber"], $account_report["r_email"], $account_report["course_name"], $account_report["course_code"],
                $account_report["course_latin_name"], $account_report["instructor_category"], "COD", $account_report["co_number"], $account_report["cost"], $account_report["order_advisor_code"]]);
        }

        $objWorksheet->fromArray($output);
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setPreCalculateFormulas(false);
        $fileProperties = Utilities::generateMonthlyFileName();
        
        $objWriter->save($fileProperties[0]);
        $time_now = null;
        $amrdb = new \models\AccountReportMonthlyDB($db_host, $db_username, $db_password, $db_dbname);
        $amrdb->insert(date("Y"), date("m"), $fileProperties[1], $time_now);
        $amrdb->dbClose();
        //we save it to database;
        error_log("monthly report:".$fileProperties[0]);
    }
}else{
    //get from now to the begin of month
    $f_status_date = htmlspecialchars($_GET["fdate"]);
    $t_status_date = htmlspecialchars($_GET["tdate"]);
    error_log("create instance report");
    error_log($t_status_date);
    error_log($f_status_date);
    $odb = new OrderDB();
    $account_reports = $odb->accountReport($f_status_date, $t_status_date);
    $odb->dbClose();
    error_log("This report has: ".count($account_reports)." records");
    if(count($account_reports) > 0){
        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $output = array();
        array_push($output, ["Completed", "BuyerName",
            "Latin Name", "Phone Number", "User", "Course", "Course Code",
            "Course English name", "Category", "Payment", "Tracking Code", "Revenue (BAHT)", "Advisor",
            "Note"]);
        foreach($account_reports as $account_report){
            array_push($output, [$account_report["status_date"], $account_report["r_name"],
                "", $account_report["r_phonenumber"], $account_report["r_email"], $account_report["course_name"], $account_report["course_code"],
                $account_report["course_latin_name"], $account_report["instructor_category"], "COD", $account_report["co_number"], $account_report["cost"], $account_report["order_advisor_code"]]);
        }

        $objWorksheet->fromArray($output);
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setPreCalculateFormulas(false);
        $fileProperties = Utilities::generateMonthlyFileName();
        $objWriter->save($fileProperties[0]);
        $amrdb = new \models\AccountReportMonthlyDB();
        $amrdb->insert(date("Y"), date("m"), $fileProperties[1], $t_status_date);
        $amrdb->dbClose();
        //we save it to database;
        error_log("instance monthly report:".$fileProperties[0]);
    }
}


