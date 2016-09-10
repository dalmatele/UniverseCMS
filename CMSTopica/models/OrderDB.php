<?php
//http://stackoverflow.com/questions/2418473/difference-between-require-include-and-include-once
require_once '../models/Order.php';
require_once '../models/Database.php';

use models\AccountReport;
use models\Order;

/**
 * Description of Database
 *
 * @author duc
 */
class OrderDB extends Database{
    
    /**
     * Insert new database
     * @param type $order
     * @return type
     */
    public function insert($order){
        $query = $this->connection->prepare("INSERT INTO `order` (id,r_name,r_email,r_address,r_phonenumber,co_number, postal_code, cost, is_sent, cod_provider, created_date, service_code, order_course_code, order_advisor_code) VALUES(NULL,?,?,?,?,?,?,?,?,?, ?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $r_name = $order->getR_name();
        $r_email = $order->getR_email();
        $r_address = $order->getR_address();
        $r_phonenumber = $order->getR_phonenumber();
        $co_number = $order->getCo_number();
        $postal_code = $order->getPostal_code();
        $cost = $order->getCost();
        $cod_provider = $order->getCod_provider();
        $created_date = $order->getCreatedDate();
        $service_code = $order->getService_code();
        $order_course_code = $order->getOrder_course_code();
        $order_advisor_code = $order->getOrder_advisor_code();
        $status = "-1";
        $query->bind_param("sssssssssssss",$r_name, $r_email, $r_address, $r_phonenumber,
                $co_number, $postal_code, $cost, $status, $cod_provider, $created_date,$service_code,
                $order_course_code,$order_advisor_code);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function unsentOrder(){
        $query = $this->connection->prepare("SELECT o.id,o.r_name,o.r_email,o.r_address,o.r_phonenumber,o.co_number,o.postal_code,o.cost,a.district_name,a.province_name"
                . " FROM `order` as o LEFT JOIN `adress_table` as a ON o.postal_code = a.postal_code"
                . " WHERE is_sent = '-1'");
//        $query = $this->connection->prepare("SELECT o.id,o.r_name,o.r_email,o.r_address,o.r_phonenumber,o.co_number,o.postal_code,o.cost,a.district_name,a.province_name"
//                . " FROM u878596405_tpc.order as o LEFT JOIN u878596405_tpc.adress_table as a ON o.postal_code = a.postal_code"
//                . " WHERE is_sent = 0");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->execute();
        $query->store_result();
        return $query;
    }
    
    /**
     * After sending shipment request
     */
    public function updateStatusCode($status, $id){
        $squery = "UPDATE `order` SET is_sent=? WHERE id=?";
//        $squery = "UPDATE u878596405_tpc.order SET is_sent=? WHERE id=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("si",$status, $id);
        $result = $query->execute();
        return $result;
    }
    
    /**
     * Tìm kiếm các đơn hàng, có hỗ trợ phân trang
     * @link http://www.onlinetuting.com/pagination-in-php-mysqli/
     * @link http://stackoverflow.com/questions/13474207/sql-query-if-parameter-is-null-select-all
     * @param type $code
     * @param type $name
     * @param type $status
     * @param type $pageSize
     * @param type $pageIndex
     * @return array
     */
    public function search($code, $name, $status, $pageSize, $pageIndex){
        $squery = "SELECT o.co_number,o.r_name,o.is_sent,o.r_email,o.r_address,o.r_phonenumber,o.cost,`order_status`.status_code FROM `order` as o "
                ."Inner Join `order_status` on o.co_number = `order_status`.con_no "
                ."WHERE "
                ."co_number like IFNULL(?,co_number) "
                ."AND r_name like IFNULL(?,r_name) "
                ."AND `order_status`.status_code like IFNULL(?, `order_status`.status_code) "
                ."AND `order_status`.is_newest = 1 "
                ."LIMIT ?, ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla ".mysqli_error($this->connection), 0);
        }
        $code = "%".$code."%";
        $name = "%".$name."%";
        $status = "%".$status."%";
        $query->bind_param("sssii", $code, $name, $status, /*$importDateF, $importDateT,*/ $pageIndex, $pageSize);
        $query->execute();
        $query->bind_result($co_number, $r_name, $is_sent, $r_email,
                $r_address, $r_phonenumber, $cost, $status_code);
        $orders = array();
        while($query->fetch()){
            $order = new Order();
            $order->setCo_number($co_number);
            $order->setIs_sent($is_sent);
            $order->setCost($cost);
            $order->setR_address($r_address);
            $order->setR_email($r_email);
            $order->setR_name($r_name);
            $order->setR_phonenumber($r_phonenumber);
            $order->setOrder_status($status_code);
            array_push($orders, $order);
        }
        return $orders;
    }
    
    /**
     * 
     * @param type $f_status_date
     * @param type $t_status_date
     * @return array
     */
    public function accountReport($f_status_date, $t_status_date){
        $squery = "select o.r_name,o.r_email, o.r_address, o.r_phonenumber, "
                ."o.cost,c.course_code,c.course_name,c.instructor_category,o.order_advisor_code,o.co_number,"
                ."os.status_date,c.course_latin_name "
                ."from `order` o "
                ."inner join `course` c on o.order_course_code = c.course_code "
                ."inner join `order_status` os on o.co_number = os.con_no "
                ."WHERE "
                ."os.status_date > ? "
                ."AND "
                ."os.status_date < ? "
                ."AND os.is_newest = 1";
        
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->bind_param("ss", $f_status_date, $t_status_date);
        $query->execute();
        
        $account_reports[] = $this->generateResult($query);
        return $account_reports;
    }
    
}
