<?php

require_once __DIR__ .'/../models/Database.php';

/**
 * Description of ConfigDB
 *
 * @author duc
 */
class ConfigDB extends Database{

    
    public function getConfigValueByName($configName){
        $squery = "SELECT c.config_value FROM `order_config` as c "
                ."WHERE "
                ."config_name = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("s",$configName);
        $query->execute();
        $query->bind_result($configValue);
        $result = "";
        while($query->fetch()){
            $result = $configValue;
            break;
        }
        return $result;
    }
    
    public function updateConfigValueByName($configName, $configValue){
        $squery = "UPDATE `order_config` SET config_value=? WHERE config_name=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("ss",$configValue, $configName);
        $result = $query->execute();
        return $result;
    }
}
