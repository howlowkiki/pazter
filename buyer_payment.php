<?php
	session_start();
	//import common file
	require("controller/user_controller.php");

	$cart=new cart();
	$user	=	new user();
	if($cart->check_num_basket()==0){
		page_togo("cart.php");
	}else{
		$cart_result=$cart->get_order_list();
	}

	//付款SESSION
	$_SESSION['payment']="no";


	if(	$_POST['token'] != $_SESSION['token']	){
		page_togo("oops.php");
	}
	if(	$_POST['buyer_name']=="" || mb_strlen($_POST['buyer_name'])>20){
			page_togo("oops.php");
	}
	if(	$_POST['buyer_tel']=="" || mb_strlen($_POST['buyer_tel'])>20){
			page_togo("oops.php");
	}
	if(	$_POST['buyer_email']=="" || mb_strlen($_POST['buyer_email'])>50 || !$user->is_email_format($_POST['buyer_email'])){
			page_togo("oops.php");
	}
	if(	$_POST['zip_county'][0]=="" || mb_strlen($_POST['zip_county'][0])>10){
		page_togo("oops.php");
	}
	if(	$_POST['zip_area'][0]=="" || mb_strlen($_POST['zip_area'][0])>10){
			page_togo("oops.php");
	}
	if(	$_POST['zip_code'][0]=="" || mb_strlen($_POST['zip_code'][0])>5 || !is_numeric($_POST['zip_code'][0])){
			page_togo("oops.php");
	}
	if(	$_POST['buyer_add']=="" || mb_strlen($_POST['buyer_add'])>80){
			page_togo("oops.php");
	}
	$_SESSION['buyer']=array(
		'name'=>mysql_real_escape_string($_POST['buyer_name']),
		'tel'=>mysql_real_escape_string($_POST['buyer_tel']),
		'email'=>mysql_real_escape_string($_POST['buyer_email']),
		'zip_county'=>mysql_real_escape_string($_POST['zip_county'][0]),
		'zip_area'=>mysql_real_escape_string($_POST['zip_area'][0]),
		'zip_code'=>mysql_real_escape_string($_POST['zip_code'][0]),
		'add'=>mysql_real_escape_string($_POST['buyer_add'])
	);

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("buyer_payment_content.php");
	require("footer.php");

?>