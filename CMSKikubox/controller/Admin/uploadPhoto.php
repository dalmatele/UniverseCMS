<?php

include("../../models/Configs.php");
include ("../../models/Admin/UploadHandler.php");

//Kiểm tra phân quyền
$inDirectCall = TRUE;
require_once("../../models/config.php");
securePage($_SERVER['PHP_SELF']);

/*Phụ trách việc upload ảnh sản phẩm*/

$upload_handler = new UploadHandler();

