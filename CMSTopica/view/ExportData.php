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
        <title>Xuất báo cáo</title>
        <link rel="stylesheet" type="text/css" href="../js/w2ui/w2ui.min.css?v=072309092016" />
        <link rel="stylesheet" type="text/css" href="../js/jqPlot/jquery.jqplot.min.css?v=072309092016" />
        
        <script type="text/javascript" src="../js/jquery/jquery.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/w2ui/w2ui.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/jqPlot/jquery.jqplot.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/jqPlot/plugins/jqplot.pieRenderer.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/export.js?v=072309092016"></script>
    </head>
    <script type="text/javascript">
        $(document).ready(function(){
            var e = new Export();
        });
    </script>
    <body>
        <?php include 'header.php' ?>
        <div class="panel panel-primary" style="margin-top: 10px;width: 95%;margin-left: 20px;">
            <div class="panel-heading">Thống kê tình hình đơn hàng</div>
            <div class="panel-body">
                <div class="container-fluid" style="margin-top: 10px;">
                    <div class="row">
                        <div class="col-md-3" id="statistic_day">
                            <div class="row">
                                <div class="col-md-12 graph_title">
                                    Thống kê trạng thái đơn hàng trong ngày &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="day_graph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="statistic_week">
                            <div class="row">
                                <div class="col-md-12 graph_title">
                                    Thống kê trạng thái đơn hàng 7 ngày gần nhất
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="week_graph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="statistic_month">
                            <div class="row">
                                <div class="col-md-12 graph_title">
                                    Thống kê trạng thái đơn hàng 30 ngày gần nhất
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="month_graph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="statistic_year">
                            <div class="row">
                                <div class="col-md-12 graph_title">
                                    Thống kê trạng thái đơn hàng 365 ngày gần nhất
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="year_graph"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary" style="width: 95%;margin-left: 20px;">
            <div class="panel-heading">Xuất báo cáo dữ liệu</div>
            <div class="panel-body">
                <div id="export_tabs_manager">
                    <div id="tabs" style="width:100%;height:35px;"></div>
                    <div id="kerry_report_tab" class="tab">
                        <div class="container-fluid" style="margin-top: 10px;">
                            <div class="row" id="toolbar">
                                <div class="col-md-2">
                                    Trạng thái:
                                </div>
                                <div class="col-md-2">
                                    <input type="list" id="status" readonly="true"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    Thời gian cập nhật từ:
                                </div>
                                <div class="col-md-2">
                                    <input type="us-date1" id="f_date" />
                                </div>
                                <div class="col-md-2">
                                    Thời gian cập nhật tới:
                                </div>
                                <div class="col-md-2">
                                    <input type="us-date2" id="t_date" />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-1">
                                    <input id="search_btn" type="button" value="Xuất báo cáo"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="account_report_tab" class="tab">
                        <div class="container-fluid" style="margin-top: 10px;">
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    Thời gian cập nhật từ:
                                </div>
                                <div class="col-md-2">
                                    <input type="us-date3" id="f_date_account_report" />
                                </div>
                                <div class="col-md-2">
                                    Thời gian cập nhật tới:
                                </div>
                                <div class="col-md-2">
                                    <input type="us-date4" id="t_date_account_report" />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-1">
                                    <input id="search_account_report_btn" type="button" value="Xuất báo cáo"/>
                                </div>
                                <div class="col-md-1 col-md-offset-1">
                                    <input id="account_monthly_report_btn" type="button" value="Xem báo cáo tháng"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tm_report_tab" class="tab"></div>
                    <div id="sale_report_tab" class="tab"></div>
                </div>
            </div>
        </div>
        
        
        <div class="waiting_modal"></div>
        <?php include './footer.php'?>
    </body>
</html>
