<?php


namespace models;
require_once '../models/Database.php';


/**
 * Description of AccountReportMonthlyDB
 *
 * @author duc
 */
class AccountReportMonthlyDB extends \Database{
    
    
    public function insert($year_report, $month_report, $file_name, $time_now){
        $query = $this->connection->prepare("INSERT INTO `account_report_monthly` (id,year_report,month_report,file_name, time_now) "
                . "VALUES(NULL,?,?,?, ?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
//        $advisor_email = $advisor->getAdvisor_email();
        $query->bind_param("ssss",$year_report, $month_report, $file_name, $time_now);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function searchMonthlyReport($year){
        $squery = $squery = "Select year_report,month_report,file_name "
                ."From `account_report_monthly` "
                ."Where year_report = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->bind_param("s",$year);
        $query->execute();
        $account_reports = array();
        $meta = $query->result_metadata();
        while ($field = $meta->fetch_field()){
            $params[] = &$row[$field->name];
        } 
        call_user_func_array(array($query, 'bind_result'), $params);
        while ($query->fetch()) {
            foreach($row as $key => $val){
                $c[$key] = $val;
            }
            $account_reports[] = $c;
        } 
        $query->close();
        return $account_reports;
    }
    
    public function searchChildMonthlyReport($year, $month){
        $squery = $squery = "Select year_report,month_report,file_name,time_now "
                ."From `account_report_monthly` "
                ."Where year_report = ? and month_report = ? and time_now IS NOT NULL";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->bind_param("ss",$year, $month);
        $query->execute();
        $account_reports = array();
        $meta = $query->result_metadata();
        while ($field = $meta->fetch_field()){
            $params[] = &$row[$field->name];
        } 
        call_user_func_array(array($query, 'bind_result'), $params);
        while ($query->fetch()) {
            foreach($row as $key => $val){
                $c[$key] = $val;
            }
            $account_reports[] = $c;
        } 
        $query->close();
        return $account_reports;
    }
}
