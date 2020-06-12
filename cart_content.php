<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
  <div id="content_wrap">
    <div id="user_menu">
      <div id="user_option">
      	<?=get_member_center_list()?>
      </div>
    </div>
    <div id="user_wrap">
      <ul id="order_step_status">
      	<li><a href="javascript:;"><span class="selected">1</span>購物清單</a></li>
      	<li><a href="javascript:;"><span>2</span>資料確認</a></li>
      	<li><a href="javascript:;"><span>3</span>付款方式</a></li>
      	<li><a href="javascript:;"><span>4</span>訂單完成</a></li>
      </ul>
	  <div id="order_step_content">
	    <table class="order_list_table">
          <tr>
            <th width="405">商品</th>
            <th width="83">價格</th>
            <th width="91">數量</th>
            <th width="91">小計</th>
          </tr>
          <!--	IF CART NOT EMPTY -->
<?
	$is_exist_order=false;

	if($cart_result and mysqli_num_rows($cart_result)	>	0){

		$is_exist_order=true;
		while($row = mysqli_fetch_array($cart_result)){
			$arr_img	=	explode(",",$row['product_image']);
			$img_40	=	($arr_img[0]=="")	? "default_img_40.jpg"	:	$arr_img[0];
			$sum_itmes+=$row['item_count'];
			$sum_price+=($row['item_count'] * $row['item_price']);
			$color_name=$my_cart->get_product_color_name($row['product_color'], $conn);
?>
          <tr id="od_<?=$row['product_id'].'_'.$row['product_color']?>">
            <td height="60" colspan="4">
                <table border="1" cellpadding="6" class="list_item">
                  <tr>
                    <td width="9%" rowspan="2"><img class="order_item_img" src="p_images/<?=$img_40?>"/></td>
                    <td width="51%"><p class="product_describe"><?=$row['product_name']?>(<?=$color_name?>)</p></td>
                    <td width="13%"><p><?=$row['item_price']?></p></td>
                    <td width="14%">
                    	<select id="qty_<?=$row['product_id'].'_'.$row['product_color']?>" class="qty_column">
<?
	for($i=1;$i<=10;$i++){
		if($i==$row['item_count']){
			echo '<option value="'.$i.'" selected >'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'" >'.$i.'</option>';
		}
	}
?>
						</select>
                    </td>
                    <td width="13%"><p id="subtotal_<?=$row['product_id'].'_'.$row['product_color']?>"><?=$row['item_price']*$row['item_count']?></p></td>
                  </tr>
                  <tr>
                    <td height="30" colspan="4"><a id="del_<?=$row['product_id'].'_'.$row['product_color']?>" href="javascript:;" class="delete_item">刪除</a></td>
                  </tr>
                </table>
            </td>
          </tr>
<?
		}

?>
          <tr id="sum_column">
            <td height="60" colspan="4">
    	        <div id="order_sum_title">總計</div>
                <img id="loading_mini" src="images/loading_mini.gif" />
    	        <div class="order_total_col">商品合計：共 <span id="sum_qty" class="price_normal_style"><?=$sum_itmes?></span>件</div>
   	          <div class="order_total_col">合計金額：NT $<span id="sum_price" class="price_normal_style"><?=$sum_price?></span>元</div>
   	          <div class="order_total_col">運費：NT $<span class="price_normal_style">0</span>元</div>
   	          <div class="order_total_price">消費總金額：NT $<span id="total_price" class="price_total_style"><?=$sum_price?></span>元</div>
   				<a href="javascript:;" id="empty_basket" class="empty_order">清空購物車</a>            </td>
          </tr>
<?
	}

?>
          <!--	IF CART NOT EMPTY -->

          <!--	IF CART EMPTY-->
          <tr id="order_empty_msg" <? if($is_exist_order){echo 'class="order_empty_msg"';}?> >
            <td height="60" colspan="4"><div align="center" style="padding:100px 0 100px 0;">購物車沒有任何商品。</div></td>
          </tr>
          <!--	IF CART EMPTY-->

        </table>
	  </div>

<?
	if($is_exist_order){
		echo '<a id="order_next_step" class="order_next_step" href="customer.php">下一步</a>';
	}
?>
    </div>
    <div class="pay_method_icons"></div>
  </div>
</div>

