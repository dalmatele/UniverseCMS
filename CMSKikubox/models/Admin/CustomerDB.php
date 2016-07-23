<?php


/**
 * Quản lý bảng Customer
 *
 * @author duc
 */
class CustomerDB {
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
     * Thêm mới thông tin khách hàng
     * @param type $name
     * @param type $address
     * @param type $phonenumber
     * @param type $email
     * @param type $sex
     * @param type $birthday
     */
    public function addNewCustomer($name, $address, $phonenumber, $email, $sex, $birthday){
        $query = $this->connection->prepare("INSERT INTO Customer (CustomerName,Address,PhoneNumber,Email,Sex,Birthday) VALUES "
                ."(?,?,?,?,?,?)");
        $query->bind_param("ssssis", $name, $address, $phonenumber, $email, $sex, $birthday);
        $result = $query->execute();
        if(!$result){
            return -1;
        }else{
            return $query->insert_id;//trả về id của row vừa được tạo.
        }
    }
    
    /**
     * 
     * @param type $id id của khách hàng
     * @return \CustomerObject
     */
    public function getCustomer($id){
        $squery = "SELECT ID, CustomerName, Address, PhoneNumber, Email, Sex, Birthday from customer WHERE ID = ?";
        $query = $this->connection->prepare($squery);
        $query->bind_param("i", $id);
        $query->execute();
        $query->bind_result($rid, $rname, $raddress, $rphone, $remail, $rsex, $rbirthday);
        $customer = new CustomerObject();
        while($query->fetch()){
            $customer->id = $rid;
            $customer->name = $rname;
            $customer->phone = $rphone;
            $customer->email = $remail;
            $customer->address = $raddress;
            $customer->sex = $rsex;
            $customer->birthday = $rbirthday;
        }
        
        return $customer;
    }
    
    public function searchCustomer($name, $phone, $email, $pageSize, $pageIndex){
        $squery = "SELECT ID,CustomerName,PhoneNumber,Email FROM customer WHERE "
                ."CustomerName like IFNULL(?,CustomerName) "
                ."AND PhoneNumber like IFNULL(?,PhoneNumber) "
                ."AND Email like IFNULL(?, Email)"
                ."LIMIT ?, ?";
        $query = $this->connection->prepare($squery);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $pname = "%".$name."%";
        $pphone = "%".$phone."%";
        $pemail = "%".$email."%";
        $query->bind_param("sssii", $pname, $pphone , $pemail, $pageIndex, $pageSize);
        if(!$query){
            error_log(mysqli_error($this->connection), 0);
        }
        $query->execute();
        $query->bind_result($rid, $rname, $rphone, $remail);
        $customers = array();
        while($query->fetch()){
            $customer = new CustomerObject();
            $customer->id = $rid;
            $customer->name = $rname;
            $customer->phone = $rphone;
            $customer->email = $remail;
            $customers[] = $customer;
        }
        return $customers;
    }
    
    /**
     * Cập nhật thông tin khách hàng
     * @param type $name
     * @param type $address
     * @param type $phone
     * @param type $email
     * @param type $sex
     * @param type $birthday
     * @param type $id
     * @return int
     */
    public function update($name, $address, $phone, $email, $sex, $birthday, $id){
        $squery = "UPDATE customer SET "
            ."CustomerName = ? "
            ."Address = ? "
            ."PhoneNumber = ? "
            ."Email = ? "
            ."Sex = ? "
            ."Birthday = ? "
            ."WHERE ID = ?";
        $query->bind_param("ssssisi",$name, $address, $phone, $email, $sex, $birthday, $id);
        $result = $query->execute();
        if(!$result){
            return 1;
        }else{
            return 0;
        }
    }
}

class CustomerObject{
    public $id;
    public $name;
    public $phone;
    public $email;
    public $address;
    public $sex;
    public $birthday;
}
