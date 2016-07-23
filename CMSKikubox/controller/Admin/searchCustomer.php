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
        $name = $oJson->name;
        $phone = $oJson->phone;
        $email = $oJson->email;
        $pageSize = intval($oJson->pageSize);
        $pageIndex = intval($oJson->pageIndex);
        $cdb = new CustomerDB($db_host, $db_username, $db_password, $db_dbname);
        $customers = $cdb->searchCustomer($name, $phone, $email, $pageSize, $pageIndex);
        $cdb->dbClose();
        $response = array("result" => $customers);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

