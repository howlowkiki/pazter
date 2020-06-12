<?php 
	session_start();
//	require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
	require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');

	require('common.php');
	is_admin();
	
	class blog{
		public $p_images_path = "/pazter/b_images/";
//		public $p_images_path = "/b_images/";			
		public $allowed_ext = array("jpg","jpeg","gif","png");
		public $p_images_subname = array("0"=>"_1","1"=>"_2","2"=>"_3","3"=>"_4","4"=>"_5");
		public $max_p_images = 5;
		
		public function blog(){
		}
		
		//	GET 流水號
		public function get_bg_seq($str_date){
				
			$sql="SELECT MAX(RIGHT(id,4)) as seq 
						FROM blog
						WHERE LEFT(id,6)='".$str_date."'";
					
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				$row=mysqli_fetch_array($result);
				return sprintf("%04s",$row['seq']+1);				
			}else{
				return '0001';
			}
		}
	
	
		public function set_new_blog($p_array){
			// 西元年4碼2010 + 月份 %02s + 流水號 %04s
			$str_date=date("Ym");				
			$blog_id=$str_date.$this->get_bg_seq($str_date);		
			
			$sql="INSERT blog(
							id,
							type,
							title,
							content,
							pic,
							display
						) VALUES(
							'".$blog_id."',							
						  '".$p_array['bg_type']."',
							'".$p_array['bg_title']."',
							'".$p_array['bg_content']."',
							'".$p_array['bg_pic']."',							
							'".$p_array['display']."'							
						)";
			
			if(!$result=mysql_query($sql)){
				$this->set_new_pd($p_array);
			}else{
				return true;
			}
			
		}
		
		public function update_bg($p_array){
			$sql="UPDATE blog SET 							
							type='".$p_array['bg_type']."',
							title='".$p_array['bg_title']."',
							content='".$p_array['bg_content']."',
							pic='".$p_array['bg_pic']."',							
							display='".$p_array['display']."' 							
						WHERE id='".$p_array['bg_id']."'
							";
							
			if(mysql_query($sql)){
				return true;
			}
			
		}
		
		public function del_bg($id){
			$sql="DELETE FROM blog WHERE id='".$id."'";
			if(mysql_query($sql)){
				return true;
			}
		}
		
		public function get_row_pd(){
			
		}
		
		public function get_one_bg($id){
			$sql="SELECT * FROM blog WHERE id='".$id."'";
			if($result=mysql_query($sql) and mysql_num_rows($result)>0){
				return mysqli_fetch_array($result);
			}
		}
		
		//	取得類別
		public function show_bg_type(){
			$sql="SELECT * FROM blog_type";			
			if($result=mysql_query($sql)){
				return $result;
			}
		}
		
		//	取得文章id與標題
		public function show_bg_name(){
			$sql="SELECT id,title FROM blog ORDER BY id DESC";			
			if($result=mysql_query($sql)){
				return $result;
			}
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
			$sql="SELECT pic FROM blog WHERE id='".$p_id."'";			
			$row=mysqli_fetch_array(mysql_query($sql));
			return explode(",",$row['pic']);			
		}
		
		//	取得產品圖片路徑
		public function get_p_images_path($p_id){
			$sql="SELECT pic FROM blog WHERE id='".$p_id."'";			
			$row=mysqli_fetch_array(mysql_query($sql));
			$arr_path=explode(",",$row['pic']);
			for($key=0 ; $key < count($arr_path) ; $key++){
				if($arr_path[$key]!=""){				
					$arr_path[$key]=$this->p_images_path.$arr_path[$key];				
				}
			}
			return $arr_path;
		}
		
	}
	
///////////////////////////////////////////////////////	

	//選擇更新文章
	if($_POST['action']=="select_update_item"){
		$url="../bg.php?action=update&u_id=".$_POST['bg_id'];
		page_togo($url);
	}
	
	//刪除文章
	if($_POST['action']=="del_bg"){		
		$bg_id=mysql_real_escape_string(trim($_POST['bg_id']));
		$bg=new blog();
		if($bg->del_bg($bg_id)){
			$url="../bg.php?action=del";
			page_alert_togo("刪除成功!",$url);
		}
	}
	
	//更新文章
	if($_POST['action']=="update_bg"){
				
		$p_array['bg_id']=mysql_real_escape_string(trim($_POST['bg_id']));
		$p_array['bg_type']=mysql_real_escape_string(trim($_POST['bg_type']));
		$p_array['bg_title']=mysql_real_escape_string(trim($_POST['bg_title']));
		$p_array['bg_content']=mysql_real_escape_string(trim($_POST['bg_content']));
		$p_array['bg_pic']=mysql_real_escape_string(trim($_POST['pic']));
		$p_array['display']=mysql_real_escape_string(trim($_POST['display']));
		
		$bg=new blog();
		//設定圖片上傳檔名 ARRAY
		$pic_name_arr=$bg->set_upload_pic_name($p_array['bg_id']);
		
		//上傳圖檔
		$bg->upload_pic($pic_name_arr);		
		
		//取得原有圖檔檔名 ARRAY
		$ori_pic=$bg->get_ori_images_name($p_array['bg_id']);

		for($i=0;$i<$bg->max_p_images;$i++){			
			if($pic_name_arr[$i]['name']=="" && $ori_pic[$i]!=""){
				$pic_name_arr[$i]['name'] = $ori_pic[$i];
			}			
		}

		//檔名 ARRAY TO STRING
		$p_array['bg_pic']	=	$bg->pic_name_array_to_string($pic_name_arr);
		
		
		if($bg->update_bg($p_array)){
			$url="../bg.php?action=update&u_id=".$p_array['bg_id'];
			page_alert_togo("修改成功!",$url);
		}
				
	}	
	
	//新增文章
	if($_POST['action']=="new"){
		$p_array['bg_type']=mysql_real_escape_string(trim($_POST['bg_type']));
		$p_array['bg_title']=mysql_real_escape_string(trim($_POST['bg_title']));
		$p_array['bg_content']=mysql_real_escape_string(trim($_POST['bg_content']));
		$p_array['bg_pic']=",,,,";
		$p_array['display']=mysql_real_escape_string(trim($_POST['display']));	
		$bg=new blog();
		if($bg->set_new_blog($p_array)){
			page_alert_togo("新增成功!","../bg.php");			
		}
		
		
	}
?>