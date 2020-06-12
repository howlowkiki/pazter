<?
	session_start();
	//import common file
	require("controller/common_func.php");

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("oops_content.php");
	require("footer.php");

?>
