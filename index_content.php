<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
    <div id="content">
      <div class="slider">
      	<div class="option_button">
        	<div id="nav"></div>
        </div>
          <div id="s4img" class="show_img">
<?
	while($banner_data=mysqli_fetch_array($rs_banner)){
?>
                <a href="<?=$banner_data['link']?>"><img src="<?=$banner_data['image_path']?>" width="700px" height="240px" /></a>
<?
	}
?>
          </div>
          <div class="slide_shadow"></div>
      </div>
    </div>

    <div id="head_line_wrap">
<?
	$item_num=1;
	$item_right_class="";
	while($row_pd=mysqli_fetch_array($rs_rand_pd)){
		$arr_image_name=$pd->get_product_image_name($row_pd['product_image']);
		$img_140=($arr_image_name[1]=="")?"default_img_140.jpg":$arr_image_name[1];
		$row_pd['product_name']=stripslashes($row_pd['product_name']);
		$alt=$row_pd['product_name'];


/*		if(mb_strlen($row_pd['product_name'],'UTF-8')>10){
			$row_pd['product_name']	=	mb_substr($row_pd['product_name'],0,10,'UTF-8');
			$row_pd['product_name']	=	$row_pd['product_name']."...";
		}else{
			$row_pd['product_name']	=	stripslashes($row_pd['product_name']);
		}*/

		
		$row_pd['product_name']	=	stripslashes($row_pd['product_name']);
		


		if(	$item_num==5 ){$item_right_class="product_item_right";}
?>
	<div class="product_item <?=$item_right_class?>">
 		<a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>" title="加入購物車" class="item_add"></a>
      <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>" title="<?=$row_pd['product_name']?>" alt="<?=$row_pd['product_name']?>">
      	<div class="pd_img" src="p_images/<?=$img_140?>" title="<?=$row_pd['product_name']?>" alt="<?=$row_pd['product_name']?>">
      	</div>
      </a>
		<a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>"><h2 class="item_name"><?=$row_pd['product_name']?></h2></a>
		<div class="fb_like_button"><iframe src="http://www.facebook.com/widgets/like.php?href=http://www.pazter.com/<?=$page_links['detail']."?id=".$row_pd['product_id']?>&layout=button_count"
        scrolling="no" frameborder="0" style="border:none; width:200px; height:80px"></iframe>
    </div>
    </div>
<?
		$item_num++;
	}
?>
    </div>

	<div id="category">
		<ul id="accordion">
		  <li><a href="<?=$page_links['category']."?id=1"?>" class="cate_1">
              <div class="accordion_op_bg"></div>
			  <div id="accordion_title_1">綠意鄉村</div>
			  <div id="accordion_en_title">Nature / Country</div>
              <img class="default_shadow" title="綠意鄉村" src="images/accordion_shadow.png" /></a>
          </li>
		  <li><a href="<?=$page_links['category']."?id=2"?>" class="cate_2">
              <div class="accordion_op_bg"></div>
			  <div id="accordion_title_2">翻滾童趣</div>
			  <div id="accordion_en_title">Forever Young</div>
              <img class="default_shadow" title="翻滾童趣" src="images/accordion_shadow.png" /></a>
          </li>
		  <li><a href="<?=$page_links['category']."?id=3"?>" class="cate_3">
              <div class="accordion_op_bg"></div>
			  <div id="accordion_title_3">古典華麗</div>
			  <div id="accordion_en_title">Classic / Luxury</div>
              <img class="default_shadow" title="古典華麗" src="images/accordion_shadow.png" /></a>
          </li>
		  <li><a href="<?=$page_links['category']."?id=4"?>" class="cate_4">
              <div class="accordion_op_bg"></div>
			  <div id="accordion_title_4">時尚樂活</div>
			  <div id="accordion_en_title">Urban</div>
              <img class="default_shadow" title="時尚樂活" src="images/accordion_shadow.png" /></a>
          </li>
		  <li><a href="<?=$page_links['category']."?id=5"?>" class="cate_5">
              <div class="accordion_op_bg"></div>
			  <div id="accordion_title_5">意象空間</div>
			  <div id="accordion_en_title">Design</div>
              <img class="default_shadow" title="意象空間" src="images/accordion_shadow.png" /></a>
          </li>
		</ul>
  </div>
</div>
