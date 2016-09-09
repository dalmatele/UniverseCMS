<?php

require_once "../Include/config.php";
require_once '../models/Order.php';
require_once '../models/OrderDB.php';
require_once '../models/ConfigDB.php';
require_once './SharepointRequest.php';
require_once './Utilities.php';

use models\Order;

$config = new ConfigDB();
$value = $config->getConfigValueByName("tracking_code");
//$value2 = $config->getConfigValueByName("tracking_pod");
if(strcmp($value, "-1") != 0){
    $sharepointConnection = new SharepointRequest("minhnv@edumallinternational.onmicrosoft.com",
        "qsysopr@16",
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
    //pod data
//    $sharepointConnection->setBeginId($value2);
//    $sharepointConnection->setListItem("PODs");
//    $pods = $sharepointConnection->podMining();
//    $podb = new OrderPodDB($db_host, $db_username, $db_password, $db_dbname);
//    $count2 = 0;
//    foreach($pods as $pod){
//        $order = new OrderPod();
//        $order->setconsignment($pod->consignment);
//        $order->setBooking_no($pod->booking_no);
////        echo $pod->booking_datetime;
//        if(strpos($pod->booking_datetime, "T") === false){
//            $order->setBooking_datetime(Utilities::standardDatetime1($pod->booking_datetime));
//        }else{
//            $order->setBooking_datetime(Utilities::standardDatetime2($pod->booking_datetime));
//        }
//        $order->setAct_pickup_datetime(Utilities::standardDatetime2($pod->act_pickup_datetime));
//        $order->setAct_delivery_datetime(Utilities::standardDatetime2($pod->act_delivery_datetime));
//        $order->setRecipient_zipcode($pod->recipient_zipcode);
//        $order->setOrigin_station($pod->origin_station);
//        $order->setDestination_station($pod->destination_station);
//        $order->setService_code($pod->service_code);
//        $order->setRoute_code($pod->route_code);
//        $order->setCod_amount($pod->cod_amount);
//        $order->setTot_pkg($pod->tot_pkg);
//        $order->setChargeable_wt($pod->chargeable_wt);
//        $order->setRemark($pod->remark);
//        $order->setTracking_datetime(Utilities::standardDatetime2($pod->tracking_datetime));
//        $order->setDestination_state_code($pod->destination_state_code);
//        $order->setException_code($pod->exception_code);
//        $order->setPerson_incharge($pod->person_incharge);
//        if(strpos($pod->est_delivery_datetime, "T") === false){
//            $order->setEst_delivery_datetime(Utilities::standardDatetime1($pod->est_delivery_datetime));
//        }else{
//            $order->setEst_delivery_datetime(Utilities::standardDatetime2($pod->est_delivery_datetime));
//        }
//        $order->setCustid($pod->custid);
//        $order->setCust_name($pod->cust_name);
//        $order->setRecipient_name($pod->recipient_name);
//        $order->setRecipient_address1($pod->recipient_address1);
//        $order->setRecipient_address2($pod->recipient_address2);
//        $order->setState_name($pod->state_name);
//        $order->setTot_dim_wt($pod->tot_dim_wt);
//        $order->setOrigin_state_code($pod->origin_state_code);
//        $order->setTot_act_wt($pod->tot_act_wt);
//        $podb->insert($order);
//        $count2++;
//    }
    $value = intval($value);
    $value = $value + $count;
//    $value2 = intval($value2);
//    $value2 = $value2 + $count2;
    $value = strval($value);
    $config->updateConfigValueByName("tracking_code", $value);
//    $config->updateConfigValueByName("tracking_pod", $value2);
    $config->dbClose();
}




