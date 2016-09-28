<?php

namespace models;
require_once '../models/Database.php';

/**
 * Description of EmailTemplateDB
 *
 * @author duc
 */
class EmailTemplateDB extends \Database{
    
    private $TABLE = "`email_templates` ";
    
    public function insert($subject, $email_content){
        $query = $this->connection->prepare("INSERT INTO "
                .$this->TABLE
                ."(id,subject, email_content, created_date) VALUES(NULL,?,?,?)");
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $created_date = $this->getCreatedDate();
        $query->bind_param("sss",$subject, $email_content, $created_date);
        $result = $query->execute();
        if(!$result){
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function updateEmailTotal($id, $email_total){
        $squery = "UPDATE "
                .$this->TABLE
                ."SET email_total = ? WHERE id=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("si",$email_total, $id);
        $result = $query->execute();
        return $result;
    }
    
    public function updateEmailCount($id, $email_count){
        $squery = "UPDATE "
                .$this->TABLE
                ."SET email_count = ? WHERE id=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("si",$email_count, $id);
        $result = $query->execute();
        return $result;
    }    
    
    public function getEmailTemplate($id){
        $squery = "SELECT * FROM "
                .$this->TABLE
                ."WHERE id = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $query->bind_param("i", $id);
        $query->execute();
        $tempates[] = $this->generateResult($query);
        return $tempates;
    }
    
    public function getEmailStatistic($subject, $f_date, $t_date, $index, $pageSize){
        $squery = "SELECT DISTINCT subject, created_date, email_total, email_count, l.send_status as status, l.send_at as send_at FROM "
                .$this->TABLE." t "
                ."INNER JOIN `send_email_task_log` l ON t.id = l.email_id "
                ."WHERE "
                ."subject like IFNULL(?,subject) "
                ."AND created_date >= ? "
                ."AND created_date <= ? "
                ."LIMIT ?,?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            errorLogger(mysqli_error($this->connection), "error");
        }
        $subject = "%".$subject."%";
        $query->bind_param("sssii", $subject, $f_date, $t_date, /*$importDateF, $importDateT,*/ $index, $pageSize);
        $query->execute();
        $templates[] = $this->generateResult($query);
        return $templates;
    }
}
