<?php

require_once './config.php';
require_once '../models/Database.php';
require_once './functions.php';

sec_session_start();

if(isset($_POST["email"], $_POST["p"])){
    $email = $_POST["email"];
    $password = $_POST["p"];
    if(login($email, $password) == true){
        //session has values
        header("Location: ../view/home.php");
        session_write_close();
        exit;
    }else{
        header("Location: ../index.php?error=1");
    }
}else{
    echo "Invalid Request";
}

