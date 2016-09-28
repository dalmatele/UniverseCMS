<?php

require_once 'models/Database.php';

$error_msg = "";
$success = false;
$email = "";
$username = "";
$timestamp = 0;
if(isset($_POST["username"], $_POST["email"], $_POST["p"])){
    if(empty(PREAUTH_KEY)){
        $error_msg.='Can not activate account. Please try again late.';
    }
    $dbConnection = new Database();
    $mysqli = $dbConnection->getConnection();
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
//    check through email whitelist
    $stmt = $mysqli->prepare("Select config_value From `order_config` Where config_name = 'email_white_list'");
    if($stmt){
        $stmt->execute();
        $stmt->bind_result($config);
        while($stmt->fetch()){
            if(strpos($config, $email) === true){
                $error_msg .= '<p class="error">Your email is not accept! Please try another.</p>';
                break;
            }
        }
    }
    $stmt->close();
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
    $password = filter_input(INPUT_POST, "p", FILTER_SANITIZE_STRING);
    if(strlen($password) != 128){
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
    
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
        //create active code for new account
        $timestamp = time()*1000;
        $preauthToken = hash_hmac("sha1", $email."|".$username."|".$timestamp, PREAUTH_KEY);
        if($insert_stmt = $mysqli->prepare("Insert Into `members` (username, email, password, active_code) Values (?, ?, ?, ?)")){
            $insert_stmt->bind_param("ssss", $username, $email, $password, $preauthToken);
            if(!$insert_stmt->execute()){
                $insert_stmt->close();
                header("Location: ./error.php?error=Registration failure: Insert");
            }
        }
        //Hien thong bao yeu cau kich hoat tai khoan
        //Co the bam vao link de yeu cau gui lai ma kich hoat
        $link = WEB_PREAUTH_KEY."?email=".$email."&username=".$username."&timestamp=".$timestamp."&active_code=".$preauthToken;
        $email_content = '<p>Dear customer,</p><p>This is your active account link: <a href="'.$link.'">Active my account</a></p><p>If above link does not work, you can copy below link then paste into your browser.</p><p>'.$link.'</p><p>This is email is auto generate so do not reply it.</p><p>Thank you</p><p>&nbsp;</p>';
        sendEmail($email_content, $email);
        $insert_stmt->close();
        $success = true;
//        header("Location: ./register.php");
    }
}

