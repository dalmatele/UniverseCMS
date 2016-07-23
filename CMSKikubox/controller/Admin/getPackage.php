<?php

include("../../models/Configs.php");
include("../../models/Admin/PackageDB.php");
include("../../models/Admin/CustomerDB.php");
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
        $package = $pdb->getPackage($packageCode);
        $pdb->dbClose();
        $cdb = new CustomerDB($db_host, $db_username, $db_password, $db_dbname);
        $customer = $cdb->getCustomer($package->CustomId);
        $cdb->dbClose();
        $ppdb = new ProductDB($db_host, $db_username, $db_password, $db_dbname);
        $products = $ppdb->getProducts($package->Id);
        $result = new GetPackageResponse();
        $result->package = $package;
        $result->customer = $customer;
        $result->products = $products;
        $response = array("result" => $result);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

class GetPackageResponse{
    public $package;
    public $customer;
    public $products;
}