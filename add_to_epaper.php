<?
	require_once('controller/common_func.php');
	require_once('controller/user_controller.php');
	$user	=	new user();

	//GET VARS
	if($_GET['epaper_mail']!="" && $_GET['act']!=""){

		$_GET['epaper_mail']=mysql_real_escape_string($_GET['epaper_mail']);

		if( !($_GET['act']=="add" ||  $_GET['act']=="cancel") ){
			page_togo("/");
			return;
		}
		if( (!$user->is_email_format($_GET['epaper_mail'])) || strlen($_GET['epaper_mail'])>50 ){
			page_togo("/");
			return;
		}
		if($_GET['act']=="add"){
			if($user->add_epaper($_GET['epaper_mail'])){

			}else{

			}
		}
		if($_GET['act']=="cancel"){
			if($user->cancel_epaper($_GET['epaper_mail'])){

			}else{

			}
		}

	}else{// POST VARS

		if($_POST['epaper_mail']!="" && $_POST['act']!=""){

			if($_SESSION['epaper_token']!=$_POST['epaper_token']){
					echo "-90a";
					return;
			}
			if( (!$user->is_email_format($_POST['epaper_mail'])) || strlen($_POST['epaper_mail'])>50 ){
				echo "-90b";
				return;
			}
			if( !($_POST['act']=="add" ||  $_POST['act']=="cancel") ){
				echo "-90c";
				return;
			}
			if($_POST['act']=="add"){
				if($user->add_epaper($_POST['epaper_mail'])){
						echo "10";
						return;
				}else{
					echo "-90d";
					return;
				}
			}
			if($_POST['act']=="cancel"){
				if($user->cancel_epaper($_POST['epaper_mail'])){
					echo "10";
					return;
				}else{
					echo "-90";
					return;
				}
			}

		}else{
			echo "-90";
			return;
		}
	}

?>
