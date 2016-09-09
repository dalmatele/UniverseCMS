<?php
//http://www.melonfire.com/community/columns/trog/article.php?id=244&page=4
//https://books.google.co.th/books?id=rg9Si_yksiAC&pg=PT187&lpg=PT187&dq=XML_SERIALIZER_OPTION_SCALAR_AS_ATTRIBUTES&source=bl&ots=vPVTZy4Dp2&sig=YTMGHwZsQqBSYxsu1pZZKAVVCkI&hl=en&sa=X&ved=0ahUKEwjt1aDLpt7OAhXGp48KHezKDgcQ6AEIPzAG#v=onepage&q=XML_SERIALIZER_OPTION_SCALAR_AS_ATTRIBUTES&f=false
//http://paulryan.com.au/2014/spo-remote-authentication-rest/
//https://macfoo.wordpress.com/2012/06/23/how-to-log-into-office365-or-sharepoint-online-using-php/
require_once '../libs/XML/Serializer.php';
require_once '../libs/SharePoint/ClientContext.php';
require_once '../libs/Runtime/Auth/AuthenticationContext.php';

use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\SharePoint\ListCreationInformation;
use Office365\PHP\Client\SharePoint\SPList;


/**
 * Description of SharepointRequest
 *
 * @author duc
 */
class SharepointRequest {
    
    private $connection;
    private $username;
    private $password;
    private $url;
    private $listItem;
    private $beginId;
    private $expand = "";
    
    
    public function __construct($username, $password, $url) {
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
        $this->authCtx = new \Office365\PHP\Client\Runtime\Auth\AuthenticationContext($this->url);
        $this->authCtx->acquireTokenForUser($this->username, $this->password);
        $this->ctx = new ClientContext($this->url,$this->authCtx);
        $this->web = $this->ctx->getWeb();
    }
    
    function setListItem($listItem) {
        $this->listItem = $listItem;
    }
    
    function setBeginId($beginId) {
        $this->beginId = $beginId;
    }
    
    function getExpand() {
        return $this->expand;
    }

    function setExpand($expand) {
        $this->expand = $expand;
    }

    
    
        
    /**
     * Get security token from sharepoint
     * @return string
     */
    private function getSecurityToken(){
        $xml = array ( 
            "s:Header" => array(
                "a:Action" => array(
                    "content" => "http://schemas.xmlsoap.org/ws/2005/02/trust/RST/Issue",
                    "s:mustUnderstand" => "1"
                    ),
                "a:ReplyTo" => array(
                    "a:Address" => "http://www.w3.org/2005/08/addressing/anonymous"
                ),
                "a:To" => array(
                    "content" => "https://login.microsoftonline.com/extSTS.srf",
                    "s:mustUnderstand" => "1"
                ),
                "o:Security" => array(
                    "s:mustUnderstand" => "1",
                    "xmlns:o" => "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
                    "o:UsernameToken" => array(
                        "o:Username" => "minhnv@edumallinternational.onmicrosoft.com",
                        "o:Password" => "qsysopr@16"
                    )
                )
            ),
            "s:Body" => array(
                "t:RequestSecurityToken" => array(
                    "xmlns:t" => "http://schemas.xmlsoap.org/ws/2005/02/trust",
                    "wsp:AppliesTo" => array(
                        "xmlns:wsp" => "http://schemas.xmlsoap.org/ws/2004/09/policy",
                        "a:EndpointReference" => array(
                            "a:Address" => "http://edumallinternational.sharepoint.com/"
                        )
                    ),
                    "t:KeyType" => "http://schemas.xmlsoap.org/ws/2005/05/identity/NoProofKey",
                    "t:RequestType" => "http://schemas.xmlsoap.org/ws/2005/02/trust/Issue",
                    "t:TokenType" => "urn:oasis:names:tc:SAML:1.0:assertion"
                )
            )
        );
        
        $serializer = new XML_Serializer();
        $serializer->setOption(
                XML_SERIALIZER_OPTION_SCALAR_AS_ATTRIBUTES, array(
                    "a:Action" => array(
                        "s:mustUnderstand"
                    ),
                    "a:To" => array(
                        "s:mustUnderstand"
                    ),
                    "o:Security" => array(
                        "s:mustUnderstand",
                        "xmlns:o"
                    ),
                    "t:RequestSecurityToken" => array(
                        "xmlns:t"
                    ),
                    "wsp:AppliesTo" => array(
                        "xmlns:wsp"
                    )
                )
          );
        $serializer->setOption("addDecl", true);
        $serializer->setOption("rootName", "s:Envelope");
        $serializer->setOption("rootAttributes", array(
            "xmlns:s" => "http://www.w3.org/2003/05/soap-envelope",
            "xmlns:a" => "http://www.w3.org/2005/08/addressing",
            "xmlns:u" => "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"
        ));
        $result = $serializer->serialize($xml);
        
        if($result === true){
            $xmlOutput = $serializer->getSerializedData();
            //This a trick, we replace all <content> tag to value of element
            $xmlOutput = str_replace("<content>", "", $xmlOutput);
            $xmlOutput = str_replace("</content>", "", $xmlOutput);
            $xmlOutput = str_replace("</content>", "", $xmlOutput);
            $xmlOutput = str_replace("<?xml version=\"1.0\"?>", "", $xmlOutput);
            $this->connection = curl_init("https://login.microsoftonline.com/extSTS.srf");
            curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->connection,CURLOPT_SSL_VERIFYPEER, false);//<-- this to make connect to https?
            curl_setopt($this->connection, CURLOPT_POSTFIELDS, $xmlOutput);
    //        http://stackoverflow.com/questions/5514139/why-does-curl-always-return-a-status-code
            curl_setopt($this->connection,CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($this->connection);
            if($result === false){
                return curl_errno($this->connection);
            }
            
            //only fetch error before close connection
            curl_close($this->connection);
            $rxml = new DOMDocument();
            $rxml->loadXML($result);
            $xpath = new DOMXPath($rxml);
            $nodelist = $xpath->query("//wsse:BinarySecurityToken");
            foreach($nodelist as $n){
                return $n->nodeValue;
            }
        }else{
            return "1";
        }
        
    }
    
