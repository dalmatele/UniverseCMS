<?php

require_once '../models/Database.php';
require_once '../models/Advisor.php';

/**
 * Description of AdvisorDB
 *
 * @author duc
 */
class AdvisorDB extends Database{
    
    public function insert($advisor){
        $query = $this->connection->prepare("INSERT INTO `advisor` (id,advisor_email) VALUES(NULL,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
//        $advisor_email = $advisor->getAdvisor_email();
        $query->bind_param("s",$advisor);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getAll(){
        $query = $this->connection->prepare("SELECT a.advisor_email"
                . " FROM `advisor` as a");
        if(!$query){
            error_log("Error prepare query. ".mysqli_error($this->connection));
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->execute();
        $query->bind_result($advisor_email);
        $advisors = array();
        while($query->fetch()){
            array_push($advisors, $advisor_email);
        }
        return $advisors;
    }
}
