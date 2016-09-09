<?php

/* 
 * Phục vụ tính năng báo cáo thống kê trên biểu đồ
 */

require_once "../Include/config.php";
require_once '../models/OrderStatus.php';
require_once '../models/OrderStatusDB.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $f_status_date = empty($oJson->f_status_date) ? NULL : $oJson->f_status_date;
        $t_status_date = empty($oJson->t_status_date) ? NULL : $oJson->t_status_date;
        $osdb = new OrderStatusDB();
        $orders = $osdb->searchForReport($f_status_date, $t_status_date);
        $osdb->dbClose();
        $response = array("res" => $orders);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

