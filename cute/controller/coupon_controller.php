<?
session_start();
//require_once($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
require_once('common.php');
is_admin();

/*
	user_id varchar(50)
	coupon_no varchar(6)
	coupon_value smallint(6)
	used varchar(1)
	order_id varchar(14)
*/
class coupon{
	
	//產生coupon號碼
	public function get_coupon_no(){
		$coupon_no="";
		while(strlen($coupon_no)<6){
		  switch(rand(1,2)){    
		    case 1:
		      $coupon_no.=chr(rand(97,122));
		      break;
		    case 2:
		      $$coupon_no.=chr(rand(48,57));
		      break;
		  }
		}
		return $coupon_no;
	}
	
	// 設定新coupon
	public function new_coupon($coupon_data){
		
		if($coupon_data['user_id']=="") return false;
		if($coupon_data['coupon_value']=="") $coupon_data['coupon_value']=50;
		$coupon_data['coupon_no']=$this->get_coupon_no();
		$coupon_data['used']='0';
		
		$sql="INSERT INTO coupon(user_id,coupon_no,coupon_value,used,order_id) 
					VALUES('".$coupon_data['user_id']."','".$coupon_data['coupon_no']."','".$coupon_data['coupon_value'].
					"','".$coupon_data['used']."','".$coupon_data['order_id']."') ";
		if(!mysql_query($sql)){
			return false;
		}
	}
	
	//取得coupon data
	public function get_coupon_data($user_id){
		$sql="SELECT * FROM coupon WHERE user_id='".$user_id."'";
		$result=mysql_query($sql);
		return $result;
	}
	
	//更新coupon data
	public function update_coupon_data($coupon_data){
		$sql="SELECT user_id FROM coupon WHERE user_id='".$coupon_data['user_id']."'";
		$result=mysql_query($sql);
		if($result and mysql_num_rows($result)>0){
			$sql_update="UPDATE coupon SET coupon_value='".$coupon_data['coupon_value']."',used=".
			$coupon_data['used'].",order_id='".$coupon_data['order_id']."' WHERE user_id=".$coupon_data['user_id']." AND coupon_no='".$coupon_data['coupon_no']."'";
			mysql_query($sql_update);
		}
	}
	
	//刪除coupon date
	public function del_doupon_data($coupon_data){
		$sql="DELETE FROM coupon WHERE user_id='".$coupon_data['user_id']."' AND coupon_no='".$coupon_data['coupon_no']."'";
		mysql_query($sql) or die("delete fail");
	}

}

?>