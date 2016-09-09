<?php

require_once "../Include/config.php";
require_once './SendRequest.php';
require_once '../models/Order.php';
include '../libs/PHPExcel.php';
include '../models/OrderDB.php';
require_once '../models/Result.php';


/*Phụ trách việc upload file*/
if(isset($_FILES["file"]["type"])){
    $validExtensions = array("xls", "xlsx", "csv");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if((($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ||
            ($_FILES["file"]["type"] == "application/vnd.ms-excel") ||
            ($_FILES["file"]["type"] == "text/csv")) &&
            ($_FILES["file"]["size"] < 100000) &&
        in_array($file_extension, $validExtensions)){
        if ($_FILES["file"]["error"] > 0){
            echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
        }else{
            if(file_exists("upload/".$_FILES["file"]["name"])){
                echo $_FILES["file"]["name"]." <span id='invalid'><b>already exists.</b></span>";
            }else{
                $sourcePath = $_FILES["file"]["tmp_name"];// Storing source path of the file in a variable
                $targetPath = dirname($_SERVER["SCRIPT_FILENAME"])."/../upload/".$_FILES["file"]["name"];// Target path where file is to be stored
                move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                //Read excel file
                $orders = array();
                if($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    //excel 2007 and more
                    $objReader = new PHPExcel_Reader_Excel2007();
                    $objReader->setReadDataOnly(true);
                    $objPHPExcel = $objReader->load($targetPath);
                    $objWorksheet = $objPHPExcel->getActiveSheet();
                    $row_count = 0;
                    foreach($objWorksheet->getRowIterator() as $row){
                        if($row_count == 0){
                            $row_count++;
                            continue;
                        }
                        $cellIterator = $row->getCellIterator();
                        $order = new Order();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        $count = 0;
                        foreach($cellIterator as $cell){
                            $count++;
                            switch ($count){
                                case 1:
                                    $co_number = $cell->getValue();
                                    $l = strlen($co_number);
                                    if($l < 9){
                                        for($i = 0; $i < 9 - $l;$i++){
                                            $co_number = "0".$co_number;
                                        }
                                    }
                                    $co_number = "TWLL".$co_number;
                                    $order->setCo_number($co_number);
                                    break;
                                case 4:
                                    $order->setR_name($cell->getValue());
                                    break;
                                case 5:
                                    $order->setR_email($cell->getValue());
                                    break;
                                case 6:
                                    $order->setR_phonenumber($cell->getValue());
                                    break;
                                case 7:
                                    $order->setR_address($cell->getValue());
                                    break;
                                case 8:
                                    $order->setPostal_code($cell->getValue());
                                    break;
                                case 11:
                                    $order->setCost($cell->getValue());
                                    break;
                            }
                        }
                        $order->setCod_provider(1);
                        $date = new DateTime();
                        $order->setCreatedDate($date->format("Y-m-d H:i:s"));
                        array_push($orders, $order);
                        
                    }
                }elseif($_FILES["file"]["type"] == "text/csv"){
                    //normal csv file
                    $file = fopen($targetPath,"r");
                    $row_count = 0;
                    while(!feof($file)){
                        $data = fgetcsv($file);//an array data
                        if($row_count == 0){
                            $row_count++;
                            continue;
                        }
                        $order = new Order();
//                        http://www.w3schools.com/php/func_filesystem_fgetcsv.asp
                        $size = count($data);
                        for($i = 0; $i < $size; $i++){
                            switch ($i){
                                case 0:
                                    $co_number = $data[$i];
                                    $l = strlen($co_number);
                                    if($l < 9){
                                        for($j = 0; $j < 9 - $l;$j++){
                                            $co_number = "0".$co_number;
                                        }
                                    }
                                    $co_number = "TWLL".$co_number;
                                    $order->setCo_number($co_number);
                                    break;
                                case 3:
                                    $order->setR_name($data[$i]);
                                    break;
                                case 4:
                                    $order->setR_email($data[$i]);
                                    break;
                                case 5:
                                    $order->setR_phonenumber($data[$i]);
                                    break;
                                case 6:
                                    $order->setR_address($data[$i]);
                                    break;
                                case 7:
                                    $order->setPostal_code($data[$i]);
                                    break;
                                case 10:
                                    $order->setCost($data[$i]);
                                    break;
                            }
                        }
                        $order->setCod_provider(0);
                        array_push($orders, $order);
                    }
                    fclose($file);
                }else {
                    //read csv/excel 2003 files
                    
                    $ext = substr(strrchr($_FILES["file"]["name"], '.'), 1);
                    if(strcmp($ext, "xls") == 0){
                        //excel 2003
                        $objReader = new PHPExcel_Reader_Excel5();
                        $objReader->setReadDataOnly(true);
                        $objPHPExcel = $objReader->load($targetPath);
                        $objWorksheet = $objPHPExcel->getActiveSheet();
                        $row_count = 0;
                        foreach($objWorksheet->getRowIterator() as $row){
                            if($row_count == 0){
                                $row_count++;
                                continue;
                            }
                            $cellIterator = $row->getCellIterator();
                            $order = new Order();
                            $cellIterator->setIterateOnlyExistingCells(false);
                            $count = 0;
                            foreach($cellIterator as $cell){
                                $count++;
                                switch ($count){
                                    case 1:
                                        $co_number = $cell->getValue();
                                        $l = strlen($co_number);
                                        if($l < 9){
                                            for($i = 0; $i < 9 - $l;$i++){
                                                $co_number = "0".$co_number;
                                            }
                                        }
                                        $co_number = "TWLL".$co_number;
                                        $order->setCo_number($co_number);
                                        break;
                                    case 4:
                                        $order->setR_name($cell->getValue());
                                        break;
                                    case 5:
                                        $order->setR_email($cell->getValue());
                                        break;
                                    case 6:
                                        $order->setR_phonenumber($cell->getValue());
                                        break;
                                    case 7:
                                        $order->setR_address($cell->getValue());
                                        break;
                                    case 8:
                                        $order->setPostal_code($cell->getValue());
                                        break;
                                    case 11:
                                        $order->setCost($cell->getValue());
                                        break;
                                }
                            }
                            array_push($orders, $order);
                        }
                    }  else {
                        //csv files
                        $objReader = new PHPExcel_Reader_CSV();
                        $objReader->setReadDataOnly(true);
                        $objReader->setDelimiter(';');
                        $objReader->setEnclosure('');
                        $objPHPExcel = $objReader->load($targetPath);
                        $objWorksheet = $objPHPExcel->getActiveSheet();
                        $row_count = 0;
                        foreach($objWorksheet->getRowIterator() as $row){
                            if($row_count == 0){
                                $row_count++;
                                continue;
                            }
                            $cellIterator = $row->getCellIterator();
                            $order = new Order();
                            $cellIterator->setIterateOnlyExistingCells(false);
                            $count = 0;
                            foreach($cellIterator as $cell){
                                $count++;
                                switch ($count){
                                    case 1:
                                        
                                        $co_number = $cell->getValue();
                                        $l = strlen($co_number);
                                        if($l < 9){
                                            for($i = 0; $i < 9 - $l;$i++){
                                                $co_number = "0".$co_number;
                                            }
                                        }
                                        $co_number = "TWLL".$co_number;
                                        $order->setCo_number($co_number);
                                        echo $co_number;
                                        break;
                                    case 4:
                                        $order->setR_name($cell->getValue());
                                        break;
                                    case 5:
                                        $order->setR_email($cell->getValue());
                                        break;
                                    case 6:
                                        $order->setR_phonenumber($cell->getValue());
                                        break;
                                    case 7:
                                        $order->setR_address($cell->getValue());
                                        break;
                                    case 8:
                                        $order->setPostal_code($cell->getValue());
                                        break;
                                    case 11:
                                        $order->setCost($cell->getValue());
                                        break;
                                }
                            }
                            array_push($orders, $order);

                        }
                        
                    }
                   
                    
                }
                $db_connection = new OrderDB($db_host, $db_username, $db_password, $db_dbname);
                $count = 0;
                foreach($orders as $o){
                    $result = $db_connection->insert($o);
                    $count++;
                }
                $count--;
                $db_connection->dbClose();
                $result = new Result();
                $result->result = "1";
                
                $result->desc = "Tải lên thành công! Số lượng bản tin: <b>".$count."</b>";
                $response = array("res"=>$result);
                echo json_encode($response);
                //test send request
//                $connection = new SendRequest("http://202.183.215.38/ediwebapi/ediv2/shipment_info");
//                $output = $connection->testSendRequest();
//                echo json_encode($output);
//                echo "test";
            }
        }
    }else{
        $result = new Result();
        $result->result = "0";
        $result->desc = "Invalid file size/Invalid file type:".$_FILES["file"]["type"];
        $response = array("res"=>$result);
        echo json_encode($response);
    }
}else{
    $result = new Result();
    $result->result = "0";
    $result->desc = "Invalid file type";
    $response = array("res"=>$result);
    echo json_encode($response);
}

