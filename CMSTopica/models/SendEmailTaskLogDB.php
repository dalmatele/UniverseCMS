<?php

require_once '../models/Database.php';

namespace models;

/**
 * Description of SendEmailTaskLogDB
 *
 * @author duc
 */
class SendEmailTaskLogDB extends \Database{
    public function insert($from_id, $send_to, $email_id){
        $query = $this->connection->prepare("INSERT INTO `send_email_task_log` (id,from_id, send_to, email_id) VALUES(NULL,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("isi",$from_id, $send_to, $email_id);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getEmailToSend($time){
        $query = $this->connection->prepare("SELECT setl.send_to, ses.send_from, et.email_content,et.subject "
                                            ."FROM `send_email_task_log` setl "
                                            ."INNER JOIN 'send_email_schedule` ses ON setl.from_id = ses.id "
                                            ."INNER JOIN `email_template` et ON setl.email_id = et.id "
                                            ."WHERE "
                                            ."setl.send_at <= ?");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("s",$time);
        $query->execute();
        $emails = array();
        $emails = $this->generateResult($emails, $query);
        return $emails;
    }
    
    
}
