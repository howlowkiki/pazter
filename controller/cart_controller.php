<?/*
Page:           cart_func.php
Created:        July 2010
Last Mod:
Original File:	ajax_slick_shopping_cart_jquery_php
---------------------------------------------------------
Modify items:
									Customers can add multi-items to cart
									More variables check

		http://www.pazter.com
		virkler@gmail.com

UPDATE:
		2010/09/15
		-> ALL USER USE PHPSESSID TO ADD BASKETS
			 NO MORE RECORD USER_ID
--------------------------------------------------------- */
if(!isset($_SESSION)){session_start();}
require('db_config.php');
setcookie('PHPSESSID', session_id(), time()+60*60*24*3);

class cart{
	
	public $cart_session_id,
				 $cart_product_id,
				 $cart_product_name,
				 $cart_product_price,
				 $cart_qty,
				 $product_color,
				 $total_itme,
				 $total_count,
				 $total_price,
				 $no_javascript,
				 $user_login,
				 $image_40,
				 $msg;
		
		public function cart($conn){
			
			$this->cart_session_id = $_COOKIE['PHPSESSID'];			

			if($_POST['action'] != '' || $_GET['action'] != '') {
				
				if($_POST['action'] == '')	//	IF METHOD BY GET
				{
					$this->cart_product_id	= mysqli_real_escape_string($conn,(trim($_GET['product_id'])));					
					$this->cart_qty	= mysqli_real_escape_string($conn,(trim($_GET['p_qty'])));
					$this->no_javascript = true;
					
				} else {	//IF METHOD BY POST
				
					$this->cart_product_id = mysqli_real_escape_string($conn,(trim($_POST['product_id'])));
					$this->cart_qty	= mysqli_real_escape_string($conn,(trim($_POST['p_qty'])));
					$this->no_javascript = false;
					
				}
			}
			
		}
		//	檢查數量格式
		public function is_qty_format(){
			if( !(is_numeric($this->cart_qty) && $this->cart_qty!=0) ){
				$this->cart_qty=1;
			}			
			return true;			
		}
		
		//	檢查產品編號格式
		public function is_product_format(){
			if( !(is_numeric($this->cart_product_id) && $this->cart_product_id!=0) ){
				$this->msg="-20";
				return false;
			}else{
				return true;
			}
		}

		
		//取得產品名稱 單價 及圖片路徑		
		public function get_product_data($conn){
			
			if(	strlen($this->cart_product_id)>8 ||	!is_numeric($this->cart_product_id)){
				$this->msg="-30";
				return false;
			}
			
			$str_query  = "SELECT product_name,product_price,product_image FROM products WHERE product_id = '".$this->cart_product_id."'";
			
			if($result	=	mysqli_query($conn,$str_query)	and mysqli_num_rows($result)	>	0){
				$row = mysqli_fetch_array($result);
				$this->cart_product_name=$row['product_name'];
				$this->cart_product_price=$row['product_price'];
				$arr_img	=	explode(",",$row['product_image']);
				$this->image_40	=	($arr_img[0]=="")	?	"default_img_40.jpg"	:	$arr_img[0];
				return true;
			}else{
				$this->msg="-30";
				return false;
			}
			
		}
		
		//檢查產品顏色
		public function is_product_color($pd_id,$color_id,$conn){
			if(	!is_numeric($color_id)	|| $color_id < 0 || $color_id > 99 ){return false;}
			$sql="SELECT product_color FROM products WHERE product_id='".$pd_id."'";			
			if($result=mysqli_query($conn,$sql)){				
				$color_data=mysqli_fetch_array($result);
				$arr_color=explode(",",$color_data['product_color']);
				
				if(in_array($color_id,$arr_color)){					
					return true;
				}else{					
					return false;
				}
			}else{
				return false;
			}
		}
		
		//取得產品顏色
		public function get_product_color_name($color_id,$conn){
			$sql="SELECT color FROM product_color WHERE color_id='".$color_id."'";
			$result=mysqli_query($conn,$sql);
			if($result){
				$arr_name=mysqli_fetch_array($result);
				return $arr_name['color'];
			}else{
				return false;
			}			
		}
		
