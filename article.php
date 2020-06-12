<?php
	//import common file
	require("controller/blog_controller.php");
	require_once("controller/product_controller.php");
	$pd	=		new product();
	$article=new blog();
	$rs_arti=$article->get_lately_article($conn);
	$rs_arti_title=$article->get_lately_article_title($conn);
	$rs_type=$article->get_article_type_name($conn);
	$rs_monthly=$article->get_month_by_article($conn);

	if(! $row_arti=$article->get_article_content($_GET['id'], $conn) ){
		page_togo("oops.php");
	}

	$arr_img_path	=	explode(",",$row_arti['pic']);
	$rs_reply=$article->get_reply_commets($_GET['id'], $conn);
	set_page_counter($_GET['id'], $conn);
	$timestamp=strtotime($row_arti['date']);

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$sub_title=$row_arti['title']."-";
	$page_title	=	" 網誌-".$sub_title." ";
	$meta_desc="pazter.com ".$row_arti['title']." 主題壁貼";
	//import pages
	require("header.php");
	require("article_content.php");
	require("footer.php");

?>