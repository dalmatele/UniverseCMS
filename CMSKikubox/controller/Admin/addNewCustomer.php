<?php
//http://www.w3schools.com/php/php_includes.asp
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
        $address = $oJson->address;
        $phonenumber = $oJson->phonenumber;
        $email = $oJson->email;
        $sex = intval($oJson->sex);
        $birthday = $oJson->birthday;
        $cdb = new CustomerDB($db_host, $db_username, $db_password, $db_dbname);
        $result = $cdb->addNewCustomer($name, $address, $phonenumber, $email, $sex, $birthday);
        echo $result;
        break;
    case "GET":
        echo "Invalid request!";
        break;
}

