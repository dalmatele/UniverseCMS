<?php
require_once "../Include/config.php";
require_once '../models/OrderDB.php';
require_once '../models/Order.php';
require_once './SendRequest.php';

$connection = new OrderDB();
//find unsent orders;
$query = $connection->unsentOrder();
$query->bind_result($id, $r_name, $r_email, $r_address, $r_phonenumber, $co_number, $postal_code,$cost, $district, $province);
$orders = array();
while($query->fetch()){
    $order = new shipment();
    $order->id = $id;
    $order->r_name = $r_name;
    $order->r_email = $r_email;
    $order->r_address = $r_address;
    $order->r_mobile1 = $r_phonenumber;
    $order->con_no = $co_number;
    $order->r_zipcode = $postal_code;
    $order->r_district = $district;
    $order->r_subdistrict = $district;
    $order->r_province = $province;
    $order->r_contactperson = $r_name;
    $order->service_code = "ND";
    $order->cod_amount = $cost;
    $order->cod_type = "CASH";
    //sender's info
    $order->s_name = "Top English (Thailand) Co, Ltd.";
    $order->s_address = "2 Ploenchit Center, G Floor, Room 21";
    $order->s_village = "";
    $order->s_soi = "";
    $order->s_road = "Sukhumvit Road";
    $order->s_subdistrict = "Klongtoey";
    $order->s_district = "Klongtoey";
    $order->s_province = "BangKok";
    $order->s_zipcode = "10110";
    $order->s_mobile1 = "023056673";
    $order->s_mobile2 = "";
    $order->s_telephone = "023056673";
    $order->s_email = "";
    $order->s_contactperson = "Karounyakorn Korntal";
    $order->action_code = "A";
    $order->tot_pkg ="1";
    
    
    //optional argument
    $order->r_village = "";
    $order->r_soi = "";
    $order->r_mobile2 = "";
    $order->r_telephone = "";
    $order->special_note = "";
    $order->declare_value = "";
    $order->ref_no = "";
    $order->r_road = "";
    $order->declare_value = "0.0";
    array_push($orders, $order);
}
//$conn = new SendRequest("http://202.183.215.38/ediwebapi/ediv2/shipment_info");
$conn = new SendRequest("http://th.rnd.kerryexpress.com/EDIWebAPI/SmartEDI/shipment_info");
foreach($orders as $o){
    $output = array("shipment" => $o);
    $req = ["req" => $output];
    $result = $conn->sendShipment_Info($req);
    $oResult = json_decode($result);
    $oRes = $oResult->res;
    $oShipment = $oRes->shipment;
    $status = $oShipment->status_code;
    $dbResult = $connection->updateStatusCode($status, $o->id);
    echo "Sent Order:".$o->con_no."<br\>";
}
$connection->dbClose();

//$output = $conn->testSendRequest();


