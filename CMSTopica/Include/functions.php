<?php

require_once __DIR__ .'/../libs/log4php/Logger.php';
require_once __DIR__ .'/../libs/PHPMailer/PHPMailerAutoload.php';
/* 
 * To control all login actions
 * @link: http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
 */

function sec_session_start(){
    $session_name = "sec_session_id";
    session_name($session_name);
//    If TRUE cookie will only be sent over secure connections. 
//    So if not HTTPS, you can not get cookies' value
    $secure = false;
    $http_only = true;
    if(ini_set("session.use_only_cookies", 1) === FALSE){
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $http_only);
    session_start();
    session_regenerate_id(true);
}

function login($email, $password){
    $dbconnection = new Database();
    $mysqli = $dbconnection->getConnection();
    if($stmt = $mysqli->prepare("Select id, username, password, active_code From `members` Where email = ? Limit 1")){
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $db_password, $active_code);
        $stmt->fetch();
        if($stmt->num_rows == 1){
            if(!empty($active_code)){
                return false;
            }
            if(checkbrute($user_id, $mysqli) == true){
                return false;
            }else{
                if(password_verify($password, $db_password)){
                    $user_browser = $_SERVER["HTTP_USER_AGENT"];
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION["user_id"] = $user_id;
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                    $_SESSION["username"] = $username;
                    $_SESSION["login_string"] = hash("sha512", $db_password.$user_browser);
//                    foreach ($_SESSION as $key=>$val){
//                        error_log($key." ".$val);
//                    }
                    return true;
                }  else {
                    $now = time();
                    $mysqli->query("Insert into `secure_login` (user_id, time) Values ('$user_id', '$now'");
                    return false;
                }
            }
        }else{
//            no user exist
            return false;
        }
    }
}

function checkbrute($user_id, $mysqli){
    $now = time();
    
    $valid_attempts = $now - (2 * 60 * 60);
    if($stmt = $mysqli->prepare("Select time From `secure_login` Where user_id = ? And time > '$valid_attempts'")){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 5){
            return true;
        }else{
            return false;
        }
    }
}

function get_username(){
    return  $_SESSION["username"];
}

function get_userid(){
    return  $_SESSION["user_id"];
}

function login_check($mysqli){
//    error_log("g?");
//    foreach ($_SESSION as $key=>$val){
//        error_log($key." ".$val);
//    }
    if(isset($_SESSION["user_id"], $_SESSION["username"], $_SESSION["login_string"])){
        $user_id = $_SESSION["user_id"];
        $login_string = $_SESSION["login_string"];
        $username = $_SESSION["username"];
        $user_browser = $_SERVER["HTTP_USER_AGENT"];
        if($stmt = $mysqli->prepare("Select password From members Where id = ? Limit 1")){
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows == 1){
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash("sha512", $password.$user_browser);
                if(hash_equals($login_check, $login_string)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function esc_url($url){
    if($url == ""){
        return $url;
    }
    $url = preg_replace("|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i", "", $url);
    $strip = array("%0d", "%0a", "%0D", "%0A");
    $url = (string)$url;
    $count = 1;
    while($count){
        $url = str_replace($strip, "", $url, $count);
    }
    $url = str_replace(";//", "://", $url);
    $url = htmlentities($url);
    $url = str_replace("&amp;", "&#038;", $url);
    $url = str_replace("'", "&#039;", $url);
    if($url[0] !== "/"){
        return "";
    }else{
        return $url;
    }
}

function emailLogger($message, $level){
    Logger::configure(__DIR__.'/../Include/log4Email.php');
    $logger = Logger::getLogger("email");
    if(strcmp($level, "info") == 0){
        $logger->info($message);
    }else if(strcmp($level, "error") == 0){
        $logger->error($message);
    }else if(strcmp($level, "debug") == 0){
        $logger->debug($message);
    }else{
        $logger->fatal($message);
    }
};

function syncLogger($message, $level){
    Logger::configure(__DIR__.'/../Include/log4Sync.php');
    $logger = Logger::getLogger("sync");
    if(strcmp($level, "info") == 0){
        $logger->info($message);
    }else if(strcmp($level, "error") == 0){
        $logger->error($message);
    }else if(strcmp($level, "debug") == 0){
        $logger->debug($message);
    }else{
        $logger->fatal($message);
    }
}

function errorLogger($message, $level){
    Logger::configure(__DIR__.'/../Include/log4Error.php');
    $logger = Logger::getLogger("error");
    if(strcmp($level, "info") == 0){
        $logger->info($message);
    }else if(strcmp($level, "error") == 0){
        $logger->error($message);
    }else if(strcmp($level, "debug") == 0){
        $logger->debug($message);
    }else{
        $logger->fatal($message);
    }
}

function sendEmail($content, $receivers){
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = "128.199.227.1";
    $mail->SMTPAuth = true;
//    $mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->SMTPKeepAlive = true; // prevent the SMTP session from being closed after each message
    $email_log = "";
    //https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting
    //disable certifcate check
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Username = EMAIL_USER;
    $mail->Password = EMAIL_PASSWORD;
    $mail->setFrom(EMAIL);
    $mail->addCustomHeader("Precedence: bulk");
    $mail->addCustomHeader("List-Unsubscribe", "<unsubscribe@edumall.co.th>");
    $mail->addAddress($receivers); 
    $mail->Subject = "Your activate account link";
    $mail->Body    = $content;
    if(!$mail->send()) {
//        emailLogger("Message has been sent:".EMAIL." --> ".$receivers.$mail->ErrorInfo, "error");
    } else {
//                $email_log = 'Message has been sent';
//        emailLogger("Message has been sent:".EMAIL." --> ".$receivers, "info");

    }
    $mail->SmtpClose();
}