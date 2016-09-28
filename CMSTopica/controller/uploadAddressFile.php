<?php

require_once __DIR__ ."/../Include/config.php";
include '../libs/PHPExcel.php';
require_once '../models/Result.php';
require_once '../models/collection/User.php';
require_once '../models/EmailTemplateDB.php';
require_once '../models/SendEmailTaskLogDB.php';
require_once './Utilities.php';

/*Phụ trách việc upload file*/

//error_log($_POST['mail_id']);
date_default_timezone_set("Asia/Ho_Chi_Minh");
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
$maxFileSize = Utilities::max_file_upload_in_bytes();
//save mail data before save mail's address
$etdb = new models\EmailTemplateDB();
$email_id = $etdb->insert($mailSubject, $mailData);

$setldb = new \models\SendEmailTaskLogDB();
if($email_id != 0){
    if(isset($_FILES["file"]["type"])){
        $validExtensions = array("xls", "xlsx", "csv");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if((($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ||
                ($_FILES["file"]["type"] == "application/vnd.ms-excel") ||
                ($_FILES["file"]["type"] == "text/csv")) &&
                ($_FILES["file"]["size"] < $maxFileSize) &&
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
                        $email_total = 0;
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
                            $email_total++;
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
                        $etdb->updateEmailTotal($email_id, $email_total);
                        
                    }elseif($_FILES["file"]["type"] == "text/csv"){
                    }else {
                    }
                    $setldb->dbClose();
                    $count = 0;
                    //
                    $result = new Result();
                    $result->result = "1";
                    $result->desc = "Hẹn giờ gửi thành công!";
                    $response = array("res"=>$result);
                    echo json_encode($response);
                }
            }
        }else{
            $result = new Result();
            $result->result = "0";
            $result->desc = "Sai loại tập tin/vượt quá giới hạn dung lượng: (ext)-".$file_extension."-(size)-".$_FILES["file"]["size"];
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
$etdb->dbClose();


