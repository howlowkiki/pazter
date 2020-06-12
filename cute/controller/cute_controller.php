<?php 
	session_start();
//	require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
	require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
	require_once('common.php');
	
	class admin{
		//PRIMARY KEY	->	admin_email
		
		function admin(){
			
		}
		
		//	管理員登入
		public function admin_login($id,$pw,$verify){
			
			if($verify!='seeu'){ 
				session_destroy();
				page_togo('../index.php');
			}
			
			$str_sql="SELECT name
							  FROM admin 
							  WHERE id='".$id."' AND pw='".$pw."'";			
			if($result = mysql_query($str_sql) and mysql_num_rows($result)>0){
				
				$row = mysqli_fetch_array($result);
				$_SESSION['admin_id']=$id;
				$_SESSION['admin_name']=$row['name'];
				page_togo('pd.php?action=new');
			}else{
				session_destroy();
				page_togo('../index.php');
			}
			
		}
		
		//	會員登出
		public function admin_logout(){
				session_destroy();
		}
		
		

	}
	
	
////////////////////////////////////////////////////////////////
//
//		MESSEAGE	CODE:
//
//								10	:	註冊成功
//								20	:	登入成功
//								30	:	登出成功
//								40	:	個人資料更新成功
//								50	:	更新成功，EMAIL或密碼已更改
//								-10	:	EMAIL帳號已經有人使用
//								-20	:	登入失敗
//								-30	:	EMAIL格式錯誤
//								-40	:	密碼格式錯誤
//								-50	:	查無此帳號資料
//								-50	:	尚未登入
//								-60	:	更新資料失敗
//								-999	:	


//	登入
if($_POST['action']=="cute"){
	
	$admin = new admin();	
	$id=mysql_real_escape_string(trim($_POST['textfield']));
	$pw=mysql_real_escape_string(trim($_POST['textfield2']));		
	$verify=mysql_real_escape_string(trim($_POST['code']));
	$admin->admin_login($id,md5($pw),$verify);	
}

//	登出
if($_GET['action']=="admin_logout"){
		
	$guest = new admin();
	$guest->admin_logout();
	page_alert_togo("登出成功","../index.php");
	
}

?>