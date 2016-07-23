<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChartDB
 *
 * @author duc
 */
class ChartDB {
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
    
    
    public Function getPieChartData($fDate, $tDate){
        error_log($fDate);
        $squery = "SELECT Sum(Product.ImportValue) as iv , Sum(Product.ExportValue) as ev FROM Product "
            ."LEFT JOIN Package ON Product.PackageId = Package.ID "
            ."WHERE str_to_date(`ImportDate`,'%Y-%m-%d') >= ? "
            ."AND str_to_date(`ImportDate`,'%Y-%m-%d') <= ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("ss", $fDate, $tDate);
        $query->execute();
        $query->bind_result($iv, $ev);
        $output = array();
        while($query->fetch()){
            $output[] = $iv;
            $output[] = $ev;
        }
        return $output;
    }
}
