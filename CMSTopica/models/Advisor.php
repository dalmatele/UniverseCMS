<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Advisor
 *
 * @author duc
 */
class Advisor {
    
    public $id;
    public $advisor_email;
    
    public function getId() {
        return $this->id;
    }

    public function getAdvisor_email() {
        return $this->advisor_email;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setAdvisor_email($advisor_email) {
        $this->advisor_email = $advisor_email;
    }




}
