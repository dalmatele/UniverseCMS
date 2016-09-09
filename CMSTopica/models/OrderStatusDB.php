<?php

require_once '../models/Database.php';
require_once '../models/OrderStatus.php';


/**
 * Description of OrderStatusDB
 *
 * @author duc
 */
class OrderStatusDB extends Database{
    
    /**
     * Cap nhat trang thai cac don hang ve trang thai da cu
     * @param type $code
     */
    public function updateNewestByCode($code) {
        $squery = "UPDATE `order_status` SET is_newest=0 WHERE con_no=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("s",$code);
        $result = $query->execute();
        return $result;
    }
    
    public Function insert($orderStatus){
        // must use `` to select right database;
        $query = $this->connection->prepare("INSERT INTO `order_status`"
                . "(id,con_no,status_code,location,ref_no, status_date, status_desc,update_date,is_newest)"
                . " VALUES(NULL,?,?,?,?,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $con_no = $orderStatus->getCon_no();
        $status_code = $orderStatus->getStatus_code();
        $location = $orderStatus->getLocation();
        $ref_no = $orderStatus->getRef_no();
        $status_date = $orderStatus->getStatus_date();
        $status_desc = $orderStatus->getStatus_desc();
        $update_date = $orderStatus->getUpdate_date();
        $is_newest = $orderStatus->getIs_newest();
        $query->bind_param("sssssssi", $con_no, $status_code, $location, $ref_no,
                $status_date, $status_desc, $update_date, $is_newest);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return 1;
        }else{
            return 0;
        }
    }
    
    
    