		//加入購物車
		public function add_to_basket($data_type,$conn){
						
			if( !($this->is_qty_format() && $this->is_product_format())){
				exit();
			}			
			
			if(!$this->get_product_data($conn)){
				exit();
			}
			if(!$this->is_product_color($this->cart_product_id,$this->product_color,$conn)){
				exit();
			}
			$color_name=$this->get_product_color_name($this->product_color,$conn);
			
			$str_sql="SELECT product_id FROM baskets WHERE basket_session='".$this->cart_session_id."' AND product_id='".$this->cart_product_id."' AND product_color='".$this->product_color."' ";
			
			if($result = mysqli_query($conn,$str_sql) and mysqli_num_rows($result)	>	0){
				
				if($data_type=="text"){	//	購物清單直接update數量
					$str_sql="UPDATE baskets SET product_count=".$this->cart_qty." WHERE basket_session='".$this->cart_session_id."' AND product_id='".$this->cart_product_id."' AND product_color='".$this->product_color."' ";
				}else{
					$str_sql="UPDATE baskets SET product_count=product_count+".$this->cart_qty." WHERE basket_session='".$this->cart_session_id."' AND product_id='".$this->cart_product_id."' AND product_color='".$this->product_color."' ";
				}
				
			}else{
				$str_sql="INSERT INTO baskets (product_id, basket_session, product_count,product_color) VALUES ('".$this->cart_product_id."', '".$this->cart_session_id."',	'".$this->cart_qty."','".$this->product_color."')";
			}
			
			$result=mysqli_query($conn,$str_sql);
			if (!$result) {			// IF INSERT FAILED
				$this->msg="-40";
 		  	die('Invalid query: ' . mysql_error());
 		  	return false;
 		  	exit();
			}				
			
			$str_item_sum= "SELECT SUM(product_count) AS total_item FROM baskets WHERE product_id = " . $this->cart_product_id . 
											" AND basket_session = '" . $this->cart_session_id . "' AND product_color = '".$this->product_color."'";

			$result_subtotal = mysqli_query($conn,$str_item_sum);
			$row = mysqli_fetch_array($result_subtotal);
			$this_item_qty = $row['total_item'];				
			$this_item_price = $this_item_qty * $this->cart_product_price;
			
			if($this->no_javascript){
				header("Location: ../detail.php?p=".$this->cart_product_id."");
				return true;
			}else{
				if($data_type=="html"){
					echo
						'<li id="product_id_' . $this->cart_product_id . '_'.$this->product_color.'">
							<img class="p_icon" src="p_images/'.$this->image_40.'" />
							<div class="p_name">'.$this->cart_product_name.'('.$color_name.')</div>
							<div class="p_qty">數量：'.$this_item_qty.'</div>
							<div class="p_price">$'.$this_item_price.'-</div>
							<div class="p_delete">
							<a href=controller/cart_func.php?action=deleteFromBasket&product_id=' . $this->cart_product_id . ' onClick="return false;"><img src="images/delete.png" id="deleteid_' . $this->cart_product_id .'_'.$this->product_color.'"></a></div>												
						</li>';
				}else{
					echo $this_item_price;
				}
				return true;
			}
			
		}
		
		//刪除購物車裡面的項目
		public function delete_from_basket($conn){
			if(!$this->is_product_format() || strlen($this->cart_product_id)>8){
				return false;
			}
			
			$str_query = "DELETE FROM baskets WHERE product_id = '" . $this->cart_product_id . "' AND basket_session = '" . $this->cart_session_id . "' AND product_color='".$this->product_color."'";			
			mysqli_query($conn,$str_query) or die('Error, delete query failed');
		
			if ($noJavaScript == 1) {
				// IF NO JAVASCRIPT
				header("Location: ../detail.php?p=".$this->cart_product_id."");
				return true;
			}else{
				$this->msg="20";
				return true;
			}
		}
		
