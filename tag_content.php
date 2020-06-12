<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
	<div id="content_wrap">
<div id="color_tag_column"><!--		COLOR_TAG_COLUMN	-->
        	<div class="tag_title_wrap">
            	<div class="tag_title">壁貼標籤搜尋</div></div>
            <form action="<?=$page_links['tag']?>" method="get" id="form_tag_search">
		        		<input name="tag" value="粉紅 , love" type="text" class="tag_search_text" id="tag" onFocus="if (value =='粉紅 , love'){value =''}" />
                <input type="submit" value="GO" class="tag_search_submit" />
            </form>

          <h3>顏色標籤</h3>
          <div id="tag_color_select"><!--	TAG_COLOR_SELECT	-->
			      <a href="<?=$page_links['tag']?>?tag=red,紅" class="color_tag_red" title="RED"></a>
			      <a href="<?=$page_links['tag']?>?tag=pink,粉紅" class="color_tag_pink" title="PINK"></a>
			      <a href="<?=$page_links['tag']?>?tag=purple,紫" class="color_tag_purple" title="PURPLE"></a>
			      <a href="<?=$page_links['tag']?>?tag=blue,藍"  class="color_tag_blue" title="BLUE"></a>
			      <a href="<?=$page_links['tag']?>?tag=green,綠" class="color_tag_green" title="GREEN"></a>
			      <a href="<?=$page_links['tag']?>?tag=yellow,黃" class="color_tag_yellow" title="YELLOW"></a>
			      <a href="<?=$page_links['tag']?>?tag=orange,橘" class="color_tag_orange" title="ORANGE"></a>
			      <a href="<?=$page_links['tag']?>?tag=brown,咖啡,棕" class="color_tag_brown" title="BROWN"></a>
			      <a href="<?=$page_links['tag']?>?tag=gray,灰" class="color_tag_gray" title="GRAY"></a>
			      <a href="<?=$page_links['tag']?>?tag=white,白" class="color_tag_white" title="WHITE"></a>
			      <a href="<?=$page_links['tag']?>?tag=black,黑" class="color_tag_black" title="BLACK"></a>
          </div><!--	TAG_COLOR_SELECT	-->

          <h3>熱門標籤</h3>
						<div  class="hot_tag_wrap"><!--		TAG_WRAP	-->
<?
	while($row_tag=mysqli_fetch_array($rs_hot_tag)){
?>
                            <a class="tag_butt" href="<?=$page_links['tag']."?tag=".$row_tag['tag']?>"><?=$row_tag['tag']?>(<?=$tag->key_length_to_num($row_tag['key_len'])?>)</a>
<?
	}
?>
					</div>
<!--		TAG_WRAP	-->
	  </div><!--		COLOR_TAG_COLUMN	-->

<?
	if($_SESSION['SEARCH']){
		$num_search_result=mysqli_num_rows($search_result);
?>
 	    <div id="search_content"><!--	SEARCH RESULT	-->
       	<div class="tag_title_wrap" id="search_title_wrap"><div class="tag_title" >我們找到了<?=$num_search_result ?>項壁貼產品</div></div>
<?
		$pd_count=1;
		while($row=mysqli_fetch_array($search_result) and mysqli_num_rows($search_result)>0	){
			$pd_class=($pd_count%4==0)?"product_item":"product_item_right";
			$arr_image_name	=	$pd->get_product_image_name($row['product_image']);
			$image_140	=	($arr_image_name[1]=="")	?	"default_img_140.jpg"	:	$arr_image_name[1];
			$row['product_name']=stripslashes($row['product_name']);
?>
			  <div class="<?=$pd_class?>">
          <a href="detail.php?id=<?=$row['product_id']?>" title="加入購物車" class="item_add"></a>
					<a href="detail.php?id=<?=$row['product_id']?>"><img src="p_images/<?=$image_140?>" /></a>
					<a href="detail.php?id=<?=$row['product_id']?>"><div class="item_name"><?=$row['product_name']?></div></a>
					<div class="item_price">售價:$<?=$row['product_price']?></div>
        </div>
<?
		}
		if($num_search_result==0){
?>
			  <div class="product_item">
          <a href="javascript:;" title="加入購物車" class="item_add"></a>
					<a href="javascript:;"><img src="p_images/default_img_140.jpg" /></a>
					<a href="javascript:;"><div class="item_name">找不到這個東西。</div></a>
					<div class="item_price">售價:$999999</div>
        </div>
<?
		}
?>
	  </div><!--	SEARCH RESULT	-->
<?
	}
?>

  <div id="tag_content"><!--	TAG_CONTENT	-->
    	   <div class="tag_title_wrap" >
        	<div class="tag_title" >所有標籤</div>
        </div>
   <?
  		$rs_group=$tag->get_keyval_group($conn);
  		$max_keyval_len=$tag->get_max_keyval($conn);
  		$min_keyval_len=$tag->get_min_keyval($conn);
  		while($row_group=mysqli_fetch_array($rs_group)){

   ?>
         <div id="tag_item"><!--	TAG ITEM	-->
         	<div id="tag_strokes"><?=strtoupper($row_group['key_val'])?></div>
          <div id="tag_list_wrap">
   <?
   			$rs_item=$tag->get_keyval_item($row_group['key_val'], $conn);
   			while($row_item=mysqli_fetch_array($rs_item)){
   				$width=$tag->get_tag_csswidth($row_item['len'],$max_keyval_len,$min_keyval_len);
   ?>
           	<a href="<?=$page_links['tag']?>?tag=<?=$row_item['tag']?>">
             	<span class="show_count" style="width:<?=$width?>px">%</span>
               	<div class="my_tag">
                   <span class="count_num"><?=$tag->key_length_to_num($row_item['len'])?></span>
                 	<span class="tag_name"><?=$row_item['tag']?></span>
                 </div>
             </a>
	<?
				}
	?>
   				</div>
       	</div><!--	TAG ITEM	-->
 	<?
 			}
 	?>
		</div><!--	TAG_CONTENT	-->


  </div>
</div>
