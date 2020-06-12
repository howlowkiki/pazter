<?
	session_start();
	require('controller/mail_controller.php');	
	require('controller/common.php');	
	is_admin();
	is_referer_pazter();	
	if( isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['title']) && isset($_POST['content'])){
		$mailer	=	new mailer();
		if($mailer->send_mail($_POST['to'],$_POST['subject'],$_POST['title'],$_POST['content'])){
			page_alert_togo("寄送成功",'mailer.php');	
		}else{
			page_alert_togo("寄送失敗",'mailer.php');	
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
-->
</style>
<body>
<div class="functions_wrap">
  <ul id="function">
    	<li><a href="pd.php" >產品</a></li>
   	<li><a href="bg.php" >Blog</a></li>
    	<li><a href="od.php">訂單</a></li>
			<li><a href="member.php">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php"  class="active">BANNER</a></li>
            
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
</div>

<div class="functions_wrap">
<form action="mailer.php" method="post" enctype="multipart/form-data" name="new_bg_form">
	<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td>SEQ</td>
    <td width="816"><input type="text" name="seq" id="seq"/></td>
  </tr>
  <tr>
    <td>描述</td>
    <td><textarea name="desc" cols="60" rows="6" id="desc"></textarea></td>
  </tr>
  <tr>
    <td width="112">日期</td>
    <td>
      <input name="date" type="text" size="60" id="date"/>    </td>
  </tr>
  <tr>
    <td>連結網址</td>
    <td><input name="link" type="text" size="60" id="link"/></td>
  </tr>
  <tr>
    <td>圖片</td>
    <td><input name="image_path" type="text" size="60" id="image_path"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="button" id="button" value="送出" /></td>
  </tr>
</table>

</form>
</div>

</body>
</html>
