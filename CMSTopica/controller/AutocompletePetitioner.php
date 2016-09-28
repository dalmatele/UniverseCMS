<?php

require_once __DIR__.'/../Include/config.php';
require_once __DIR__ .'/../models/Database.php';
require_once __DIR__ .'/../models/MemberDB.php';
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
            echo "Post method is invalide";
            break;
        case "GET":
            $term = htmlspecialchars($_GET["term"]);
            error_log($term);
            $mdb = new \models\MemberDB();
            $members = $mdb->listMember($term);
            $mdb->dbClose();
            echo json_encode($members);
            break;
    }
}