		//取得總計欄位
		public function get_total_data($conn){

			$str_query  = "SELECT SUM(baskets.product_count) AS item_count,products.product_price AS item_price FROM baskets LEFT JOIN products ON baskets.product_id=products.product_id WHERE baskets.basket_session = '".$this->cart_session_id."' GROUP BY baskets.product_id";				
			
			if($result = mysqli_query($conn,$str_query) and mysqli_num_rows($result) >	0){
				while($row = mysqli_fetch_array($result))
				{
					$item_count=$row['item_count'];
					$item_price=$row['item_price'];
					$sum_itmes+=$item_count;
					$sum_price+=($item_count * $item_price);					
				}
				echo ('<li><div class="p_sum_qty">總數：' . $sum_itmes . ' </div> <div class="p_sum_price">總價：$' . $sum_price . '-</div><a href="cart.php" id="buy_now" class="p_buy">結帳</a></li>');
			}else{
				echo ('<li class="empty_item">目前沒有加入任何項目</li>');
			}
			
		}
		
		//取得上方購物車顯示欄
		public function get_basket($conn){

			$str_query  = "SELECT baskets.product_id AS product_id,baskets.product_color AS product_color,SUM(baskets.product_count) AS item_count,
											products.product_price AS item_price,products.product_name as product_name,products.product_image as product_image 
											FROM baskets 
											LEFT JOIN products ON baskets.product_id=products.product_id WHERE baskets.basket_session = '".$this->cart_session_id."' GROUP BY baskets.product_id,baskets.product_color";

			if($result = mysqli_query($conn,$str_query) and mysqli_num_rows($result)	>	0){
				while($row = mysqli_fetch_array($result)){
					
					$product_id	=	$row['product_id'];
					$product_color	=	$row['product_color'];
					$product_name	=	$row['product_name'];
					$item_count	=	$row['item_count'];
					$item_price	=	$row['item_price'];
					$arr_img	=	explode(",",$row['product_image']);
					$image_40	=	($arr_img[0]=="")	?	"default_img_40.jpg"	:	$arr_img[0];					
					$sum_itmes+=$item_count;
					$sum_price+=($item_count * $item_price);
					$color_name=$this->get_product_color_name($product_color,$conn);
					$basket_text = $basket_text . 
												'<li id="product_id_' . $product_id . '_'.$product_color.'">
													<img class="p_icon" src="p_images/'.$image_40.'" />
													<div class="p_name">'.$product_name.'('.$color_name.')</div>
													<div class="p_qty">數量：'.$item_count.'</div>
													<div class="p_price">$'.$item_count * $item_price.'-</div>
													<div class="p_delete">														
														<a href="javascript:;" onClick="return false;"><img src="images/delete.png" id="deleteid_' . $product_id . '_'.$product_color.'"></a>
													</div>												
												</li>';
				}
												
				
					$basket_text=$basket_text.
										'<li><div class="p_sum_qty">總數：' . $sum_itmes . ' </div> <div class="p_sum_price">總價：$' . $sum_price . '-</div><a id="buy_now" href="cart.php" class="p_buy">結帳</a></li>';
										
					echo $basket_text;
					
			}else{
				echo ('<li class="empty_item">目前沒有加入任何項目</li>');
			}
			
		}
		
		//取得購物車清單
		public function get_order_list($conn){								
			$str_query  = "SELECT baskets.product_id AS product_id,baskets.product_color AS product_color,SUM(baskets.product_count) AS item_count,products.product_price AS item_price,
											products.product_name as product_name,products.product_image as product_image 
											FROM baskets LEFT JOIN products ON baskets.product_id=products.product_id 
											WHERE baskets.basket_session = '".$this->cart_session_id."' GROUP BY baskets.product_id,baskets.product_color";
											
			$result = mysqli_query($conn,$str_query);
			return 	$result;
		}
		//取得購物車總計欄位
		public function get_order_sum_data(){

			$str_query  = "SELECT SUM(baskets.product_count) AS item_count,products.product_price AS item_price FROM baskets LEFT JOIN products ON baskets.product_id=products.product_id WHERE baskets.basket_session = '".$this->cart_session_id."' GROUP BY baskets.product_id";
			
			if($result = mysqli_query($conn,$str_query) and mysqli_num_rows($result)	>	0){
				while($row = mysqli_fetch_array($result))
				{
					$item_count=$row['item_count'];
					$item_price=$row['item_price'];
					$sum_itmes+=$item_count;
					$sum_price+=($item_count * $item_price);					
				}
				echo $sum_itmes . '_'.$sum_price;
			}else{
				echo "0";
			}
			
		}
		
