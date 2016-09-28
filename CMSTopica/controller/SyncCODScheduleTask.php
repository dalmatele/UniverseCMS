<?php

require_once __DIR__ ."/../Include/config.php";
require_once __DIR__ ."/../models/Order.php";
require_once __DIR__ ."/../models/OrderDB.php";
require_once __DIR__ .'/../models/ConfigDB.php';
require_once __DIR__ .'/./SharepointRequest.php';
require_once __DIR__ .'/./Utilities.php';
require_once __DIR__ .'/../Include/functions.php';

use models\Order;

date_default_timezone_set("Asia/Ho_Chi_Minh");
$config = new ConfigDB();
$value = $config->getConfigValueByName("tracking_code");
syncLogger("Begin sync COD data.", "info");
//$value2 = $config->getConfigValueByName("tracking_pod");
if(strcmp($value, "-1") != 0){
    $sharepointConnection = new SharepointRequest("minhnv@edumallinternational.onmicrosoft.com",
        "qsysopr12!@",
        "https://edumallinternational.sharepoint.com/"
        );
    $sharepointConnection->setListItem("CODs");
    $sharepointConnection->setBeginId($value);
    $items = $sharepointConnection->dataMining();
    $odb = new OrderDB();
    $count = 0;
    $date = new DateTime();
    foreach($items as $item){
        $order = new Order();
        
        $order->setCo_number($item->TrackingCodeTitle);
        $order->setR_name($item->BuyerName);
        $order->setR_email($item->BuyerTitle);
        $order->setR_address($item->BuyerAddress);
        $order->setCost($item->Amount);
        $order->setPostal_code($item->BuyerPostalCode);
        $order->setR_phonenumber($item->BuyerMobile);
        $order->setCod_provider(1);
        $order->setCreatedDate($date->format("Y-m-d H:i:s"));
        $order->setService_code($item->ServiceCode->ServiceCode);
        $order->setOrder_advisor_code($item->Advisor->Title);
        $order->setOrder_course_code($item->CourseCode->CourseCode);
        $odb->insert($order);
        $count++;
    }
    $odb->dbClose();
    $value = intval($value);
    syncLogger("Sync COD data. We have ".$value." items.", "info");
    $value = $value + $count;
//    $value2 = intval($value2);
//    $value2 = $value2 + $count2;
    $value = strval($value);
    $config->updateConfigValueByName("tracking_code", $value);
//    $config->updateConfigValueByName("tracking_pod", $value2);
    $config->dbClose();
}




