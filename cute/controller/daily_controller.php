<?php
	session_start();
//require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');
	class daily{

		//	每日工作
		public function check_daily(){
			$type=array(1,2,3);
			$today=date("Y-m-d");

			foreach($type as $type_val){
				$sql="SELECT date FROM daily_work WHERE date='".$today."' AND type='".$type_val."'";
				$result=mysql_query($sql);
				if( mysql_num_rows($result)<1 ){
					if($type_val=='1'){$this->deadline_order_cancel();}
					if($type_val=='2'){$this->clean_up_basket();}
					if($type_val=='3'){$this->push_sale_value();}
				}
			}

		}

		//	每月工作
		public function check_monthly(){
			$type=array(11);
			$this_month=date("Y-m");

			foreach($type as $type_val){
				$sql="SELECT SUBSTR(date,1,7) AS month FROM daily_work WHERE type='".$type_val."' AND SUBSTR(date,1,7)='".$this_month."'";
				$result=mysql_query($sql);
				if( mysql_num_rows($result)<1 ){
					if($type_val=='11'){$this->clean_up_cancel_order();}
				}
			}

		}

		//	取消逾期付款訂單	type 1
		public function  deadline_order_cancel(){
			$today=date("Y-m-d");
			$sql="UPDATE orders SET order_status='5' WHERE order_status='1' AND pay_deadline<'".$today."' ";
			if(mysql_query($sql)){
				$sql_daily="INSERT INTO daily_work(date,type) VALUES('".$today."','1')";
				mysql_query($sql_daily);
			}
		}

		//	清除過期購物車 保留7天 type 2
		public function clean_up_basket(){
			$live_time=7;
			$today=date("Y-m-d");
			$a_week_ago=date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-$live_time,date("Y")));
			$sql="DELETE FROM baskets WHERE basket_date<'".$a_week_ago."'";
			if(mysql_query($sql)){
				$sql_weekly="INSERT INTO daily_work(date,type) VALUES('".$today."','2')";
				mysql_query($sql_weekly);
			}
		}

		//	銷售量PUSH
	public function push_sale_value(){
		$today=date("Y-m-d");
		$push_nums=rand(1,5);
		$sql="UPDATE products SET sold=sold+1 WHERE stock>0 ORDER BY RAND() LIMIT ".$push_nums;
		if(mysql_query($sql)){
			$sql_weekly="INSERT INTO daily_work(date,type) VALUES('".$today."','3')";
			mysql_query($sql_weekly);
		}
	}


		//	清除6個月前的取消訂單	type 11
		public function clean_up_cancel_order(){
			$today=date("Y-m-d");
			$live_month=6;
			//6 month ago
			$six_month_ago=date("Ym",mktime(0,0,0,date("m")-$live_month,0,date("Y")))."00";
			$sql_clean_order_id="SELECT CONCAT(order_date,order_seq) AS order_id FROM orders
													WHERE order_status='1' AND order_date<'".$six_month_ago."'";

			//刪除寄送資料
			$sql_delete_ship_data="DELETE FROM ship_order WHERE order_id IN(".$sql_clean_order_id.")";
			mysql_query($sql_delete_ship_data);

			//刪除訂單商品
			$sql_delete_order_product="DELETE FROM order_products WHERE order_id IN(".$sql_clean_order_id.")";
			mysql_query($sql_delete_order_product);

			//刪除訂單
			$sql_delete_order="DELETE FROM orders WHERE order_status='1' AND order_date<'".$six_month_ago."'";
			mysql_query($sql_delete_order);

			$sql_monthly="INSERT INTO daily_work(date,type) VALUES('".$today."','11')";
			mysql_query($sql_monthly);
		}

	}
?>