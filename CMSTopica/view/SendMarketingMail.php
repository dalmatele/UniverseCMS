<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gửi Email Marketing</title>
    </head>
    
    <link rel="stylesheet" type="text/css" href="../js/w2ui/w2ui.min.css?v=531329082016" />
    
    <script type="text/javascript" src="../js/jquery/jquery.js?v=461601092016"></script>
    <script type="text/javascript" src="../js/w2ui/w2ui.min.js?v=010926072016"></script>
    <script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../js/blueimp-file-upload/js/vendor/jquery.ui.widget.min.js?v=010926072016"></script>
    <script type="text/javascript" src="../js/blueimp-file-upload/js/jquery.fileupload.js?v=010926072016"></script>
    <script type="text/javascript" src="../js/send_email.js"></script>
     <script type="text/javascript">
        $(document).ready(function(){
           init();
           var send_email = new SendEmail();
           send_email.init();
        });
    </script>
    <body>
        <?php include 'header.php' ?>
        <div class="panel panel-primary" style="margin-top: 10px;width: 80%;position: absolute; left: 10%;">
            <div class="panel-heading">Tiện ích gửi thư Marketing</div>
            <div class="panel-body">
              <div class="container-fluid" style="margin-top: 2px;">
                  <div class="row">
                    <div class="col-md-2">
                        <span class="label label-primary">Gửi từ:</span>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="from_address" style="width: 100%;"/>
                    </div>
<!--                    <div class="col-md-2">
                        <span class="label label-primary">Mật khẩu hộp thư:</span>
                    </div>
                    <div class="col-md-3">
                        <input  id="from_address_password" type="password" />
                    </div>-->
                  </div>
                  <div class="row" style="margin-top: 5px;">
                        <div class="col-md-2">
                            <span class="label label-primary">Gửi tới:</span>
                        </div>
                        <div class="col-md-3">
                            <input id="address_file_upload" type="file" name="file" data-url="" />
                        </div>
                        <div class="col-md-5">
                            <div id="upload_address_result" style="color: red;font-weight: bold;"></div>
                        </div>
                    </div>
                  <div class="row" style="margin-top: 5px;">
                      <div class="col-md-2">
                          <span class="label label-primary">Chọn thời gian bắt đầu gửi:</span>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group margin-bottom-sm">
                            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                            <input class="form-control" type="us-time" id="time-to-send">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group margin-bottom-sm">
                            <span class="input-group-addon"><i class="fa fa-calendar-o fa-fw"></i></span>
                            <input class="form-control" type="us-date" id="date-to-send">
                          </div>
                          
                      </div>
                  </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-2">
                            <span class="label label-primary">Tiêu đề thư:</span>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="email_subject" style="width: 100%;"/>
                        </div>
                    </div>
<!--                  <div class="row" style="margin-top: 5px;">
                      <div class="col-md-2">
                          <span class="label label-primary">Nội dung thư:</span>
                      </div>
                  </div>-->
                  <div class="row" style="margin-top: 5px;">
                      <div class="col-md-12">
                          <textarea name="email-content" id="email-content" rows="10" cols="80"></textarea>
                      </div>
                  </div>
                  <div class="row" style="margin-top: 5px;">
                      <div class="col-md-2 col-md-offset-10">
                          <button id="send-mail" type="button" class="btn btn-primary navbar-btn">Bắt đầu gửi thư</button>
                      </div>
                  </div>
                </div>
            </div>
          </div>
        
    </body>
</html>
