<?php 
	require_once('db_config.php');
	require_once('common_func.php');	
	if(!isset($_SESSION)){session_start();}
	
	class user{
		//PRIMARY KEY	->	user_email
		public $user_email,
					 $user_pw,
					 $user_name,
					 $user_tel,
					 $user_mobile,
					 $user_zipcode,
					 $user_add,
					 $user_register_date,
					 $user_this_login,
					 $user_last_login,
					 $user_pw_sample="3d3be561",
					 $msg;
		
		function user(){
			
		}
		
		//	取得USER IP
		public function get_user_ip(){			
		  if ( !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ){		    
		    $temp_ip = split(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
		    $user_ip = $temp_ip[0];
		  } else {
		    $user_ip = $_SERVER["REMOTE_ADDR"];
		  }
			return $user_ip;
		}
		
		//	檢查EMAIL格式
		public function is_email_format($email){
			if($email!=""){$this->user_email=$email;}
			if(preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $this->user_email)){
				return true;
			}else{
				$this->msg = "-30";
				return false;
			}
		}
		
		//	檢查密碼格式
		public function is_password_format($password){
			
			if(preg_match('/^[a-zA-Z0-9]+$/',$password)){
				return true;
			}else{
				$this->msg = "-40";
				return false;
			}
		}
		
		//檢查重複EMAIL
		public function is_user_exist($email){	
			
			if(!$this->is_email_format($email)){
				return false;
				exit();
			}
				$str_sql="SELECT user_email FROM user_profile WHERE user_email='".$email."'";
				
				if($result = mysqli_query($str_sql) and mysql_num_rows($result)>0){
					//USER ID IS EXIST
					return true;
				}else{
					$this->msg = "-10";
					return false;
				}
			
		}
		
		//	檢查會員登入
		public function is_user_login(){
			if(	isset($_SESSION['user_email']) ){				
				if($_SESSION['user_ip']!=$this->get_user_ip()){
					session_destroy ();
					$this->msg="-50";
					return false;
				}
				return true;
			}else{				
				$this->msg="-50";
				return false;
			}			
		}
		//	取得會員資料
		public function get_user_data(){
			if(!$this->is_user_login()){
				return false;
				exit();
			}
			$str_sql="SELECT user_email,user_name,user_tel,user_mobile,user_zipcode,user_county,user_area,user_add,user_confirm,user_register_date,user_last_login
								 FROM user_profile WHERE user_email='".$_SESSION['user_email']."'";
			
			if($result = mysqli_query($str_sql) and mysql_num_rows($result)>0){
				$row = mysqli_fetch_array($result);
				return $row;				
			}else{
				$this->msg = "-50";
				return false;
			}
		}
		
		//新增會員資料
		public function set_new_user($user_data) {
			
			//	CHECK E-MAIL AND PASSWORD FORMAT
			if(!$this->is_email_format($user_data['user_email'])){	
				$this->msg="-30";
				return false;				
			}
			
			if(!$this->is_password_format($user_data['user_pw'])){
				$this->msg="-40";
				return false;			
			}
			
			if($this->is_user_exist($user_data['user_email'])){
				$this->msg="-10";
				return false;				
			}
						
			$md5_pw=md5($user_data['user_pw']);
			$user_this_login=date("Y-m-d H:i:s",mktime());		//GMT +8
			$str_sql = "INSERT INTO user_profile (user_email, user_pw, user_name,user_tel,user_zipcode,user_county,user_area,user_add,user_this_login) 
									VALUES ('".$user_data['user_email']."',
									'".$md5_pw."',
									'".$user_data['user_name']."',
									'".$user_data['user_tel']."',
									'".$user_data['user_zipcode']."',
									'".$user_data['user_county']."',
									'".$user_data['user_area']."',
									'".$user_data['user_add']."',
									'".$user_this_login."')";
			
			$result=mysqli_query($str_sql);
			if (!$result) {
 		  	die('Invalid query: ' . mysql_error());
 		  	return false;
			}else{
				$this->send_confirm_mail($user_data['user_email']);
				$this->msg = "10";
				return true;
			}
		}
		
		//	會員登入
		public function user_login(){
			
			if(!$this->is_email_format($this->user_emal)){				
				return false;
				exit();
			}
			
			$login_pw=md5($this->user_pw);
			
			$str_sql="SELECT user_name,user_email,user_confirm FROM user_profile WHERE 
					user_email='".$this->user_email."' AND user_pw='".$login_pw."'";
			
			if($result = mysqli_query($str_sql) and mysql_num_rows($result)>0){
				
				$row = mysqli_fetch_array($result);
				$_SESSION['user_name']	=	$row['user_name'];
				$_SESSION['user_email']	=	$row['user_email'];
				$_SESSION['user_confirm']	=	$row['user_confirm'];
				$_SESSION['user_ip']	=	$this->get_user_ip();
				
				$user_this_login=date("Y-m-d H:i:s",mktime());		
				
				$str_update_login="UPDATE user_profile SET user_last_login=user_this_login , user_this_login='".$user_this_login."' 
													WHERE user_email='".$this->user_email."'";
				
				$result_update=mysqli_query($str_update_login);
				if (!$result_update) {
	 		 	 	die('Invalid query: ' . mysql_error());
	 		 	 	$this->msg = "-20";
					return false;
				}
				
				$this->msg = "20";
				return true;
				
			}else{
				$this->msg = "-20";
				return false;
			}
		}
		//	會員登出
		public function user_logout(){
				session_destroy();
				$this->msg="30";			
		}
		
		//	會員信箱確認
		public function set_user_confirm($id,$reg_date){
			//CONFIRM BY LINK ARGUMENTS
			//KEY:MD5( USER_REGISTER_DATE )
			if(!$this->is_email_format($id)){return false;}
			$id=mysql_real_escape_string($id);
			$reg_date=mysql_real_escape_string($reg_date);
			$str_sql="SELECT user_register_date FROM user_profile WHERE user_email='".$id."' 
							AND MD5(user_register_date)='".$reg_date."' AND user_confirm='0' ";
			
			if($result = mysqli_query($str_sql) and mysql_num_rows($result)>0){				
				$str_sql="UPDATE user_profile SET user_confirm='1' WHERE user_email='".$id."'";
					
				$result=mysqli_query($str_sql);
				
				if (!$result) {
	 		 	 	die('Invalid query: ' . mysql_error());
				}		
				return true;
			}else{
				return false;
			}
		}
		
		//	會員資料修改
		public function set_user_data($array_data){
			
			$email_change=false;
			$pw_change=false;
			
			if(!$this->is_user_login()){
				$this->msg="-50";
				return false;	//	-50
				exit();				
			}
			
			if(!$this->is_password_format($array_data['pw'])){				
				$this->msg="-40";
				return false;	//	-40
				exit();
			}
			
			if($array_data['pw']!=$this->user_pw_sample){				
				$str_pw=" user_pw='".md5($array_data['pw'])."',";
				$pw_change=true;
			}
			if(is_numeric($array_data['zipcode']) && strlen($array_data['zipcode'])<=5){
				$str_zipcode=" user_zipcode='".$array_data['zipcode']."', ";
			}
			
			$str_update="UPDATE user_profile SET ".$str_pw
												.$str_zipcode
												."user_name='".$array_data['name']."',
													user_tel='".$array_data['tel']."',
													user_mobile='".$array_data['mobile']."',													
													user_area='".$array_data['area']."',
													user_county='".$array_data['county']."',
													user_add='".$array_data['add']."' 
													WHERE user_email='".$_SESSION['user_email']."'";
			

			$result_update=mysqli_query($str_update);
				
			if (!$result_update) {
	 		 	die('Invalid query: ' . mysql_error());
	 		 	$this->msg = "-60";
				return false;
				exit();
			}
			
			if($pw_change){
				$this->user_logout();
				$this->msg	=	"50";
			}
			$this->msg="40";
			return true;								
		}
		
		//	會員信箱確認信
		public function send_confirm_mail($id){
			
			$str_sql="SELECT * FROM user_profile WHERE user_email='".$id."'";
				
			if(!($result = mysqli_query($str_sql) and mysql_num_rows($result)>0)){
				return false;
			}
			
			$row = mysqli_fetch_array($result);
			//	確認摘要值 MD5(USER_REGISTER_DATE) + MD5(USER_EMAIL + USER_REGISTER_DATE)
			$verify=md5($row['user_register_date']);
			$to      = $row['user_email'];
			$subject = 'PAZTER 會員確認信';
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
	        <td width="656"><span style="font-size:12px; color:#333333;">親愛的會員您好：請點選下列連結完成會員信箱確認</span></td>
	      </tr>
	      <tr>
	        <td>&nbsp;</td>
	        <td>
	        	<p style="font-size:12px; color:#333333;">完成確認後，將不定時提供您更多的活動資訊及認證會員專屬E-COUPON優惠。</p>
	        	<span style="font-size:12px;">
	        		<a href="http://pazter.com/user_confirm.php?id='.$id.'&verify='.$verify.'">http://pazter.com/user_confirm.php?id='.$id.'&verify='.$verify.'</a>
	        	</span>	
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
		
		//	聯絡我們 送出內容至SERVICE@PAZTER.COM
		public function mailto_service($data){
			$to="service@pazter.com";
			$subject	=		"FROM PAZTER SERVICE 訂單編號：".$data['contact_odno'];
			$subject	=		"=?UTF-8?B?". base64_encode($subject)."?=";
			$contact_name	=	"=?UTF-8?B?". base64_encode($data['contact_name'])."?=";
			$message	=		"姓名：".$data['contact_name']."<br>";
			$message	.=	"電話：".$data['contact_tel']."<br>";
			$message	.=	"意見：".$data['contact_opinion']."<br>";
			$headers 	=		"MIME-Version: 1.0\r\n";
			$headers 	.=	"Content-type: text/html; charset=utf-8\r\n";
			$headers 	.= 	"From:".$contact_name." <".$data['contact_email'].">\r\n"; //optional headerfields			
			
			mail($to, $subject, $message, $headers);
			return true;
		}

		//	聯絡我們 送出內容至SERVICE@PAZTER.COM
		public function corp_mail($data){
			$to="service@pazter.com";			
			$subject	=		"FROM PAZTER <合作提案>：";
			$subject	=		"=?UTF-8?B?". base64_encode($subject)."?=";
			$contact_name	=	"=?UTF-8?B?". base64_encode($data['contact_name'])."?=";
			$message	=		"聯絡人：".$data['contact_name']."<br>";
			$message	.=	"電話：".$data['contact_tel']."<br>";
			$message	.=	"網站：".$data['contact_web']."<br>";
			$message	.=	"提案內容：".$data['contact_opinion']."<br>";
			
			$headers 	=		"MIME-Version: 1.0\r\n";
			$headers 	.=	"Content-type: text/html; charset=utf-8\r\n";
			$headers 	.= 	"From:".$contact_name." <".$data['contact_email'].">\r\n"; //optional headerfields			
			
		if(mail($to, $subject, $message, $headers)){
			return true;
		}else{
			return false;
		}
			
		}		
		
		
		//	檢查配送資料完整
		public function is_ship_data($user_email){
			$sql="SELECT user_tel,user_zipcode,user_add FROM user_profile WHERE user_email='".$user_email."'";
			
			if($result=mysqli_query($sql) and mysql_num_rows($result)>0){
				$row=mysqli_fetch_array($result);				
				if( $row['user_tel']=="" || $row['user_zipcode']=="" || $row['user_add']==""){
					return false;
				}else{
					return true;
				}	
			}else{
				return false;
			}
		}
		
		//	取得亂數密碼
		public function get_rand_pw(){
			$pw="";
			while(strlen($pw)<6){
			  switch(rand(1,2)){    
			    case 1:
			      $pw=$pw.chr(rand(97,122));
			      break;
			    case 2:
			      $pw=$pw.chr(rand(48,57));
			      break;
			  }
			}
			return $pw;			
		}
		
		//	重設密碼
		public function reset_user_pw($user_eamil,$pw){			
			$sql="UPDATE user_profile SET user_pw='".md5($pw)."' WHERE user_email='".$user_eamil."'";			
			return mysqli_query($sql);			
		}
		
		//	送出重設密碼郵件
		public function send_pw_email($user_eamil,$pw){
			
			$to      = $user_eamil;
			$subject = 'PAZTER 會員服務';
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
			        	<p style="font-size:12px; color:#333333;">您的密碼已經重新設定了，請利用下列新密碼登入後修改。</p>
			        	<p style="font-size:12px; color:#333333;">'.$pw.'</p>
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
		
		//訂閱電子報
		public function add_epaper($email){
			
			if(!$this->is_email_format($email)) return false;
			$email=mysql_real_escape_string($email);
			$sql="SELECT user_email FROM news_list WHERE user_email='".$email."'";
			$rs=mysqli_query($sql);
			if(mysql_num_rows($rs)>0){
				$sql_add="UPDATE news_list SET status='1' WHERE user_email='".$email."'";
			}else{
				$sql_add="INSERT INTO news_list(user_email,status) VALUES('".$email."','1')";
			}
			
			$rs_add=mysqli_query($sql_add);
			if($rs_add){
				return true;
			}else{
				return false;
			}
				
		}
		
		//取消電子報
		public function cancel_epaper($email){
			
			if(!$this->is_email_format($email)) return false;			
			$email=mysql_real_escape_string($email);	
			$sql_update="UPDATE news_list SET status='0' WHERE user_email='".$email."'";			
			$rs_cancel=mysqli_query($sql_update);
			if($rs_cancel){
				return true;
			}else{
				return false;
			}
			
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
//								-70	:	輸入資料含有不合法字元
//								-999	:	

//	註冊申請
if($_POST['action']=="add_new_user"){
	$arr_data;
	
	if( !is_numeric($_POST['register_zipcode']) ){
		$_POST['register_zipcode']='100';
	}
	
	if(!$name=filter_symbol_char($_POST['register_name'],20)){
		 echo "-70";
		 return;
	}
	if(!$county=filter_symbol_char($_POST['register_county'],10)){
		 echo "-70";
		 return;
	}
	if(!$area=filter_symbol_char($_POST['register_area'],10)){
		 echo "-70";
		 return;
	}
	
	if(mb_strlen($add=str_replace(" ","",$_POST['register_add']))>80){
		echo "-70";
		return;
	}
	if(mb_strlen($tel=str_replace(" ","",$_POST['register_tel']))>20){
		echo "-70";
		return;
	}
	
	$arr_data['user_name']=mysql_real_escape_string($name);
	$arr_data['user_email']=mysql_real_escape_string($_POST['register_email']);
	$arr_data['user_pw']=mysql_real_escape_string($_POST['register_pw']);
	$arr_data['user_tel']=mysql_real_escape_string(htmlspecialchars($tel));
	$arr_data['user_zipcode']=mysql_real_escape_string($_POST['register_zipcode']);
	$arr_data['user_county']=mysql_real_escape_string($county);
	$arr_data['user_area']=mysql_real_escape_string($area);
	$arr_data['user_add']=mysql_real_escape_string(htmlspecialchars($add));
	
	$register = new user();
	$register->set_new_user($arr_data);
	$_SESSION['register_for_yahoo']=true;	//register count for yahoo
	echo $register->msg;	
}


//	登入
if($_POST['action']=="user_login"){
	
	$guest = new user();	
	$guest->user_email=mysql_real_escape_string(trim($_POST['login_email']));
	$guest->user_pw=mysql_real_escape_string(trim($_POST['login_pw']));	
	
	$guest->user_login();
	echo $guest->msg;
	
}

//	登出
if($_POST['action']=="user_logout"){
		
	$guest = new user();
	$guest->user_logout();
	echo $guest->msg;
	
}

//	註冊資料修改
if($_POST['action']=="user_update"){
	
	$update_user = new user();
	
	$update_data=array(
//		"email"=>mysql_real_escape_string(trim($_POST['update_email'])),
		"pw"=>mysql_real_escape_string(trim($_POST['update_pw'])),
		"confirm_pw"=>mysql_real_escape_string(trim($_POST['update_confirm_pw'])),
		"name"=>mysql_real_escape_string(trim($_POST['update_name'])),
		"tel"=>mysql_real_escape_string(trim($_POST['update_tel'])),
		"mobile"=>mysql_real_escape_string(trim($_POST['update_mobile'])),
		"zipcode"=>mysql_real_escape_string(trim($_POST['update_zipcode'])),
		"area"=>mysql_real_escape_string(trim($_POST['update_area'])),
		"county"=>mysql_real_escape_string(trim($_POST['update_county'])),
		"add"=>mysql_real_escape_string(trim($_POST['update_add']))
	);
	
	
	if(	strlen($_POST['update_pw'])>32 ||
			$update_data['pw']!=$update_data['confirm_pw'] ||
			mb_strlen($update_data['name'])>20 ||	
			mb_strlen($update_data['tel'])>20	 ||
			mb_strlen($update_data['mobile'])>10 ||
			mb_strlen($update_data['zipcode'])>5 ||
			mb_strlen($update_data['county'])>10 ||
			mb_strlen($update_data['area'])>10 ||			
			mb_strlen($update_data['add'])>80
		){			
			echo "-40";
			return;
		}
	
	
	$update_user->set_user_data($update_data);	
	echo $update_user->msg;
}

//	聯絡我們
if($_POST['action']=="contact_us"){
	
	if( mb_strlen($_POST['contact_name'])>20 || mb_strlen($_POST['contact_email'])>40 || 
		mb_strlen($_POST['contact_opinion'])>500 || mb_strlen($_POST['contact_tel'])>20 || mb_strlen($_POST['contact_odno'])>14 ){
		echo "-20";
		return;
	}
	
	if($_SESSION['captcha']!= strtoupper(trim($_POST['check_cha']))){
		echo "-10";
		return;
	}else{
		
		$member=new user();
		$contact_data=array(
			"contact_name"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_name']))),
			"contact_email"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_email']))),
			"contact_opinion"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_opinion']))),
			"contact_tel"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_tel']))),
			"contact_odno"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_odno'])))
		);
		
		if(!$member->is_email_format($_POST['contact_email'])){
				echo "-20";
				return;
		}
		
		if($member->mailto_service($contact_data)){		
			echo "10";
		}else{
			echo "-20";			
		}
		
	}
}

//	合作提案
if($_POST['action']=="corp_mail"){
	
	if( mb_strlen($_POST['contact_name'])>20 || mb_strlen($_POST['contact_email'])>40 || 
		mb_strlen($_POST['contact_opinion'])>500 || mb_strlen($_POST['contact_tel'])>20 || mb_strlen($_POST['contact_web'])>50 ){
		echo "-20";
		return;
	}
	
	if($_SESSION['captcha']!= strtoupper(trim($_POST['check_cha']))){
		echo "-10";
		return;
	}else{
		
		$member=new user();
		$contact_data=array(
			"contact_name"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_name']))),
			"contact_email"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_email']))),
			"contact_opinion"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_opinion']))),
			"contact_tel"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_tel']))),
			"contact_web"=>mysql_real_escape_string(htmlspecialchars(trim($_POST['contact_web'])))
		);
		
		if(!$member->is_email_format($_POST['contact_email'])){
				echo "-20";
				return;
		}
		
		if($member->corp_mail($contact_data)){		
			echo "10";
		}else{
			echo "-20";			
		}
		
	}
}


//	非會員購買
	if($_POST['action']=="not_member_buyer"){
		$user=new user();
		
		if(	$_POST['buyer_name']=="" || mb_strlen($_POST['buyer_name'])>20 ){
			echo "-70";
			return;
		}
		if(	$_POST['buyer_tel']=="" || mb_strlen($_POST['buyer_tel'])>20 ){
			echo "-70";
			return;
		}
		if(	$_POST['buyer_email']=="" || mb_strlen($_POST['buyer_email'])>50 || !$user->is_email_format($_POST['buyer_email'])){
			echo "-70";
			return;
		}
		if(	$_POST['buyer_zip_code']=="" || mb_strlen($_POST['buyer_zip_code'])>5 || !is_numeric($_POST['buyer_zip_code'])){
			echo "-70";
			return;
		}
		if(	$_POST['buyer_county']=="" || mb_strlen($_POST['buyer_county'])>10 ){
			echo "-70";
			return;
		}
		if(	$_POST['buyer_area']=="" || mb_strlen($_POST['buyer_area'])>10 ){
			echo "-70";
			return;
		}
		if(	$_POST['token']=="" || $_POST['token']!=$_SESSION['token']){
			unset($_SESSION['token']);
			echo "-70";
			return;
		}
//		$_SESSION['buyer']=$_SESSION['token'];
//		$_SESSION['buyer_name']=mysql_real_escape_string($_POST['buyer_name']);
//		$_SESSION['buyer_tel']=mysql_real_escape_string($_POST['buyer_tel']);
//		$_SESSION['buyer_email']=mysql_real_escape_string($_POST['buyer_email']);
//		$_SESSION['buyer_zip_code']=mysql_real_escape_string($_POST['buyer_zip_code']);
//		$_SESSION['buyer_county']=mysql_real_escape_string($_POST['buyer_county']);
//		$_SESSION['buyer_area']=mysql_real_escape_string($_POST['buyer_area']);
		
		echo "10";
	
	}
	
	//重設密碼
	if($_POST['action']=="reset_password"){
			
		$user	=	new user();		
		if(!$user->is_email_format($_POST['reset_pw_email'])){
			echo "-30";
			return;
		}
		if(!$user->is_user_exist($_POST['reset_pw_email'])){
			echo "-10";
			return;
		}
		
		$new_pw=$user->get_rand_pw();
		$user->reset_user_pw($_POST['reset_pw_email'],$new_pw);		
		$user->send_pw_email($_POST['reset_pw_email'],$new_pw);
		echo "50";

		return;
	}
	
?>