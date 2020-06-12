<?php
	//import common file
	require("controller/db_config.php");
	require("controller/blog_controller.php");
	$article=new blog();

	//	取得分類文章
	$rs_arti=$article->get_lately_article($conn);

	if(isset($_GET['type'])){
		if($article->get_type_name($_GET['type'], $conn)){
			$sub_title="分類:".$article->get_type_name($_GET['type'], $conn);
			$rs_arti=$article->get_type_article($_GET['type'], $conn);
		}else{
			$rs_arti=$article->get_lately_article($conn);
		}
	}

	//取得月份文章
	if(isset($_GET['m'])){
		if($article->get_monthly_article($_GET['m'], $conn)){
			$rs_arti=$article->get_monthly_article($_GET['m'], $conn);
			$title_y=mb_substr($_GET['m'],0,4);
			$title_m=mb_substr($_GET['m'],4,2);
			$sub_title=$title_y." ".date("M",mktime(0,0,0,$title_m,1,$title_y));
		}else{
			$rs_arti=$article->get_lately_article($conn);
		}
	}
	

	$rs_arti_title=$article->get_lately_article_title($conn);
	$rs_type=$article->get_article_type_name($conn);
	$rs_monthly=$article->get_month_by_article($conn);

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	$page_title	=	" 網誌-".$sub_title." ";
	$meta_desc="pazter.com的生活隨筆,主題活動 包含各類壁貼相關優惠及討論";

	//import pages
	require("header.php");
	require("blog_content.php");
	require("footer.php");

?>