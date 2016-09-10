<?php

namespace models;

require_once '../models/Database.php';

/**
 * Description of RequestManagementDB
 *
 * @author duc
 */
class RequestManagementDB extends \Database{
    
    private $TABLE = "`request_management`";
    
    public function insert($petitioner, $worker, $request_type, $request_content, $request_important){
        $query = $this->connection->prepare("INSERT INTO "
                .$this->TABLE
                ." (id,petitioner, worker, request_type, request_content, request_important, request_at) "
                ."VALUES(NULL,?,?,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("ssisis",$petitioner, $worker, $request_type, $request_content, $request_important, $this->getCreatedDate());
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getRequest($id){
        $query = $this->connection->prepare("SELECT t.id as id,petitioner,worker, r.request_type as request_type, "
                . "l.request_important as request_important, s.request_status as request_status "
                . "FROM ".$this->TABLE." t"
                . "INNER JOIN `request_type` r on t.request_type=r.id "
                . "INNER JOIN `request_level` l on t.request_important=l.id "
                . "INNER JOIN `request_status` s on t.request_status=s.id "
                . "WHERE t.id = ?");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("i", $id);
        $query->execute();
        $requests[] = $this->generateResult($query);
        return $requests;
    }
    
    public function search($worker, $request_type, $request_important, $request_status,
            $f_request_at, $t_request_at, $index, $pageSize){
        error_log($this->TABLE);
        $query = $this->connection->prepare("SELECT t.id, petitioner,worker, r.request_type as type, "
                . "l.request_level as level, s.request_status as status "
                . "FROM ".$this->TABLE." as t "
                . "INNER JOIN `request_type` r on t.request_type=r.id "
                . "INNER JOIN `request_level` l on t.request_important=l.id "
                . "INNER JOIN `request_status` s on t.request_status=s.id "
                . "WHERE "
                . "worker like IFNULL(?,worker) "
                . "AND t.request_type = IFNULL(?,t.request_type) "
                . "AND t.request_important = IFNULL(?,t.request_important) "
                . "AND t.request_status = IFNULL(?,t.request_status) "
                . "AND t.request_at >= IFNULL(?,t.request_at) "
                . "AND t.request_at <= IFNULL(?,t.request_at)"
                . "LIMIT ?, ?");
        if(!$query){
            error_log(mysqli_error($this->connection));
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $worker = $worker != NULL ? "%".$worker."%" : NULL;
        $query->bind_param("siiissii", $worker, $request_type, $request_important, $request_status, 
                $request_status, $t_request_at, $index, $pageSize);
        $query->execute();
        $requests[] = $this->generateResult($query);
        return $requests;
    }
}
