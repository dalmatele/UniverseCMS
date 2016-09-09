<?php

namespace models;
/**
 * To make json request
 * For making request
 */
class shipment{
    Function __construct() {
        
    }
    
    public $con_no;
    public $s_name;
    public $s_address;
    public $s_village;
    public $s_soi;
    public $s_road;
    public $s_subdistrict;
    public $s_district;
    public $s_province;
    public $s_zipcode;
    public $s_mobile1;
    public $s_mobile2;
    public $s_telephone;
    public $s_email;
    public $s_contactperson;
    public $r_name;
    public $r_address;
    public $r_village;
    public $r_soi;
    public $r_road;
    public $r_subdistrict;
    public $r_district;
    public $r_province;
    public $r_zipcode;
    public $r_mobile1;
    public $r_mobile2;
    public $r_telephone;
    public $r_email;
    public $r_contactperson;
    public $special_note;
    public $service_code;
    public $cod_amount;
    public $cod_type;
    public $tot_pkg;
    public $declare_value;
    public $ref_no;
    public $action_code;
    public $id;
}

/**
 * For account report excel
 */
class AccountReport{
    public $r_name;
    public $r_email;
    public $r_address;
    public $r_phonenumber;
    public $cost;
    public $course_code;
    public $course_name;
    public $instructor_category;
    public $order_advisor_code;
    public $co_number;
    public $status_date;
    public $course_latin_name;
    
    public function getR_name() {
        return $this->r_name;
    }

    public function getR_email() {
        return $this->r_email;
    }

    public function getR_address() {
        return $this->r_address;
    }

    public function getR_phonenumber() {
        return $this->r_phonenumber;
    }

    public function getCost() {
        return $this->cost;
    }

    public function getCourse_code() {
        return $this->course_code;
    }

    public function getCourse_name() {
        return $this->course_name;
    }

    public function getInstructor_category() {
        return $this->instructor_category;
    }

    public function getOrder_advisor_code() {
        return $this->order_advisor_code;
    }

    public function getCo_number() {
        return $this->co_number;
    }

    public function getStatus_date() {
        return $this->status_date;
    }

    public function setR_name($r_name) {
        $this->r_name = $r_name;
    }

    public function setR_email($r_email) {
        $this->r_email = $r_email;
    }

    public function setR_address($r_address) {
        $this->r_address = $r_address;
    }

    public function setR_phonenumber($r_phonenumber) {
        $this->r_phonenumber = $r_phonenumber;
    }

    public function setCost($cost) {
        $this->cost = $cost;
    }

    public function setCourse_code($course_code) {
        $this->course_code = $course_code;
    }

    public function setCourse_name($course_name) {
        $this->course_name = $course_name;
    }

    public function setInstructor_category($instructor_category) {
        $this->instructor_category = $instructor_category;
    }

    public function setOrder_advisor_code($order_advisor_code) {
        $this->order_advisor_code = $order_advisor_code;
    }

    public function setCo_number($co_number) {
        $this->co_number = $co_number;
    }

    public function setStatus_date($status_date) {
        $this->status_date = $status_date;
    }


}


/**
 * Order for DB
 *
 * @author duc
 */
class Order {
    //put your code here
    Function __construct() {
        
    }
    
    public $r_name;
    public $r_email;
    public $r_address;
    public $r_phonenumber;
    public $co_number;
    public $postal_code;
    public $cost;
    public $is_sent;
    public $cod_provider;
    public $createdDate;
    public $service_code;
    public $order_status;
    public $order_course_code;
    public $order_advisor_code;
    
    public function getOrder_course_code() {
        return $this->order_course_code;
    }

    public function getOrder_advisor_code() {
        return $this->order_advisor_code;
    }

    public function setOrder_course_code($order_course_code) {
        $this->order_course_code = $order_course_code;
    }

    public function setOrder_advisor_code($order_advisor_code) {
        $this->order_advisor_code = $order_advisor_code;
    }

    
        
    function getOrder_status() {
        return $this->order_status;
    }

    function setOrder_status($order_status) {
        $this->order_status = $order_status;
    }

        
    function getService_code() {
        return $this->service_code;
    }

    function setService_code($service_code) {
        $this->service_code = $service_code;
    }

        
    public function getR_name() {
        return $this->r_name;
    }

    public function getR_email() {
        return $this->r_email;
    }

    public function getR_address() {
        return $this->r_address;
    }

    public function getR_phonenumber() {
        return $this->r_phonenumber;
    }

    public function getCo_number() {
        return $this->co_number;
    }

    function setR_name($r_name) {
        $this->r_name = $r_name;
    }

    function setR_email($r_email) {
        $this->r_email = $r_email;
    }

    function setR_address($r_address) {
        $this->r_address = $r_address;
    }

    function setR_phonenumber($r_phonenumber) {
        $this->r_phonenumber = $r_phonenumber;
    }

    function setCo_number($co_number) {
        $this->co_number = $co_number;
    }

    function getPostal_code() {
        return $this->postal_code;
    }

    function setPostal_code($postal_code) {
        $this->postal_code = $postal_code;
    }  
    
    function getCost() {
        return $this->cost;
    }

    function setCost($cost) {
        $this->cost = $cost;
    }

    function getIs_sent() {
        return $this->is_sent;
    }

    function setIs_sent($is_sent) {
        $this->is_sent = $is_sent;
    }

    function getCod_provider() {
        return $this->cod_provider;
    }

    function setCod_provider($cod_provider) {
        $this->cod_provider = $cod_provider;
    }

    function getCreatedDate() {
        return $this->createdDate;
    }

    function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }



}
