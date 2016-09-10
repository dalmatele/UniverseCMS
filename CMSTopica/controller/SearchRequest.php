<?php

require_once '../Include/config.php';
require_once '../Include/functions.php';
require_once '../models/RequestManagementDB.php';

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
            $worker = $oJson->worker == "" ? NULL : $oJson->worker;
            $request_type = $oJson->request_type == "" ? NULL : $oJson->request_type;
            $request_important = $oJson->request_important == "" ? NULL : $oJson->request_important;
            $request_status = $oJson->request_status == "" ? NULL : $oJson->request_status;
            $f_request_at = empty($oJson->f_request_at) ? NULL : $oJson->f_request_at;
            $t_request_at = empty($oJson->t_request_at) ? NULL : $oJson->t_request_at;
            $pageSize = intval($oJson->pageSize);
            $pageIndex = intval($oJson->pageIndex);
            $rmdb = new \models\RequestManagementDB();
            $requests = $rmdb->search($worker, $request_type, $request_important, $request_status, $f_request_at,
                    $t_request_at, $pageIndex, $pageSize);
            $rmdb->dbClose();
            $response = array("res" => $requests);
            echo json_encode($response);
            break;
        case "GET":
            echo "Get method is invalid!";
            break;
    }
}

