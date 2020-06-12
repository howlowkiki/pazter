<?
session_start();
require("controller/common_func.php");
require('controller/rating_draw.php');
require('controller/product_controller.php');
require('controller/product_tag_controller.php');

$pd=new product();
$rs_hot_product=$pd->get_hot_product($conn);
if(isset($_SESSION['admin_id'])){
	$row_pd=$pd->get_hide_product_detail($_GET['id'],$conn);
	$arr_image_name	=	$pd->get_product_image_name($row_pd['product_image']);
	$image_400_1	=	($arr_image_name[2]	==	"")	?	"default_img_400_1.jpg"	:	$arr_image_name[2];
	$image_400_2	=	($arr_image_name[3]	==	"")	?	""	:	$arr_image_name[3];
	$image_400_3	=	($arr_image_name[4]	==	"")	?	""	:	$arr_image_name[4];

	foreach($row_pd as &$value){
		$value=stripslashes($value);
	}
	unset($value);

	//進口地圖示
	switch($row_pd['nation']){
		case "韓國":
			$row_pd['nation_icon']="icon_korea.png";
			break;
		case "日本":
			$row_pd['nation_icon']="icon_japan.png";
			break;
		default:
			$row_pd['nation_icon']="icon_taiwan.png";
	}
}else{
	if($pd->get_product_detail($_GET['id'],$conn)){
		$row_pd=$pd->get_product_detail($_GET['id'],$conn);
		$arr_image_name	=	$pd->get_product_image_name($row_pd['product_image']);
		$image_400_1	=	($arr_image_name[2]	==	"")	?	"default_img_400_1.jpg"	:	$arr_image_name[2];
		$image_400_2	=	($arr_image_name[3]	==	"")	?	""	:	$arr_image_name[3];
		$image_400_3	=	($arr_image_name[4]	==	"")	?	""	:	$arr_image_name[4];
				
		foreach($row_pd as &$value){
			$value=stripslashes($value);
		}
		unset($value);

		//進口地圖示
		switch($row_pd['nation']){
			case "韓國":
				$row_pd['nation_icon']="icon_korea.png";
				break;
			case "日本":
				$row_pd['nation_icon']="icon_japan.png";
				break;
			default:
				$row_pd['nation_icon']="icon_taiwan.png";
		}
	}else{
		page_togo('oops.php');
	}
}

$rs_cate_list=$pd->get_category_list($conn);
$tag=new product_tag();
$rs_hot_tag=$tag->get_hot_tags($conn);
$rs_pd_tag=$pd->get_product_tags($row_pd['product_id'],$conn);

set_page_counter($_GET['id'],$conn);
//set var
$GLOBALS["FULL_PAGE"]='yes';
$page_title="壁貼產品-".$row_pd['product_name']."-";
$meta_desc="產品名稱:".$row_pd['product_name']." 材質:".$row_pd['product_material']." 售價:".$row_pd['product_price']." 進口地:".$row_pd['nation'];
//import pages
require("header.php");
require("sticker_content.php");
require("footer.php");

?>