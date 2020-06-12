<?php 
	session_start();
//	require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
	require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
	require('common.php');
	require('get_chinese.php');
	is_admin();
	
	class product{

		public $p_images_path = "/pazter/p_images/";
//		public $p_images_path = "/p_images/";
		public $allowed_ext = array("jpg","jpeg","gif","png");
		public $p_images_subname = array("0"=>"_40","1"=>"_140","2"=>"_400_1","3"=>"_400_2","4"=>"_400_3");
		public $max_p_images = 5;
		
		public function product(){
			
		}
		
		//	修改商品資料		
		public function update_pd($p_array){
			$sql="UPDATE products SET 							
							product_type='".$p_array['pd_type']."',
							product_name='".$p_array['pd_name']."',
							product_image='".$p_array['pic']."',
							product_price='".$p_array['pd_price']."',
							nation='".$p_array['pd_nation']."',
							product_size='".$p_array['pd_size']."',
							product_color='".$p_array['pd_color']."',
							product_material='".$p_array['pd_material']."',
							product_val='".$p_array['pd_val']."',
							product_feature='".$p_array['pd_feature']."',
							display='".$p_array['display']."',
							stock='".$p_array['stock']."' 
						WHERE product_id='".$p_array['pd_id']."'
							";

			$this->renew_pd_tag($p_array['pd_id'],$p_array['pd_ori_tag'],$p_array['pd_tag']);

			if(mysql_query($sql)){
				return true;
			}
			
		}
		
		//	GET 流水號
		public function get_pd_seq($product_type){
				
			$sql="SELECT MAX(product_seq) as p_seq 
			FROM products 
			WHERE LEFT(product_id,2)='".date("y")."' AND RIGHT(LEFT(product_id,4),2)='".date("m")."'";
					
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				$row=mysqli_fetch_array($result);
				return sprintf("%04s",$row['p_seq']+1);				
			}else{
				return '0001';
			}
		}
	
		
		//	新增商品
		public function set_new_pd($p_array){
			
			$pd_seq=$this->get_pd_seq($p_array['pd_type']);
			// 西元年二碼10 + 月份 %02s + 流水號 %04s
			$pd_id=date("y").date("m").$pd_seq;
			
			$sql="INSERT products(
							product_id,
							product_seq,
							product_type,
							product_name,
							product_image,
							product_price,
							nation,
							product_size,
							product_color,
							product_material,
							product_val,
							product_feature,
							display
						) VALUES(
							'".$pd_id."',
							'".$pd_seq."',
						  '".$p_array['pd_type']."',
							'".$p_array['pd_name']."',
							'".$p_array['pic']."',
							'".$p_array['pd_price']."',
							'".$p_array['pd_nation']."',
							'".$p_array['pd_size']."',
							'".$p_array['pd_color']."',
							'".$p_array['pd_material']."',
							'".$p_array['pd_val']."',
							'".$p_array['pd_feature']."',
							'".$p_array['display']."'
						)";
						
			if(!$result=mysql_query($sql)){
				$this->set_new_pd($p_array);
			}else{
				if($p_array['pd_tag']!=""){$this->set_new_tag($p_array['pd_tag'],$pd_id);}
				return true;
			}
			
		}
		
		//刪除這個商品
		public function del_pd($pd_id){
			$sql="DELETE FROM products WHERE product_id='".$pd_id."'";
			if(mysql_query($sql)){
				return true;
			}
		}		
		
		
		//	取得商品資料
		public function get_one_pd($p_id){
			$sql="SELECT * FROM products WHERE product_id='".$p_id."'";			
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				$pd_data=mysqli_fetch_array($result);
				if(is_array($pd_data)){
					foreach( $pd_data as &$value){
						$value=stripslashes($value);
					}
				}
				return $pd_data;
			}else{
				return false;
			}
		}
		
		//	取得商品種類
		public function show_pd_type(){
			$sql="SELECT * FROM product_type";			
			if($result=mysql_query($sql)){
				return $result;
			}
		}
		
		//取得商品名稱
		public function show_pd_name(){
			$sql="SELECT product_id,product_name FROM products ORDER BY product_name";
			return mysql_query($sql);
		}
		
		/*----------------------------------------------------
				標籤FUNCTION
		----------------------------------------------------*/
				
		//	新增TAG
		public function set_new_tag($tag,$pd_id){
			$tag_arr=explode(',',$tag);
			$tag_arr=$this->remove_empty_array_element($tag_arr);
			$cnt=count($tag_arr);
			
			for($i=0;$i<$cnt;$i++){
				if(!$this->is_tag_exist($tag_arr[$i])){	// 如果標籤資料庫沒有
					
					$first_word=mb_substr($tag_arr[$i],0,1,"utf-8");
					if(strlen($first_word)>1){
						//中文
						$first_word=iconv('utf-8','big5//IGNORE',$first_word);
						$key_val=get_ChineseStroke($first_word);//筆劃
						$sql="INSERT INTO tags(key_val,tag,product_id)
									VALUES('".$key_val."','".$tag_arr[$i]."','".$pd_id."')";
					}else{
						//英數字
						$sql="INSERT INTO tags(key_val,tag,product_id)
									VALUES('".$first_word."','".$tag_arr[$i]."','".$pd_id."')";
					}
					if(!$result=mysql_query($sql)){return false;}
					
				}else{//如果已經有這個標籤了
				
					//	這個標籤還沒有這個商品
					if(!$this->is_pid_exist($tag_arr[$i],$pd_id)){
						$sql="SELECT product_id FROM tags WHERE tag='".$tag_arr[$i]."'";
						$result=mysql_query($sql);
						$row=mysqli_fetch_array($result);
						if($row['product_id']==""){
							$sql_update="UPDATE tags SET product_id='".$pd_id."' WHERE tag='".$tag_arr[$i]."'";
						}else{
							$total_id=$row['product_id'].",".$pd_id;
							$sql_update="UPDATE tags SET product_id='".$total_id."' WHERE tag='".$tag_arr[$i]."'";
						}
						if(!mysql_query($sql_update)){return false;}
					}					
				}
			}
		}
		
		//	移除陣列空白元素
		public function remove_empty_array_element($arr){
			$att_tmp = array();
			for( $i=0, $max=count($arr); $i < $max; $i++ ){
			  if(trim($arr[$i])!=''){
			    $att_tmp[]=$arr[$i];
			  }
			}
			return $att_tmp;
		}
		
		//字串轉陣列
		public function pidstr_to_array($tag_str){
			return explode(',',$tag_str);
		}
		
		//陣列轉字串
		public function array_to_pidstr($tag_arr){
			if(!is_array($tag_arr)){return false;}
			$tag_str="";
			$cnt=count($tag_arr);
			if($cnt > 0){
				for($i=0;$i<$cnt;$i++){
					if($i==0){
						$tag_str=$tag_arr[$i];
					}else{
						$tag_str=$tag_str.",".$tag_arr[$i];
					}
				}
				return $tag_str;
			}
		}
		
		//	是否有重複tag
		public function is_tag_exist($tag){
			$sql="SELECT tag FROM tags WHERE tag='".$tag."'";
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				return true;
			}else{
				return false;
			}
		}
		
		//檢查tag是否有重複的product_id
		public function is_pid_exist($tag,$pid){
			$sql="SELECT tag FROM tags WHERE tag='".$tag."' AND product_id LIKE '%".$pid."%'";
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				return true;
			}else{
				return false;
			}
		}
		
		//	更新商品標籤
		public function renew_pd_tag($pd_id,$ori_tag,$new_tag){
			$ori_tag_arr=explode(',',$ori_tag);
			$new_tag_arr=explode(',',$new_tag);
			$ori_tag_arr=$this->remove_empty_array_element($ori_tag_arr);
			$new_tag_arr=$this->remove_empty_array_element($new_tag_arr);
			$del_tag_arr=array_merge(array_diff($ori_tag_arr,$new_tag_arr));
			$new_tag_arr=array_merge(array_diff($new_tag_arr,$ori_tag_arr));
			$new_tag_str=$this->array_to_pidstr($new_tag_arr);
			if(count($del_tag_arr)>0){ $this->del_pid_from_tag($pd_id,$del_tag_arr);}
			if(count($new_tag_arr)>0){ $this->set_new_tag($new_tag_str,$pd_id);}						
		}
		
		//	刪除這個標籤的商品ID
		public function del_pid_from_tag($pid,$tag_arr){
			
			for($i=0;$i<count($tag_arr);$i++){
				$ori_pid_str=$this->get_this_tag_pid($tag_arr[$i]);
				$ori_pid_arr=$this->pidstr_to_array($ori_pid_str);
				$pid_arr[0]=$pid;
				$new_id_arr=array_merge(array_diff($ori_pid_arr,$pid_arr));
				$new_id_str=$this->array_to_pidstr($new_id_arr);
				$update_sql="UPDATE tags SET product_id='".$new_id_str."' WHERE tag='".$tag_arr[$i]."'";
				mysql_query($update_sql);				
				//如果PRODUCT_ID空的就刪除標籤
				if($this->get_this_tag_pid($tag_arr[$i])==""){
					$this->del_the_tag($tag_arr[$i]);
				}
				
			}
		}
		
		//	刪除這個標籤
		public function del_the_tag($tag){
			$sql="DELETE FROM tags WHERE tag='".$tag."'";
			if(mysql_query($sql)){return true;}
		}
		
		//	取得這個商品ID的標籤	RETURN RS
		public function get_this_pd_tag($pd_id){
			$sql="SELECT tag FROM tags WHERE product_id LIKE '%".$pd_id."%'";
			$result=mysql_query($sql);
			
			return $result;
		}
		
		//	取得這個標籤的商品ID
		public function get_this_tag_pid($tag){
			$sql="SELECT product_id FROM tags WHERE tag='".$tag."'";
			$result=mysql_query($sql);
			$row=mysqli_fetch_array($result);
			return $row['product_id'];
		}
		
		//	把TAG轉成格式	標籤,標籤2 
		public function rs_tag_to_string($result){
			$first_item=true;
			$str_tag;
			while($row=mysqli_fetch_array($result) and mysql_num_rows($result)>0){
				if($first_item){
					$str_tag=$row['tag'];
					$first_item=false;
				}else{
					$str_tag=$str_tag.",".$row['tag'];
				}
			}
			return $str_tag;
		}
		
		//	上傳圖片
		public function upload_pic($pic_name_arr){
			
			$path =$_SERVER['DOCUMENT_ROOT']. $this->p_images_path;

			for($i=0;$i<$this->max_p_images;$i++){

				$is_allowed=in_array($pic_name_arr[$i]['ext'], $this->allowed_ext);
				
				if($is_allowed){
					$k=$i+1;
					$new_file_name=$path.$pic_name_arr[$i]['name'];
					move_uploaded_file($_FILES["upload_file_".$k]["tmp_name"],$new_file_name);
				}
			}
			
		}
		
		//	設定上傳圖片檔名
		public function set_upload_pic_name($p_id){
			
			$sub_name = $this->p_images_subname;
			$pic_name_arr;
			
			for($i=0;$i<$this->max_p_images;$i++){
				$k=$i+1;
				$file_name=strtolower($_FILES["upload_file_".$k]["name"]);
			
				if($file_name!=""){
					$file_name_arr=explode(".",$file_name);
					$file_ext=$file_name_arr[count($file_name_arr)-1];
					$pic_name_arr[$i]['name']=$p_id.$sub_name[$i].".".$file_ext;
					$pic_name_arr[$i]['ext']=$file_ext;
					
				}
			}

			return $pic_name_arr;					
		}
		
		//	新檔名ARRAY TO STING
		public function pic_name_array_to_string($pic_name_array){
			for($i=0;$i<$this->max_p_images;$i++){
				if($i==0){
					$str_name=$pic_name_array[$i]['name'];	
				}else{
					$str_name=$str_name.",".$pic_name_array[$i]['name'];
				}
			}
			return $str_name;
		}
		
		//	舊檔名ARRAY TO STING
		public function ori_pic_name_array_tostring($pic_name_array){
			for($i=0;$i<$this->max_p_images;$i++){
				if($i==0){
					$str_name=$pic_name_array[$i];	
				}else{
					$str_name=$str_name.",".$pic_name_array[$i];
				}
			}
			return $str_name;
		}
		
		//	刪除圖片
		public function delete_pic($category,$file){
			
		}
		
		public function p_images_exist($p_id){
			
		}
		
		//	取得原有產品圖片檔名
		public function get_ori_images_name($p_id){
			$sql="SELECT product_image FROM products WHERE product_id='".$p_id."'";			
			$row=mysqli_fetch_array(mysql_query($sql));
			return $arr_path=explode(",",$row['product_image']);			
		}
		
		//	取得產品圖片路徑
		public function get_p_images_path($p_id){
			$sql="SELECT product_image FROM products WHERE product_id='".$p_id."'";			
			$row=mysqli_fetch_array(mysql_query($sql));
			$arr_path=explode(",",$row['product_image']);
			for($key=0 ; $key < count($arr_path) ; $key++){
				if($arr_path[$key]!=""){				
					$arr_path[$key]=$this->p_images_path.$arr_path[$key];				
				}
			}
			return $arr_path;
		}
	
	//	取得所有顏色	
	public function get_all_colors(){
			$sql="SELECT color_id,color FROM product_color ORDER BY color_id";
			return mysql_query($sql);
	}
		
		
	}
	
