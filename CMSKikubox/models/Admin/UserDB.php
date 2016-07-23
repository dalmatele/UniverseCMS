<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Quan ly bang User
 *
 * @author duc
 */
class UserDB {
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
    
    public Function getUser($username){
        $query = $this->connection->prepare("SELECT Password FROM user WHERE Username = ? and Status = 1");
        $query->bind_param("s",$username);
        $query->execute();
        //http://stackoverflow.com/questions/18753262/example-of-how-to-use-bind-result-vs-get-result
        $query->bind_result($password);
        if(!$query){
            die("Could not get data from database. ".mysql_error());
        }
        while($query->fetch()){
            return $password;
        }
    }
}
