<?php

require_once "../Include/config.php";
require_once '../models/AccountReportMonthlyDB.php';


$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $year = empty($oJson->year) ? NULL : $oJson->year;
        $month = empty($oJson->month) ? NULL : $oJson->month;
        $arpmdb = new \models\AccountReportMonthlyDB();
        if(empty($month)){
            $reports = $arpmdb->searchMonthlyReport($year);
        }else{
            $reports = $arpmdb->searchChildMonthlyReport($year, $month);
        }
        $arpmdb->dbClose();
        $response = array("res" => $reports);
        echo json_encode($response);
        break;
    case "GET":
        //for download request
        $fileName = htmlspecialchars($_GET["filename"]);
        
        $destinationPath = $file_upload_path."monthly_report/".$fileName.".xlsx";
        header("Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=account_report.xlsx");
        echo file_get_contents($destinationPath);
        break;
}

