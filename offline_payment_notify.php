<?
require('controller/user_controller.php');
require('controller/order_controller.php');

	$client=new user();
	$od=new order();
	$remote_ip=$client->get_user_ip();

	$_GET['access_key']=mysql_real_escape_string($_GET['access_key']);
	$_GET['txid']=mysql_real_escape_string($_GET['txid']);
	$_GET['amount']=mysql_real_escape_string($_GET['amount']);
	$_GET['pay_type']=mysql_real_escape_string($_GET['pay_type']);
	$_GET['status']=mysql_real_escape_string($_GET['status']);
	$_GET['tid']=mysql_real_escape_string($_GET['tid']);
	$_GET['verify']=mysql_real_escape_string($_GET['verify']);

//	$remote_ip='139.175.160.251';
//	$_GET['access_key']="83964456";
//	$_GET['txid']="20101221000002";
//	$_GET['amount']="	740";
//	$_GET['pay_type']="9";
//	$_GET['status']="3";
//	$_GET['tid']="	201012213867";
//	$_GET['verify']=$od->check_return_verify($_GET['txid'],$_GET['pay_type'],$_GET['status'],$_GET['tid']);

	$offline_verify=$od->check_return_verify($_GET['txid'],$_GET['pay_type'],$_GET['status'],$_GET['tid']);

	if($remote_ip!='139.175.160.251' || $od->access_key!=$_GET['access_key'] || $offline_verify!=$_GET['verify']){
		page_togo('oops.php');
	}else{

		//離線付款	虛擬帳號轉帳 7-11 ibon 全家 FamiPort
		if($_GET['pay_type']=='2' || $_GET['pay_type']=='9' || $_GET['pay_type']=='12'){
			$update_result=$od->offline_payment_complete($_GET['txid'],$_GET['tid'],$_GET['status']);
			if($update_result){
				echo "OK";
			}else{
				echo "INTERNAL ERROR";
			}
		}
	}


?>