<?php

//database's configs
$file_upload_path = dirname($_SERVER["SCRIPT_FILENAME"])."/../upload/";
define("DBHOST", "127.0.0.1");
define("DBUSER", "topica");
define("DBPASSWORD", "123456");
define("DATABASE", "topica_cms");
define("SECURE", FALSE);
define("IMAGE_BLANK", "http://report.edumall.co.th/controller/MailOpenCounting.php?email_id=");
define("PREAUTH_KEY", "0f6f5bbf7f3ee4e99e2d24a7091e262db37eb9542bc921b2ae4434fcb6338389");
define("WEB_PREAUTH_KEY", "http://report.edumall.co.th/controller/ActiveAccount.php");
define("EMAIL_USER", "noreply");
define("EMAIL", "noreply@edumall.co.th");
define("EMAIL_PASSWORD", "123456a@A");

//test online server
//define("DBHOST", "mysql.hostinger.vn");
//define("DBUSER", "u878596405_tpc");
//define("DBPASSWORD", "R0bMsmskza");
//define("DATABASE", "u878596405_tpc");
//define("SECURE", FALSE);
//real server
//define("DBHOST", "127.0.0.1");
//define("DBUSER", "root");
//define("DBPASSWORD", "edumall@topica");
//define("DATABASE", "topica_cms");
//define("SECURE", FALSE);



