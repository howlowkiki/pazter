<?/*
Page:           search_controller.php
Created:        July 2010
Last Mod:
---------------------------------------------------------
Modify items:
http://www.pazter.com
virkler@gmail.com
--------------------------------------------------------- */
require('common_func.php');
require('db_config.php');

class search{
	
	public function search(){
		
	}
	//	搜尋搜尋標籤的產品	
	public function search_tag($keyword,$conn){
		if(strlen($keyword)>60){
			return false;
		}
		
		$bad = array("<!--","-->","'","<",">","(",")","'",'&','$','=',';','?','/',"%20","%22","%3c","%253c","%3e","%0e"
								,"%28","%29","%2528","%26","%24","%3f","%3b","%3d");
		
		$keyword=str_replace($bad,"",$keyword);
		if($keyword==""){return false;}
		$keyword=mysqli_real_escape_string($conn,$keyword);
		$keyword_arr=explode(",",$keyword);
		$keyword_arr=array_unique($keyword_arr);
		$where_str=implode("','",$keyword_arr);		
		$sql="SELECT product_id FROM tags WHERE tag IN('$where_str')";
		if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
			$sql_update="UPDATE tags SET count= count+1 WHERE tag IN('$where_str')";
			mysqli_query($sql_update);
		}
		
		$first_row=true;		
		while($row=mysqli_fetch_array($result)){			
			if($first_row){
				$pd_str=$row['product_id'];
			}else{
				$pd_str=$pd_str.",".$row['product_id'];
			}
			$first_row=false;
		}
		
		$pd_id_arr=explode(",",$pd_str);
		$pd_id_arr=array_unique($pd_id_arr);
		
		$pd_id_str=implode("','",$pd_id_arr);
		
//		for($i=0;$i<count($pd_id_arr);$i++){
//			if($i==0){
//				$pd_id_str="product_id='".$pd_id_arr[$i]."' ";
//			}else{
//				$pd_id_str=$pd_id_str." OR product_id='".$pd_id_arr[$i]."'";
//			}
//		}
		
//		$sql="SELECT product_id,product_name,product_image,product_price FROM products WHERE ".$pd_id_str.
//					" AND display='1'";

		$sql="SELECT product_id,product_name,product_image,product_price FROM products WHERE product_id IN ('$pd_id_str') 
					 AND display='1'";		
		return mysqli_query($conn,$sql);
		
	}
}
	
?>