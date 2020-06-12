<?
	session_start();	
	require('controller/order_controller.php');
	is_referer_pazter();
	is_admin();
	$od	= new order();
	if(!$od->is_order_exist($_GET['od'])){
		session_destroy();
		page_togo("../index.php");
	}
	$order_result=$od->get_order_list($_GET['od']);
	$discount_data=$od->get_discount_price($_GET['od']);
	$ship_data=$od->get_ship_data($_GET['od']);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cute</title>
<body>
訂單編號：<?=$_GET['od']?>
<table class="order_list_table" border="1px">
  <tr>
    <th width="405">商品</th>
    <th width="83">價格</th>
    <th width="91">數量</th>
    <th width="91">小計</th>
  </tr>
  <!--	IF CART NOT EMPTY -->
  <?
	
		while($row=mysqli_fetch_array($order_result)){
			$arr_img	=	explode(",",$row['product_image']);
			$img_40	=	($arr_img[0]=="")	? "default_img_40.jpg"	:	$arr_img[0];
			$sum_itmes+=$row['product_qty'];
			$sum_price+=($row['product_qty'] * $row['product_price']);			
			$color=$od->get_order_color_name($row['product_color']);
?>
  <tr id="od_<?=$row['product_id']?>" class="payment_hidel_block">
    <td height="60" colspan="4"><table width="100%" border="1" cellpadding="0" cellspacing="0" class="list_item">
      <tr>
        <td><p class="product_describe">
        	<? if($row['etc_data']!="") echo "<font color='red'>".$row['etc_data']."</font><br/>" ?>
          <?=$row['product_id']?>-<a target="_blank" href="http://pazter.com/sticker.php?id=<?=$row['product_id']?>"><?=stripslashes($row['product_name'])?></a>(<?=$color?>)
        </p></td>
        <td width="13%"><p>
          <?=$row['product_price']?>
        </p></td>
        <td width="14%"><p>
          <?=$row['product_qty']?>
        </p></td>
        <td width="13%"><p id="subtotal_<?=$row['product_id']?>">
          <?=$row['product_price']*$row['product_qty']?>
        </p></td>
      </tr>
    </table></td>
  </tr>
  <?
		}
		
?>
  <tr id="sum_column">
    <td height="60" colspan="4"><div class="payment_total_col">消費總金額：NT $<span class="price_total_style">
      <?=$discount_data['pay_price']?>
    </span>元</div>
        <div class="payment_total_col">折扣金額：NT $<span class="price_total_style">
          <?=$discount_data['total_price']-$discount_data['pay_price']?>
        </span>元</div>
      <div class="payment_total_col">合計金額：NT $<span class="price_normal_style">
        <?=$sum_price?>
      </span>元</div>
      <div class="payment_total_col">商品合計：共 $<span class="price_normal_style">
        <?=$sum_itmes?>
      </span>件</div></td>
  </tr>
</table>


	
<p>&nbsp;</p>
<table class="order_list_table" border="1px">
  <!--	IF CART NOT EMPTY -->
  <tr class="payment_hidel_block">
    <td width="670" height="29" colspan="2">收件人資料</td>
  </tr>
  <tr id="sum_column2">
    <td height="29">姓名：<?=$ship_data['name']?></td>
    <td>郵遞區號：<?=$ship_data['zipcode']?></td>
  </tr>
  <tr>
    <td height="29">電話：<?=$ship_data['tel']?></td>
    <td>地址：<?=$ship_data['address']?></td>
  </tr>
  <tr>
    <td height="21">手機：<?=$ship_data['mobile']?></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