    private function getAccessToken($token){
        $this->connection = curl_init("https://edumallinternational.sharepoint.com/_forms/default.aspx?wa=wsignin1.0");
        curl_setopt($this->connection, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->connection,CURLOPT_SSL_VERIFYPEER, false);//<-- this to make connect to https?
        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $token);
//        http://stackoverflow.com/questions/5514139/why-does-curl-always-return-a-status-code
        curl_setopt($this->connection,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->connection, CURLOPT_HEADER, true); 
        $result = curl_exec($this->connection);
        if($result === false){
            return curl_errno($this->connection);
        }
        curl_close($this->connection);
//        return $result;
        return $this->getCookieValue($result);
    }
    
    private $authCtx;
    private $ctx;
    private $web;
    
    /**
     * Not a really good name but it for data all other data from sharepoinr
     */
    public function otherDataMining(){
        try{
            $list = $this->web->getLists()->getByTitle($this->listItem);
            if(empty($this->expand)){
                //not have expand
                $items = $list->getItems()
                        ->top(500);
                $this->ctx->load($items);
                $this->ctx->executeQuery();
                return $items->getData();
            }else{
                $items = $list->getItems()
                        ->expand($this->expand)
                        ->top(500);
                $this->ctx->load($items);
                $this->ctx->executeQuery();
                return $items->getData();
            }
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * https://blog.vgrem.com/2014/05/31/sharepoint-online-client-php/
     * https://github.com/vgrem/phpSPO/blob/master/examples/listitem_examples.php
     * Get data from sharepoint server
     */
    public function dataMining(){
        try{
            $list = $this->web->getLists()->getByTitle($this->listItem);
            $filter = "Id gt ".  $this->beginId;
            $items = $list->getItems()
                    ->select("BuyerName,BuyerTitle,BuyerAddress,BuyerMobile,TrackingCodeTitle,"
                            . "BuyerPostalCode,Amount,ServiceCode/ServiceCode,Advisor/Title,CourseCode/CourseCode")
                    ->expand("ServiceCode,Advisor,CourseCode")
                    ->filter($filter)
                    ->top(100);
//            error_log($items->getResourceUrl());
            $this->ctx->load($items);
            $this->ctx->executeQuery();
            return $items->getData();
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function podMining() {
        try{
            
            $list = $this->web->getLists()->getByTitle($this->listItem);
            $filter = "Id gt ".  $this->beginId;
            $items = $list->getItems()
                    ->select("consignment,ref_no,booking_no,booking_datetime,act_pickup_datetime,act_delivery_datetime,recipient_zipcode,origin_station,destination_station,service_code,route_code,cod_amount,tot_pkg,chargeable_wt,remark,status_code,tracking_datetime,destination_state_code,payerid,exception_code,person_incharge,est_delivery_datetime,custid,cust_name,recipient_name,recipient_address1,recipient_address2,state_name,tot_dim_wt,origin_state_code,tot_act_wt")
                    ->filter($filter)
                    ->top(100);
            $this->ctx->load($items);
            $this->ctx->executeQuery();
            return $items->getData();
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * Get the cookie value from the http header
     *
     * @param string $header
     * @return array 
     */
    private function getCookieValue($header)
    {
        $authCookies = array();
        $header_array = explode("\r\n",$header);
        foreach($header_array as $header) {
            $loop = explode(":",$header);
            if($loop[0] == 'Set-Cookie') {
                $authCookies[] = $loop[1];
            }
        }
//        unset($authCookies[0]); // No need for first cookie
        return array_values($authCookies);
    }
}

