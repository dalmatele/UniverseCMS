<?php 
require_once './Include/config.php';
require_once './models/Database.php';
require_once './Include/functions.php';

sec_session_start();
$db = new Database();
$conn = $db->getConnection();
$isLogin = login_check($conn);
$db->dbClose();
if($isLogin){
    $logged = "in";
}else{
    $logged = "out";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Topica CMS login</title>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css?v=461601092016" />
        <link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.css?v=461601092016"/>
        <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css?v=461601092016" />
        <script type="text/javascript" src="js/jquery/jquery.js"></script>
        <script type="text/javascript" src="js/sha512.js"></script>
        <script type="text/javascript" src="js/forms.js"></script>
    </head>
    <body>
        <script type="text/javascript">
            $(document).ready(function(){
               $(".input-field").keypress(function(e){
                   if(e.which === 13) {
                        $("#login-btn").trigger("click");
                    }
               });
            });
        </script>
        <?php include_once("./Include/analyticstracking.php") ?>
        <div class="panel panel-primary" style="margin-top: 50px;width: 50%;position: absolute; left: 20%;">
            <div class="panel-heading">Đăng nhập</div>
            <div class="panel-body">
                <?php if(!$isLogin){ ?>
                <form action="Include/process_login.php" method="post" name="login_form">
                    <?php if(isset($_GET["error"])){ ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo '<p class="error">Error Logging In!</p>'; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="error">Wrong username or password or your account is not actived!</p>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-2">
                            Email:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="email" class="input-field"/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-2">
                            Password:
                        </div>
                        <div class="col-md-4">
                            <input type="password"  name="password" id="password" class="input-field"/>
                        </div>
                    </div>
                    <input type="button" id="login-btn"
                           value="Login" 
                           onclick="formhash(this.form, this.form.password);" /> 
                </form>
                <?php }?>
                <?php
                    if($isLogin){
                        echo "<p>Currently logged " . $logged . " as " . htmlentities($_SESSION['username']) . ".</p>";
                        echo "<p><a href='view/home.php'>Click here</a> to continue</p>";
                        echo '<p>Do you want to change user? <a href="Include/logout.php">Log out</a>.</p>';
                    }else {
                        echo '<p>Currently logged ' . $logged . '.</p>';
                        echo "<p>If you don't have a login, please <a href='register.php'>register</a></p>";
                    }
        //            print '<pre>';
        //            var_dump($isLogin);
        //            var_dump($_SESSION);
        //            print '</pre>';
                ?>
            </div>
        </div>
        
        
    </body>
</html>
