<?php 
	if(!isset($_SESSION)){session_start();}
	require('db_config.php');
	
	class product_tag{


		public function product_tag(){
		}

		
		//	取得KEY_VAL排序GROUP
		public function get_keyval_group($conn){
			$sql="SELECT key_val FROM tags WHERE key_val REGEXP '^[a-z]*$' GROUP BY key_val  UNION 
						SELECT key_val FROM tags WHERE key_val REGEXP '^[0-9]*$' GROUP BY key_val 
ORDER BY CAST(key_val AS UNSIGNED)";
			return $result=mysqli_query($conn,$sql);
		}
		
		//	取得KEY_VAL GROUP的TAG
		public function get_keyval_item($group,$conn){			
			$sql="SELECT tag,LENGTH(product_id) AS len FROM tags WHERE key_val='".$group."'";
			return $result=mysqli_query($conn,$sql);			
		}
		
		//	取得KEY_VAL中最大長度
		public function get_max_keyval($conn){
			$sql="SELECT MAX(LENGTH(product_id)) as x FROM tags";
			$result=mysqli_query($conn,$sql);
			$row=mysqli_fetch_array($result);
			return $row['x'];
		}
		
		//	取得KEY_VAL中最小長度
		public function get_min_keyval($conn){
			$sql="SELECT MIN(LENGTH(product_id)) as x FROM tags";			
			$result=mysqli_query($conn,$sql);
			$row=mysqli_fetch_array($result);
			return $row['x'];
		}
		
		//	取得標籤CSS寬度		
		public function get_tag_csswidth($keyval,$max_keyval,$min_keyval){
			$min_width=100;
			$max_width=610;
			$percent=($keyval-$min_keyval)/($max_keyval-$min_keyval);			
			$width=$percent*($max_width-$min_width)+$min_width;			
			return $width;
		}
		
		//	把KEY_VAL長度換算成產品數量
		public function key_length_to_num($keyval){
			if($keyval=='8'){
				return '1';
			}else{				
				return ($keyval-8)/9+1;
			}
		}
		
		//	取得熱門標籤前10
		public function get_hot_tags($conn){
			$sql="SELECT seq,tag,LENGTH(product_id) AS key_len FROM tags ORDER BY LENGTH(product_id) DESC LIMIT 20";
			$result=mysqli_query($conn,$sql);
			return $result;
		}
	}
?>