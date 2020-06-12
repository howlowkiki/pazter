<?php
	session_start();
	//import common file
	require("controller/common_func.php");

	$my_cart=new cart($conn);
	$cart_result=$my_cart->get_order_list($conn);

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="會員中心/購物車-";
	$meta_desc="會員中心/購物車";

	//import pages
	require("header.php");
	require("cart_content.php");
	require("footer.php");

?>