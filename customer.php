<?php
	session_start();
	//import common file
	require("controller/user_controller.php");

	//set vars
	if(isset($_SESSION['user_email'])){
		$member=new user();
		$user_data=$member->get_user_data();
		$my_cart=new cart();
		$cart_result=$my_cart->get_order_list();

		if($member->is_ship_data($_SESSION['user_email'])){
			$next_step_css="order_next_step";
			$next_step_link=$page_links['pay'];
		}else{
			$next_step_css="order_next_step_disabled";
			$next_step_link="javascript:;";
		}

		if($user_data['user_tel']==""){
			$user_data['user_tel']="請至我的帳戶填寫聯絡電話";
		}
		if($user_data['user_add']==""){
			$user_data['user_add']="請至我的帳戶填寫配送地址";
		}
	}


	//set var
	$GLOBALS["FULL_PAGE"]='yes';

	//import pages
	require("header.php");

	if(isset($_SESSION['user_email'])){
		require("customer_content.php");
	}else{
		require("customer_login_content.php");
	}
	require("footer.php");

?>