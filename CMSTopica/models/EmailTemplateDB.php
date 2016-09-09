<?php

require_once '../models/Database.php';

namespace models;

/**
 * Description of EmailTemplateDB
 *
 * @author duc
 */
class EmailTemplateDB extends \Database{
    public function insert($subject, $email_content){
        $query = $this->connection->prepare("INSERT INTO `email_templates` (id,subject, email_content) VALUES(NULL,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("ss",$subject, $email_content);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
}
