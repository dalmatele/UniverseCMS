<?php


namespace models\collection;

/**
 * Description of User
 *
 * @author duc
 */
class User {
    public $id;
    public $user_name;
    public $user_email;
    public $user_phone;
    public $user_address;
    public $user_zipcode;
    
    public function getId() {
        return $this->id;
    }

    public function getUser_name() {
        return $this->user_name;
    }

    public function getUser_email() {
        return $this->user_email;
    }

    public function getUser_phone() {
        return $this->user_phone;
    }

    public function getUser_address() {
        return $this->user_address;
    }

    public function getUser_zipcode() {
        return $this->user_zipcode;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser_name($user_name) {
        $this->user_name = $user_name;
    }

    public function setUser_email($user_email) {
        $this->user_email = $user_email;
    }

    public function setUser_phone($user_phone) {
        $this->user_phone = $user_phone;
    }

    public function setUser_address($user_address) {
        $this->user_address = $user_address;
    }

    public function setUser_zipcode($user_zipcode) {
        $this->user_zipcode = $user_zipcode;
    }


}
