<?php
	require("controller/user_controller.php");

	$u	=	new user();
	$_GET['id']	=	mysql_real_escape_string($_GET['id']);
	$_GET['verify']	=	mysql_real_escape_string($_GET['verify']);

	if($u->set_user_confirm($_GET['id'],$_GET['verify'])){
		$GLOBALS['USER_CONFIRM']=true;
	}else{
		$GLOBALS['USER_CONFIRM']=false;
	}

	//set var
	$GLOBALS["FULL_PAGE"]='yes';
	//import pages
	require("header.php");
	require("user_confirm_content.php");
	require("footer.php");

?>