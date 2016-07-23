<?php

include("../../models/Configs.php");
include("../../models/Admin/PackageDB.php");
include("../../models/Admin/ProductDB.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $packageCode = $oJson->code;
        $pdb = new PackageDB($db_host, $db_username, $db_password, $db_dbname);
        $id = $pdb->deletePackage($packageCode);
        $pdb->dbClose();
        if($id !== -1){
            $ppdb = new ProductDB($db_host, $db_username, $db_password, $db_dbname);
            //Phải xóa các tập tin ảnh đi kèm sản phẩm đi
            $number_effected_rows = $ppdb->delAllProducts($id);
            $result = $number_effected_rows;
            $output = array("result" => $result);
            echo json_encode($output);
        }else{
            $result = $id;
            $output = array("result" => $result);
            echo json_encode($output);
        }
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

