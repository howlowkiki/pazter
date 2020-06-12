<?php
	
	require('db_config.php');
	
	class product{

		public function product(){
		}

		//	檢查商品分類參數
		public function is_category_exist($id,$conn){
			if(!is_numeric($id) || strlen($id)>2){return false;}
			$id=mysqli_real_escape_string($conn,$id);
			$id=sprintf('%02d',$id);
			$sql="SELECT type_id FROM product_type WHERE type_id='".$id."' ";
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return true;
			}else{
				return false;
			}
		}

		//	取得分類項目
		public function get_category_list($conn){
			$sql="SELECT type_id,type_name FROM product_type ORDER BY type_id";
			if($result=mysqli_query($conn,$sql)){
				return $result;
			}
		}

		//	取得分類商品
		public function get_cate_product($type,$conn){
			if(!is_numeric($type) || strlen($type)>2){return false;}
			if($type==0){
				$sql="SELECT product_id,product_name,product_price,product_image FROM products WHERE display='1' ORDER BY product_id DESC";
			}else{
				$type=mysqli_real_escape_string($conn,$type);
				$type=sprintf('%02d',$type);
				$sql="SELECT product_id,product_name,product_price,product_image FROM products WHERE product_type='".$type."' AND display='1'";
			}

			$result=mysqli_query($conn,$sql);
			return $result;
		}

		//	無庫存及不顯示商品列表
		public function get_hide_product($conn){

			$sql="SELECT product_id,product_name,product_price,product_image FROM products WHERE display='0' OR stock='0'  ORDER BY product_id DESC";
			$result=mysqli_query($conn,$sql);
			return $result;
		}

		//  新商品資料
		public function get_hide_product_detail($id,$conn){
			$sql="SELECT product_id,product_type,product_name,product_image,product_price,product_size,nation,
										product_material,product_val,product_feature,product_color,stock,sold
						FROM products WHERE product_id='".$id."'";
			if($result=mysqli_query($conn,$sql)){
				return $row=mysqli_fetch_array($result);
			}else{
				return false;
			}
		}

		//檢查有無此商品
		public function is_product_exist($id,$conn){
			if(!is_numeric($id) || strlen($id)>14){return false;}
			$id=mysqli_real_escape_string($conn,$id);
			$sql="SELECT product_id FROM products WHERE product_id='".$id."'";
			if( !($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0) ){
				return false;
			}
			return true;
		}

		//	取得商品詳細資料
		public function get_product_detail($id,$conn){
			if(	!is_numeric($id) || strlen($id)>8){
				return false;
			}

			$id=mysqli_real_escape_string($conn,$id);
			if(!$this->is_product_exist($id,$conn)){
				return false;
			}

			$sql="SELECT product_id,product_type,product_name,product_image,product_price,product_size,nation,
										product_material,product_val,product_feature,product_color,stock,sold
						FROM products WHERE product_id='".$id."' AND display='1'";
			if($result=mysqli_query($conn,$sql)){
				return $row=mysqli_fetch_array($result);
			}else{
				return false;
			}
		}

		//	取得產品標籤
		public function get_product_tags($pd_id,$conn){
			$id=mysqli_real_escape_string($conn,$pd_id);
			$sql="SELECT seq,tag FROM tags WHERE product_id LIKE '%".$pd_id."%' ";
			return mysqli_query($conn,$sql);
		}

		//取得產品圖片欄位的每一個檔名
		public function get_product_image_name($str_img){
			$arr_img_name	=	explode(",",trim($str_img));
			return $arr_img_name;
		}

		//取得產品的顏色
		public function get_product_color_name($str_color,$conn){

			$first_color=true;
			$arr_color=explode(",",$str_color);

			foreach( $arr_color as $value) {
	    	if($first_color){
	    		$str_color=" color_id='".$value."' ";
	    	}else{
	    		$str_color=$str_color." OR color_id='".$value."' ";
	    	}
	    	$first_color=false;
		  }
		  $sql="SELECT color_id,color FROM product_color WHERE ".$str_color;
		  return mysqli_query($conn,$sql);
		}

		//	前5熱門商品
		public function get_hot_product($conn){
			$sql="SELECT products.product_id AS id,products.product_name AS name,products.product_image AS image,products.sold AS sold,
						page_counter.counter AS counter
						FROM products LEFT JOIN page_counter ON products.product_id=page_counter.id ORDER BY sold DESC  LIMIT 5";
			return $result=mysqli_query($conn,$sql);
		}

		//	取得產品顏色 RGB
		public function get_product_color_rgb($str_color,$conn){
			$arr_color=explode(",",$str_color);
			for(	$i=0	;	$i< count($arr_color)	;	$i++){
				if($i==0){
					$str_where=" color_id='".$arr_color[$i]."' ";
				}else{
					$str_where=$str_where." OR color_id='".$arr_color[$i]."' ";
				}
			}
			$sql="SELECT color_id,web_rgb FROM product_color WHERE ".$str_where;
			return mysqli_query($conn,$sql);
		}

		//隨機取得5個產品
		public function get_rand_product($conn){
			$sql="SELECT product_id,product_name,product_image FROM products
						WHERE display='1' ORDER BY RAND() LIMIT 5";
			return mysqli_query($conn,$sql);
		}

	}
?>