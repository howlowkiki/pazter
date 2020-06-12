<?php
	session_start();
	//import common file

	require("controller/user_controller.php");
	require("controller/order_controller.php");
	$member=new user();
	$user_data=$member->get_user_data();
	$od=new order();

	//set var
	is_refer_domain($page);
	if(!$member->is_user_login()){
		page_togo($page_links['user_login']);
	}
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="會員中心/訂單明細-";
	$meta_desc="會員中心/訂單明細";

	//import pages
	require("header.php");
	require("user_order_content.php");
	require("footer.php");

?>