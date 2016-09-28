<?php
require_once __DIR__.'/../Include/config.php';
require_once __DIR__ .'/../models/Database.php';
require_once __DIR__ .'/../models/RequestTypeDB.php';
require_once __DIR__ .'/../Include/functions.php';

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
            $rtdb = new \models\RequestTypeDB();
            $types = $rtdb->getAllType();
            $rtdb->dbClose();
            $response = array("res" => $types);
            echo json_encode($response);
            break;
        case "GET":
            echo "Get method is invalid!";
            break;
    }
}

