<?php
	session_start();
	//import common file
	require("controller/user_controller.php");
	require("controller/order_controller.php");

	//付款SESSION
	$_SESSION['payment']="no";
	$member=new user();

	//	會員購買

		if(!$member->is_user_login()){
			page_togo($page_links['user_login']);
		}
		// 檢查配送資料
		if(!$member->is_ship_data($_SESSION['user_email'])){
			page_togo($page_links['customer']);
		}

		$_GET['od']=mysql_real_escape_string(trim($_GET['od']));
		$od=new order();

		//檢查­訂單 no.
		if($_GET['od']!="" && $od->is_order_exist($_GET['od'])){

			//檢查訂單owner
			if($od->is_od_owner($_GET['od'])){
				//	取得訂單商品資料
				$order_result=$od->get_order_list($_GET['od']);
				//	取得配送資料
				$ship_data=$od->get_ship_data($_GET['od']);
				//是否為取消訂單
				$cancel_order=$od->is_cancel_order($_GET['od']);
				//	取得付款資料
				$payment_data=$od->get_order_data($_SESSION['user_email'],$_GET['od']);
				//	取得付款(折扣)金額
				$discount_data=$od->get_discount_price($_SESSION['user_email'],$_GET['od']);

				if($payment_data){
					$payment_title="付款狀態";
					$payment_type=array("1"=>"信用卡","2"=>"虛擬帳號轉帳","4"=>"WEB-ATM","9"=>"7-11 ibon","12"=>"全家 FamiPort");
					$payment_status=array("1"=>"已完成付款","2"=>"付款失敗","3"=>"尚未付款");

					if($payment_data['pay_type']=="2" || $payment_data['pay_type']=="9" || $payment_data['pay_type']=="12"){
						//虛擬帳號
						if($payment_data['pay_type']=="2") $offline_payment_no="<a href=\"".$payment_data['bill_url']."\" target=\"_blank\">繳費單</a>";
						//ibon繳費號碼
						if($payment_data['pay_type']=="9") $offline_payment_no="<a href=\"".$payment_data['bill_url']."\" target=\"_blank\">繳費單</a>";
						//FamiPort繳費號碼
						if($payment_data['pay_type']=="12") $offline_payment_no="<a href=\"".$payment_data['bill_url']."\" target=\"_blank\">繳費單</a>";
						$payment_deadline=$payment_data['pay_deadline'];
					}else{
						$offline_payment_no="----";
						$payment_deadline="----";
					}
				}else{
					$payment_title="請選擇付款方式";
				}
			}else{
				page_togo("cart.php");
			}
		}else{	//如果沒有訂單

			$c_cart=new cart();
			$buy_items=$c_cart->check_num_basket();
			//購物車是空的
			if($buy_items==0){
				$member->user_logout();
				page_togo("cart.php");
			}else{
				//新增訂單
				$order_id=$od->add_new_order($_SESSION['user_email']);

				//取得­­配送資料
				$user_data=$member->get_user_data();
				$od->set_ship_data($order_id,$user_data);
				page_togo("payment.php?od=".$order_id);
			}
		}


	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("payment_content.php");
	require("footer.php");

?>