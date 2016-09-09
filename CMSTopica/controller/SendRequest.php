<?php

require_once '../models/Order.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendRequest
 *
 * @author duc
 */
class SendRequest {
    
    private $connection;
    private $uri;
    
    Function __construct($uri) {
        $this->uri = $uri;
//        $this->createConnection($this->uri);
    }
    
    private Function createConnection($uri){
        $this->connection = curl_init($uri);
        curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, "POST");
        
    }
    
//    http://lornajane.net/posts/2011/posting-json-data-with-php-curl
    public Function sendShipment_Info($request){
        $this->createConnection($this->uri);
        $sRequest = json_encode($request);
        curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $sRequest);
//        http://stackoverflow.com/questions/5514139/why-does-curl-always-return-a-status-code
        curl_setopt($this->connection,  CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($this->connection, CURLOPT_HTTPHEADER, array(
//            "Content-Type: application/json",
//            "Content-Length:".  strlen($sRequest),
//            "app_id:TOPE",
//            "app_key:vvti23-409-kkoicg62-6689",
//            "user_id: TOPE",
//            "password: T@PE1234"
//        ));
        curl_setopt($this->connection, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Content-Length:".  strlen($sRequest),
            "app_id:TOPE",
            "app_key:cfc885swp-22ac-wkj908abs",
            "user_id: TOPE",
            "password: T@PE1234"
        ));
        $result = curl_exec($this->connection);
        $this->closeConnection();
        return $result;
    }
    
    private Function closeConnection(){
        curl_close($this->connection);
    }
    
    
    /**
     * Only for unit test
     * For Kerry
     * @return type
     */
    public Function testSendRequest(){
        $shipment = new shipment();
        $shipment->con_no = "TOPE0001002";
        $shipment->s_name = "Top English (Thailand) Co, Ltd.";
        $shipment->s_address = "2 Ploenchit Center, G Floor, Room 21";
        $shipment->s_village = "";
        $shipment->s_soi = "";
        $shipment->s_road = "Sukhumvit Road";
        $shipment->s_subdistrict = "Klongtoey";
        $shipment->s_district = "Klongtoey";
        $shipment->s_province = "BangKok";
        $shipment->s_zipcode = "10110";
        $shipment->s_mobile1 = "023056673";
        $shipment->s_mobile2 = "";
        $shipment->s_telephone = "023056673";
        $shipment->s_email = "";
        $shipment->s_contactperson = "Karounyakorn Korntal";
        $shipment->r_name = "Karounyakorn Korntal";
        $shipment->r_address = "541 ถ.พหลโยธิน ต.ปากเพรียว,เมืองสระบุรี,สระบุรี";
        $shipment->r_village = "";
        $shipment->r_soi = "";
        $shipment->r_road = "";
        $shipment->r_subdistrict = "POMPRAB/SUMP";
        $shipment->r_district = "เมืองสระบุรี";
        $shipment->r_province = "สระบุรี";
        $shipment->r_zipcode = "10400";
        $shipment->r_mobile1 = "0849034538";
        $shipment->r_mobile2 ="";
        $shipment->r_telephone = "";
        $shipment->r_email = "pootragool10@gmail.com";
        $shipment->r_contactperson = "ภาคภูมิ  ภูตระกูล";
        $shipment->special_note = "đồng ý mua, Tài khoản từ email này đã được user tạo trước đó";
        $shipment->service_code = "ND";
        $shipment->cod_amount = "499.0";
        $shipment->cod_type = "CASH";
        $shipment->tot_pkg ="1";
        $shipment->declare_value = "0.0";
        $shipment->ref_no = "573c5db0577cca6b7f00001d";
        $shipment->action_code = "A";
        $output = array("shipment" => $shipment);
        $result = ["req" => $output];
        $response = $this->sendShipment_Info($result);
        return $response;
    }
}
