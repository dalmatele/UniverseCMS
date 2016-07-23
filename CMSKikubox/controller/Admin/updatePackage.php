<?php
/**
 * Cập nhật thông tin cho Package, bao gồm:
 * - PackageDB
 * - ProductDB
 */
include("../../models/Configs.php");
include("../../models/Admin/PackageDB.php");
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
        
        $package = $oJson->package;
        $products =  $oJson->product;
        
        $pdb = new PackageDB($db_host, $db_username, $db_password, $db_dbname);
        $id = intval($package->id);
        $code = $package->code;
        $customId = intval($package->customId);
        $status = 0;
        $importDate = $package->importDate;
        $exportDate = "";
        $packageDesc = $package->desc;
        $result = $pdb->updatePackage($code, $customId, $status, $importDate, $exportDate, $packageDesc, $id);
        if($result == 1){
            $response = array("result" => $result);
            break;
        }
        $pdb->dbClose();
        //Cập nhật thông tin gói hàng bằng cách xóa hết đi và chèn lại các sản phẩm
        $ppdb = new ProductDB($db_host, $db_username, $db_password, $db_dbname);
        $ppdb->delAllProducts($id);
        $ids = array();
        foreach($products as $product){
            $packageId = $id;
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
            $productId = $ppdb->addNewProduct($packageId, $importValue, $exportValue, $desc, $isAccept, $exportDate, $imagePath, $productName);
            $ids[] = $productId;
        }
        $ppdb->dbClose();
        if(count($ids) > 0){
            $result = 0;
        }
        $response = array("result" => $result);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

