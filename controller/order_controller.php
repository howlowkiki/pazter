<?/*
Page:           order_controller.php
Created:        July 2010
Last Mod:
---------------------------------------------------------
Modify items:

http://www.pazter.com
virkler@gmail.com
--------------------------------------------------------- */
session_start();
require_once('db_config.php');
require_once('common_func.php');
	
class order{
	public 	$order_id,
			$order_seq,
			$product_id,
			$product_qty,
			$user_id,
			$pay_date,
			$send_date,
			$order_status,
			$today,
			$msg,
			$deadline_days=5;
				 
	
	//台灣里付款欄位			 
	public $code1="dd0eeca9ba5e76e1bc468aa21da864ee",
				 $code2="d8c608968b170afc0d4a2b4603d10f1d", 
				 $mid="21130",
				 $version="2.1",
				 $access_key="83964456",
				 $pay_url="https://www.twv.com.tw/openpay/pay.php",
				 $return_url="http://pazter.com/pay_result.php",
				 $amount,$txid,$verify,$mode,$select_paymethod,
				 $prefer_paymethod,$iid,$charset,$language,$description,
				 $description_tchinese,$cid,$cname,$caddress,$ctel,$cemail,
				 $xtype,$xname,$xaddress,$xtel;
	

//		"1"=>"creditcard",
//		"2"=>"vcont",
//		"3"=>"webatm",
//		"8"=>"conv_store",
//		"9"=>"icon",
//		"10"=>"china_bank",
//		"11"=>"pay_pal",
//		"12"=>"fami"

				 
	public function order(){
		$this->today=date("Ymd");
		$this->order_seq="000001";
		$this->basket_session=$_COOKIE['PHPSESSID'];
	}
	
	//	訂單格式	EX : 20101225000001
	public function is_order_id($order_id){		
		if( !is_numeric($order_id) || strlen($order_id)!=14){
			return false;
		}else{
			return true;
		}
	}
	
	//	取得訂單摘要值
	public function get_totwe_verify($user_email,$od_id){
				
		$price=$this->get_discount_price($user_email,$od_id);		
		if(!$this->is_order_id($od_id)){return false;}			
		$msg=$this->code1."|".$this->mid."|".$od_id."|".$price['pay_price']."|".$this->code2;
		$this->verify=md5($msg);		
		return $this->verify;
		
	}
	
	//取得付款金額
	public function get_pay_price($od_id){
		
		$order_date=mb_substr($od_id,0,8);
		$order_seq=mb_substr($od_id,8,6);
		
		$sql="SELECT pay_price FROM orders WHERE order_date='".$order_date."' AND order_seq='".$order_seq."' ";
		
		if( $result=mysqli_query($sql) ){
			$row=mysqli_fetch_array($result);
			return $row['pay_price'];
		}else{
			return false;
		}
		
	}
	
	//	檢查回傳摘要值是否正確
	public function check_return_verify($txid,$pay_type,$status,$tid){		
		if(!$this->is_order_id($txid)){return false;}		
		$pay_price=$this->get_pay_price($txid);		
		$msg=$this->code1."|".$txid."|".$pay_price."|".$pay_type."|".$status."|".$tid."|".$this->code2;		
		$this->verify=md5($msg);		
		return $this->verify;
	}
	
	//檢查是否加入訂單
	public function is_order_exist($order_id){		
		if(!$this->is_order_id($order_id)){return false;}
		$order_date=substr($order_id,0,8);
		$order_seq=substr($order_id,8,6);		
		$str_sql="SELECT order_seq FROM orders WHERE order_seq='".$order_seq."' AND order_date='".$order_date."'";		
		$result=mysqli_query($str_sql);		
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
		$order_seq=substr($order_id,7,6);
		$str_sql="UPDATE order SET order_status='".$order_status."' WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
		
		$result=mysqli_query($str_sql);
		
		if (!$result) {
			die('Invalid query: ' . mysql_error());
			$this->msg = "-10";
			return false;
		}
		return true;
	}
	
