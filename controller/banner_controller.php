<?php 
	if(!isset($_SESSION)){session_start();}
	require('db_config.php');
	
		
	class banner{		
		public function banner(){}
		
		public function get_banner_data($conn){

			$today=date("Y-m-d H:i:s");
			$sql="SELECT seq,banner_desc,start_date,link,image_path FROM banner WHERE start_date<='".$today."' AND end_date>'".$today."'  ORDER BY seq DESC LIMIT 4";

			return mysqli_query($conn,$sql);

		}
	}

?>