<?php


require_once __DIR__ ."/../Include/config.php";

/**
 * Description of Utilities
 *
 * @author duc
 */
class Utilities {
    //put your code here
    
    /**
     * Not have T character
     * @param type $strDate
     * @return type
     */
    public static function standardDatetime1($strDate){
        $dates = explode(" ", $strDate);
        $strDate = $dates[0];
        $dates = explode("/", $strDate);
        return $dates[2]."-".$dates[0]."-".$dates[1];
    }
    
    public static function standardDatetime2($strDate){
        $dates = explode("T", $strDate);
        return $dates[0];
    }
    
    /**
     * auto generate filename
     * @return array
     */
    public static function generateFileName(){
        $date = new DateTime();
        $fileName = $date->getTimestamp().".xlsx";
        $file_upload_path = dirname($_SERVER["SCRIPT_FILENAME"])."/../upload/";
        $sourcePath = $file_upload_path.$fileName;// Target path where file is to be stored
        $fileNames = explode(".", $fileName);
        $fileName = $fileNames[0];
        $result = array();
        array_push($result, $sourcePath);
        array_push($result, $fileName);
        return $result;
    }
    
    public static function generateMonthlyFileName(){
        $date = new DateTime();
        $fileName = $date->getTimestamp().".xlsx";
        $file_upload_path = dirname($_SERVER["SCRIPT_FILENAME"])."/../upload/monthly_report/";
        $sourcePath = $file_upload_path.$fileName;// Target path where file is to be stored
        $fileNames = explode(".", $fileName);
        $fileName = $fileNames[0];
        $result = array();
        array_push($result, $sourcePath);
        array_push($result, $fileName);
        return $result;
    }
    
    private static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) 
        {
            case 'g':
            $val *= 1024;
            case 'm':
            $val *= 1024;
            case 'k':
            $val *= 1024;
        }
        return $val;
    }

    public static function max_file_upload_in_bytes() {
        //select maximum upload size
        $max_upload = Utilities::return_bytes(ini_get('upload_max_filesize'));
        //select post limit
        $max_post = Utilities::return_bytes(ini_get('post_max_size'));
        //select memory limit
        $memory_limit = Utilities::return_bytes(ini_get('memory_limit'));
        // return the smallest of them, this defines the real limit
        return min($max_upload, $max_post, $memory_limit);
    }
}
