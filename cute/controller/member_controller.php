<?
session_start();
//require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
require('common.php');
is_admin();
	
class member{
	
	public function member(){
		
	}
	
	public function get_all_user(){
		$sql="SELECT user_profile.user_email,user_name,user_tel,user_mobile,user_zipcode,user_county,
					user_area,user_add,user_confirm,LEFT(user_register_date,10) AS register_date,
					act_1.status  
					FROM user_profile LEFT JOIN act_1 ON user_profile.user_email=act_1.user_email 
					ORDER BY LEFT(user_register_date,10) DESC";
		return mysql_query($sql);
	}
	
	public function set_act_1($user_email,$status){
		$user_email=mysql_real_escape_string($user_email);
		$status=mysql_real_escape_string($status);
		$sql="SELECT user_email FROM act_1 WHERE user_email='".$user_email."'";
		if($result=mysql_query($sql) and mysql_num_rows($result)>0){
			$sql_update="UPDATE act_1 SET status='".$status."' WHERE user_email='".$user_email."'";
			mysql_query($sql_update);
		}else{
			$sql_insert="INSERT INTO act_1(user_email,status) VALUES('".$user_email."','".$status."')";
			mysql_query($sql_insert);
		}
		return true;
	}
	
	public function set_member_to_newslist(){
		$sql="SELECT user_email FROM user_profile";
		$rs=mysql_query($sql);
		while( mysql_num_rows($rs)>0	and $rows=mysqli_fetch_array($rs)){			
			$sql_memeber_exist="SELECT user_email FROM news_list WHERE user_email='".$rows['user_email']."'";
			$rs_member	=	mysql_query($sql_memeber_exist);			
			if(	mysql_num_rows($rs_member)<1	){
				$sql_insert="INSERT INTO news_list VALUES('".$rows['user_email']."','1')";
				mysql_query($sql_insert);
			}
		}
		return true;
	}
	
	public function add_to_newslist($user_email){
		if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $user_email)){
			return false;			
		}
		
		$sql_memeber_exist="SELECT user_email FROM news_list WHERE user_email='".$user_email."'";
		$rs_member	=	mysql_query($sql_memeber_exist);
		if(	mysql_num_rows($rs_member)<1	){
			$sql_insert="INSERT INTO news_list VALUES('".$user_email."','1')";
			mysql_query($sql_insert);			
		}
		return true;
	}
	
}

?>