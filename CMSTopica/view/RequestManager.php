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
        <title>Quản lý yêu cầu</title>
        <link rel="stylesheet" type="text/css" href="../js/w2ui/w2ui.min.css?v=072309092016" />
        <script type="text/javascript" src="../js/jquery/jquery.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js?v=001210092016"></script>
        <script type="text/javascript" src="../js/w2ui/w2ui.min.js?v=072309092016"></script>
        <script type="text/javascript" src="../js/request.js?v=511410092016"></script>
    </head>
    <script type="text/javascript">
        $(document).ready(function(){
            var r = new Request();
        });
    </script>
    <body>
       <?php include 'header.php' ?>
        <div class="panel panel-primary" style="margin-top: 10px;width: 95%;position: absolute;left:2%;">
            <div class="panel-heading">Quản lý yêu cầu dịch vụ</div>
            <div class="panel-body">
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="...">
                            <span id="search-btn" class="input-group-addon" id="basic-addon2">Tìm kiếm</span>
                            <span id="advanced-search-btn" class="input-group-addon" id="basic-addon2">Tìm kiếm nâng cao</span>
                          </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-3">
                        <button id="new-request" type="button" class="btn btn-primary btn-sm">Tạo yêu cầu mới</button>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-12">
                        <div id="request_table"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
