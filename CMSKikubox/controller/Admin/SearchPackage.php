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
        $code = $oJson->code == "" ? NULL : $oJson->code;
        $status = $oJson->status == "9999" ? NULL : intval($oJson->status);
        $importDateF = empty($oJson->fDate) ? NULL : $oJson->fDate;
        $importDateT = empty($oJson->tDate) ? NULL : $oJson->tDate;
        $isFirst = $oJson->isFirst;
        $pageSize = intval($oJson->pageSize);
        $pageIndex = intval($oJson->pageIndex);
        $pdb = new PackageDB($db_host, $db_username, $db_password, $db_dbname);
        if(!$isFirst){
            $packages = $pdb->search($code, $status, $importDateF, $importDateT, $pageSize, $pageIndex);
        }else{
            $packages = $pdb->searchF($code, $status, $importDateF, $importDateT, $pageSize, $pageIndex);
        }
        $pdb->dbClose();
        $response = array("result" => $packages);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}
