<?
	//import common file
	require("controller/common_func.php");
	require("controller/product_controller.php");

	if(isset($_GET['req_id'])){
		$pd	=	new product();
		if($pd->is_product_exist($_GET['req_id'])){
			if($pd->get_product_detail($_GET['req_id'])){
				$row=$pd->get_product_detail($_GET['req_id']);
				$msg="商品編號:".$_GET['req_id']."  ".$row['product_name']."  到貨時請通知我。";
			}else{
				page_togo($page_links['contact']);
			}
		}
	}

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="連絡我們-";
	$meta_desc="對pazter.com有任何疑問,您可以透過這個頁面將訊息傳遞給我們.";
	//import pages
	require("header.php");
	require("contact_content.php");
	require("footer.php");

?>
