<?php

require_once "../Include/config.php";
include '../libs/PHPExcel.php';
require_once '../models/Result.php';
require_once '../models/collection/User.php';
require_once '../models/EmailTemplateDB.php';
require_once '../models/SendEmailTaskLogDB.php';

/*Phụ trách việc upload file*/

//error_log($_POST['mail_id']);

$from_id = $_POST['mail_id'];
$mailData = $_POST["mail_data"];
$send_at = $_POST["send_at"];
$mailData = htmlentities($mailData);
$mailSubject = htmlentities($_POST["mail_subject"]);
$mailSubject = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}, $mailSubject);
$mailData = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}, $mailData);

//save mail data before save mail's address
$etdb = new models\EmailTemplateDB();
$email_id = $etdb->insert($mailSubject, $mailData);
$etdb->dbClose();
$setldb = new \models\SendEmailTaskLogDB();
if($email_id != 0){
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
                    if($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                        //excel 2007 and more
                        $objReader = new PHPExcel_Reader_Excel2007();
                        $objReader->setReadDataOnly(true);
                        $objPHPExcel = $objReader->load($targetPath);
                        $objWorksheet = $objPHPExcel->getActiveSheet();
                        $row_count = 0;
                        $addresses = array();
                        $index = 0;
                        foreach($objWorksheet->getRowIterator() as $row){
                            $email = "";
                            if($row_count == 0){
                                $row_count++;
                                continue;
                            }
                            $index++;
                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(false);
                            $count = 0;
                            foreach($cellIterator as $cell){
                                $count++;
                                switch ($count){
                                    case 1:
                                        $email .= $cell->getValue();
                                        break;
                                    case 2:
                                        if(!empty($cell->getValue())){
                                            $email .= "|".$cell->getValue();
                                        }else{
                                            $email .= "|".$email;
                                        }
                                        break;
                                }
                            }
                            array_push($addresses, $email);
                            if($index == 200){
                                $index = 0;
                                //insert it to database
                                $send_to = implode(",", $addresses);
                                $setldb->insert($from_id, $send_to, $email_id, $send_at);
                                //empty array for a new begin
                                $addresses = array();
                            }
                        }
                        //we try to save the last data
                        $send_to = implode(",", $addresses);
                        $setldb->insert($from_id, $send_to, $email_id, $send_at);
                        $addresses = array();
                        
                    }elseif($_FILES["file"]["type"] == "text/csv"){
                        //normal csv file
                        $file = fopen($targetPath,"r");
                        $row_count = 0;
                        $count = 0;
                        $addresses = array();
                        while(!feof($file)){
                            $data = fgetcsv($file);//an array data
                            if($row_count == 0){
                                $row_count++;
                                continue;
                            }
                            $user = new models\collection\User();
    //                        http://www.w3schools.com/php/func_filesystem_fgetcsv.asp
                            $size = count($data);
                            $email = "";
                            for($i = 0; $i < $size; $i++){
                                
                                switch ($i){
                                    case 0:
//                                        array_push($addresses, $data[$i]);
                                        $email .= $data[$i];
                                        break;
                                    case 1:
                                        $email .= "|".$data[$i];
                                }
                            }
                            array_push($addresses, $email);
                            error_log($email);
                            $row_count++;
                            $count++;
                            if($count == 200){
                                $count = 0;
                                //insert it to database
                                $send_to = implode(",", $addresses);
                                $setldb->insert($from_id, $send_to, $email_id, $send_at);
                                //empty array for a new begin
                                $addresses = array();
                            }
                        }
                        //we try to save the last data
                        $send_to = implode(",", $addresses);
                        $setldb->insert($from_id, $send_to, $email_id, $send_at);
                        $addresses = array();
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
                                            error_log($cell->getValue());
                                            break;
                                    }
                                }

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
                                $cellIterator->setIterateOnlyExistingCells(false);
                                $count = 0;

                                foreach($cellIterator as $cell){
                                    $count++;
                                    switch ($count){
                                        case 1:
                                            error_log($cell->getValue());
                                            break;
                                    }
                                }

                            }

                        }
                    }
                    $setldb->dbClose();
                    $count = 0;
                    //
                    $result = new Result();
                    $result->result = "1";
                    $result->desc = "Tải lên thành công!";
                    $response = array("res"=>$result);
                    echo json_encode($response);
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
}else{
    //Can not process data
    $response = array("res" => "Can not process data");
    echo json_encode($response);
}


