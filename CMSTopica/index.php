<?php 
require_once './Include/config.php';
require_once './models/Database.php';
require_once './Include/functions.php';

sec_session_start();
$db = new Database();
$conn = $db->getConnection();
$isLogin = login_check($conn);
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
        <script type="text/javascript" src="js/sha512.js"></script>
        <script type="text/javascript" src="js/forms.js"></script>
    </head>
    <body>
        <?php
            if(isset($_GET["error"])){
                echo '<p class="error">Error Logging In!</p>';
            }
        ?>
        <form action="Include/process_login.php" method="post" name="login_form">                      
            Email: <input type="text" name="email" />
            Password: <input type="password" 
                             name="password" 
                             id="password"/>
            <input type="button" 
                   value="Login" 
                   onclick="formhash(this.form, this.form.password);" /> 
        </form>
        <?php
            if($isLogin){
                echo "<p>Currently logged " . $logged . " as " . htmlentities($_SESSION['username']) . ".</p>";
                echo "<p><a href='view/home.php'>Click here</a> to continue</p>";
                echo '<p>Do you want to change user? <a href="includes/logout.php">Log out</a>.</p>';
            }else {
                echo '<p>Currently logged ' . $logged . '.</p>';
                echo "<p>If you don't have a login, please <a href='register.php'>register</a></p>";
            }
//            print '<pre>';
//            var_dump($isLogin);
//            var_dump($_SESSION);
//            print '</pre>';
        ?>
    </body>
</html>