	//	付款期限逾時則取消訂單
	public function deadline_cancel_order($order_id,$user_id){
		$order_date=substr($order_id,0,8);
		$order_seq=substr($order_id,7,6);
		$sql="SELECT pay_deadline FROM orders WHERE order_date='".$order_date."' AND order_seq='".$order_seq."' 
					AND user_id='".$user_id."' AND order_status='1' ";
					
		if($result=mysqli_query($sql) and mysql_num_rows($result)>0){			
			$row=mysqli_fetch_array($result);
			$deadline=$row['pay_deadline'];
			$today=date("Y-m-d H:i:s");
			
		}else{
			return;
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
		
		if(!$this->is_order_id($order_id)){return false;}
		$total_price;
		$str_sql="SELECT op.product_id,op.product_qty,p.product_price 
							FROM order_products as op
							LEFT JOIN products as p
							ON op.product_id=p.product_id
							WHERE op.order_id='".$order_id."'";	
		$result=mysqli_query($str_sql);
		
		while($row=mysqli_fetch_array($result)){
			$total_price+=$row['product_qty']*$row['product_price'];
		}
		return $total_price;
		
	}
	
	//	建立訂單 RETURN 訂單序號 FORMAT: 20100911000001
	public function set_order_id($user_email){
		$pay_deadline=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$this->deadline_days,date("Y")));
		$str_sql="SELECT MAX(order_seq) as max_seq FROM orders WHERE order_date='".$this->today."'";
		
