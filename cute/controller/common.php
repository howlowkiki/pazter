<?
	session_start();
	
	function page_togo($url){
	exit('<script Language="JavaScript"><!--
       		location.replace("'. $url .'"); 
       	// --></script>');
	}

	function page_alert($msg){
		exit('<script Language="JavaScript"><!--
	       		alert("'. $msg .'"); 
	       	// --></script>');
	}
	       	
	function page_alert_togo($msg,$url){
		exit('<script Language="JavaScript"><!--
	       		alert("'. $msg .'");
	       		location.replace("'. $url .'"); 
	       	// --></script>');
	}
	
	function is_admin(){
		if(!isset($_SESSION['admin_name'])){			
			session_destroy();
			page_togo('../index.php');
		}
	}
	function is_referer_pazter(){
		$arr_url=explode('/',$_SERVER['HTTP_REFERER']);
		$path=$arr_url[count($arr_url)-3]."/".$arr_url[count($arr_url)-2];
		
//		if(!($path=="www.pazter.com/cute" || $path=="pazter.com/cute" || $path=="pazter/cute")){
//			session_destroy();
//			page_togo("../index.php");	
//		}
		
	}
	
?>