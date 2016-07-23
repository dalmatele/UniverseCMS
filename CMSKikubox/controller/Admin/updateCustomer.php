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
        $id = $oJson->id;
        $name = $oJson->name;
        $address = $oJson->address;
        $phonenumber = $oJson->phonenumber;
        $email = $oJson->email;
        $sex = intval($oJson->sex);
        $birthday = $oJson->birthday;
        $cdb = new CustomerDB($db_host, $db_username, $db_password, $db_dbname);
        $result = $cdb->update($name, $address, $phonenumber, $email, $sex, $birthday, $id);
        $cdb->dbClose();
        $output = array("result" => $result);
        echo json_encode($output);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}
