<?php
	session_start();
	//import common file
	require("controller/user_controller.php");
	$member=new user();
	$member->get_user_data();
	//set var
	if($member->is_user_login()){
		page_togo($page_links['user']);
	}
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="登入/註冊/忘記密碼?-";
	$meta_desc="pazter.com會員登入/註冊/忘記密碼";
	//import pages
	require("header.php");
	require("user_login_content.php");
	require("footer.php");

?>