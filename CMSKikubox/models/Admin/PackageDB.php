<?php
/**
 * Quan ly viec truy cap vao bang Package
 *
 * @author duc
 */
class PackageDB {
    Function __construct($host, $username, $password, $dbname){
        $this->db_username = $username;
        $this->db_host = $host;
        $this->db_password = $password;
        $this->db_dbname = $dbname;
        $this->dbConnect();
    }
    
    private $connection;
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_dbname;
    private $db;
    /*
     * Ket noi toi co so du lieu
     */
    private Function dbConnect(){
        $this->connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_dbname);
        if($this->connection->connect_errno){
            die("Could not connect to database server");
        }
        $this->connection->set_charset("utf8");
    }
    
    public Function dbClose(){
        $this->connection->close();
    }
    
    /**
     * Xóa gói tin
     * @param string $packageCode
     * @return int mã id gói hàng đã bị xóa.
     */
    public function deletePackage($packageCode){
        $squery = "SELECT ID From package WHERE Code = ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("s", $packageCode);
        $result = $query->execute();
        $query->bind_result($rid);
        $id = 0;
        while($query->fetch()){
            $id = $rid;
            break;
        }
        $this->dbClose();
        $this->dbConnect();
        $squery = "DELETE from package WHERE ID = ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("i", $id);
        $query->execute();
        return $id;
    }
    
    /**
     * Lấy thông tin một gói hàng
     * @param type $code mã gói hàng
     * @return \PackageObject
     */
    public function getPackage($code){
        $squery = "SELECT ID,Code,PackageDesc,ImportDate,CustomId From package WHERE Code = ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("s", $code);
        $query->execute();
        $query->bind_result($rid, $rCode, $rDesc, $rImportDate, $rcustomId);
        $package = new PackageObject();
        while($query->fetch()){
            $package->Id = $rid;
            $package->Code = $rCode;
            $package->Desc = $rDesc;
            $package->ImportDate = $rImportDate;
            $package->CustomId = $rcustomId;
        }
        return $package;
    }
    
    /**
     * Kiểm tra xem mã gói hàng đã có hay chưa
     * @param type $code
     */
    public function codeIsExist($code){
        //Package -- remote server
        $query = $this->connection->prepare("SELECT ID FROM package WHERE Code = ?");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $query->bind_param("s", $code);
        $query->execute();
        $query->store_result();
        if($query->num_rows > 0){
            return true;
        }
        return false;
    }
    
    public function addNewPackage($packageCode, $importDate, $customerId, $packageDesc){
        $query = $this->connection->prepare("INSERT INTO package (Code,CustomID,Status,ImportDate,ExportDate,PackageDesc) VALUES (?,?,?,?,?,?)");
        if(!$query){
            die("Error prepare query. ".mysqli_error($this->connection));
        }
        $status = 0;
        $exportDate = "";
        $query->bind_param("siisss",$packageCode,$customerId,$status,$importDate,$exportDate,$packageDesc);
        $result = $query->execute();
        if(!$result){
            error_log(mysqli_error($this->connection), 0);
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    public function  updatePackage($code,$customId,$status,$importDate,$exportDate,$packageDesc, $id){
        $squery = "UPDATE package SET CustomID=?,Status=?,ImportDate=?,ExportDate=?,PackageDesc=?, Code=? WHERE ID=?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log("ducla".mysqli_error($this->connection), 0);
        }
        $query->bind_param("iissssi",$customId,$status,$importDate,$exportDate,$packageDesc,$code, $id);
        $result = $query->execute();
        if(!$result){
            return 1;
        }else{
            return 0;
        }
    }
    
    /**
     * Tìm kiếm các gói sản phẩm, có hỗ trợ phân trang
     * @link http://www.onlinetuting.com/pagination-in-php-mysqli/
     * @link http://stackoverflow.com/questions/13474207/sql-query-if-parameter-is-null-select-all
     * @param type $code
     * @param type $status
     * @param type $importDateF
     * @param type $importDateT
     */
    public function search($code, $status, $importDateF, $importDateT, $pageSize, $pageIndex){
        $squery = "SELECT package.Code,package.PackageDesc,package.ImportDate,package.Status,SUM(product.ExportValue) FROM package "
                ."LEFT JOIN product ON package.ID = product.PackageId "
                ."WHERE "
                ."Code like IFNULL(?,Code) "
                ."AND Status = IFNULL(?,Status) "
                ."AND str_to_date(`ImportDate`,'%Y-%m-%d') >= IFNULL(?,str_to_date(`ImportDate`,'%Y-%m-%d')) "
                ."AND str_to_date(`ImportDate`,'%Y-%m-%d') <= IFNULL(?,str_to_date(`ImportDate`,'%Y-%m-%d')) "
                ."GROUP BY package.Code "
                ."LIMIT ?, ?";
        $query = $this->connection->prepare($squery);
        $pcode = "%".$code."%";
        $query->bind_param("sissii", $pcode, $status, $importDateF, $importDateT, $pageIndex, $pageSize);
        $query->execute();
        $query->bind_result($rCode, $rDesc, $rImportDate, $rStatus, $rCost);
        $packages = array();
        while($query->fetch()){
            $package = new PackageObject();
            $package->Code = $rCode;
            $package->Desc = $rDesc;
            $package->ImportDate = $rImportDate;
            $package->Status = $rStatus;
            $package->Cost = $rCost;
            $packages[] = $package;
        }
        return $packages;
    }
    
    /**
     * Tìm kiếm các gói sản phẩm
     * @link http://stackoverflow.com/questions/13474207/sql-query-if-parameter-is-null-select-all
     * @param type $code
     * @param type $status
     * @param type $importDateF
     * @param type $importDateT
     */
    public function searchF($code, $status, $importDateF, $importDateT, $pageSize, $pageIndex){
        $squery = "SELECT package.Code,package.PackageDesc,package.ImportDate,package.Status,SUM(product.ExportValue) FROM package "
                ."LEFT JOIN product ON package.ID = product.PackageId "
                . "WHERE "
                ."Code like IFNULL(?,Code) "
                ."AND Status <> 3 "
                ."AND str_to_date(`ImportDate`,'%Y-%m-%d') >= IFNULL(?,str_to_date(`ImportDate`,'%Y-%m-%d')) "
                ."AND str_to_date(`ImportDate`,'%Y-%m-%d') <= IFNULL(?,str_to_date(`ImportDate`,'%Y-%m-%d')) "
                ."GROUP By package.Code "
                ."LIMIT ?, ?";
        $query = $this->connection->prepare($squery);
        $pcode = "%".$code."%";
        $query->bind_param("sssii", $pcode, $importDateF, $importDateT, $pageIndex, $pageSize);
        $query->execute();
        $query->bind_result($rCode, $rDesc, $rImportDate, $rStatus, $rCost);
        $packages = array();
        while($query->fetch()){
            $package = new PackageObject();
            $package->Code = $rCode;
            $package->Desc = $rDesc;
            $package->ImportDate = $rImportDate;
            $package->Status = $rStatus;
            $package->Cost = $rCost;
            $packages[] = $package;
        }
        return $packages;
    }
}

class PackageObject{
    public $Id;
    public $Code;
    public $Desc;
    public $ImportDate;
    public $Status;
    public $CustomId;
    public $Cost;
}
