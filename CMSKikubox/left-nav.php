<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

if (!securePage($_SERVER['PHP_SELF'])){die();}

//Links for logged in user
if(isUserLoggedIn()) {
	echo "
	<ul>
	<li><a href='account.php'>Trang chủ</a></li>
	<li><a href='user_settings.php'>Thiết lập người dùng</a></li>
	<li><a href='logout.php'>Thoát</a></li>
	</ul>";
	
	//Links for permission level 2 (default admin)
	if ($loggedInUser->checkPermission(array(2))){
	echo "
	<ul>
	<li><a href='admin_configuration.php'>Cấu hình</a></li>
	<li><a href='admin_users.php'>Quản lý tài khoản</a></li>
	<li><a href='admin_permissions.php'>Quản lý quyền</a></li>
	<li><a href='admin_pages.php'>Quản lý trang</a></li>
	</ul>";
	}
} 
//Links for users not logged in
else {
	echo "
	<ul>
	<li><a href='index.php'>Trang chủ</a></li>
	<li><a href='login.php'>Đăng nhập</a></li>
	<li><a href='register.php'>Đăng ký</a></li>
	<li><a href='forgot-password.php'>Quên mật khẩu</a></li>";
	if ($emailActivation)
	{
	echo "<li><a href='resend-activation.php'>Gửi thư kích hoạt tài khoản</a></li>";
	}
	echo "</ul>";
}

?>
