<?

	require_once('controller/common_func.php');
	is_full_page();
	$cate_title_bg_style=($_GET['id']==6)? "class=\"subject_bg\" " : "" ;
?>
<div id="content_bg">

        <div id="cate_product">
        <div id="cate_left_menu"><!--	CATE LEFT MENU	-->
      		<div id="cate_option">
                <h3>產品分類</h3>
                <ul>
<?
	if(isset($_SESSION['admin_id'])){
?>
	<li><a class="<?=$new_pd_class?>" href="<?=$page_links['category']?>?id=-1">NEW</a></li>
<?
	}
?>

                		<li><a class="<?=$cate_class?>" href="<?=$page_links['category']?>">所有商品</a></li>
<?
	while($row_cate_list=mysqli_fetch_array($rs_cate_list)){
		$cate_class=($_GET['id']==intval($row_cate_list['type_id']))?"active":"";
?>
                    <li><a class="<?=$cate_class?>" href="<?=$page_links['category']."?id=".intval($row_cate_list['type_id'])?>"><?=$row_cate_list['type_name']?></a></li>
<?
	}
?>
                </ul>
            </div><!--	ETC ITEM	-->

                <h3>熱門商品</h3>
                <div id="hot_product">
<?
	while($row_hot_pd=mysqli_fetch_array($rs_hot_product)){
		$arr_image_name40=explode(",",$row_hot_pd['image']);
		$row_hot_pd['name']=stripslashes($row_hot_pd['name']);
		$img_40=($arr_image_name40[0]=="")?"default_img_40.jpg":$arr_image_name40[0];
?>
                	<a href="<?=$page_links['detail']."?id=".$row_hot_pd['id']?>">
                        <img src="p_images/<?=$img_40?>" />
                        <div class="item_name"><?=$row_hot_pd['name']?></div>
                        <div class="item_sold"><?=$row_hot_pd['sold']?> 人購買</div>
                        <div class="item_views"><?=$row_hot_pd['counter']?> views</div>
                  </a>
<?
	}
?>
                </div>

		          <h3>熱門標籤</h3>
                    <div  id="detail_hot_tag"><!--		TAG_WRAP	-->
<?
	while($row_tag=mysqli_fetch_array($rs_hot_tag)){
?>
                            <a class="tag_butt" href="<?=$page_links['tag']."?tag=".$row_tag['tag']?>"><?=$row_tag['tag']?>(<?=$tag->key_length_to_num($row_tag['key_len'])?>)</a>
<?
	}
?>
              </div><!--		TAG_WRAP	-->
		  </div><!--	CATE LEFT MENU	-->


        	<div id="product_list" <?=$cate_title_bg_style?> ><!--	PRODUCT LIST	-->
	              <div class="title"><img src="images/category_title_s<?=intval($_GET['id'])?>.png" /></div>
<?
	
	$pd_count=1;


	while($row_pd=mysqli_fetch_array($rs_pd)){

		$pd_class=($pd_count%4==0)?"product_item":"product_item_right";
		$arr_image_name=$pd->get_product_image_name($row_pd['product_image']);
		$img_140=($arr_image_name[1]=="")?"default_img_140.jpg":$arr_image_name[1];
		$row_pd['product_name']=stripslashes($row_pd['product_name']);
		$alt=$row_pd['product_name'];


		if(strlen($row_pd['product_name'])>20){
			$row_pd['product_name']	=	substr($row_pd['product_name'],0,20,'UTF-8');
			$row_pd['product_name']	=	$row_pd['product_name']."...";
		}else{
			$row_pd['product_name']	=	stripslashes($row_pd['product_name']);
		}

?>
                <div class="<?=$pd_class?>">
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>" title="加入購物車" class="item_add"></a>
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>" title="<?=$row_pd['product_name']?>" alt="<?=$row_pd['product_name']?>">
                    	<div class="pd_img" src="p_images/<?=$img_140?>" title="<?=$row_pd['product_name']?>" alt="<?=$row_pd['product_name']?>">
                    	</div>
                    </a>
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>"><h2 class="item_name"><?=$row_pd['product_name']?></h2></a>
                    <div class="item_price">售價:$<?=$row_pd['product_price']?></div>
               </div>
<?
		$pd_count++;
	}
?>


      </div>
  </div><!--	PRODUCT LIST	-->

</div>