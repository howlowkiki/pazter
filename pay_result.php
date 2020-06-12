<?
	session_start();
	//import common file
	require("controller/order_controller.php");
	$od=new order();
	$GLOBALS["PAY_STATUS"]=false;
	$GLOBALS["PAY_OFFLINE"]=false;

	//test vars

	$_POST['txid']=mysql_real_escape_string($_POST['txid']);
	$_POST['amount']=mysql_real_escape_string($_POST['amount']);
	$_POST['pay_type']=mysql_real_escape_string($_POST['pay_type']);
	$_POST['status']=mysql_real_escape_string($_POST['status']);
	$_POST['tid']=mysql_real_escape_string($_POST['tid']);
	$_POST['verify']=mysql_real_escape_string($_POST['verify']);
	$_POST['cname']=mysql_real_escape_string($_POST['cname']);
	$_POST['caddress']=mysql_real_escape_string($_POST['caddress']);
	$_POST['ctel']=mysql_real_escape_string($_POST['ctel']);
	$_POST['cemail']=mysql_real_escape_string($_POST['cemail']);
	$_POST['xname']=mysql_real_escape_string($_POST['xname']);
	$_POST['xaddress']=mysql_real_escape_string($_POST['xaddress']);
	$_POST['xemail']=mysql_real_escape_string($_POST['xemail']);
	$_POST['error_code']=mysql_real_escape_string($_POST['error_code']);
	$_POST['error_desc']=mysql_real_escape_string($_POST['error_desc']);
	$_POST['auth_code']=mysql_real_escape_string($_POST['auth_code']);
	$_POST['xid']=mysql_real_escape_string($_POST['xid']);
	$_POST['ref_no']=mysql_real_escape_string($_POST['ref_no']);
	$_POST['account_no']=mysql_real_escape_string($_POST['account_no']);
	$_POST['bill_no']=mysql_real_escape_string($_POST['bill_no']);
	$_POST['bill_url']=mysql_real_escape_string($_POST['bill_url']);


	//檢查回傳摘要值
	if($od->check_return_verify($_POST['txid'],$_POST['pay_type'],$_POST['status'],$_POST['tid'])!=$_POST['verify']){
		page_togo('oops.php');
	}else{

		//信用卡
		if($_POST['pay_type']=='1'){
			if($_POST['status']=='1'){
				$update_result=$od->pay_by_creditcard($_POST['txid'],$_POST['tid'],$_POST['pay_type'],$_POST['status'],$_POST['xid']);
				if($update_result){
					$od->send_order_mail($_POST['txid'],"online");
					$GLOBALS["PAY_STATUS"]=true;
				}
			}else{
				$GLOBALS["PAY_STATUS"]=false;
			}
		}

		//WEB ATM
		if($_POST['pay_type']=='4'){
			if($_POST['status']=='1'){
				$update_result=$od->pay_by_webatm($_POST['txid'],$_POST['tid'],$_POST['pay_type'],$_POST['status']);
				if($update_result){
					$od->send_order_mail($_POST['txid'],"online");
					$GLOBALS["PAY_STATUS"]=true;
				}
			}else{
				$GLOBALS["PAY_STATUS"]=false;
			}
		}

		//離線付款	虛擬帳號轉帳 7-11 ibon 全家 FamiPort
		if($_POST['pay_type']=='2' || $_POST['pay_type']=='9' || $_POST['pay_type']=='12'){

			if($_POST['pay_type']=='2'){
				$update_result=$od->pay_by_offline($_POST['txid'],$_POST['tid'],$_POST['pay_type'],$_POST['status'],$_POST['account_no'],$_POST['bill_url']);
			}

			if($_POST['pay_type']=='9'){
				$update_result=$od->pay_by_offline($_POST['txid'],$_POST['tid'],$_POST['pay_type'],$_POST['status'],"",$_POST['bill_url']);
			}

			if($_POST['pay_type']=='12'){
				$update_result=$od->pay_by_offline($_POST['txid'],$_POST['tid'],$_POST['pay_type'],$_POST['status'],$_POST['bill_no'],$_POST['bill_url']);
			}

			if($update_result){
				$GLOBALS["PAY_STATUS"]=true;
				$GLOBALS["PAY_OFFLINE"]=true;
				$od->send_order_mail($_POST['txid'],"offline");
			}

		}

		//	更新購買人次
		if($GLOBALS["PAY_STATUS"]){
			$od->update_sold_count($_POST['txid']);
		}

	}

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("pay_result_content.php");
	require("footer.php");

?>
