<?php

namespace models;
require_once '../models/Database.php';

/**
 * Description of MemberDB
 *
 * @author duc
 */
class MemberDB extends \Database{
    
     private $TABLE = "`members` ";
    
    public function listMember($username){
        $squery = "SELECT id, username FROM "
                .$this->TABLE
                ."WHERE "
                ."username like ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $username = "%".$username."%";
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($id, $member);
        $members = array();
        while($query->fetch()){
            array_push($members, array("id" => $id, "label" => $member, "value" => $member));
        }
        return $members;
    }
    
    public function getActiveCode($email){
        $squery = "SELECT active_code FROM "
                .$this->TABLE
                ."WHERE "
                ."email = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $e = $email;
        $query->bind_param("s", $e);
        $query->execute();
        $query->bind_result($active_code);
        while($query->fetch()){
            return $active_code;
        }
        return "";
    }
    
    public function updateActiveCode($active_code, $email){
         $squery = "UPDATE "
                 .$this->TABLE
                 . " SET active_code = ? WHERE email = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("ss",$active_code, $email);
        $result = $query->execute();
        return $result;
    }
}