		if($result	=	mysqli_query($str_sql)	and mysql_num_rows($result)	>	0){
			
			$row = mysqli_fetch_array($result);
			$this->order_seq = $this->order_seq_format($row['max_seq']+1);
			
			$str_insert="INSERT orders (order_seq,user_id,order_date,order_time,order_status,pay_deadline)
									 VALUES('".$this->order_seq."','".$user_email."','".$this->today."','".date("His")."','1',
									 '".$pay_deadline."')";
			
			if(!$result_insert=mysqli_query($str_insert)){
				$this->set_order_id($user_email);							
			}
			return $this->today.$this->order_seq;
			
		}else{
		
			$str_insert="INSERT orders (order_seq,user_id,order_date,order_time,order_status)
									 VALUES('".$this->order_seq."','".$user_email."','".$this->today."','".date("His")."','1',
									 '".$pay_deadline."')";
			if(!$result_insert=mysqli_query($str_insert)){
				$this->set_order_id($user_email);
			}
				return $this->today.$this->order_seq;
		}
	}
	
		// 訂單產品顏色		
		public function get_order_color_name($color_id){
			$sql="SELECT color FROM product_color WHERE color_id='".$color_id."'";
			$result=mysqli_query($sql);
			if($result){
				$arr_name=mysqli_fetch_array($result);
				return $arr_name['color'];
			}else{
				return false;
			}			
		}	
	
	//	刪除訂單
	public function delete_order($order_id){
		
		$str_query = "DELETE FROM orders WHERE order_seq = '" . substr($order_id,8,6) . "' AND order_date = '" . substr($order_id,0,8) . "'";		
		mysqli_query($str_query) or die('Error, delete query failed');
		
	}
	
	
	// 把購物車商品加到訂單
	public function set_basket_to_order($new_order_id){
		$order_date=mb_substr($new_order_id,0,8);
		$order_seq=mb_substr($new_order_id,8,6);
		
		$str_query  = "SELECT baskets.product_id AS product_id,SUM(baskets.product_count) AS item_count,product_color FROM baskets WHERE baskets.basket_session = '".$this->basket_session."' GROUP BY baskets.product_id";		
		$total_num_item;
		if($result = mysqli_query($str_query) and mysql_num_rows($result) > 0){			
			while($row = mysqli_fetch_array($result)){
					$product_id=$row['product_id'];
					$item_count=$row['item_count'];					
					$str_insert="INSERT order_products (order_id,product_id,product_qty,product_color) VALUES ('".$new_order_id."','".$product_id."','".$item_count."','".$row['product_color']."')";
					mysqli_query($str_insert);
					$total_num_item=$total_num_item+$row['item_count'];
			}
			/*--------------------------------------------
			/*	會員折扣設定			
			/*--------------------------------------------*/
			$discount=0.9;
			$total_price=$this->get_total_price($new_order_id);
			$today=date("Ymd");
			if(	$today	>	20110131	){
				if($total_price	>=3000){
					$pay_price=intval($total_price*$discount);
				}else{
					$pay_price=$total_price;
					$discount=1;
				}
			}else{
				if($total_num_item>=3){
					$pay_price=intval($total_price*$discount);
				}else{
					$pay_price=$total_price;
					$discount=1;
				}
			}
			
			$sql_update="UPDATE orders SET total_price='".$total_price."',discount='".$discount."',pay_price='".$pay_price."' 
										WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
			if(mysqli_query($sql_update)){
				return true;
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
	}
	
	//	清空購物車
	public function set_basket_empty(){
		$str_sql="DELETE FROM baskets WHERE basket_session='".$this->basket_session."'";
				
		if (!mysqli_query($str_sql)) {
			die('Invalid query: ' . mysql_error());
			$this->msg = "-30";
			return false;
		}		
		return true;
	}
	
	//	加入新訂單
	public function add_new_order($user_email){
		
		//取得訂單流水號
		$id=$this->set_order_id($user_email);
		
		//把購物車商品加入訂單		
		if($this->set_basket_to_order($id)){
			//清空購物車
			$this->set_basket_empty();
			return $id;
		}else{			
			$this->delete_order($id);
		}
		
	}
	
	//	取得訂單商品資料
	public function get_order_list($order_id){
		
		$str_sql="SELECT op.product_id,op.product_qty,op.product_color,op.etc_data,pd.product_name,pd.product_image,pd.product_price 
							FROM order_products as op LEFT JOIN products as pd ON op.product_id=pd.product_id 
							WHERE op.order_id='".$order_id."' ";
									
		$result=mysqli_query($str_sql);		
		return $result;		
	}
	
	// 取得訂單總金額及總數量
	public function get_order_total_val($result){
		$total['price']=0;
		$total['qty']=0;
		while($row=mysqli_fetch_array($result)){
			$total['qty']+=$row['product_qty'];
			$total['price']+=$row['product_price']*$row['product_qty'];
		}
		return $total;
	}
	
	//	取得訂單資料
	public function get_order_data($user_email,$order_id){
		$order_date=mb_substr($order_id,0,8);
		$order_seq=mb_substr($order_id,8,6);
		$sql="SELECT order_date,pay_date,pay_type,pay_status,account_no,tid,bill_no,pay_deadline,bill_url,total_price,discount,pay_price,memo 
				FROM orders WHERE
				user_id='". $user_email ."' AND order_date='".$order_date."' AND order_seq='".$order_seq."' AND tid!='' ";
				
		if($result=mysqli_query($sql)and mysql_num_rows($result)>0){
			return mysqli_fetch_array($result);
		}else{
			return false;
		}
	}
	
	//	取得付款(折扣)金額
	public function get_discount_price($user_email,$order_id){
		$order_date=mb_substr($order_id,0,8);
		$order_seq=mb_substr($order_id,8,6);
		$sql="SELECT total_price,discount,pay_price FROM orders 
					WHERE user_id='".$user_email."' AND order_date='".$order_date."' AND order_seq='".$order_seq."'";		
		if($result=mysqli_query($sql)){
			return mysqli_fetch_array($result);
		}else{
			return false;
		}
	}
	//檢查是否為逾期取消訂單
	public function is_cancel_order($order_id){
		$order_date=mb_substr($order_id,0,8);
		$order_seq=mb_substr($order_id,8,6);
		$sql="SELECT order_status FROM orders WHERE order_status='5' AND order_date='".$order_date."' AND order_seq='".$order_seq."'";
		if( $result=mysqli_query($sql) and mysql_num_rows($result)>0 ){
			return true;
		}else{
			return false;
		}
	}
	
	//檢查客戶身分
	public function is_od_owner($order_id){
		if(	!$this->is_order_id($order_id)	){return false;}
		$str_sql="SELECT order_seq FROM orders WHERE order_date='".substr($order_id,0,8)."' AND order_seq='".substr($order_id,8,6)."' AND user_id='".$_SESSION['user_email']."'";
		$result=mysqli_query($str_sql);
		if( mysql_num_rows($result) == 0){
			return false;
		}else{
			return true;
		}
	}
	
	//	寫入收貨人資料
	public function set_ship_data($order_id,$user_data){
		$user_address=$user_data['user_county'].$user_data['user_area'].$user_data['user_add'];
		$str_sql="INSERT INTO ship_order (order_id,name,tel,mobile,zipcode,address)
							VALUES('".$order_id."','".$user_data['user_name']."','".$user_data['user_tel']."',
							'".$user_data['user_mobile']."','".$user_data['user_zipcode']."','".$user_address."')";							
		
		$result=mysqli_query($str_sql);
		if(!$result){
			return false;
		}
		
	}
	
	//	寫入非會員寄送資料
	public function set_buyer_ship_data($od_id,$buyer_data){
		$add=$buyer_data['zip_county'].$buyer_data['zip_area'].$buyer_data['add'];
				
		$sql="INSERT INTO ship_order (order_id,name,tel,zipcode,address)
							VALUES('".$od_id."','".$buyer_data['name']."','".$buyer_data['tel']."',
							'".$buyer_data['zip_code']."','".$add."')";							
		
		$result=mysqli_query($sql);
		if(!$result){
			return false;
		}
	}
	
	
	//	取得收貨人資料
	public function get_ship_data($order_id){
		$str_sql="SELECT * FROM ship_order WHERE order_id='".$order_id."'";		
		$result=mysqli_query($str_sql);		
		return mysqli_fetch_array($result);		
	}
	
	//	顯示最近5筆訂單
	public function get_user_lately_order(){
		$sql="SELECT order_seq,order_date,order_status 
					FROM orders WHERE user_id='".$_SESSION['user_email']."' 
					ORDER BY order_date DESC LIMIT 5";
		if($result=mysqli_query($sql)){
			return $result;
		}
	}
	
	//顯示user全部訂單
	public function get_user_all_order(){
		$sql="SELECT order_seq,order_date,order_status,pay_deadline 
					FROM orders WHERE user_id='".$_SESSION['user_email']."' 
					ORDER BY order_date DESC";
		if($result=mysqli_query($sql)){
			return $result;
		}
	}
	
	
	//顯示日期格式 2010/10/10
	public function show_orderdate_format($str_date){
		return substr($str_date,0,4)."/".substr($str_date,4,2)."/".substr($str_date,6,2);
	}
	
	//更新商品購買數量
	public function update_sold_count($order_id){
		if(!$this->is_order_id($order_id) ) return false;
		$sql_product="SELECT product_id,product_qty FROM order_products WHERE order_id='".$order_id."'";
		$rs_prodcut=mysqli_query($sql_product);
		while($row=mysqli_fetch_array($rs_prodcut)){
			$sql_update="UPDATE products SET sold=sold+".$row['product_qty']." WHERE product_id='".$row['product_id']."'";
			mysqli_query($sql_update);
		}
	}
	
	//送出訂單至金流服務商
	public function order_to_pay($od_id,$method,$user_email){

		$price_data=$this->get_discount_price($user_email,$od_id);		
		$verify=$this->get_totwe_verify($user_email,$od_id);		
		$ship_data=$this->get_ship_data($od_id);
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		echo "<title>PAZTER.COM</title>";
		echo "</head>";
		echo "<body>";
		
		echo "<form name=\"payment\" method=\"post\" action=\"$this->pay_url\">";		
		echo "<input type=\"hidden\" name=\"version\" value=\"$this->version\" />";
		echo "<input type=\"hidden\" name=\"mid\" value=\"$this->mid\" />";
		echo "<input type=\"hidden\" name=\"amount\" value=\"".$price_data['pay_price']."\" />";
		echo "<input type=\"hidden\" name=\"txid\" value=\"$od_id\" />";
		echo "<input type=\"hidden\" name=\"verify\" value=\"$verify\" />";
		echo "<input type=\"hidden\" name=\"mode\" value=\"1\" />";
		echo "<input type=\"hidden\" name=\"select_paymethod\" value=\"$method\" />";
		echo "<input type=\"hidden\" name=\"return_url\" value=\"$this->return_url\" />";
		echo "<input type=\"hidden\" name=\"charset\" value=\"UTF-8\" />";
		echo "<input type=\"hidden\" name=\"description_tchinese\" value=\"\" />";
		echo "<input type=\"hidden\" name=\"cname\" value=\"".$ship_data['name']."\" />";
		echo "<input type=\"hidden\" name=\"caddress\" value=\"".$ship_data['address']."\" />";
		echo "<input type=\"hidden\" name=\"ctel\" value=\"".$ship_data['tel']."\" />";
		echo "<input type=\"hidden\" name=\"cemail\" value=\"".$_SESSION['user_email']."\" />";
		echo "<input type=\"submit\" style=\"color:#FFFFFF; background:#FFFFFF; border:0;\" />";
		echo "</form>";
		echo "<div style=\"-moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; width:300px; height:100px; border: #999999 solid 4px; position:relative; margin:160px auto; \"><img src=\"../images/loader.gif\"  style=\"position:relative; margin:32px 0 0 70px;\"/><div style=\"position:absolute; top:40px; left:110px;\">處理中請稍後....</div></div>";			
		echo "<script type=\"text/javascript\">";	
		echo "  document.payment.submit();";		
		echo "</script>";
		echo "</body></html>";		
		
	}
	
	//非會員購物
	public function buyer_order_to_pay($od_id,$method,$user_email){

		$price_data=$this->get_discount_price($user_email,$od_id);		
		$verify=$this->get_totwe_verify($user_email,$od_id);		
		$ship_data=$this->get_ship_data($od_id);
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		echo "<head>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		echo "<title>PAZTER.COM</title>";
		echo "</head>";
		echo "<body>";

		echo "<form name=\"payment\" method=\"post\" action=\"$this->pay_url\">";		
		echo "<input type=\"hidden\" name=\"version\" value=\"$this->version\" />";
		echo "<input type=\"hidden\" name=\"mid\" value=\"$this->mid\" />";
		echo "<input type=\"hidden\" name=\"amount\" value=\"".$price_data['pay_price']."\" />";
		echo "<input type=\"hidden\" name=\"txid\" value=\"$od_id\" />";
		echo "<input type=\"hidden\" name=\"verify\" value=\"$verify\" />";
		echo "<input type=\"hidden\" name=\"mode\" value=\"1\" />";
		echo "<input type=\"hidden\" name=\"select_paymethod\" value=\"$method\" />";
		echo "<input type=\"hidden\" name=\"return_url\" value=\"$this->return_url\" />";
		echo "<input type=\"hidden\" name=\"charset\" value=\"UTF-8\" />";
		echo "<input type=\"hidden\" name=\"description_tchinese\" value=\"\" />";
		echo "<input type=\"hidden\" name=\"cname\" value=\"".$ship_data['name']."\" />";
		echo "<input type=\"hidden\" name=\"caddress\" value=\"".$ship_data['address']."\" />";
		echo "<input type=\"hidden\" name=\"ctel\" value=\"".$ship_data['tel']."\" />";
		echo "<input type=\"hidden\" name=\"cemail\" value=\"".$user_email."\" />";
		echo "<input type=\"submit\" style=\"color:#FFFFFF; background:#FFFFFF; border:0;\" />";
		echo "</form>";
		echo "<div style=\"-moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; width:300px; height:100px; border: #999999 solid 4px; position:relative; margin:160px auto; \"><img src=\"../images/loader.gif\"  style=\"position:relative; margin:32px 0 0 70px;\"/><div style=\"position:absolute; top:40px; left:110px;\">處理中請稍後....</div></div>";			
		echo "<script type=\"text/javascript\">";	
		echo "  document.payment.submit();";		
		echo "</script>";
		echo "</body></html>";		
	}
	
	//信用卡付款
	public function pay_by_creditcard($od_id,$tid,$pay_type,$pay_status,$account_no){
			$order_date=mb_substr($od_id,0,8);
			$order_seq=mb_substr($od_id,8,6);
		if(!$this->is_order_exist($od_id)){
			page_togo('oops.php');
			return false;
		}else{
			//	order_status=2 完成付款	$pay_status=1 付款成功
			$order_status=($pay_status=='1')?"2":"1";
			$sql="UPDATE orders SET 
					pay_date='". date("Y-m-d H:i:s") ."',
					order_status='".$order_status."',
					pay_type='".$pay_type."',
					pay_status='".$pay_status."',
					tid='".$tid."',
					account_no='".$account_no."'
				  WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
			  
			if(!mysqli_query($sql)){
				return false;
			}else{
				return true;
			}
		}
	}

	
	//使用WEB ATM付款
	public function pay_by_webatm($od_id,$tid,$pay_type,$pay_status){
			$order_date=mb_substr($od_id,0,8);
			$order_seq=mb_substr($od_id,8,6);
		if(!$this->is_order_exist($od_id)){
			page_togo('oops.php');
			return false;
		}else{
			$order_status=($pay_status=='1')?"2":"1";
			$sql="UPDATE orders SET 
					pay_date='". date("Y-m-d H:i:s") ."',
					order_status='".$order_status."',
					pay_type='".$pay_type."',
					pay_status='".$pay_status."',
					tid='".$tid."'
				  WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";
			  
			if(!mysqli_query($sql)){
				return false;
			}else{
				return true;
			}
		}
	}

	//使用7-11ibon 虛擬帳號轉帳 FamiPort 付款
	public function pay_by_offline($od_id,$tid,$pay_type,$pay_status,$account_no,$bill_url){
			$order_date=mb_substr($od_id,0,8);
			$order_seq=mb_substr($od_id,8,6);		
		if(!$this->is_order_exist($od_id)){
			page_togo('oops.php');
			return false;
		}else{
			if($pay_type=="2"){
				$account_str="account_no='".$account_no."', ";
			}
			if($pay_type=="12"){
				$account_str="bill_no='".$account_no."', ";	
			}
			//	order_status=2 完成付款	$pay_status=1 付款成功
			$order_status=($pay_status=='1')?"2":"1";
			$sql="UPDATE orders SET 
					pay_date='". date("Y-m-d H:i:s") ."',
					order_status='".$order_status."',
					pay_type='".$pay_type."',
					pay_deadline='".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$this->deadline_days,date("Y")))."',
					".$account_str."
					pay_status='".$pay_status."',
					tid='".$tid."',
					bill_url='".$bill_url."'
				  WHERE order_date='".$order_date."' AND order_seq='".$order_seq."'";			  
			return mysqli_query($sql);
		}
	}
	
	//	離線付款 付款完成
	public function offline_payment_complete($od_id,$tid,$pay_status){
		$order_date=mb_substr($od_id,0,8);
		$order_seq=mb_substr($od_id,8,6);
		$sql="UPDATE orders SET 
			  order_status='2',
			  pay_status='1',
			  pay_date='". date("Y-m-d H:i:s") ."'
			  WHERE order_date='".$order_date."' AND order_seq='".$order_seq."' AND tid='".$tid."'";

		return mysqli_query($sql);		
	}
	
	public function send_order_mail($od_id,$offline_type){
		
		if(!$this->is_order_id($od_id)){return false;}
		
		$order_date=mb_substr($od_id,0,8);
		$order_seq=mb_substr($od_id,8,6);
		$offline_msg="";
		$sql="SELECT user_id,pay_deadline,bill_url FROM orders 
				WHERE order_date='".$order_date."' AND order_seq='".$order_seq."' ";
				
		if($result=mysqli_query($sql) and mysql_num_rows($result)>0){
			$row=mysqli_fetch_array($result);
			
			$to      = $row['user_id'];
			$subject = 'PAZTER 訂單通知信';
			$subject="=?UTF-8?B?". base64_encode($subject)."?=";
			
			if($offline_type=="offline"){
				$payment_msg='<p style="font-size:12px; color:#333333;">感謝您的訂購，將在完成付款後幾天內為您寄送。</p>
										<p style="font-size:12px; color:#333333;">訂單編號：'.$od_id.'</p>
										<p style="font-size:12px; color:#333333;">付款期限：'.$row['pay_deadline'].'</p>
										<p style="font-size:12px; color:#333333;">繳費單：<a href="'.$row['bill_url'].' target=_blank">繳費資訊</a></p>
										<p style="font-size:12px; color:#333333;">為保護個人隱私，訂單通知信不會顯示訂單明細。</p>
										<p style="font-size:12px; color:#333333;">訂單明細可由pazter.com登入後查詢。</p>
										<p style="font-size:12px; color:#333333;">此為系統自動發出，請勿直接回信。</p>
										';
			}else{
				$payment_msg='<p style="font-size:12px; color:#333333;">感謝您的訂購，訂單已完成付款，將在幾天內為您寄送。</p>
											<p dir="ltr" style="margin-left: 20px; margin-right: 0px"><p style="font-size:12px; color:#333333;">訂單編號：'.$od_id.'</p>
											<p style="font-size:12px; color:#333333;">為保護個人隱私，訂單通知信不會顯示訂單明細。</p>
											<p style="font-size:12px; color:#333333;">訂單明細可由pazter.com登入後查詢。</p>
											<p style="font-size:12px; color:#333333;">此為系統自動發出，請勿直接回信。</p>
											';
			}
									
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
			        	'.$payment_msg.'
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
			
		}else{
			return false;
		}
				
	}
	
	//非會員訂單查詢
	public function not_member_order_query($order_mail,$order_tel){
		
		$order_mail	=	mysql_real_escape_string($order_mail);
		$order_tel	=	mysql_real_escape_string($order_tel);
		$sql_member="SELECT user_email FROM user_profile WHERE user_email='".$order_mail."'";
		$rs_member=mysqli_query($sql_member);
		if(mysql_num_rows($rs_member)>0){	return false;	}
		
		$sql="SELECT CONCAT(order_date,order_seq) AS order_id FROM orders AS od
					LEFT JOIN ship_order AS so ON CONCAT(od.order_date,od.order_seq)=so.order_id 
					WHERE od.user_id='".$order_mail."' AND so.tel='".$order_tel."' 
					ORDER BY order_id DESC LIMIT 1";
		$rs=mysqli_query($sql);
		if(mysql_num_rows($rs)>0){
			$row=mysqli_fetch_array($rs);
			return $row['order_id'];
		}else{
			return false;
		}
		
	}	
	
}
////////////////////////////////////////////////////////////////
//
//		MESSEAGE	CODE:
//
//								10	:	新增訂單成功//	
//								-10	:	BASKETS更新USER_ID 失敗
//								-20	:	尚未登入
//								-30	:	購物車資料清除失敗
//								-40	:	購物車商品加入訂單失敗


//付款方式頁面
if($_POST['action']=="to_pay"){
	
	$arr_url=explode('/',$_SERVER['HTTP_REFERER']);
	$domain=$arr_url[count($arr_url)-2];
	$domain_twv=$arr_url[count($arr_url)-3];
	
	if($_SESSION['payment']!="no"){
		if( !($domain=="pazter" || $domain=="pazter.com" || $domain=="www.pazter.com" || $domain_twv=="www.twv.com.tw") ){
			page_togo('../index.php');
		}else{
			page_togo('../payment.php?od='.$_POST['order_id']);
		}
		
	}else{
		
		$od=new order();	
		if( (!$od->is_order_exist($_POST['order_id'])) || (!$od->is_od_owner($_POST['order_id'])) || !is_numeric($_POST['select_paymethod']) || strlen($_POST['select_paymethod'])>2	){
			page_togo('../index.php');
		}else{
			if(isset($_SESSION['user_email'])){
				$user_email=$_SESSION['user_email'];
			}else{
				$user_email=$_SESSION['buyer_email'];
			}
			$od->order_to_pay($_POST['order_id'],$_POST['select_paymethod'],$user_email);			
		}
		$_SESSION['payment']="yes";
	}
}

//	非會員購賣
if($_POST['action']=="buyer_to_pay"){
	
	if($_SESSION['buyer_token']!=$_POST['buyer_token']){
		session_destroy();
		page_togo('../cart.php');
	}
	
	if($_SESSION['payment']!="no"){
		if( !($domain=="pazter" || $domain=="pazter.com" || $domain=="www.pazter.com" || $domain_twv=="www.twv.com.tw") ){
			page_togo('../index.php');
		}else{
			page_togo('../customer.php');
		}
		
	}else{
		if(!is_numeric($_POST['select_paymethod']) || strlen($_POST['select_paymethod'])>2){
			page_togo('../cart.php');
		}
//		echo $_SESSION['buyer']['name']."<br>";
//		echo $_SESSION['buyer']['tel']."<br>";
//		echo $_SESSION['buyer']['email']."<br>";
//		echo $_SESSION['buyer']['zip_county']."<br>";
//		echo $_SESSION['buyer']['zip_area']."<br>";
//		echo $_SESSION['buyer']['zip_code']."<br>";
//		echo $_SESSION['buyer']['zip_add']."<br>";		
//		echo $_POST['select_paymethod']."<br>";
//		echo $cart->get_buyer_discount()."<br>";

		$od=	new order();
		$cart	=	new cart();
		
		$order_id=$od->add_new_order($_SESSION['buyer']['email']);
		$od->set_buyer_ship_data($order_id,$_SESSION['buyer']);
		$od->buyer_order_to_pay($order_id,$_POST['select_paymethod'],$_SESSION['buyer']['email']);		
		$_SESSION['payment']="yes";
		unset($_SESSION['buyer']);
	}
}
?>