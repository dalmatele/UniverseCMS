<?php
    require_once '../Include/config.php';
    require_once '../Include/functions.php';
    require_once '../models/Database.php';
    sec_session_start();
    $db = new Database();
    $conn = $db->getConnection();
    $isLogin = login_check($conn);
    $db->dbClose();
    if(!$isLogin){
        header("Location: ../index.php");
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Quản lý đơn hàng</title>
    </head>
<!--    <link rel="stylesheet" type="text/css" href="../css/style.css?v=144226072016"/>-->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css?v=072309092016" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css?v=072309092016"/>
    <link rel="stylesheet" type="text/css" href="../js/w2ui/w2ui.min.css?v=072309092016" />
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css?v=072309092016" />
    <link rel="stylesheet" type="text/css" href="../js/jquery-contextmenu/jquery.contextMenu.min.css?v=072309092016"/>
    
    <script type="text/javascript" src="../js/jquery/jquery.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/w2ui/w2ui.min.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/raphael/raphael.min.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/flowchart/flowchart.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/order.js?v=242309092016"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var order = new Order();
        });
    </script>
    <body>
        <?php include 'header.php' ?>
        
        <div class="container-fluid" style="margin-top: 10px;">
            <div class="row" id="toolbar">
                <div class="col-md-2">
                    Tên người mua:
                </div>
                <div class="col-md-2">
                    <input type="text" id="buyer" />
                </div>
                <div class="col-md-2">
                    Mã đơn hàng:
                </div>
                <div class="col-md-2">
                    <input type="text" id="co_number" />
                </div>
                <div class="col-md-1">
                    Trạng thái:
                </div>
                <div class="col-md-2">
                    <input type="list" id="status" />
                </div>
                <div class="col-md-1">
                    <input id="search_btn" type="button" value="Tìm kiếm"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="overflow: hidden;padding-right: 20px;">
                    <div style="float: right;cursor: pointer;">
                         <i id="next_btn" class="fa fa-caret-right fa-lg"></i>
                    </div>
                    <div style="float: right;margin-right: 10px;cursor: pointer;">
                        <i id="prev_btn" class="fa fa-caret-left fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="row" id="table_content">
                <div class="col-md-12">
                    <div id="order_table"></div>
                </div>
            </div>
        </div>
        <div class="waiting_modal"></div>
        <?php include './footer.php'?>
    </body>
</html>