////////////////////////////////////////////////////////////////
//
//		MESSEAGE	CODE:


	//選擇更新商品
	if($_POST['action']=="select_update_item"){
		$url="../pd.php?action=update&u_id=".$_POST['pd_id'];
		page_togo($url);
	}
	
	//刪除商品
	if($_POST['action']=="del_pd"){		
		$pd_id=mysql_real_escape_string(trim($_POST['pd_id']));
		$pd=new product();
		if($pd->del_pd($pd_id)){
			$url="../pd.php?action=del";
			page_alert_togo("刪除成功!",$url);
		}
	}
	
	//更新商品
	if($_POST['action']=="update_pd"){
		
		$first_color=true;
		if ( isset($_POST['color']) ) {
		    foreach( $_POST['color'] as $value) {
		    	if($first_color){
		    		$str_color=$value;
		    	}else{
		    		$str_color=$str_color.",".$value;
		    	}
		    	$first_color=false;		        
		    }
		}
		
		$pd=new product();		
		$p_array['pd_id']=mysql_real_escape_string(trim($_POST['pd_id']));
		$p_array['pd_type']=mysql_real_escape_string(trim($_POST['pd_type']));
		$p_array['pd_name']=mysql_real_escape_string(trim($_POST['pd_name']));
		$p_array['pd_price']=mysql_real_escape_string(trim($_POST['pd_price']));
		$p_array['pd_nation']=mysql_real_escape_string(trim($_POST['pd_nation']));
		$p_array['pd_size']=mysql_real_escape_string(trim($_POST['pd_size']));
		$p_array['pd_color']=$str_color;
		$p_array['pd_material']=mysql_real_escape_string(trim($_POST['pd_material']));
		$p_array['pd_val']=mysql_real_escape_string(trim($_POST['pd_val']));
		$p_array['pd_ori_tag']=mysql_real_escape_string(trim($_POST['pd_ori_tag']));
		$p_array['pd_tag']=mysql_real_escape_string(trim($_POST['pd_tag']));
		$p_array['pd_feature']=mysql_real_escape_string(trim($_POST['pd_feature']));
		$p_array['display']=mysql_real_escape_string(trim($_POST['display']));
		$p_array['stock']=mysql_real_escape_string(trim($_POST['pd_stock']));
		
		//設定圖片上傳檔名 ARRAY
		$pic_name_arr=$pd->set_upload_pic_name($p_array['pd_id']);

		//上傳圖檔
		$pd->upload_pic($pic_name_arr);		
		
		//取得原有圖檔檔名 ARRAY
		$ori_pic=$pd->get_ori_images_name($p_array['pd_id']);

		for($i=0;$i<$pd->max_p_images;$i++){
			if($pic_name_arr[$i]['name']=="" && $ori_pic[$i]!=""){
				$pic_name_arr[$i]['name'] = $ori_pic[$i];
			}		
		}

		//檔名 ARRAY TO STRING
		$pic_str=$pd->pic_name_array_to_string($pic_name_arr);
		
		$p_array['pic']=$pic_str;
		
		if($pd->update_pd($p_array)){
			$url="../pd.php?action=update&u_id=".$p_array['pd_id'];
			page_alert_togo("修改成功!",$url);			
		}
				
	}	
	
	//新增商品
	if($_POST['action']=="new"){
		
		$first_color=true;
		if ( isset($_POST['color']) ) {
		    foreach( $_POST['color'] as $value) {
		    	if($first_color){
		    		$str_color=$value;
		    	}else{
		    		$str_color=$str_color.",".$value;
		    	}
		    	$first_color=false;		        
		    }
		}
		
		$p_array['pd_type']=mysql_real_escape_string(trim($_POST['pd_type']));
		$p_array['pd_name']=mysql_real_escape_string(trim($_POST['pd_name']));
		$p_array['pd_price']=mysql_real_escape_string(trim($_POST['pd_price']));
		$p_array['pd_size']=mysql_real_escape_string(trim($_POST['pd_size']));
		$p_array['pd_color']=$str_color;
		$p_array['pd_material']=mysql_real_escape_string(trim($_POST['pd_material']));
		$p_array['pd_nation']=mysql_real_escape_string(trim($_POST['pd_nation']));
		$p_array['pd_val']=mysql_real_escape_string(trim($_POST['pd_val']));
		$p_array['pd_tag']=mysql_real_escape_string(trim($_POST['pd_tag']));
		$p_array['pd_feature']=mysql_real_escape_string(trim($_POST['pd_feature']));
		$p_array['pic']=",,,,";		
		$p_array['display']=mysql_real_escape_string(trim($_POST['display']));
		
		$pd=new product();
		if($pd->set_new_pd($p_array)){
			page_alert_togo("新增成功!","../pd.php");			
		}
		
	}
	
?>