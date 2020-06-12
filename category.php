<?
	//import common file
	require("controller/db_config.php");
	require("controller/common_func.php");
	require('controller/rating_draw.php');
	require('controller/product_controller.php');
	require('controller/product_tag_controller.php');
	$tag=new product_tag();
	$rs_hot_tag=$tag->get_hot_tags($conn);

	$pd=new product();

	$rs_hot_product=$pd->get_hot_product($conn);
	$rs_cate_list=$pd->get_category_list($conn);

	if(isset($_SESSION['admin_id']) && $_GET['id']=='-1'){
		$rs_pd=$pd->get_hide_product($conn);
		$new_pd_class="active";
	}else{


		if(!$pd->is_category_exist($_GET['id'],$conn)){

				$_GET['id']=0;
				$cate_class="active";
				$page_title="所有壁貼商品-";
				$meta_desc="各式藝術壁貼,創意壁貼 類別:綠意鄉村,翻滾童趣,古典華麗,時尚樂活,意象空間,節日主題 ";
		}

		$rs_pd=$pd->get_cate_product($_GET['id'],$conn);
	}


	switch($_GET['id']){
		case 1:
		$page_title="分類商品-綠意鄉村";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:綠意鄉村";
		break;

		case 2:
		$page_title="分類商品-翻滾童趣";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:翻滾童趣";
		break;

		case 3:
		$page_title="分類商品-古典華麗";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:古典華麗";
		break;

		case 4:
		$page_title="分類商品-時尚樂活";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:時尚樂活";
		break;

		case 5:
		$page_title="分類商品-意象空間";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:意象空間";
		break;

		case 6:
		$page_title="分類商品-節日主題";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:節日主題";
		break;

		case 7:
		$page_title="分類商品-日製商品";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:日製商品";
		break;

		default:
		$page_title="所有壁貼商品-";
		$meta_desc="各式藝術壁貼,創意壁貼 類別:綠意鄉村,翻滾童趣,古典華麗,時尚樂活,意象空間,節日主題 ";
	}

	//set var
	$GLOBALS["FULL_PAGE"]='yes';

	//import pages
	require("header.php");
	require("category_content.php");
	require("footer.php");

?>