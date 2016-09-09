<?php


require_once "../Include/config.php";

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
}
