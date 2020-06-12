<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">

        <div id="cate_product">
        <div id="cate_left_menu"><!--	CATE LEFT MENU	-->
      		<div id="cate_option">
                <h3>所有商品</h3>
                <ul>
<?
	while($row_cate_list=mysqli_fetch_array($rs_cate_list)){
		$cate_class=($row_pd['product_type']==$row_cate_list['type_id'])?"active":"";
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


		<div id="product_list">
			<h1 id="detail_title"><?=$row_pd['product_name']?></h1>
	      <div id="detail_sub_title">商品編號:<?=$row_pd['product_id']?> | <?=get_page_counter($_GET['id'],$conn)?> views</div>

		<div id="right_bar">

	        <div class="detail_item">
	            <div class="p_item" href="#">尺寸：<?=$row_pd['product_size']?></div>
	            <div class="p_item" href="#">材質：<?=$row_pd['product_material']?></div>
	            <div class="p_item"href="#">內含數量：<?=$row_pd['product_val']?>張</div>
	            <div class="p_item" href="#">售價：<?=$row_pd['product_price']?>元</div>
	          <div class="p_item">購買數量：
	                <select name="p_qty" id="p_qty" >
	                    <option>1</option>
	                    <option>2</option>
	                    <option>3</option>
	                    <option>4</option>
	                    <option>5</option>
	                    <option>6</option>
	                    <option>7</option>
	                    <option>8</option>
	                    <option>9</option>
	                    <option>10</option>
	              </select>
	            </div>
	          <div class="p_item"><span class="color_span">顏色：</span>
              <div id="select_color">
<?
	$rs_rgb=$pd->get_product_color_rgb($row_pd['product_color'],$conn);
	$first_row=true;
	if($rs_rgb){
		while($arr_rgb=mysqli_fetch_array($rs_rgb) and mysqli_num_rows($rs_rgb)>0){
			if($arr_rgb['color_id']==15){$color_bg="background-image: url(images/colors.png);"; }
			if($first_row){
				echo "<a href=\"javascript:;\" id=\"color_".$arr_rgb['color_id']."\" class=\"color_current\" style=\"background-color:".$arr_rgb['web_rgb'].";".$color_bg."\"></a>";
				$first_row=false;
			}else{
				echo "<a href=\"javascript:;\" id=\"color_".$arr_rgb['color_id']."\" style=\"background-color:".$arr_rgb['web_rgb'].";".$color_bg."\"></a>";
			}
		}
	}
?>
						</div>
	          </div>
						<div class="p_item"><span class="color_span">產地/進口地：</span><a id="nation_icon" href="javascript:;" title="<?=$row_pd['nation']?>" alt="<?=$row_pd['nation']?>" style="background-image:url(images/<?=$row_pd['nation_icon']?>);"></a></div>
<?
	if($row_pd['stock']==0){
?>
						<div class="p_item">售完補貨中。<a class="require" href="<?=$page_links['contact']?>?req_id=<?=$row_pd['product_id']?>">《 到貨通知我 》</a></div>
<?
	}else{
?>
	          <a class="p_item" alt="加入購物車" href="javascript:;" onClick="return false;" ><img id="product_<?=$row_pd['product_id']?>" src="images/add_to_cart_button.png"/><div id="notificationsLoader"><img src="images/loader.gif"></div></a>
<?
	}
?>
						<div class="p_item">購買人次：<span class="sold_count"><?=$row_pd['sold']?></span></div>
	          <div class="p_item"><iframe src="http://www.facebook.com/widgets/like.php?href=http://www.pazter.com/<?=$page_links['detail']."?id=".$row_pd['product_id']?>&layout=standard"
        scrolling="no" frameborder="0" style="border:none; width:250px; height:70px"></iframe>
              </div>
	          <div class="p_item">
					<!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style ">
                    <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=pazter" class="addthis_button_compact">Share</a>
                    <span class="addthis_separator">|</span>
                    <a class="addthis_button_preferred_1"></a>
                    <a class="addthis_button_preferred_2"></a>
                    <a class="addthis_button_preferred_3"></a>
                    <a class="addthis_button_preferred_4"></a>
                    </div>
                    <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=pazter"></script>
                    <!-- AddThis Button END -->
              </div>
	        </div>
			<div class="detail_tag">
	            <p>標籤:</p>
<?
	while($row_pd_tag=mysqli_fetch_array($rs_pd_tag)){
?>
	            <a class="tag_butt" href="<?=$page_links['tag']."?tag=".$row_pd_tag['tag']?>"><?=$row_pd_tag['tag']?></a>
<?
	}
?>
            </div>

		</div>


	      <div id="detail_img" ><img src="p_images/<?=$image_400_1?>" alt="<?=$row_pd['product_name']?>圖片" /></div>
	      <div id="detail_memo">
	      	<h3>特色</h3><?=$row_pd['product_feature']?>
	      </div>
<?
	if($image_400_2!=""){
?>
	      <div id="detail_img"><img src="p_images/<?=$image_400_2?>" alt="<?=$row_pd['product_name']?>圖片 2"/></div>
<?
	}
	if($image_400_3!=""){
?>
	      <div id="detail_img"><img src="p_images/<?=$image_400_3?>" alt="<?=$row_pd['product_name']?>尺寸圖"/></div>
<?
	}
?>
				<div class="reply_form">
					<div id="fb-root"></div><script src="http://connect.facebook.net/zh_TW/all.js#appId=205407766137194&amp;xfbml=1"></script><fb:comments href="http://pazter.com/sticker.php?id=<?=$_GET['id']?>" num_posts="10" width="660"></fb:comments>
				</div>
	  </div>
      </div>

</div>