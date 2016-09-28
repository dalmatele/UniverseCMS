<?php


namespace models;
require_once '../models/Database.php';

/**
 * Description of RequestLevelDB
 *
 * @author duc
 */
class RequestLevelDB extends \Database{
     private $TABLE = "`request_level` ";
     
     public function getAllLevel(){
        $squery = "SELECT * FROM "
                .$this->TABLE
                ."WHERE 1";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->execute();
        $levels[] = $this->generateResult($query);
        return $levels;
    }
}
