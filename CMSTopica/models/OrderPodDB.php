<?php

require_once '../models/Database.php';
require_once '../models/OrderPod.php';
/**
 * Description of OrderPodDB
 *
 * @author duc
 */
class OrderPodDB extends Database{
    
    public function insert($orderpod){
        $query = $this->connection->prepare("INSERT INTO `order_pod`(id, consignment, booking_no, booking_datetime,"
                . " act_pickup_datetime, "
                . "act_delivery_datetime, recipient_zipcode, origin_station, destination_station, "
                . "service_code, route_code, cod_amount, tot_pkg, chargeable_wt, remark, tracking_datetime,"
                . " destination_state_code, exception_code, person_incharge, est_delivery_datetime, custid,"
                . " cust_name, recipient_name, recipient_address1, state_name, tot_dim_wt, origin_state_code, tot_act_wt,"
                . " recipient_address2) "
                ."VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        if(!$query){
            error_log("ducla: ".mysqli_error($this->connection), 0);
        }
        $consignment = $orderpod->getConsignment();
        $booking_no = $orderpod->getBooking_no();
        $booking_datetime = $orderpod->getBooking_datetime();
        $act_pickup_datetime = $orderpod->getAct_pickup_datetime();
        $act_delivery_datetime = $orderpod->getAct_delivery_datetime();
        $recipient_zipcode = $orderpod->getRecipient_zipcode();
        $origin_station = $orderpod->getOrigin_station();
        $destination_station = $orderpod->getDestination_station();
        $service_code = $orderpod->getService_code();
        $route_code = $orderpod->getRoute_code();
        $cod_amount = $orderpod->getCod_amount();
        $tot_pkg = $orderpod->getTot_pkg();
        $chargeable_wt = $orderpod->getChargeable_wt();
        $remark = $orderpod->getRemark();
        $tracking_datetime = $orderpod->getTracking_datetime();
        $destination_state_code = $orderpod->getDestination_state_code();
        $exception_code = $orderpod->getException_code();
        $person_incharge = $orderpod->getPerson_incharge();
        $est_delivery_datetime = $orderpod->getEst_delivery_datetime();
        $custid = $orderpod->getCustid();
        $cust_name = $orderpod->getCust_name();
        $recipient_address1 = $orderpod->getRecipient_address1();
        $recipient_address2 = $orderpod->getRecipient_address2();
        $state_name = $orderpod->getState_name();
        $tot_dim_wt = $orderpod->getTot_dim_wt();
        $origin_state_code = $orderpod->getOrigin_state_code();
        $tot_act_wt = $orderpod->getTot_act_wt();
        $recipient_name = $orderpod->getRecipient_name();
        $query->bind_param("sssssssssssiisssssssssssssis",
                $consignment,$booking_no,$booking_datetime,$act_pickup_datetime,$act_delivery_datetime,
                $recipient_zipcode,$origin_station,$destination_station,$service_code,$route_code,
                $cod_amount,$tot_pkg,$chargeable_wt,$remark,$tracking_datetime,$destination_state_code,
                $exception_code,$person_incharge,$est_delivery_datetime,$custid,$cust_name,
                $recipient_name,$recipient_address1,
                $state_name,$tot_dim_wt,$origin_state_code,$tot_act_wt,$recipient_address2
        );
        $result = 0;
        try{
            $result = $query->execute();
        }catch(Exception $e){
            error_log("ducla1: ".$e->getMessage(), 0);
        }
        if(!$result){
            die("Error prepare query. ".mysqli_error($this->connection));
            return -1;
        }else{
            return $query->insert_id;
        }
    }
}
