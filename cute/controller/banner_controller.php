<?php 
	session_start();
//	require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
	require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');

	require('common.php');
	is_admin();
	
	class banner{		
		public function banner(){}
		
		public function get_banner_data(){
			$sql="SELECT seq,desc,date,link,image_path FROM banner ORDER BY seq";
			return mysql_query($sql);
		}
		
		public function set_banner_data($banner_data,$cnt){
			
			for($i=1;$i<=$cnt;$i++){
				$sql="UPDATE banner SET seq='".$banner_data[$i]['seq']."',
																desc='".$banner_data[$i]['desc']."',
																date='".$banner_data[$i]['date']."',
																link='".$banner_data[$i]['link']."' 
							WHERE seq='".$banner_data[$i]['seq']"'";							
				mysql_query($sql);
			}			
		}
		
	}

?>