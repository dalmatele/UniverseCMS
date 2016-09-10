<?php

require_once '../Include/config.php';
require_once '../models/OrderDB.php';
require_once './SharepointRequest.php';
require_once '../Include/functions.php';

sec_session_start();
$db = new Database();
$conn = $db->getConnection();
$isLogin = login_check($conn);
$db->dbClose();
if(!$isLogin){
    $response = array("res" => "Not autherized action!");
    echo json_encode($response);
}else{
    $method = $_SERVER["REQUEST_METHOD"];
    switch ($method){
        case "POST":
            $json = file_get_contents("php://input");
            $oJson = json_decode($json);
            $code = $oJson->code == "" ? NULL : $oJson->code;
            $name = $oJson->name == "" ? NULL : $oJson->name;
            $status = $oJson->status == "9999" ? NULL : $oJson->status;
    //        $importDateF = empty($oJson->fDate) ? NULL : $oJson->fDate;
    //        $importDateT = empty($oJson->tDate) ? NULL : $oJson->tDate;
            $pageSize = intval($oJson->pageSize);
            $pageIndex = intval($oJson->pageIndex);
            $odb = new OrderDB();
            $orders = $odb->search($code, $name, $status, $pageSize, $pageIndex);
            $odb->dbClose();
            $response = array("res" => $orders);
            echo json_encode($response);
            break;
        case "GET":
            echo "Get method is invalid!";
            break;
    }
}


