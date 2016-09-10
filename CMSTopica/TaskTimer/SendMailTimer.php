<?php

require_once "../Include/config.php";
require_once '../models/SendEmailTaskLogDB.php';
require_once '../libs/PHPMailer/PHPMailerAutoload.php';
/* 
 * Will run after each 30 minutes
 */
//get data
$sesdb = new \models\SendEmailTaskLogDB();
date_default_timezone_set("Asia/Ho_Chi_Minh");
$now = new DateTime();
$nowstr = $now->format("Y-m-d H:i:s");


$emails = $sesdb->getEmailToSend($nowstr)[0];
//echo json_encode($emails[0]["send_to"]);
if(count($emails) > 0){
    $mail = new PHPMailer();
//    $mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host = "128.199.227.1";
    $mail->SMTPAuth = true;
//    $mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
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
    
    foreach ($emails as $email){
        $id = $email["id"];
//        $mail->Username = $email["send_from"];
        //zimbra does not use username@domain.com, just username
        $mail->Username = explode("@", $email["send_from"])[0];
        $mail->Password = $email["send_password"];
        $mail->setFrom($email["send_from"], $email["fullname"]);
        $tos = explode(",", $email["send_to"]);
        
        foreach($tos as $to){
            $addresses = explode("|", $to);
            $mail->addAddress($addresses[0], $addresses[1]);  
            error_log($addresses[0]."|".$addresses[1]);
            echo $addresses[0]."|".$addresses[1];
        }
        $mail->Subject = $email["subject"];
        $mail->Body    = $email["email_content"];
        if(!$mail->send()) {
//            echo 'Message could not be sent.';
//            echo 'Mailer Error: ' . $mail->ErrorInfo;
            $email_log = $mail->ErrorInfo;
        } else {
            $email_log = 'Message has been sent';
//            echo 'Message has been sent';
            
        }
    }
    $sesdb->updateMailLog($id, $email_log, 1);
}
$sesdb->dbClose();


