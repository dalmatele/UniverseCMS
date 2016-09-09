<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderStatus
 *
 * @author duc
 */
class OrderStatus {
    Function __construct() {
        
    }
    
    private $con_no;
    public $status_code;
    public $status_desc;
    public $status_date;
    public $update_date;
    private $ref_no;
    public $location;
    public $number;//the number of orders that have a specify status
    public $is_newest;
    public $r_name;
    public $r_address;
    public $postal_code;
    public $cost;
    public $dc_in_service;
    public $province_name;
    
    
    public $booking_no;
    public $booking_datetime;
    public $act_pickup_datetime;
    public $act_delivery_datetime;
    public $service_code;
    public $route_code;
    public $remark;
    public $tracking_datetime;
    public $destination_state_code;
    public $person_incharge;
    public $est_delivery_datetime;
    public $tot_act_wt;
    public $recipient_address2;
    public $exception_code;
    
    public function getException_code() {
        return $this->exception_code;
    }

    public function setException_code($exception_code) {
        $this->exception_code = $exception_code;
    }

        
    function getService_code() {
        return $this->service_code;
    }

    function setService_code($service_code) {
        $this->service_code = $service_code;
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

    function getRoute_code() {
        return $this->route_code;
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

    function getPerson_incharge() {
        return $this->person_incharge;
    }

    function getEst_delivery_datetime() {
        return $this->est_delivery_datetime;
    }

    function getTot_act_wt() {
        return $this->tot_act_wt;
    }

    function getRecipient_address2() {
        return $this->recipient_address2;
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

    function setRoute_code($route_code) {
        $this->route_code = $route_code;
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

    function setPerson_incharge($person_incharge) {
        $this->person_incharge = $person_incharge;
    }

    function setEst_delivery_datetime($est_delivery_datetime) {
        $this->est_delivery_datetime = $est_delivery_datetime;
    }

    function setTot_act_wt($tot_act_wt) {
        $this->tot_act_wt = $tot_act_wt;
    }

    function setRecipient_address2($recipient_address2) {
        $this->recipient_address2 = $recipient_address2;
    }
    
    function getProvince_name() {
        return $this->province_name;
    }

    function setProvince_name($province_name) {
        $this->province_name = $province_name;
    }

        
    function getDc_in_service() {
        return $this->dc_in_service;
    }

    function setDc_in_service($dc_in_service) {
        $this->dc_in_service = $dc_in_service;
    }

        
    function getCost() {
        return $this->cost;
    }

    function setCost($cost) {
        $this->cost = $cost;
    }

        
    function getPostal_code() {
        return $this->postal_code;
    }

    function setPostal_code($postal_code) {
        $this->postal_code = $postal_code;
    }

        
    function getR_address() {
        return $this->r_address;
    }

    function setR_address($r_address) {
        $this->r_address = $r_address;
    }

        function getR_name() {
        return $this->r_name;
    }

    function setR_name($r_name) {
        $this->r_name = $r_name;
    }

        
    function getCon_no() {
        return $this->con_no;
    }

    function getStatus_code() {
        return $this->status_code;
    }

    function getStatus_desc() {
        return $this->status_desc;
    }

    function getStatus_date() {
        return $this->status_date;
    }

    function getUpdate_date() {
        return $this->update_date;
    }

    function getRef_no() {
        return $this->ref_no;
    }

    function getLocation() {
        return $this->location;
    }

    function setCon_no($con_no) {
        $this->con_no = $con_no;
    }

    function setStatus_code($status_code) {
        $this->status_code = $status_code;
    }

    function setStatus_desc($status_desc) {
        $this->status_desc = $status_desc;
    }

    function setStatus_date($status_date) {
        $this->status_date = $status_date;
    }

    function setUpdate_date($update_date) {
        $this->update_date = $update_date;
    }

    function setRef_no($ref_no) {
        $this->ref_no = $ref_no;
    }

    function setLocation($location) {
        $this->location = $location;
    }

    function getNumber() {
        return $this->number;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function getIs_newest() {
        return $this->is_newest;
    }

    function setIs_newest($is_newest) {
        $this->is_newest = $is_newest;
    }



}
