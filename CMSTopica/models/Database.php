<?php

/**
 * Description of Database
 *
 * @author duc
 */
class Database {
//    public $connection;
//    private $db_host;
//    private $db_username;
//    private $db_password;
//    private $db_dbname;
    
    Function __construct() {
//        $this->db_username = $username;
//        $this->db_host = $host;
//        $this->db_password = $password;
//        $this->db_dbname = $dbname;
        $this->dbConnect();
    }
    
    /*
     * Ket noi toi co so du lieu
     */
    private Function dbConnect(){
        
//        $this->connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_dbname);
        $this->connection = new mysqli(DBHOST, DBUSER, DBPASSWORD, DATABASE);
        if($this->connection->connect_errno){
            die("Could not connect to database server");
        }
        $this->connection->set_charset("utf8");
    }
    
    public function getConnection(){
        return $this->connection;
    }
    
    public Function dbClose(){
        $this->connection->close();
    }
    
    //http://code.tutsplus.com/tutorials/the-problem-with-phps-prepared-statements--net-13661
    /**
     * 
     * @param type $inputArray
     * @param type $query
     * @return type
     */
    protected function generateResult($query){
        $dataArray = array();
        $meta = $query->result_metadata();
        while ($field = $meta->fetch_field()){
            $params[] = &$row[$field->name];
        } 
        call_user_func_array(array($query, 'bind_result'), $params);
        while ($query->fetch()) {
            foreach($row as $key => $val){
                $c[$key] = $val;
            }
            $dataArray[] = $c;
        }
        $query->close();
        return $dataArray;
    }
}
