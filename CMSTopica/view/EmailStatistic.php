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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Thống kê thư</title>
        <link rel="stylesheet" type="text/css" href="../js/w2ui/w2ui.min.css?v=072309092016" />
        <script type="text/javascript" src="../js/jquery/jquery.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/w2ui/w2ui.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/blueimp-file-upload/js/vendor/jquery.ui.widget.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/blueimp-file-upload/js/jquery.fileupload.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/mail_statistic.js?v=511113092016"></script>
    </head>
    <script type="text/javascript">
        $(document).ready(function(){
           init();
           var email_statistic = new EmailStatistic();
           email_statistic.init();
        });
    </script>
    <body>
        <?php include 'header.php' ?>
        <div class="panel panel-primary" style="margin-top: 10px;width: 80%;position: absolute; left: 10%;">
            <div class="panel-heading">Thống kê trạng thái thư</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">Thời gian từ:</div>
                    <div class="col-md-4">
                        <input type="us-date1" id="f_date" />
                    </div>
                    <div class="col-md-2">Thời gian tới:</div>
                    <div class="col-md-4">
                        <input type="us-date2" id="t_date" />
                    </div>
                </div>
                <div class="row" style="margin-top:5px;">
                    <div class="col-md-2">Tiêu đề thư:</div>
                    <div class="col-md-8">
                        <input id="email-subject" style="width: 100%;" type="text" placeholder="Tiêu đề thư..." />
                    </div>
                </div>
                <div class="row" style="margin-top:5px;">
                    <div class="col-md-2 col-md-offset-8">
                        <button style="width: 100%;" id="search-btn" type="button" class="btn btn-primary btn-sm">Tìm kiếm</button>
                    </div>
                    <div class="col-md-2" style="overflow: hidden;padding-right: 25px;margin-top: 8px;">
                        <div style="float: right;cursor: pointer;">
                             <i id="next_btn" class="fa fa-caret-right fa-lg"></i>
                        </div>
                        <div style="float: right;margin-right: 10px;cursor: pointer;">
                            <i id="prev_btn" class="fa fa-caret-left fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-12">
                        <div id="email-statistic-table" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="waiting_modal"></div>
    </body>
</html>
