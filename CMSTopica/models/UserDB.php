<?php


namespace models;

require_once '../models/Database.php';

use models\collection\User;
/**
 * Description of UserDB
 *
 * @author duc
 */
class UserDB extends \Database{
    
    public function insert($user){
        $query = "INSERT into `user_table` (id, user_name, user_email, user_phone, user_address, user_zipcode) "
                ."VALUES(NULL,?,?,?,?,?)";
        if(!$query){
            error_log("ducla: ".mysqli_error($this->connection), 0);
        }
        $user_name = $user->getUser_name();
        $user_email = $user->getUser_email();
        $user_phone = $user->getUser_phone();
        $user_address = $user->getUser_address();
        $user_zipcode = $user->getUser_zipcode();
        $query->bind_param("sssss", $user_name, $user_email, $user_phone, $user_address, $user_zipcode);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
}
