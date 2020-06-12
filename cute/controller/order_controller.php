<?
session_start();
//require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
require('common.php');
is_admin();
	
class order{
	
	//檢查是否加入訂單
	public function is_order_exist($order_id){		
		if( !is_numeric($order_id) || strlen($order_id)!=14){return false;}
		$order_date=substr($order_id,0,8);
		$order_seq=substr($order_id,8,6);		
		$str_sql="SELECT order_seq FROM orders WHERE order_seq='".$order_seq."' AND order_date='".$order_date."'";
		
		$result=mysql_query($str_sql);		
		if(mysql_num_rows($result) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//	更新訂單狀態	
	public function update_order_status($order_id,$order_status){
		if(!$this->is_order_exist($order_id)){return false;}
		$order_date=substr($order_id,0,8);
		$order_seq=substr($order_id,8,6);
		$sql="UPDATE orders SET order_status='".$order_status."' WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
		
		if($result=mysql_query($sql)){			
			if($order_status==4){
//				$this->send_shiping_mail($order_id);
			}
			return true;
		}else{
			return false;
		}
	}
	
	//	訂單序號格式
	public function order_seq_format($seq){
		return sprintf("%06s",$seq);
	}
	
	//	取得訂單編號
	public function get_order_id($seq){
		return $this->order_id=$this->today.$seq;
	}
	
	//	取得訂單總金額
	public function get_total_price($order_id){
		
		$total_price;
		$str_sql="SELECT op.product_id,op.product_qty,p.product_price 
							FROM order_products as op
							LEFT JOIN products as p
							ON op.product_id=p.product_id
							WHERE op.order_id='".$order_id."'";	
		$result=mysql_query($str_sql);
		
		while($row=mysqli_fetch_array($result)){
			$total_price+=$row['product_qty']*$row['product_price'];
		}
		return $total_price;
		
	}
	
	
	
	//	刪除訂單
	public function delete_order($order_id){
		
		$str_query = "DELETE FROM orders WHERE order_seq = '" . substr($order_id,8,6) . "' AND order_date = '" . substr($order_id,0,8) . "'";		
		mysql_query($str_query) or die('Error, delete query failed');
		
	}
	

	
	//	取得收貨人資料
	public function get_ship_data($order_id){
		$str_sql="SELECT * FROM ship_order WHERE order_id='".$order_id."'";		
		$result=mysql_query($str_sql);		
		return mysqli_fetch_array($result);		
	}
	
	//取得訂單記錄年份
	public function get_order_year(){
		$sql="SELECT LEFT(order_date,4) AS y FROM orders GROUP BY y ORDER BY y DESC";
		return mysql_query($sql);		
	}
	
	//取得訂單記錄月份
	public function get_order_month($y){
		$y=mysql_real_escape_string(trim($y));
		$sql="SELECT SUBSTR(order_date,5,2) AS m FROM orders WHERE LEFT(order_date,4)='".$y."' GROUP BY SUBSTR(order_date,5,2) ORDER BY SUBSTR(order_date,5,2) DESC";
		
		return mysql_query($sql);
	}
	
	//	取得月份訂單
	public function get_order_by_month($y,$m){
		$sql="SELECT * FROM orders WHERE  LEFT(order_date,4)='".$y."' AND SUBSTR(order_date,5,2)='".$m."' AND order_status!='5' ORDER BY order_date DESC";
		
		return mysql_query($sql);
	}
	
	//	取得訂單商品資料
	public function get_order_list($order_id){
		
		$str_sql="SELECT op.product_id,op.product_qty,op.product_color,op.etc_data,pd.product_name,pd.product_image,pd.product_price 
							FROM order_products as op LEFT JOIN products as pd ON op.product_id=pd.product_id 
							WHERE op.order_id='".$order_id."' ";
									
		$result=mysql_query($str_sql);		
		return $result;		
	}
	
	//	取得付款(折扣)金額
	public function get_discount_price($order_id){
		$order_date=mb_substr($order_id,0,8);
		$order_seq=mb_substr($order_id,8,6);
		$sql="SELECT total_price,discount,pay_price FROM orders 
					WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";		
		if($result=mysql_query($sql)){
			return mysqli_fetch_array($result);
		}else{
			return false;
		}
	}
	
	//寄送出貨通知
	public function send_shiping_mail($od_id){		
		$order_date=mb_substr($od_id,0,8);
		$order_seq=mb_substr($od_id,8,6);
		$sql="SELECT user_id FROM orders WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
		
		if(!($result = mysql_query($sql) and mysql_num_rows($result)>0)){
			return false;
		}else{
			$row=mysqli_fetch_array($result);			
		}
		
		
		$sql_ship="SELECT name,zipcode,address,etc_data FROM ship_order WHERE order_id='".$od_id."'";
		$result_ship=mysql_query($sql_ship);
		$row_ship=mysqli_fetch_array($result_ship);
		$row_ship['name']=mb_substr($row_ship['name'],0,1,"UTF-8")."**";
		$row_ship['address']=mb_substr($row_ship['address'],0,6,"UTF-8")."********";
		$to      = $row['user_id'];
		$subject = 'PAZTER 出貨通知 【訂單編號】'.$od_id;
		$subject="=?UTF-8?B?". base64_encode($subject)."?=";

		$message =	'
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>PAZTER.COM</title>
		</head>
		<body bgcolor="#F7F7F7">
		<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7">
		  <tr>
		    <td><table width="700" height="155" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		      <tr>
		        <td height="50" background="http://pazter.com/images/header_bg.gif">&nbsp;
		          </td>
		        <td height="50" background="http://pazter.com/images/header_bg.gif"><a href="http://pazter.com"><img src="http://pazter.com/images/logo.png" alt="home" border="0" /></a></td>
		      </tr>
		      <tr>
		        <td width="44" height="37">&nbsp;</td>
		        <td width="656"><span style="font-size:12px; color:#333333;">親愛的會員您好：</span></td>
		      </tr>
		      <tr>
		        <td>&nbsp;</td>
		        <td>
		        	<p style="font-size:12px; color:#333333;">您訂購的商品已為您寄出，訂單編號為:'.$od_id.'</p>
							<p style="font-size:12px; color:#333333;">為保護個人隱私，出貨通知信不會顯示訂單明細，訂單明細可由<a href="http://www.pazter.com">pazter.com</a>登入後查詢。。</p>							
		        	<p style="font-size:12px; color:#333333;">收件人:'.$row_ship['name'].'</p>
		        	<p style="font-size:12px; color:#333333;">寄送地址:'.$row_ship['zipcode'].$row_ship['address'].'</p>
		        	<p style="font-size:12px; color:#333333;">如有問題請利用<a href="http://pazter.com/contact.php">連絡我們</a>，或直接回信給我們，我們將盡快為您處理。</p>
		        	<p style="font-size:12px; color:#333333;">'.$row_ship['etc_data'].'</p>
		        </td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td><div align="center"><img src="http://pazter.com/images/email_footer.jpg" border="0"></div></td>
		  </tr>
		</table>
		</body>
		</html>';

			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type:text/html; charset=utf-8\r\n";
			$headers .= "From:".mb_encode_mimeheader('PAZTER SERVICE', 'UTF-8')." < service@pazter.com >\r\n"; //optional headerfields
			
			if(mail($to, $subject, $message, $headers)) return true;

		}
		
		public function get_order_color_name($color_id){
			$sql="SELECT color FROM product_color WHERE color_id='".$color_id."'";
			$result=mysql_query($sql);
			if($result){
				$arr_name=mysqli_fetch_array($result);
				return $arr_name['color'];
			}else{
				return false;
			}			
		}	
		
	
}

?>