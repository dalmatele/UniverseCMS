<?php

/**
 * Quản lý tập tin ảnh trong hệ thống
 *
 * @author duc
 */
class FileManage {
    
    private $srcDirectory;
    private $desDirectory;
    
    function __construct(){
        $this->desDirectory = dirname(__FILE__).'\\..\\..\\upload\\';
        $this->srcDirectory = dirname(__FILE__).'\\..\\..\\temp\\';
    }
    
    /**
     * Chuyển tập tin đã upload sang thư mục khác
     * 
     * @param type $srcName
     * @param type $desName
     * @return boolean true - nếu move thành công
     */
    public function moveFile($srcName, $desName){
        $_srcName = $this->srcDirectory.$srcName;
        $_desName = $this->desDirectory.$desName;
        error_log($_srcName."###".$_desName, 0);
        $result = rename($_srcName, $_desName);
        return $result;
    }
    
    /**
     * Đọc tập tin chỉ định
     * @param type $fileName
     * @return base64 String
     */
    public function readFile($fileName){
        $type = pathinfo($fileName, PATHINFO_EXTENSION);
        if(!file_exists($this->desDirectory.$fileName)){
            return "0";
        }
        $data = file_get_contents($this->desDirectory.$fileName);
        $base64 = 'data:image/' . $type .';charset=utf-8'. ';base64,' . base64_encode($data);
        return $base64;
    }
    
    
}
