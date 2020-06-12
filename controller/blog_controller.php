<?php 
	session_start();
	require('db_config.php');	
	require('user_controller.php');
	
	class blog{
		
		public function blog(){}
		
		//	文章是否存在
		public function is_article_exist($id,$conn){
			$id=mysqli_real_escape_string($conn,$id);
			if(	!is_numeric($id) || strlen($id)>10){return false;}
			
			if(isset($_SESSION['admin_id'])){
				$sql="SELECT id FROM blog WHERE id='".$id."'";
			}else{	
				$sql="SELECT id FROM blog WHERE id='".$id."' and display='1'";
			}
			
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return true;
			}else{
				return false;
			}
		}

		//	顯示最近5筆文章
		public function get_lately_article($conn){
			if(isset($_SESSION['admin_id'])){
				$sql="SELECT id,title,content,pic,date FROM blog ORDER BY date DESC LIMIT 5";
			}else{
				$sql="SELECT id,title,content,pic,date FROM blog WHERE display='1' ORDER BY date DESC LIMIT 5";
			}
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return $result;
			}
		}
		
		//	取得分類文章
		public function get_type_article($type,$conn){
			if(strlen($type)>2){return false;}
			$type=mysqli_real_escape_string($conn,$type);
			if(isset($_SESSION['admin_id'])){
				$sql="SELECT id,title,content,pic,date FROM blog WHERE type='".$type."' ORDER BY date DESC";			
			}else{
				$sql="SELECT id,title,content,pic,date FROM blog WHERE display='1' AND type='".$type."' ORDER BY date DESC";			
			}
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return $result;
			}else{
				return false;
			}
		}
		
		//	取得分類名稱
		public function get_type_name($type,$conn){
			if(strlen($type)>2 || (!is_numeric($type)) ){return false;}
			$type	=	mysqli_real_escape_string($conn,$type);
			$sql="SELECT type_name FROM blog_type WHERE type_id='".$type."'";
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				$row=mysqli_fetch_array($result);
				return $row['type_name'];
			}else{
				return false;
			}
		}
		
		//	取得月份文章
		public function get_monthly_article($month,$conn){
			if(strlen($month)!=6 || !is_numeric($month)){return false;}
			$month=mysqli_real_escape_string($conn,$month);			
			$sql="SELECT id,title,content,pic,date FROM blog WHERE display='1' AND SUBSTR(id,1,6)='".$month."' ORDER BY date DESC";			
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return $result;
			}else{
				return false;
			}
		}		
		
		
		//	顯示最近10筆文章標題
		public function get_lately_article_title($conn){
			$sql="SELECT id,title FROM blog WHERE display='1' ORDER BY date DESC LIMIT 10";			
			if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
				return $result;
			}
		}
		
		
		//	取得文章內容
		public function get_article_content($id,$conn){
			$id=mysqli_real_escape_string($conn,$id);
			if(	!is_numeric($id) || strlen($id)>10){return false;}
			if(isset($_SESSION['admin_id'])){
				$sql="SELECT * FROM blog WHERE id='".$id."'";
			}else{
				$sql="SELECT * FROM blog WHERE id='".$id."' AND display='1'";
			}
			$result=mysqli_query($conn,$sql);
			if(mysqli_num_rows($result)>0){
				return mysqli_fetch_array($result);
			}else{
				page_togo('oops.php');
			}
		}
		
		//	文章點閱次數
		public function get_article_views($seq,$conn){
			$str_sql="SELECT views FROM blogs WHERE article_seq='".$seq."'";
			if($result=mysqli_query($conn,$str_sql) and mysqli_num_rows($result)>0){
				return mysqli_fetch_array($result['views']);
			}else{
				return false;
			}
		}
		
		//	文章留言回覆人數
		public function get_num_comment($article_seq,$conn){
			$str_sql="SELECT COUNT(*) AS total_counts FROM blog_reply WHERE article_seq='".$article_seq."'";
			$row=mysqli_fetch_array(mysqli_query($conn,$str_sql));
			return $row['total_counts'];
		}
			
		
		//	訪客文章留言	
		public function set_new_reply($arr_data,$conn){
			$sql="INSERT INTO blog_reply(blog_id,reply_name,reply_email,reply_msg,reply_ip) 
						VALUES('".$arr_data['blog_id']."','".$arr_data['reply_name']."','".$arr_data['reply_email']."','".$arr_data['reply_content']."','".$arr_data['reply_ip']."') ";				
			if(!mysqli_query($conn,$sql)){
				return false;
			}else{
				return true;
			}
		}
		
		//	取得文章回覆留言內容
		public function get_reply_commets($id,$conn){
			$id=mysqli_real_escape_string($conn,$id);
			if(	!is_numeric($id) || strlen($id)>10){return false;}
			$sql="SELECT reply_date,reply_name,reply_msg FROM blog_reply WHERE blog_id=".$id." ORDER BY reply_date DESC";			
			if($result=mysqli_query($conn,$sql)){
				return $result;
			}
		}
		
		//	取得文章回覆留言數		
		public function get_num_reply($id,$conn){
			$id=mysqli_real_escape_string($conn,$id);
			if(	!is_numeric($id) || strlen($id)>10){return false;}
			
			$sql="SELECT COUNT(*) AS cnt FROM blog_reply WHERE blog_id='".$id."'";			
			$result=mysqli_query($conn,$sql);
			$row= mysqli_fetch_array($result);
			return $row['cnt'];
			
		}
		
		//	取得文章分類標題
		public function get_article_type_name($conn){
			$sql="SELECT type_id,type_name FROM blog_type ORDER BY type_id";
			return mysqli_query($conn,$sql);
		}
		
		//文章依月份分類
		public function get_month_by_article($conn){
			$sql="SELECT SUBSTR(blog.id,1,6) as monthly  FROM `blog` WHERE display='1'  GROUP BY monthly ORDER BY date DESC";
			return mysqli_query($conn,$sql);
		}
		
		
	}
	
	
////////////////////////////////////////////////////////////////
//
//		MESSEAGE	CODE:
//			-10:驗證碼錯誤
//			-20:資料錯誤

	if($_POST['action']=="blog_reply"){
		$user	=	new user();
		//檢查資料長度
		if( mb_strlen($_POST['reply_name'])>20 || mb_strlen($_POST['reply_email'])>40 || mb_strlen($_POST['reply_content'])>500 ){
			echo "-20";
			return;
		}
		
		if($_SESSION['captcha']!= strtoupper(trim($_POST['check_cha']))){
			echo "-10";
		}else{
			$reply_data=array(
				"blog_id"=>mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['blog_id']))),
				"reply_name"=>mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['reply_name']))),
				"reply_email"=>mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['reply_email']))),
				"reply_content"=>mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['reply_content']))),
				"reply_ip"=>	$user->get_user_ip()
			);
				
			$bg=new blog();
			if($bg->is_article_exist($reply_data['blog_id'],$conn)){
				if($bg->set_new_reply($reply_data,$conn)){
					echo "10";	
				}
			}
		}
		
	}
	

?>