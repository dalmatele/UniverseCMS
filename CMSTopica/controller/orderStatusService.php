<?php
require_once "../Include/config.php";
require_once '../models/OrderStatus.php';
require_once '../models/OrderStatusDB.php';

$method = $_SERVER["REQUEST_METHOD"];
switch ($method){
    case "POST":
        //authenticate
        $headers = apache_request_headers();
        $username = "";
        $password = "";
        foreach ($headers as $header => $value) {
            if(strcmp($header, "username") == 0){
                $username = $value;
            }
            if(strcmp($header, "password") == 0){
                $password = $value;
            }
        }
//        if((strcmp($username, "kerry") !== 0) && strcmp($password, "123456a@") !== 0) {
//            $response = array("result" => "1", "desc" => "Authenticate failed!");
//            echo json_encode($response);
//        }else{
//        $username = $_SERVER["HTTP_username"];
//        $password = $_SERVER["HTTP_password"];
            $json = file_get_contents("php://input");
            $oJson = json_decode($json);
            $oReq = $oJson->req;
            $oData = $oReq->status;
            $orderStatus = new OrderStatus();
            $orderStatus->setCon_no($oData->con_no);
            $orderStatus->setStatus_code($oData->status_code);
            $orderStatus->setLocation($oData->location);
            $orderStatus->setRef_no($oData->ref_no);
            $orderStatus->setStatus_date($oData->status_date);
            $orderStatus->setStatus_desc($oData->status_desc);
            $orderStatus->setUpdate_date($oData->update_date);
            $orderStatus->setIs_newest(1);
            $dbCon = new OrderStatusDB();
            $result = $dbCon->updateNewestByCode($orderStatus->getCon_no());
            if($result != 0){
                error_log("ducla: can not make status of order ".$orderStatus->getCon_no()." to old status", 0);
            }
            $result =  $dbCon->insert($orderStatus);
            $dbCon->dbClose();
            if($result != 0){
                //error
                $response = array("result" => "2", "desc"=>"No order/Can not update order's status");
                echo json_encode($response);
            }else{
                $response = array("result" => "0", "desc"=>"Update successful!");
                echo json_encode($response);
            }
//        }
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

