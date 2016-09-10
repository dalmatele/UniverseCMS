<?php

/* 
 * Xem chi tiet thong tin don hang
 */

require_once "../Include/config.php";
require_once '../models/OrderStatusDB.php';
require_once '../Include/functions.php';

$method = $_SERVER["REQUEST_METHOD"];
sec_session_start();
$db = new Database();
$conn = $db->getConnection();
$isLogin = login_check($conn);
$db->dbClose();
if(!$isLogin){
    $response = array("res" => "Not autherized action!");
    echo json_encode($response);
}else{
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
}



