<?php

namespace models;
require_once '../models/Database.php';

/**
 * Description of SendEmailSchedule
 *
 * @author duc
 */
class SendEmailScheduleDB extends \Database{
    
    public function insert($send_from, $send_password){
        $query = $this->connection->prepare("INSERT INTO `send_mail_schedule` (id,send_from, send_password) VALUES(NULL,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("ss",$send_from, $send_password);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function update($id, $send_from, $send_password){
        $squery = "UPDATE `send_mail_schedule` SET send_from = ?, send_password = ? WHERE id = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("ssi",$send_from, $send_password, $id);
        $result = $query->execute();
        return $result;
    }
    
    public function search($send_from, $index, $pageSize){
        $query = $this->connection->prepare("SELECT id,send_from,fullname FROM `send_mail_schedule` "
                . "WHERE "
                . "send_from like IFNULL(?,send_from) "
                . "LIMIT ?, ?");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $send_from = "%".$send_from."%";
        $query->bind_param("sii",$send_from, $index, $pageSize);
        $query->execute();
        $send_froms[] = $this->generateResult($query);
        return $send_froms;
    }
}
