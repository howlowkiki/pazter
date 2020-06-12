<?	
	if(!isset($_SESSION)){session_start();}
	require('cart_controller.php');
		
	$page_links=array(
		"index"=>"index.php" ,
		"category"=>"category.php" ,
		"product"=>"product.php" , 
		"detail"=>"sticker.php" ,
		"contact"=>"item_right",
		"blog"=>"blog.php",
		"tag"=>"tag.php",
		"contact"=>"contact.php",
		"about"=>"about.php",
		"privacy"=>"privacy.php",
		"search"=>"search.php",
		"search_con"=>"controller/search_controller.php",
		"help"=>"help.php",
		"user"=>"user.php",
		"user_info"=>"user_info.php",
		"user_order"=>"user_order.php",
		"user_login"=>"user_login.php",
		"cart"=>"cart.php",
		"customer"=>"customer.php",
		"corp"=>"corp.php",
		"pay"=>"payment.php"
	);
	

//	轉移頁面
function page_togo($url){
	exit('<script Language="JavaScript"><!--
       		location.replace("'. $url .'"); 
       	// --></script>');
}

//	警告對話框
function page_alert($msg){
	exit('<script Language="JavaScript"><!--
       		alert("'. $msg .'"); 
       	// --></script>');
}

//	
function page_alert_togo($msg,$url){
	exit('<script Language="JavaScript"><!--
       		alert("'. $msg .'");
       		location.replace("'. $url .'"); 
       	// --></script>');
}

	//	CHECK PAGE REQUIRE
function is_full_page(){
	if($GLOBALS["FULL_PAGE"]!="yes"){
		page_togo('oops.php');
	}
}

//	取得REFERER
function get_referer_page(){	
	$array_name=explode('/',$_SERVER['HTTP_REFERER']);
	return $array_name[count($array_name)-1];	
}

//	確認refer
function is_refer_domain($page){
	$arr_url=explode('/',$_SERVER['HTTP_REFERER']);
	$domain=$arr_url[count($arr_url)-2];
//	if(!($domain=="pazter.com" || $domain=="www.pazter.com")){
//		page_togo('index.php');
//	}
	if(!($domain=="pazter.com" || $domain=="www.pazter.com" || $domain=="pazter" )){
		if($page==""){
			page_togo('index.php');
		}else{
			page_togo($page);	
		}
	}	
}

//	設定頁面瀏覽SESSION
function set_page_counter($page,$conn){

	if(!isset($_SESSION['page'.$page])){
		if(!intval($page)) return false;
		$sql="SELECT id FROM page_counter WHERE id='".$page."'";
		if($result=mysqli_query($conn,$sql) and mysqli_num_rows($result)>0){
			$sql_update="UPDATE page_counter SET counter=counter+1 WHERE id='".$page."'";
			mysqli_query($conn,$sql_update);
		}else{
			$sql_insert="INSERT INTO page_counter(id,counter) VALUES('".$page."','1')";
			mysqli_query($conn,$sql_insert);
		}
		$_SESSION['page'.$page]=true;		
	}
}

//	取得頁面瀏覽次數	
function get_page_counter($page, $conn){
	if(!intval($page)) return false;
	$sql="SELECT counter FROM page_counter WHERE id='".$page."'";
	$result=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($result);
	return $row['counter'];
}

//過濾特殊字元
function filter_symbol_char($str,$max_len){
	
	$str  =  str_replace(' ',  '',  $str);
	if($str=="")return true;
	
	if(mb_strlen($str)>$max_len){
		return false;
	}
	
	for($i=0;$i<mb_strlen($str,"utf-8");$i++){
		if(strlen($the_char=substr($str,$i,2,"utf-8"))<2){			
			if(!preg_match('/^[a-zA-Z0-9]+$/', $the_char)){				
				return false;
			}
		}	
	}
	return $str;
}

//	選項
function get_about_list(){
	global $page_links;
	echo '
	    <li><a href="'.$page_links['about'].'">關於我們</a></li>
        <li><a href="'.$page_links['help'].'">常見問題</a></li>
        <li><a href="'.$page_links['privacy'].'">隱私權保護</a></li>
        <li><a href="'.$page_links['contact'].'">聯絡我們</a></li>
        <li><a href="'.$page_links['corp'].'">合作提案</a></li>
        ';
}

//	會員中心選項
function get_member_center_list(){
	global $page_links;
	echo '
		<h3>會員中心</h3>
			<ul>
	    	<li><a href="'.$page_links['user_login'].'">登入 / 註冊</a></li>
        <li><a href="'.$page_links['user'].'">基本資料</a></li>
        <li><a href="'.$page_links['user_info'].'">我的帳戶</a></li>
        <li><a href="'.$page_links['user_order'].'">訂單明細</a></li>
        <li><a href="'.$page_links['cart'].'">購物車</a></li>
			</ul>
    <h3>非會員服務</h3>
    	<ul>
    		<li><a class="order_query" href="order_query.php" rel="prettyPopin">非會員訂單查詢</a></li>
    		<li><a class="add_epaper" href="order_query.php" rel="prettyPopin">電子報訂閱</a></li>
    	</ul>
        ';
}

//會員登入狀態
function is_user_login(){
	global $page_links;
	if(!isset($_SESSION['user_email'])){
		page_togo($page_links['user_login']);
	}else{
		return true;	
	}
}
?>
