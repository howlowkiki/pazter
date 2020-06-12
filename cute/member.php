<?
	session_start();	
	require('controller/member_controller.php');
	is_referer_pazter();
	$user	=	new member();
	$rs_user_list	=	$user->get_all_user();
	
	if( $_GET['u']!="" && $_GET['s']!=""){
		
		if($user->set_act_1($_GET['u'],$_GET['s'])){
			page_togo("member.php");	
		}else{
			page_alert("無法修改。發生錯誤!");
		}		
	}
	//更改狀態
	
	if($_GET['action']=="set_news_list"){
		if($user->set_member_to_newslist()){
			page_togo("member.php");	
		}
	}
	
	if(  isset($_POST['news_add']) && $_POST['user_email']!="" ){
		if($user->add_to_newslist($_POST['user_email'])){
			page_togo("member.php");	
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cute</title>
<style type="text/css">
<!--
a {
	text-decoration: none;
	color: #3399FF;
}
.functions_wrap {
	position: relative;
	margin: 0 auto;
	width: 1000px;
	background-color: #f7f7f7;
}
#function {
	width: 980px;
	height: 50px;
	background-color: #CCCCCC;
	margin: 10px 10px 0 10px;
	padding:0;
}
#function li{
	float:left;
	list-style-type: none;
}
#function li.logout{
	float:right;
	list-style-type: none;
}
#function a{
	display: block;
	margin: 4px 2px;
	padding: 6px 30px;
	background-color: #FFFFFF;
}
#function a.active{
	display: block;
	margin: 4px 2px;
	padding: 6px 30px;
	background-color: #3366FF;
	color: #FFFFFF;
}
#sub_function {
	height: 50px;
	background-color: #CCCCCC;
	margin: 0 10px;
	width: 980px;
	padding:0;
}
#sub_function li{
	float:left;
	list-style-type: none;
}
#sub_function a{
	display: block;
	margin: 0 2px;
	padding: 6px 10px;
	background-color: #FFFFFF;
	font-size: 0.9em;
}
#sub_function a.active{
	display: block;
	margin: 0 2px;
	padding: 6px 10px;
	background-color: #0066FF;
	font-size: 0.9em;
	color: #FFFFFF;
}
.pd_table {
	margin: 0 10px;
	width: 980px;
	font-size: 0.9em;
}
.modify_item_info{
	color:#FF0000;
}
span.modify{
	margin:0 4px;
	font-size: 0.8em;
}
-->
</style>
<body>
<div class="functions_wrap">
  <ul id="function">
    	<li><a href="pd.php" >產品</a></li>
   	<li><a href="bg.php" >Blog</a></li>
    	<li><a href="od.php">訂單</a></li>
			<li><a href="member.php"  class="active">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>

  <ul id="sub_function">
    	<li><a href="member.php" class="<?=$sub_type_css[1]?>">電子報會員列表</a></li>   	
    	<li><a href="member.php?action=set_news_list" class="<?=$sub_type_css[0]?>">設定電子報會員</a></li>
  </ul>
  <ul id="sub_function">
<form action="member.php" method="post"><input name="user_email" type="text" /><input name="news_add" type="submit" value="加入電子報" /></form>
</ul>
</div>

<div class="functions_wrap">
<table width="710" border="1" cellpadding="6" class="pd_table">

  <tr>
    <td width="89">email</td>
    <td width="105">姓名</td>
    <td width="81">電話</td>
    <td width="53">郵遞區號</td>
    <td width="126">地址</td>
    <td width="95">註冊日期</td>
    <td width="85">會員禮</td>
    <td width="134">會員禮寄送</td>
  </tr>

<?
	
	while( $rs_user_list and $row=mysqli_fetch_array($rs_user_list)){		
?>  
  <tr>
    <td><? echo $email=($row['user_email']=="")?"&nbsp;":$row['user_email']; ?></td>
    <td><? echo $name=($row['user_name']=="")?"&nbsp;":$row['user_name']; ?></td>
    <td><? echo $tel=($row['user_tel']=="")?"&nbsp;":$row['user_tel']; ?>
    		<? echo $mobile=($row['user_mobile']=="")?"":"<br/>".$row['user_mobile']; ?>
    </td>
    <td><? echo $tel=($row['user_zipcode']=="")?"&nbsp;":$row['user_zipcode']; ?></td>
    <td><? echo $row['user_county'].$row['user_area'].$row['user_add']; ?></td>
    <td><? echo $register_date=($row['register_date']=="")?"&nbsp;":$row['register_date']; ?></td>
    <td><? echo $gift=($row['status']=="1")?"已送出":"&nbsp;"; ?></td>
    <td>
    	<span class="modify"><a href="member.php?u=<?=$row['user_email']?>&s=0" onClick="return(confirm('取消？'))">取消</a></span>
    	<span class="modify"><a href="member.php?u=<?=$row['user_email']?>&s=1" onClick="return(confirm('確定送出？'))">送出</a></span>
    </td>
  </tr>
<?
	}
?>
</table>

</div>

	
</body>
</html>
