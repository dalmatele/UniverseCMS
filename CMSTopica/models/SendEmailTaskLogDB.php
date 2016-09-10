<?php

namespace models;
require_once '../models/Database.php';


/**
 * Description of SendEmailTaskLogDB
 *
 * @author duc
 */
class SendEmailTaskLogDB extends \Database{
    
    
    public function updateMailLog($id, $mail_log, $send_status){
        $squery = "UPDATE `send_email_task_log` SET  email_log = ?, send_status = ? WHERE id=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("sii",$mail_log, $send_status,$id);
        $result = $query->execute();
        return $result;
    }
    
    public function insert($from_id, $send_to, $email_id, $send_at){
        $query = $this->connection->prepare("INSERT INTO `send_email_task_log` (id,from_id, send_to, email_id, send_at) VALUES(NULL,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("isis",$from_id, $send_to, $email_id, $send_at);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function getEmailToSend($time){
        $query = $this->connection->prepare("SELECT setl.id as id, send_to, send_from , email_content, subject, send_password, fullname "
                                            ."FROM `send_email_task_log` setl "
                                            ."INNER JOIN `send_mail_schedule` ses ON setl.from_id = ses.id "
                                            ."INNER JOIN `email_templates` et ON setl.email_id = et.id "
                                            ."WHERE "
                                            ."setl.send_status = 0 "
                                            ."AND setl.send_at <= ?");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("s",$time);
        $query->execute();
        $emails[] = $this->generateResult($query);
        return $emails;
    }
    
    
}
