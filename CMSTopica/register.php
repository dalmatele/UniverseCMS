<?php
require_once './Include/config.php';
include_once './Include/register.inc.php';
include_once './Include/functions.php';
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Topica CMS Register Page</title>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css?v=461601092016" />
        <link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.css?v=461601092016"/>
        <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css?v=461601092016" />
        <script type="text/javascript" src ="js/sha512.js"></script>
        <script type="text/javascript" src="js/forms.js"></script>
        <link rel="stylesheet" href="css/style.css" />
        <?php include_once("./Include/analyticstracking.php") ?>
    </head>
    <body>
        <div class="panel panel-primary" style="margin-top: 50px;width: 50%;position: absolute; left: 20%;">
            <div class="panel-heading">Đăng ký tài khoản</div>
            <div class="panel-body">
                <?php
                    if(!empty($error_msg)){
                        echo $error_msg;
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Quy tắc đăng ký tài khoản</div>
                            <div class="panel-body">
                                <ul style="color:red;">
                                    <li>Tên tài khoản chỉ có thể chứa: chữ số, chữ in hoa, chữ in thường và dấu gạch dưới</li>
                                    <li>Địa chỉ thư phải hợp lệ</li>
                                    <li>Mật khẩu phải có độ dài tối thiểu 6 ký tự</li>
                                    <li>Quy tắc đặt mật khẩu
                                        <ul>
                                            <li>Có ít nhất một ký tự in hoa (A..Z)</li>
                                            <li>Có ít nhất một ký tự in thường (a..z)</li>
                                            <li>Có ít nhất một chữ số (0..9)</li>
                                        </ul>
                                    </li>
                                    <li>Ô mật khẩu và ô xác nhận mật khẩu phải có nội dung trùng khớp</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" 
                            method="post" 
                            name="registration_form">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-3">
                                    Username:
                                </div>
                                <div class="col-md-4">
                                    <input type='text' name='username' id='username' />
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-3">
                                    Email:
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="email" id="email" />
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-3">
                                    Password:
                                </div>
                                <div class="col-md-4">
                                    <input type="password" name="password"  id="password"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-3">
                                    Confirm password:
                                </div>
                                <div class="col-md-4">
                                    <input type="password" name="confirmpwd" id="confirmpwd" />
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <input type="button" value="Register" onclick="return regformhash(this.form,
                                               this.form.username,
                                               this.form.email,
                                               this.form.password,
                                               this.form.confirmpwd);" /> 
                                </div>
                            </div> 
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    Return to the <a href="index.php">Login page</a>.
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
