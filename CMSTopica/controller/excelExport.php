<?php
require_once "../Include/config.php";
require_once '../models/OrderStatus.php';
require_once '../models/OrderStatusDB.php';
require_once '../libs/PHPExcel.php';
require_once './Utilities.php';

/* 
 * Tao mot excel object
 * Tao 1 worksheet
 * Chen worksheet vào excel object
 * Ghi excel object
 */

$method = $_SERVER["REQUEST_METHOD"];
switch ($method){
    case "POST":
        $json = file_get_contents("php://input");
        $oJson = json_decode($json);
        $con_no = empty($oJson->con_no) ? NULL : $oJson->con_no;
        $location = empty($oJson->location) ? NULL : $oJson->location;
        $status_code = empty($oJson->status_code) ? NULL : $oJson->status_code;
        $f_status_date = empty($oJson->f_status_date) ? NULL : $oJson->f_status_date;
        $t_status_date = empty($oJson->t_status_date) ? NULL : $oJson->t_status_date;
        $f_update_date = empty($oJson->f_update_date) ? NULL : $oJson->f_update_date;
        $t_update_date = empty($oJson->t_update_date) ? NULL : $oJson->t_update_date;
        $osdb = new OrderStatusDB();
        error_log("here 1");
        $orders = $osdb->searchByLastStatus($con_no, $status_code, $location, $f_status_date, $t_status_date, $f_update_date, $t_update_date);
        error_log("here 2");
        $osdb->dbClose();
        error_log(count($orders));
        if(count($orders) > 0){
            $objPHPExcel = new PHPExcel();
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $output = array();
            array_push($output, ["consignment", "ref_no",
                "booking_no", "booking_datetime", "act_pickup_datetime", "act_delivery_datetime", "recipient_zipcode",
                "orgin_station", "destination_station", "service_code", "route_code", "cod_amount", "tot_pkg",
                "chargeable", "remark", "status_code", "tracking_datetime", "destination_state_code", "payerid","exception_code",
                "person_incharge", "est_delivery_datetime", "custid", "cust_name", "recipient_name", "recipient_address1",
                "recipient_address2", "state_name", "tot_dim_wt", "origin_state_code", "tot_act_wt"]);
            foreach($orders as $order){
                array_push($output, [$order->getCon_no(), $order->getRef_no(),
                    $order->getBooking_no(), $order->getBooking_datetime(), $order->getAct_pickup_datetime(), $order->getAct_delivery_datetime(), $order->getPostal_code(),
                    "SPY", $order->getDc_in_service(), $order->getService_code(),$order->getRoute_code(), $order->getCost(), "1",
                    "1", $order->getRemark(), $order->getStatus_code(),$order->getTracking_datetime(), $order->getDestination_state_code(), "", $order->getException_code(),
                    $order->getPerson_incharge(), $order->getEst_delivery_datetime(), "TOPE","Top English (Thailand) Co, Ltd.", $order->getR_name(), $order->getR_address(),
                    $order->getRecipient_address2(), $order->getProvince_name(), "1","BKK", $order->getTot_act_wt()]);
            }
            
            $objWorksheet->fromArray($output);
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->setPreCalculateFormulas(false);
            $date = new DateTime();
            $fileName = $date->getTimestamp().".xlsx";
            $sourcePath = $file_upload_path.$fileName;// Target path where file is to be stored
            $fileProperties = Utilities::generateFileName();
//            $sourcePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$date->getTimestamp().".xlsx";
            $objWriter->save($fileProperties[0]);
//            $paths = explode(DIRECTORY_SEPARATOR, $sourcePath);
//            $fileName_ext = $paths[count($paths) - 1];
//            $response = array("res" => $fileProperties[1]);
//            echo json_encode($response);
            error_log("here 3");
            echo $fileProperties[1];
        }else{
            error_log("here 4");
            echo "none";
        }
        
        break;
    case "GET":
        $fileName = htmlspecialchars($_GET["filename"]);
//        $destinationPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$fileName.".xlsx";
        $destinationPath = $file_upload_path.$fileName.".xlsx";
        header("Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=export.xlsx");
        echo file_get_contents($destinationPath);
        break;
}



