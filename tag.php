<?
	//import common file
	require("controller/product_tag_controller.php");
	require("controller/product_controller.php");
	require("controller/search_controller.php");

	$_SESSION['SEARCH']=false;

	if(	isset($_GET['tag']) && $_GET['tag']!=""){
		$search_tag=str_replace(" ","",$_GET['tag']);
		$search_tag=mysqli_real_escape_string($conn,$search_tag);
		$tag_names=stripslashes($search_tag);
		$tag_search = new search();
		$search_result	=	$tag_search-> search_tag($search_tag, $conn);
	}
	if(!$search_result){
		$tag_names="";
	}else{
		$_SESSION['SEARCH']=true;
	}

	$tag	=	new product_tag();
	$pd		=	new product();
	$rs_hot_tag=$tag->get_hot_tags($conn);

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title="壁貼標籤搜尋:".$tag_names." -";
	if($tag_names!=""){
		$meta_desc="pazter.com-搜尋標籤:".$tag_names;
	}else{
		$meta_desc="pazter.com-可以利用各種關鍵字標籤來搜尋您想要的商品";
	}
	//import pages
	require("header.php");
	require("tag_content.php");
	require("footer.php");

?>