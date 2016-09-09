<?php

require_once '../Include/config.php';
require_once '../models/SendEmailScheduleDB.php';

use models\SendEmailScheduleDB;

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $send_from = $oJson->send_from == "" ? NULL : $oJson->send_from;
        $pageSize = intval($oJson->pageSize);
        $pageIndex = intval($oJson->pageIndex);
        $sesdb = new SendEmailScheduleDB();
        $emails = $sesdb->search($send_from, $pageIndex, $pageSize);
        $sesdb->dbClose();
        $response = array("res" => $emails);
        echo json_encode($response);
        break;
    case "GET":
        echo "Get method is invalid!";
        break;
}

