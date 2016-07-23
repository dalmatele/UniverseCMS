<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductDB
 *
 * @author duc
 */
class ProductDB {
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
    
    public function addNewProduct($packageId, $importValue, $exportValue, $description, $isAccept, $exportDate, $imagePath, $productName){
        $query = $this->connection->prepare("INSERT INTO product (PackageId, ImportValue, ExportValue, Description, IsAccept, ExportDate, ImagePath, ProductName) VALUES (?,?,?,?,?,?,?,?)");
        $query->bind_param("iiisisss",$packageId,$importValue,$exportValue,$description,$isAccept, $exportDate, $imagePath, $productName);
        $result = $query->execute();
        if(!$result){
            return -1;
        }else{
            return $query->insert_id;
        }
    }
    
    /**
     * Tìm các sản phẩm nằm trong gói sản phẩm
     * @param type $packageId id của gói tin tương ứng
     */
    public function getProducts($packageId){
        $squery = "SELECT ID,ImportValue,ExportValue,Description, IsAccept, ExportDate, ImagePath, ProductName From product WHERE PackageId = ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("i", $packageId);
        $query->execute();
        $query->bind_result($rid, $rImportValue, $rExportValue, $rDescription, $rIsAccept, $rExportDate, $rImagePath, $rProductName);
        $products = array();
        while($query->fetch()){
            $product = new ProductObject();
            $product->id = $rid;
            $product->importValue = $rImportValue;
            $product->exportValue = $rExportValue;
            $product->description = $rDescription;
            $product->isAccept = $rIsAccept;
            $product->exportDate = $rExportDate;
            $product->productName = $rProductName;
            $product->imagePath = $rImagePath;
            $products[] = $product;
        }
        return $products;
    }
    
    /**
     * Xóa toàn bộ các product thuộc một package chỉ định
     * @param type $PackageId
     */
    public function delAllProducts($packageId){
        $squery = "DELETE from product WHERE PackageId = ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("i", $packageId);
        $query->execute();
        return $query->affected_rows;
    }   
}

class ProductObject{
    public $id;
    public $importValue;
    public $exportValue;
    public $description;
    public $isAccept;
    public $exportDate;
    public $imagePath;
    public $productName;
}
