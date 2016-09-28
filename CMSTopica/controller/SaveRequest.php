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
            $request_content = $oJson->content == "" ? NULL : $oJson->content;
            $rmdb = new \models\RequestManagementDB();
            $petitioner = get_userid();
            $id = $rmdb->insert($petitioner, $worker, $request_type, $request_content, $request_important);
            $rmdb->dbClose();
            $result = "";
            if($id == -1){
                $result = "Không thể gửi yêu cầu!";
            }else{
                $result = "Gửi yêu cầu thành công!";
            }
            $response = array("res" => $result);
            echo json_encode($response);
            break;
        case "GET":
            echo "Get method is invalid!";
            break;
    }
}

