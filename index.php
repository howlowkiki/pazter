<?
	session_start();
	require("controller/db_config.php");
	require("controller/common_func.php");
	require('controller/product_controller.php');
	require('controller/banner_controller.php');
	
	$pd	=	new product();
	$ban	=	new banner();
	$rs_banner	=	$ban->get_banner_data($conn);
	$rs_rand_pd	=	$pd->get_rand_product($conn);
	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$meta_desc="首頁-壁貼哪裡買?PAZTER.COM提供日本,韓國等優質 壁貼 為您的美學品味加分,溫暖真摯,趣味華麗,堆疊生活裡的每一個美好。";
	$page_title="首頁-優質壁貼為您的美學品味加分-";
	//import pages
	require("header.php");

	require("index_content.php");
	require("footer.php");

?>
