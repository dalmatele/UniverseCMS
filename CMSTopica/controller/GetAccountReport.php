<?php

require_once "../Include/config.php";
require_once '../models/OrderDB.php';
require_once '../libs/PHPExcel.php';
require_once './Utilities.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $f_status_date = empty($oJson->f_status_date) ? NULL : $oJson->f_status_date;
        $t_status_date = empty($oJson->t_status_date) ? NULL : $oJson->t_status_date;
        $odb = new OrderDB();
        $date = new DateTime();
        
        $account_reports = $odb->accountReport($f_status_date, $t_status_date);
        
        $odb->dbClose();
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
            $fileProperties = Utilities::generateFileName();
            $objWriter->save($fileProperties[0]);
            
            error_log("ducla account report file: ".$fileProperties[1]);
            echo $fileProperties[1];
        }else{
            echo "none";
        }
        break;
    case "GET":
        $fileName = htmlspecialchars($_GET["filename"]);
        $destinationPath = $file_upload_path.$fileName.".xlsx";
        header("Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=account_report.xlsx");
        echo file_get_contents($destinationPath);
        break;
}


