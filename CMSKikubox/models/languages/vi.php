<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

/*
%m1% - Dymamic markers which are replaced at run time by the relevant index.
*/

$lang = array();

//Account
$lang = array_merge($lang,array(
	"ACCOUNT_SPECIFY_USERNAME" 		=> "Vui lòng nhập tên tài khoản",
	"ACCOUNT_SPECIFY_PASSWORD" 		=> "Vui lòng nhập mật khẩu",
	"ACCOUNT_SPECIFY_EMAIL"			=> "Vui lòng nhập địa chỉ email",
	"ACCOUNT_INVALID_EMAIL"			=> "Địa chỉ email không hợp lệ",
	"ACCOUNT_USER_OR_EMAIL_INVALID"		=> "Tài khoản hoặc địa chỉ email không hợp lệ",
	"ACCOUNT_USER_OR_PASS_INVALID"		=> "Tài khoản hoặc mật khẩu không hợp lệ",
	"ACCOUNT_ALREADY_ACTIVE"		=> "Tài khoản của bạn đã được kích hoạt",
	"ACCOUNT_INACTIVE"			=> "Tài khoản chưa được kích hoạt. Vui lòng kiểm tra thư (có thể nằm trong thư mục Thư rác) để xem hướng dẫn kích hoạt",
	"ACCOUNT_USER_CHAR_LIMIT"		=> "Tên tài khoản phải có độ dài từ %m1% tới %m2% ký tự",
	"ACCOUNT_DISPLAY_CHAR_LIMIT"		=> "Tên hiển thị phải có độ dài từ %m1% tới %m2% ký tự",
	"ACCOUNT_PASS_CHAR_LIMIT"		=> "Mật khẩu phải có độ dài từ %m1% tới %m2% ký tự",
	"ACCOUNT_TITLE_CHAR_LIMIT"		=> "Tiêu đề phải có đội dài từ %m1% tới %m2% ký tự",
	"ACCOUNT_PASS_MISMATCH"			=> "Mật khẩu và mật khẩu nhập lại không khớp",
	"ACCOUNT_DISPLAY_INVALID_CHARACTERS"	=> "Tên hiển thị chỉ có thể chứa chữ và số",
	"ACCOUNT_USERNAME_IN_USE"		=> "Tên tài khoản %m1% đã tồn tại",
	"ACCOUNT_DISPLAYNAME_IN_USE"		=> "Tên hiển thị %m1% đã tồn tại",
	"ACCOUNT_EMAIL_IN_USE"			=> "Địa chỉ Email %m1% đã tồn tại",
	"ACCOUNT_LINK_ALREADY_SENT"		=> "Thư kích hoạt đã được gửi tới địa chỉ email vào lúc %m1%",
	"ACCOUNT_NEW_ACTIVATION_SENT"		=> "Chúng tôi đã gửi thư chứa link kích hoạt, vui lòng kiểm tra thư để kích hoạt tài khoản",
	"ACCOUNT_SPECIFY_NEW_PASSWORD"		=> "Vui lòng nhập mật khẩu",	
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD"	=> "Vui lòng xác nhận lại mật khẩu",
	"ACCOUNT_NEW_PASSWORD_LENGTH"		=> "Mât khẩu mới phải có độ dài từ %m1% tới %m2% ký tự",	
	"ACCOUNT_PASSWORD_INVALID"		=> "Mật khẩu không chính xác",	
	"ACCOUNT_DETAILS_UPDATED"		=> "Đã cập nhật tài khoản",
	"ACCOUNT_ACTIVATION_MESSAGE"		=> "Tài khoản cần được kích hoạt trước khi sử dụng. Vui lòng bấm vào link ở dưới để kích hoạt. \n\n
	%m1%activate-account.php?token=%m2%",							
	"ACCOUNT_ACTIVATION_COMPLETE"		=> "Tài khoản đã được kích hoạt. Bạn có thể đăng nhập tại <a href=\"login.php\">Đây</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "Đăng ký thành công. Bạn có thể đăng nhập tại <a href=\"login.php\">Đây</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "Đăng ký thành công. Chúng tôi sẽ gửi thư kích hoạt cho bạn. 
	Tài khoản cần được kích hoạt trước khi sử dụng.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "Bạn không thể sử dụng lại cùng một mật khẩu",
	"ACCOUNT_PASSWORD_UPDATED"		=> "Đã cập nhật mật khẩu",
	"ACCOUNT_EMAIL_UPDATED"			=> "Đã cập nhật email",
	"ACCOUNT_TOKEN_NOT_FOUND"		=> "Token không tồn tại / Tài khoản đã được kích hoạt",
	"ACCOUNT_USER_INVALID_CHARACTERS"	=> "Tên đăng nhập chỉ có thể chứa chữ và số",
	"ACCOUNT_DELETIONS_SUCCESSFUL"		=> "Đã xóa thành công tài khoản %m1%",
	"ACCOUNT_MANUALLY_ACTIVATED"		=> "Tài khoản %m1% đã được kích hoạt",
	"ACCOUNT_DISPLAYNAME_UPDATED"		=> "Đổi tên hiển thị thành %m1%",
	"ACCOUNT_TITLE_UPDATED"			=> "Tiêu đề %m1% đã được đổi thành %m2%",
	"ACCOUNT_PERMISSION_ADDED"		=> "Đã thêm quyền truy cập vào %m1%",
	"ACCOUNT_PERMISSION_REMOVED"		=> "Đã loại bỏ quyền truy cập vào %m1%",
	"ACCOUNT_INVALID_USERNAME"		=> "Tên đăng nhập không hợp lệ",
	));

