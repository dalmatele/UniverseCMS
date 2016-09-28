<?php
//http://stackoverflow.com/questions/12377719/php-cli-include-isnt-finding-the-file
//Must use this to run in the command line
require_once __DIR__ ."/../Include/config.php";
require_once __DIR__ .'/../models/SendEmailTaskLogDB.php';
require_once __DIR__ .'/../libs/PHPMailer/PHPMailerAutoload.php';
require_once __DIR__ .'/../Include/functions.php';


/* 
 * Will run after each 15 minutes
 */
//get data
$sesdb = new \models\SendEmailTaskLogDB();
date_default_timezone_set("Asia/Ho_Chi_Minh");
$now = new DateTime();
$nowstr = $now->format("Y-m-d H:i:s");


$emails = $sesdb->getEmailToSend($nowstr)[0];
emailLogger("Have: ".count($email)." to send.", "info");
if(count($emails) > 0){
    $mail = new PHPMailer();
//    $mail->SMTPDebug = 3;
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
    
    foreach ($emails as $email){
        //http://docs.drh.net/greenarrow-engine/SimpleMH-Injection/PHPMailer-SimpleMH-Multiple-Recipient-Example
        //multimail in a connection
        $id = $email["id"];
//        $mail->Username = $email["send_from"];
        //zimbra does not use username@domain.com, just username
        $mail->Username = explode("@", $email["send_from"])[0];
        $mail->Password = $email["send_password"];
        $mail->setFrom($email["send_from"], $email["fullname"]);
        //https://support.google.com/mail/answer/81126
        //Set 'Precedence: bulk' header
        // more: http://stackoverflow.com/questions/16900641/phpmailer-gmail-spam
        $mail->addCustomHeader("Precedence: bulk");
        $mail->addCustomHeader("List-Unsubscribe", "<unsubscribe@edumall.co.th>");
        $tos = explode(",", $email["send_to"]);
        
        foreach($tos as $to){
            $mail->ClearAllRecipients();
            $addresses = explode("|", $to);
            $mail->addAddress($addresses[0], $addresses[1]); 
            $subject = $email["subject"];
            $mail->Subject = str_replace('$user', $addresses[1], $subject);
            $img_src = "<img alt='' src='".IMAGE_BLANK.$email["template_id"]."' width='1' height='1' border='0' />";
            $mail->Body    = $email["email_content"].$img_src;
            if(!$mail->send()) {
                emailLogger("Message has been sent:".$email["id"]."-".$email["send_from"]." --> ".$addresses[0]."(".$addresses[1]."):".$mail->ErrorInfo, "error");
            } else {
//                $email_log = 'Message has been sent';
                emailLogger("Message has been sent:".$email["send_from"]." --> ".$addresses[0]."(".$addresses[1].")", "info");

            }
        }
     $sesdb->updateMailLog($id, "", 1);   
    }
    $mail->SmtpClose();
}else{
    emailLogger("No message has been sent.", "info");

}
$sesdb->dbClose();


