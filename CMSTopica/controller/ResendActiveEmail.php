<?php

require_once __DIR__.'/../Include/config.php';
require_once __DIR__ .'/../models/Database.php';
require_once __DIR____.'/../models/MemberDB.php';
require_once '../Include/functions.php';

$method = $_SERVER["REQUEST_METHOD"];
switch ($method){
    case "POST":
        echo "Post method is invalide";
        break;
    case "GET":
        $email = htmlspecialchars($_GET["email"]);
        $username = htmlspecialchars($_GET["username"]);
        $timestamp = time()*1000;
        $preauthToken = hash_hmac("sha1", $email."|".$username."|".$timestamp, PREAUTH_KEY);
        $mdb = new MemberDB();
        $mdb->updateActiveCode($preauthToken, $email);
        //send email
        break;
}