<?php
require_once __DIR__.'/../Include/config.php';
require_once __DIR__ .'/../models/Database.php';
require_once __DIR__ .'/../models/EmailTemplateDB.php';
require_once '../Include/functions.php';

sec_session_start();
$db = new Database();
$conn = $db->getConnection();
$isLogin = login_check($conn);
$db->dbClose();
if(!$isLogin){
    $response = array("res" => "Not autherized action!");
    echo json_encode($response);
}else{
    $method = $_SERVER["REQUEST_METHOD"];
    switch ($method){
        case "POST":
            $json = file_get_contents("php://input");
            $oJson = json_decode($json);
            $subject = $oJson->subject == "" ? NULL : $oJson->subject;
            $f_date = empty($oJson->f_date) ? NULL : $oJson->f_date;
            $t_date = empty($oJson->t_date) ? NULL : $oJson->t_date;
            $pageSize = intval($oJson->pageSize);
            $pageIndex = intval($oJson->pageIndex);
            $etdb = new models\EmailTemplateDB();
            $templates = $etdb->getEmailStatistic($subject, $f_date, $t_date, $pageIndex, $pageSize);
            $etdb->dbClose();
            $response = array("res" => $templates);
            echo json_encode($response);
            break;
        case "GET":
            echo "Get method is invalid!";
            break;
    }
}
