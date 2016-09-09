<?php

require_once "../Include/config.php";
include '../libs/PHPExcel.php';
require_once '../models/Result.php';
require_once '../models/collection/User.php';

/*Phụ trách việc upload file*/

$json = file_get_contents("php://input");
error_log($json);
$oJson = json_decode($json);
error_log("b");
error_log($_POST['mail_id']);

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
                        for($i = 0; $i < $size; $i++){
                            switch ($i){
                                case 0:
//                                    $user->setUser_email($data[$i]);
                                    array_push($addresses, $data[$i]);
                                    break;
                            }
                        }
                        $row_count++;
                        $count++;
                        if($count == 200){
                            $count = 0;
                            //insert it to database
                            $addresses = array();
                        }
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
                $count = 0;
                //
                $result = new Result();
                $result->result = "1";
                $result->desc = "Tải lên thành công! Số lượng bản tin: <b>".$count."</b>";
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