    /**
     * Tìm kiếm phục vụ thống kê trên biểu đồ
     * @return array ["Số hồ sơ thành công", "Số hồ sơ không thành công"]
     */
    public function searchForReport($f_status_date, $t_status_date){
        $squery = "Select o.status_code,  count(o.status_code) as number "
                ."From `order_status`  o "
                ."Where "
                ."o.status_date >= IFNULL(?,o.status_date) "
                ."AND o.status_date <= IFNULL(?,o.status_date) "
                ."AND o.is_newest = 1 "
                ."Group by o.status_code";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->bind_param("ss", $f_status_date, $t_status_date);
        $query->execute();
        $query->bind_result($r_status_code, $r_number);
        $orders = array();
        while($query->fetch()){
            $order = new OrderStatus();
            $order->setStatus_code($r_status_code);
            $order->setNumber($r_number);
            array_push($orders, $order);
        }
        return $orders;
    }


    
    /**
     * Xuat bao cao excel
     * @param type $con_no
     * @param type $status_code
     * @param type $location
     * @param type $f_status_date
     * @param type $t_status_date
     * @param type $f_update_date
     * @param type $t_update_date
     * @return array
     */
    public function searchByLastStatus($con_no, $status_code, $location,
            $f_status_date, $t_status_date, $f_update_date,
            $t_update_date){
//        $squery = "Select o.con_no, o.location, o.status_date, o.update_date, s.description, o.status_code "
//                ."From `order_status`  o "
//                ."Inner Join (Select `con_no`, Max(`status_date`) as max_date from `order_status` group by `con_no`) o1 on o.con_no = o1.con_no And o.status_date = o1.max_date "
//                ."Inner Join `status`  s "
//                ."On o.status_code = s.code "
//                ."Where "
//                ."o.con_no like IFNULL(?,o.con_no) "
//                ."AND o.status_code = IFNULL(?, o.status_code) "
//                ."AND o.location like IFNULL(?,o.location) "
//                ."AND str_to_date(o.status_date,'%Y-%m-%d') >= IFNULL(?,str_to_date(o.status_date,'%Y-%m-%d')) "
//                ."AND str_to_date(o.status_date,'%Y-%m-%d') <= IFNULL(?,str_to_date(o.status_date,'%Y-%m-%d')) "
//                ."AND str_to_date(o.update_date,'%Y-%m-%d') >= IFNULL(?,str_to_date(o.update_date,'%Y-%m-%d')) "
//                ."AND str_to_date(o.update_date,'%Y-%m-%d') <= IFNULL(?,str_to_date(o.update_date,'%Y-%m-%d'))";
        $squery = "Select o.con_no, o.location, o.status_date, o.update_date, o.status_desc, o.status_code, o.ref_no, "
                ."p.recipient_name, p.recipient_address1, p.recipient_zipcode, p.cod_amount, p.destination_station, p.state_name, "
                ."p.booking_no, p.booking_datetime, p.act_pickup_datetime, p.act_delivery_datetime, p.service_code, "
                ."p.route_code, p.remark, p.tracking_datetime, p.destination_state_code, p.person_incharge, p.est_delivery_datetime, "
                ."p.tot_act_wt, p.recipient_address2,p.exception_code "
                ."From `order_status`  o "
                ."Inner join `order_pod` p On o.con_no = p.consignment "
                ."Where "
//                ."o.con_no like IFNULL(?,o.con_no) "
                ."o.is_newest = 1 "
//                ."AND o.is_newest = 1 "
                ."AND o.status_code = IFNULL(?, o.status_code) "
//                ."AND o.location like IFNULL(?,o.location) "
                ."AND o.status_date >= IFNULL(?,o.status_date) "
                ."AND o.status_date <= IFNULL(?,o.status_date) ";
//                ."AND o.update_date >= IFNULL(?,o.update_date) "
//                ."AND o.update_date <= IFNULL(?,o.update_date)";
        $query = $this->connection->prepare($squery);
        error_log("1.1:".time());
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
//        $con_no = $con_no != NULL ? "%".$con_no."%" : NULL;
//        $location = $location != NULL ? "%".$location."%" : NULL;
        $query->bind_param("sss", $status_code, $f_status_date,$t_status_date);
//        $query->bind_param("sssssss", $con_no, $status_code, $location, $f_status_date,
//                $t_status_date, $f_update_date, $t_update_date);
        $query->execute();
        $query->bind_result($r_con_no, $r_location, $r_status_date, $r_update_date, $r_description,$r_status_code, $r_ref_no, 
                $recipient_name, $recipient_address1, $recipient_zipcode, $cod_amount, $destination_station,$state_name, 
                $booking_no, $booking_datetime, $act_pickup_datetime, $act_delivery_datetime,
                $service_code,$route_code, $remark, $tracking_datetime, $destination_state_code, $person_incharge,
                $est_delivery_datetime,$tot_act_wt, $recipient_address2, $exception_code);
        $orderStatus = array();
        error_log("1.2:".time());
        while($query->fetch()){
            $os = new OrderStatus();
            $os->setCon_no($r_con_no);
            $os->setLocation($r_location);
            $os->setStatus_date($r_status_date);
            $os->setUpdate_date($r_update_date);
            $os->setStatus_desc($r_description);
            $os->setStatus_code($r_status_code);
            $os->setRef_no($r_ref_no);
            $os->setR_name($recipient_name);
            $os->setR_address($recipient_address1);
            $os->setPostal_code($recipient_zipcode);
            $os->setCost($cod_amount);
            $os->setDc_in_service($destination_station);
            $os->setProvince_name($state_name);
            
            $os->setBooking_no($booking_no);
            $os->setBooking_datetime($booking_datetime); 
            $os->setAct_pickup_datetime($act_pickup_datetime); 
            $os->setAct_delivery_datetime($act_delivery_datetime); 
            $os->setService_code($service_code);
            $os->setRoute_code($route_code);
            $os->setRemark($remark);
            $os->setTracking_datetime($tracking_datetime);
            $os->setDestination_state_code($destination_state_code);
            $os->setPerson_incharge($person_incharge);
            $os->setEst_delivery_datetime($est_delivery_datetime);
            $os->setTot_act_wt($tot_act_wt);
            $os->setRecipient_address2($recipient_address2);
            $os->setException_code($exception_code);
            
            array_push($orderStatus, $os);
        }
        error_log("1.3 ".time());
        return $orderStatus;
    }
    
    /**
     * Lấy thông tin chi tiết về các trạng thái của đơn hàng
     * @param type $code mã đơn hàng
     * @return array các trạng thái của đơn hàng
     */
    public function getOrderStatus($code){
        $squery = "Select status_code,location,status_desc,status_date,is_newest "
                ."From `order_status` "
                ."Where con_no = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->bind_param("s",$code);
        $query->execute();
        $query->bind_result($status_code, $location, $status_desc, $status_date, $is_newest);
        $orderStatus = array();
        while($query->fetch()){
            $os = new OrderStatus();
            $os->setStatus_code($status_code);
            $os->setLocation($location);
            $os->setStatus_desc($status_desc);
            $os->setStatus_date($status_date);
            $os->setIs_newest($is_newest);
            array_push($orderStatus, $os);
        }
        return $orderStatus;
    }
    
}
