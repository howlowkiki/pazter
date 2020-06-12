<?
	//import common file
	require("controller/common_func.php");
	require("controller/product_controller.php");

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="合作提案-";
	$meta_desc="若您有任何整合行銷上的合作提案，歡迎與我們聯絡！";
	//import pages
	require("header.php");
	require("corp_content.php");
	require("footer.php");

?>
