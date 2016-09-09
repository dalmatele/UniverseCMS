<?php

/* 
 * Xem chi tiet thong tin don hang
 */

require_once "../Include/config.php";
require_once '../models/OrderStatusDB.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $code = $oJson->code;
        $orderStatusDB = new OrderStatusDB();
        $ordersStatus = $orderStatusDB->getOrderStatus($code);
        $orderStatusDB->dbClose();
        $response = array("res" => $ordersStatus);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

