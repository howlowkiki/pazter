<?php
	session_start();
	//import common file
	require("controller/user_controller.php");
	$member=new user();
	$user_data=$member->get_user_data();
	//set var
	is_refer_domain($page);
	if(!$member->is_user_login()){
		page_togo($page_links['user_login']);
	}
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="會員中心/我的帳戶-";
	$meta_desc="會員中心/我的帳戶";
	//import pages
	require("header.php");
	require("user_info_content.php");
	require("footer.php");

?>