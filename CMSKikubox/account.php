<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<div id='left-nav'>";

include("left-nav.php");
if($loggedInUser->checkPermission(array(2))){
    //Nếu đây là quản trị viên thì chuyển sang trang cms
    header("Location: view/Admin/Admin.php"); 
}else{
    //Nếu đây là người dùng thông thường thì chuyển hướng qua website
    echo "Bạn không có quyền truy cập trang này";
}

?>
