<?php
	session_start();
	//import common file
	require("controller/user_controller.php");
	require("controller/order_controller.php");

		$user=new user();
		$order=new order();

	if($_POST['action']=="query_buyer_order"){

		if( mb_strlen($_POST['buyer_id'])>50 || !$user->is_email_format($_POST['buyer_id'])){
			echo "-90";
				return;
		}
		if( mb_strlen($_POST['buyer_tel'])>50 || !is_numeric($_POST['buyer_tel']) ){
			echo "-90";
			return;
		}
			$order_id=$order->not_member_order_query($_POST['buyer_id'],$_POST['buyer_tel']);
		if( !$order_id ){
			echo "-90";
			return;
		}else{
			if(isset($_SESSION['user_email'])){
				unset($_SESSION['user_email']);
				unset($_SESSION['user_confirm']);
				unset($_SESSION['user_ip']);
			}
			$_SESSION['query_id']=$order_id;
			$_SESSION['buyer_email']=$_POST['buyer_id'];
			echo "10";
			return;
		}
	}else{

		if( isset($_SESSION['query_id']) && $order->is_order_exist($_SESSION['query_id'])){
				//	取得訂單商品資料
				$order_result=$order->get_order_list($_SESSION['query_id']);
				//	取得配送資料
				$ship_data=$order->get_ship_data($_SESSION['query_id']);
				$cancel_order=$order->is_cancel_order($_SESSION['query_id']);
				//	取得付款資料
				$payment_data=$order->get_order_data($_SESSION['buyer_email'],$_SESSION['query_id']);
				//	取得付款(折扣)金額
				$discount_data=$order->get_discount_price($_SESSION['buyer_email'],$_SESSION['query_id']);

		}else{
			page_togo("/");
		}
	}


	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("buyer_order_content.php");
	require("footer.php");

?>