<?php

require_once '../models/config.php';
require_once '../models/OrderDB.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case "POST":
        echo "Get method is invalid!";
        break;
    case "GET":
        $odb = new OrderDB($db_host, $db_username, $db_password, $db_dbname);
        $orders = $odb->search(NULL, NULL, "000", 50, 1);
        $odb->dbClose();
        break;
}

