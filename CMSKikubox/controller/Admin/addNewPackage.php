<?php

//http://www.w3schools.com/php/php_includes.asp
include("../../models/Configs.php");
include("../../models/Admin/PackageDB.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $code = $oJson->code;
        $desc = $oJson->desc;
        $customId = $oJson->customId;
        $importDate = $oJson->importDate;
        $pdb = new PackageDB($db_host, $db_username, $db_password, $db_dbname);
        if($pdb->codeIsExist($code)){
            $response = array("result" => -1);
            echo json_encode($response);
        }else{
            $result = $pdb->addNewPackage($code, $importDate, $customId, $desc);
            $output = array("result" => $result);
            echo json_encode($output);
        }
        $pdb->dbClose();
        
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

