<?php

require_once __DIR__.'/../Include/config.php';
require_once __DIR__ .'/../models/Database.php';
require_once __DIR__.'/../models/MemberDB.php';
require_once '../Include/functions.php';

$isLogin = true;
$valid_time = 8 * 60 * 60 * 1000;
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
            $email = htmlspecialchars($_GET["email"]);
            $username = htmlspecialchars($_GET["username"]);
            $timestamp = htmlspecialchars($_GET["timestamp"]);
            $preauthToken = htmlspecialchars($_GET["active_code"]);
            //check timestamp
            $now = time()*1000;
            if(($now - intval($timestamp)) > $valid_time){
                $result = "Invalid active code: Invalid time.";
            }else{
                //validate active code
//                $preauthToken = hash_hmac("sha1", $email."|".$username."|".$timestamp, PREAUTH_KEY);
                $mdb = new \models\MemberDB();
                $active_code = $mdb->getActiveCode($email);
                if(!strcasecmp($preauthToken, $active_code)){
                    $result = "Invalid active code: Invalide Code!";
                }else{
                    //clear active code
                    $mdb->updateActiveCode("", $email);
                    $mdb->dbClose();
                    header("Location: ../index.php");
                    break;
                }
            }
            $mdb->dbClose();
            $response = array("res" => $result);
            echo json_encode($response);
            break;
    }
}