//Configuration
$lang = array_merge($lang,array(
	"CONFIG_NAME_CHAR_LIMIT"		=> "Tên trang phải có độ dài từ %m1% tới %m2% ký tự",
	"CONFIG_URL_CHAR_LIMIT"			=> "Tên trang phải có độ dài từ %m1% tới %m2% ký tự",
	"CONFIG_EMAIL_CHAR_LIMIT"		=> "Tên trang phải có độ dài từ %m1% tới %m2% ký tự",
	"CONFIG_ACTIVATION_TRUE_FALSE"		=> "Email kích hoạt phải có giá trị là `true` hoặc `false`",
	"CONFIG_ACTIVATION_RESEND_RANGE"	=> "Khoảng cách giữa hai lần kích hoạt phải từ %m1% tới %m2% tiếng",
	"CONFIG_LANGUAGE_CHAR_LIMIT"		=> "Đường dẫn tới tập tin ngôn ngữ phải có độ dài từ %m1% tới %m2% ký tự",
	"CONFIG_LANGUAGE_INVALID"		=> "Không có tập tin ngôn ngữ phù hợp cho ngôn ngữ `%m1%`",
	"CONFIG_TEMPLATE_CHAR_LIMIT"		=> "Đường dẫn tới tập tin mẫu phải có độ dài từ %m1% tới %m2% ký tự",
	"CONFIG_TEMPLATE_INVALID"		=> "Không có tập tin mẫu phù hợp cho mẫu `%m1%`",
	"CONFIG_EMAIL_INVALID"			=> "Địa chỉ email không hợp lệ",
	"CONFIG_INVALID_URL_END"		=> "Vui lòng nhập địa chỉ URL có kết thúc bằng ký tự /",
	"CONFIG_UPDATE_SUCCESSFUL"		=> "Đã cập nhật cấu hình trang. Bạn có thể phải tải lại trang để các cập nhật có hiệu lực",
	));

//Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN"		=> "Mã kích hoạt không hợp lệ",
	"FORGOTPASS_NEW_PASS_EMAIL"		=> "Chúng tôi đã gửi mật khẩu mới tới địa chỉ của bạn",
	"FORGOTPASS_REQUEST_CANNED"		=> "Hủy yêu cầu cấp lại mật khẩu",
	"FORGOTPASS_REQUEST_EXISTS"		=> "Đã có yêu cầu lấy lại mật khẩu cho tài khoản này rồi",
	"FORGOTPASS_REQUEST_SUCCESS"		=> "Chúng tôi đã gửi thư hướng dẫn lấy lại quyền truy cập cho tài khoản",
	));

//Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR"				=> "Không thể gửi được email, vui lòng liên hệ người quản trị",
	"MAIL_TEMPLATE_BUILD_ERROR"		=> "Không thể tạo thư mẫu",
	"MAIL_TEMPLATE_DIRECTORY_ERROR"		=> "Không thể truy cập thư mục thư mẫu. Thử kiểm tra lại bằng cách thiết lập quyền cho thư mục thành %m1%",
	"MAIL_TEMPLATE_FILE_EMPTY"		=> "Tập tin mẫu rỗng ... không thể gửi thư",
	));

//Miscellaneous
$lang = array_merge($lang,array(
	"CAPTCHA_FAIL"				=> "Sai câu hỏi bảo mật",
	"CONFIRM"				=> "Xác nhận",
	"DENY"					=> "Từ chối",
	"SUCCESS"				=> "Thành công",
	"ERROR"					=> "Lỗi",
	"NOTHING_TO_UPDATE"			=> "Không có gì để cập nhật",
	"SQL_ERROR"				=> "Lỗi cơ sở dữ liệu",
	"FEATURE_DISABLED"			=> "Tính năng này hiện đang bị tắt",
	"PAGE_PRIVATE_TOGGLED"			=> "Trang hiện tại này đã chuyển sang %m1%",
	"PAGE_ACCESS_REMOVED"			=> "Đã loại bỏ quyền truy cập trang cho %m1%",
	"PAGE_ACCESS_ADDED"			=> "Đã thêm quyền truy cập trang cho %m1%",
	));

//Permissions
$lang = array_merge($lang,array(
	"PERMISSION_CHAR_LIMIT"			=> "Tên quyền phải có độ dài từ %m1% tới %m2% ký tự",
	"PERMISSION_NAME_IN_USE"		=> "Đã có tên quyền %m1% trong hệ thống",
	"PERMISSION_DELETIONS_SUCCESSFUL"	=> "Xóa thành công quyền %m1%",
	"PERMISSION_CREATION_SUCCESSFUL"	=> "Tạo thành công quyền`%m1%`",
	"PERMISSION_NAME_UPDATE"		=> "Đã đổi tên quyền thành `%m1%`",
	"PERMISSION_REMOVE_PAGES"		=> "Đã loại bỏ thành công quyền truy cập tới trang %m1%",
	"PERMISSION_ADD_PAGES"			=> "Đã thêm thành công quyền truy cập tới trang %m1%",
	"PERMISSION_REMOVE_USERS"		=> "Đã xóa thành công tài khoản %m1%",
	"PERMISSION_ADD_USERS"			=> "Đã thêm thành công tài khoản %m1%",
	"CANNOT_DELETE_NEWUSERS"		=> "Không thể xóa nhóm mặc định 'new user'",
	"CANNOT_DELETE_ADMIN"			=> "Không thể xóa nhóm mặc định 'admin'",
	));
?>