<?php

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
    if($stmt = $mysqli->prepare("Select id, username, password From `members` Where email = ? Limit 1")){
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();
        if($stmt->num_rows == 1){
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
                error_log($login_check);
                error_log($login_string);
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