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
   	  <h3>訂購紀錄</h3>
        <table class="order_table">
          <tr>
            <th width="155">訂單編號</th>
            <th width="142">訂單日期</th>
            <th width="143">繳費期限</th>
            <th width="95">數量</th>
            <th width="95">小計</th>
            <th width="52">狀態</th>
          </tr>
          <?
		  	$rs_all=$od->get_user_all_order();
			while($row_lately=mysqli_fetch_array($rs_all)){
				$order_id=$row_lately['order_date'].$row_lately['order_seq'];	//訂單id
				$rs_item_data=$od->get_order_list($order_id);	//取訂單各項商品result
				$total=$od->get_order_total_val($rs_item_data);	//計算總數及價錢
          ?>
          <tr>
            <td><a href="<?=$page_links['pay']."?od=".$order_id?>"><?=$order_id?></a></td>
            <td><?=$od->show_orderdate_format($row_lately['order_date'])?></td>
            <td><?=$row_lately['pay_deadline']?></td>
            <td><?=$total['qty']?></td>
            <td>$<?=$total['price']?></td>
            <td><img src="images/order_status_0<?=$row_lately['order_status']?>.png"/></td>
          </tr>
          <?
		  	}
			if(mysql_num_rows($rs_all)<1){
          ?>
          	<tr>
            	<td colspan="5" height="200"><div align="center">沒有任何訂購紀錄</div></td>
           	</tr>
          <?
		  	}
          ?>
        </table>
   	  <h3>狀態說明</h3>
		<table class="order_status">
          <tr>
            <td width="37"><img src="images/order_status_01.png"/></td>
            <td width="307"><div class="status">新訂單</div>
            <p>這個訂單尚未付款。</p></td>
            <td width="37"><img src="images/order_status_02.png"/></td>
            <td width="309"><div class="status">已付款</div>
            <p>已完成付款，訂單正在確認中。</p></td>
          </tr>
          <tr>
            <td><img src="images/order_status_03.png"/></td>
            <td><div class="status">已確認</div>
            <p>已確認您的資料，將在幾天內出貨。</p></td>
            <td><img src="images/order_status_04.png"/></td>
            <td><div class="status">已出貨</div>
            <p>這個訂單的商品已為您寄出。</p></td>
          </tr>
          <tr>
            <td><img src="images/order_status_05.png"/></td>
            <td><div class="status">已取消</div>
            <p>這個訂單已經取消了。</p></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>

    </div>

	</div>
</div>

