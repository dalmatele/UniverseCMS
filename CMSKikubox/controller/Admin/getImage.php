<?php
include("../../models/Admin/FileManage.php");
/* 
 * Tải file
 */

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");


securePage($_SERVER['PHP_SELF']);

$method = $_SERVER["REQUEST_METHOD"];
//$id = $_GET['id'];
//$fm = new FileManage();
//$result = $fm->readFile($id);
//error_log("a", 0);
//echo $result;
switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $id = $oJson->id;
        $fm = new FileManage();
        $result = $fm->readFile($id);
//        $type = pathinfo($id, PATHINFO_EXTENSION);
//        header("Content-type: image/jpg");
//        error_log($type, 0);
        $response = array("result" => $result);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid";
        break;
}