<?php

/* 
 * Lấy dữ liệu phục vụ việc vẽ đồ thị dạng tròn.
 * Đồ thị này biểu thị độ lệch của tổng các giá trị.
 */
include("../../models/Configs.php");
include("../../models/Admin/ChartDB.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];
switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $fDate = empty($oJson->fDate) ? NULL : $oJson->fDate;
        $tDate = empty($oJson->fDate) ? NULL : $oJson->tDate;
        $cdb = new ChartDB($db_host, $db_username, $db_password, $db_dbname);
        $result = $cdb->getPieChartData($fDate, $tDate);
        $cdb->dbClose();
        $response = array("result" => $result);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

