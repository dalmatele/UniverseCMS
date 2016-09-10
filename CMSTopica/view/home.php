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
        <title>Topical Edumall CMS</title>
    </head>
    <script type="text/javascript" src="../js/jquery/jquery.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/blueimp-file-upload/js/vendor/jquery.ui.widget.min.js?v=072309092016"></script>
    <script type="text/javascript" src="../js/blueimp-file-upload/js/jquery.fileupload.js?v=072309092016"></script>
    
<!--    <link rel="stylesheet" type="text/css" href="css/style.css?v=010926072016"/>-->
    <script type="text/javascript">
        $(document).ready(function(){
            init();
        });
    </script>
    
    <body>
        <?php include './header.php' ?>
        <div class="panel panel-primary" style="margin-top: 20px;width: 80%;position: absolute; left: 10%;">
            <div class="panel-heading">Tiện ích tải tập tin đơn hàng</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <span class="label label-primary">Tải lên tập tin đơn hàng:</span>
                    </div>
                    <div class="col-md-5">
                        <div>
                            <input id="file_upload" type="file" name="file" data-url="" />
                            <div id="upload_result" style="color: red;font-weight: bold;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <fieldset>
            <legend>Tải lên tập tin đơn hàng</legend>
            <div>
                <input id="file_upload" type="file" name="file" data-url="" />
            </div>
            <div id='progress' style='width:240px;height:20px;'>
                <div class='bar' style='width: 0%;'></div>
            </div>
            <div>
                <div id="upload_result" style="color: red;font-weight: bold;"></div>
            </div>
        </fieldset>-->
        <div class="waiting_modal"></div>
        <?php include './footer.php'?>
    </body>
</html>
