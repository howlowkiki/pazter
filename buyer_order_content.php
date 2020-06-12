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
      <form id="form_payment" name="form_payment" method="post" action="controller/order_controller.php">
      <ul id="order_step_status">
<?
	if(!$cancel_order){
?>
      	<li><a href="#"><span class="selected">1</span>購物清單</a></li>
      	<li><a href="#"><span class="selected">2</span>資料確認</a></li>
      	<li><a href="#"><span class="selected">3</span>付款方式</a></li>
      	<li><a href="#"><span <? if($payment_data) echo "class=\"selected\""; ?>>4</span>訂單完成</a></li>

<?
	}else{
?>
      	<li><a href="#"><span>1</span>購物清單</a></li>
      	<li><a href="#"><span>2</span>資料確認</a></li>
      	<li><a href="#"><span>3</span>付款方式</a></li>
      	<li><a href="#"><span>4</span>訂單完成</a></li>
<?
	}
?>
		</ul>
	  <div id="order_step_content"><!--	ORDER_STEP_CONTENT	-->
<?
	if($cancel_order){
?>
      <div id="cancel_order_msg">訂單已取消</div>
<?
	}
?>
      <!--	PRODUCT INFO	-->
         <h3 class="item_margin">商品明細</h3>
	     <table class="order_list_table">
          <tr>
            <th width="405">商品</th>
            <th width="83">價格</th>
            <th width="91">數量</th>
            <th width="91">小計</th>
          </tr>
          <!--	IF CART NOT EMPTY -->
          <tr><td height="60" colspan="4"><div id="payment_show_detail">顯示詳細清單</div></td></tr>

<?

		while($row = mysqli_fetch_array($order_result)){
			$arr_img	=	explode(",",$row['product_image']);
			$img_40	=	($arr_img[0]=="")	? "default_img_40.jpg"	:	$arr_img[0];
			$sum_itmes+=$row['product_qty'];
			$sum_price+=($row['product_price'] * $row['product_qty']);
			$color_name=$order->get_order_color_name($row['product_color']);
?>
          <tr id="od_<?=$row['product_id']?>" class="payment_hidel_block">
            <td height="60" colspan="4">
                <table border="1" cellpadding="6" class="list_item">
                  <tr>
                    <td width="9%" rowspan="2"><img class="order_item_img" src="p_images/<?=$img_40?>"/></td>
                    <td width="51%"><p class="product_describe"><?=$row['product_name']?>(<?=$color_name?>)</p></td>
                    <td width="13%"><p><?=$row['product_qty']?></p></td>
                    <td width="14%"><p><?=$row['product_price']?></p></td>
                    <td width="13%"><p id="subtotal_<?=$row['product_id']?>"><?=$row['product_price']*$row['product_qty']?></p></td>
                  </tr>
                  <tr>
                    <td height="30" colspan="4">&nbsp;</td>
                  </tr>
                </table>
            </td>
          </tr>
<?
		}

?>
          <tr id="sum_column">
            <td height="60" colspan="4">
   	          <div class="payment_total_col">消費總金額：NT $<span class="price_total_style"><?=$discount_data['total_price']?></span>元</div>
   	          <div class="payment_total_col">折扣金額：NT $<span class="price_total_style"><?=$discount_data['total_price']-$discount_data['pay_price']?></span>元</div>
   	          <div class="payment_total_col">合計金額：NT $<span class="price_normal_style"><?=$discount_data['pay_price']?></span>元</div>
			  <div class="payment_total_col">商品合計：共 $<span class="price_normal_style"><?=$sum_itmes?></span>件</div>
   				</td>
          </tr>

        </table>

        <!--	PRODUCT INFO	-->

		<div class="buyer_data"><!--	BUYER_DATA	-->
		<h3>收件人資料</h3>
          <div class="buyer_data_left">
            <p class="send_col"><span class="send_data"><?=$ship_data['name']?></span><span class="send_title">姓名：</span></p>
            <p class="send_col"><span class="send_data"><?=$ship_data['tel']?></span><span class="send_title">電話：</span></p>
            <p class="send_col"><span class="send_data"><?=$_SESSION['buyer_email']?></span><span class="send_title">E-MAIL：</span></p>
          </div>
          <div class="buyer_data_right">
            <p class="send_col"><span class="send_data"><?=$ship_data['zipcode']?></span><span class="send_title">郵遞區號：</span></p>
            <p class="send_col"><span class="send_data"><?=$ship_data['address']?></span><span class="send_title">住址：</span></p>
          </div>
<?
//	有效訂單
if(!$cancel_order){

	if($payment_data){
?>
          <h3><?=$payment_title?></h3>
            <table class="payment_table">
            	<tr>
					<th>訂單編號</th>
					<th>付款方式</th>
					<th>付款期限</th>
					<th>付款狀態</th>
					<th>繳費(轉帳)單號</th>
				</tr>
            	<tr>
					<td><?=$_GET['od']?></td>
					<td><?=$payment_type[$payment_data['pay_type']]?></td>
					<td><?=$payment_deadline?></td>
					<td><?=$payment_status[$payment_data['pay_status']]?></td>
					<td><?=$offline_payment_no?></td>
				</tr>
            </table>
<?
	}
}
?>
        </div><!--	BUYER_DATA	-->

      </div><!--	ORDER_STEP_CONTENT	-->

        </form>
        <div class="clear"></div>
    </div>
		<div class="pay_method_icons"></div>
  </div>
</div>