<?php


namespace models;
require_once '../models/Database.php';

/**
 * Description of RequestTypeDB
 *
 * @author duc
 */
class RequestTypeDB extends \Database{
    private $TABLE = "`request_type` ";
    
    public function getAllType(){
        $squery = "SELECT * FROM "
                .$this->TABLE
                ."WHERE 1";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->execute();
        $types[] = $this->generateResult($query);
        return $types;
    }
}
