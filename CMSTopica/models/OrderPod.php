<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderPod
 *
 * @author duc
 */
class OrderPod {
    public $consignment;
    public $booking_no;
    public $booking_datetime;
    public $act_pickup_datetime;
    public $act_delivery_datetime;
    public $recipient_zipcode;
    public $origin_station;
    public $destination_station;
    public $service_code;
    public $route_code;
    public $cod_amount;
    public $tot_pkg;
    public $chargeable_wt;
    public $remark;
    public $tracking_datetime;
    public $destination_state_code;
    public $exception_code;
    public $person_incharge;
    public $est_delivery_datetime;
    public $custid;
    public $cust_name;
    public $recipient_address1;
    public $recipient_address2;
    public $state_name;
    public $tot_dim_wt;
    public $origin_state_code;
    public $tot_act_wt;
    public $recipient_name;
    
    function getRecipient_name() {
        return $this->recipient_name;
    }

    function setRecipient_name($recipient_name) {
        $this->recipient_name = $recipient_name;
    }

        
    function getConsignment() {
        return $this->consignment;
    }

    function getBooking_no() {
        return $this->booking_no;
    }

    function getBooking_datetime() {
        return $this->booking_datetime;
    }

    function getAct_pickup_datetime() {
        return $this->act_pickup_datetime;
    }

    function getAct_delivery_datetime() {
        return $this->act_delivery_datetime;
    }

    function getRecipient_zipcode() {
        return $this->recipient_zipcode;
    }

    function getOrigin_station() {
        return $this->origin_station;
    }

    function getDestination_station() {
        return $this->destination_station;
    }

    function getService_code() {
        return $this->service_code;
    }

    function getRoute_code() {
        return $this->route_code;
    }

    function getCod_amount() {
        return $this->cod_amount;
    }

    function getTot_pkg() {
        return $this->tot_pkg;
    }

    function getChargeable_wt() {
        return $this->chargeable_wt;
    }

    function getRemark() {
        return $this->remark;
    }

    function getTracking_datetime() {
        return $this->tracking_datetime;
    }

    function getDestination_state_code() {
        return $this->destination_state_code;
    }

    function getException_code() {
        return $this->exception_code;
    }

    function getPerson_incharge() {
        return $this->person_incharge;
    }

    function getEst_delivery_datetime() {
        return $this->est_delivery_datetime;
    }

    function getCustid() {
        return $this->custid;
    }

    function getCust_name() {
        return $this->cust_name;
    }

    function getRecipient_address1() {
        return $this->recipient_address1;
    }

    function getRecipient_address2() {
        return $this->recipient_address2;
    }

    function getState_name() {
        return $this->state_name;
    }

    function getTot_dim_wt() {
        return $this->tot_dim_wt;
    }

    function getOrigin_state_code() {
        return $this->origin_state_code;
    }

    function getTot_act_wt() {
        return $this->tot_act_wt;
    }

    function setConsignment($consignment) {
        $this->consignment = $consignment;
    }

    function setBooking_no($booking_no) {
        $this->booking_no = $booking_no;
    }

    function setBooking_datetime($booking_datetime) {
        $this->booking_datetime = $booking_datetime;
    }

    function setAct_pickup_datetime($act_pickup_datetime) {
        $this->act_pickup_datetime = $act_pickup_datetime;
    }

    function setAct_delivery_datetime($act_delivery_datetime) {
        $this->act_delivery_datetime = $act_delivery_datetime;
    }

    function setRecipient_zipcode($recipient_zipcode) {
        $this->recipient_zipcode = $recipient_zipcode;
    }

    function setOrigin_station($origin_station) {
        $this->origin_station = $origin_station;
    }

    function setDestination_station($destination_station) {
        $this->destination_station = $destination_station;
    }

    function setService_code($service_code) {
        $this->service_code = $service_code;
    }

    function setRoute_code($route_code) {
        $this->route_code = $route_code;
    }

    function setCod_amount($cod_amount) {
        $this->cod_amount = $cod_amount;
    }

    function setTot_pkg($tot_pkg) {
        $this->tot_pkg = $tot_pkg;
    }

    function setChargeable_wt($chargeable_wt) {
        $this->chargeable_wt = $chargeable_wt;
    }

    function setRemark($remark) {
        $this->remark = $remark;
    }

    function setTracking_datetime($tracking_datetime) {
        $this->tracking_datetime = $tracking_datetime;
    }

    function setDestination_state_code($destination_state_code) {
        $this->destination_state_code = $destination_state_code;
    }

    function setException_code($exception_code) {
        $this->exception_code = $exception_code;
    }

    function setPerson_incharge($person_incharge) {
        $this->person_incharge = $person_incharge;
    }

    function setEst_delivery_datetime($est_delivery_datetime) {
        $this->est_delivery_datetime = $est_delivery_datetime;
    }

    function setCustid($custid) {
        $this->custid = $custid;
    }

    function setCust_name($cust_name) {
        $this->cust_name = $cust_name;
    }

    function setRecipient_address1($recipient_address1) {
        $this->recipient_address1 = $recipient_address1;
    }

    function setRecipient_address2($recipient_address2) {
        $this->recipient_address2 = $recipient_address2;
    }

    function setState_name($state_name) {
        $this->state_name = $state_name;
    }

    function setTot_dim_wt($tot_dim_wt) {
        $this->tot_dim_wt = $tot_dim_wt;
    }

    function setOrigin_state_code($origin_state_code) {
        $this->origin_state_code = $origin_state_code;
    }

    function setTot_act_wt($tot_act_wt) {
        $this->tot_act_wt = $tot_act_wt;
    }


}
