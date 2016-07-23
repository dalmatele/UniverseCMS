<?php

include("../../models/Configs.php");
include("../../models/Admin/CustomerDB.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];
switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $id = intval($oJson->id);
        $cdb = new CustomerDB($db_host, $db_username, $db_password, $db_dbname);
        $customers = $cdb->getCustomer($id);
        $cdb->dbClose();
        $response = array("result" => $customers);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

