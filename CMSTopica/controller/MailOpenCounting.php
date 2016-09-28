<?php

require_once "../Include/config.php";
require_once '../models/Database.php';
require_once '../models/EmailTemplateDB.php';


/*
 * We use this to check when users open our email
 * http://www.phpdevtips.com/2013/06/email-open-tracking-with-php-and-mysql/
 */

$method = $_SERVER["REQUEST_METHOD"];
    switch ($method){
        case "POST":
            $response = array("res" => "Post method is not support!");
            echo json_encode($response);
            break;
        case "GET":
            //get the id of mail template
            $id = intval(htmlspecialchars($_GET["email_id"]));
            $etdb = new models\EmailTemplateDB();
            $tempates[] = $etdb->getEmailTemplate($id)[0];
            if(count($tempates) > 0){
                $our_tempate = $tempates[0][0];
                $email_count = $our_tempate["email_count"];
                $email_count++;
                $etdb->updateEmailCount($id, $email_count);
            }
            //Full URI to the image
            $graphic_http = '../css/images/Blank.gif';
            $filesize = filesize('../css/images/Blank.gif');
            //Now actually output the image requested (intentionally disregarding if the database was affected)
            header( 'Pragma: public' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
            header( 'Cache-Control: private',false );
            header( 'Content-Disposition: attachment; filename="blank.gif"' );
            header( 'Content-Transfer-Encoding: binary' );
            header( 'Content-Length: '.$filesize );
            readfile($graphic_http);
            exit;
            break;
    }
