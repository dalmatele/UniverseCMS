<?php

include("../../models/Configs.php");
include("../../models/Admin/ProductDB.php");
include("../../models/Admin/FileManage.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        
        $db = new ProductDB($db_host, $db_username, $db_password, $db_dbname);
        $ids = array();
        foreach($oJson->data as $product){
            $packageId = $product->packageId;
            $importValue = $product->importValue;
            $exportValue = $product->exportValue;
            $exportDate = $product->exportDate;
            $isAccept = $product->isAccept;
            $desc = $product->productDesc;
            $imagePath = $product->imagePath;
            $srcName = $product->imageName;
            if(!$srcName == ""){
                $timeStamp = time();
                $type = pathinfo($srcName, PATHINFO_EXTENSION);
                $desName = $packageId."-".$timeStamp.".".$type;
                $fm = new FileManage();
                $result = $fm->moveFile($srcName, $desName);
                if(!$result){
                    $error = "Can not save product's Image";
                    $output = array("result" => $error);
                    break;
                }else{
                    $imagePath = $desName;
                }
            }
            $productName = $product->productName;
            $productId = $db->addNewProduct($packageId, $importValue, $exportValue, $desc, $isAccept, $exportDate, $imagePath, $productName);
            $ids[] = $productId;
        }
        $db->dbClose();
        $output = array("result" => $ids);
        echo json_encode($output);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