		//	清空購物車
		public function empty_basket($conn){
			
			$str_query = "DELETE FROM baskets WHERE basket_session = '" . $this->cart_session_id . "'";			
			mysqli_query($conn,$str_query) or die('Error, delete query failed');		
		
			echo $this->msg="30";			
			return true;
		}
		
		//檢查購物車是不是空的
		public function check_num_basket($conn){
			$str_sql="SELECT COUNT(*) AS num FROM baskets WHERE basket_session='".$this->cart_session_id."'";
			$result=mysqli_query($conn,$str_sql);
			$row=mysqli_fetch_array($result);
			return $row['num'];
		}
		
		//非會員優惠
		public function get_buyer_discount($conn){
				$str_query  = "SELECT SUM(baskets.product_count) AS item_count,products.product_price AS item_price 
					FROM baskets LEFT JOIN products ON baskets.product_id=products.product_id 
					WHERE baskets.basket_session = '".$this->cart_session_id."' GROUP BY baskets.product_id";
					
				if($result = mysqli_query($conn,$str_query) and mysqli_num_rows($result)	>	0){
					while($row = mysqli_fetch_array($result))
					{
						$item_count=$row['item_count'];
						$item_price=$row['item_price'];
						$sum_itmes+=$item_count;
						$sum_price+=($item_count * $item_price);					
					}
					
					$today=date("Ymd");
					if(	$today	>	20110131	){
						//3000打9折
						if( $sum_price >= 3000 ){
							$discount	=	0.9;
							$pay_price	=	intval($sum_price*$discount);
						}else{						
							$pay_price	=	$sum_price;
						}
					}else{
						//3件以上打9折
						if( $sum_itmes >= 3 ){
							$discount	=	0.9;
							$pay_price	=	intval($sum_price*$discount);
						}else{						
							$pay_price	=	$sum_price;
						}
					}
					return $pay_price;				
			}
		}
		
		
}
////////////////////////////////////////////////////////////////
//
//		MESSEAGE	CODE:
//
//								10	:	
//								20	:	刪除成功
//								30	:	清空購物車
//								-10	:	數量格式不正確
//								-20	:	產品編號不正確
//								-30	:	無此產品資料
//								-40	:	資料新增失敗
//								-50	:	查無此帳號資料
//								-999	:	
if(isset($_POST)){


	if($_POST['action']=="add_to_basket"){	
		$user_cart = new cart($conn);
		$user_cart->cart_product_id=$_POST['product_id'];
		$user_cart->cart_qty=$_POST['product_qty'];
		$user_cart->product_color=$_POST['product_color'];
		$user_cart->add_to_basket($_POST['data_type'],$conn);
	}
	if($_POST['action']=="get_total_data"){	
		$user_cart = new cart($conn);	
		$user_cart->get_total_data($conn);
	}
	if($_POST['action']=="delete_item"){	
		$user_cart = new cart($conn);
		$user_cart->cart_product_id=$_POST['product_id'];
		$user_cart->product_color=$_POST['product_color'];
		$user_cart->delete_from_basket($conn);
	}
	if($_POST['action']=="get_order_sum_data"){	
		$user_cart = new cart($conn);
		$user_cart->get_order_sum_data($conn);	
	}
	if($_POST['action']=="empty_basket"){	
		$user_cart = new cart($conn);
		$user_cart->empty_basket($conn);	
	}


}

?>