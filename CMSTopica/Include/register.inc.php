<?php

require_once 'models/Database.php';

$error_msg = "";

if(isset($_POST["username"], $_POST["email"], $_POST["p"])){
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
    $password = filter_input(INPUT_POST, "p", FILTER_SANITIZE_STRING);
    if(strlen($password) != 128){
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
    $dbConnection = new Database($host, $username, $password, $dbname);
    $mysqli = $dbConnection->getConnection();
    $stmt = $mysqli->prepare("Select id From `members` Where username = ? Limit 1");
    if($stmt){
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 1){
            $error_msg .= '<p class="error">A user with this username already exists</p>';
            $stmt->close();
        }
    }else{
        $error_msg .= '<p class="error">Database error line 55</p>';
        $stmt->close();
    }
    if(empty($error_msg)){
        $password = password_hash($password, PASSWORD_BCRYPT);
        if($insert_stmt = $mysqli->prepare("Insert Into `members` (username, email, password) Values (?, ?, ?)")){
            $insert_stmt->bind_param("sss", $username, $email, $password);
            if(!$insert_stmt->execute()){
                header("Location: /error.php?error=Registration failure: Insert");
            }
        }
        header("Location: /index.php");
    }
}

