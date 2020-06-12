<?
	session_start();
	require('controller/mail_controller.php');	
	require('controller/common.php');	
	is_admin();
	is_referer_pazter();
	$mailer	=	new mailer();	
	
	if( isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['title']) && isset($_POST['content'])){
	
		if(isset($_POST['send_mail'])){
			if($mailer->send_mail($_POST['to'],$_POST['subject'],$_POST['title'],$_POST['content'])){
				page_alert_togo("寄送成功","mailer.php");	
			}else{
				page_alert_togo("寄送失敗","mailer.php");	
			}
		}
		
		if(isset($_POST['preview_mail'])){
			$preview_mail	=	$mailer->get_preview_mail($_POST['to'],$_POST['subject'],$_POST['title'],$_POST['content']);
			$preview_mail	=	stripslashes($preview_mail);
			$_POST['content']	=	stripslashes($_POST['content']);
		}
		
		if(isset($_POST['news_mail'])){
			if($mailer->send_news_mail($_POST['subject'],$_POST['title'],$_POST['content'])){
				page_alert_togo("寄送成功","mailer.php");	
			}else{
				page_alert_togo("寄送失敗","mailer.php");	
			}
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
			<li><a href="mailer.php" class="active">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>
            
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
</div>

<div class="functions_wrap">
  <blockquote>
    <blockquote>
      <form action="mailer.php" method="post" enctype="multipart/form-data" name="new_bg_form">
        <table width="710" border="1" cellpadding="6" class="pd_table">
          <tr>
            <td>收件人</td>
        <td colspan="2"><input type="text" name="to" value="<?=$_POST['to']?>"/></td>
      </tr>
          <tr>
            <td>信件標題</td>
        <td colspan="2"><input type="text" name="subject" value="<?=$_POST['subject']?>"/></td>
      </tr>
          <tr>
            <td width="112">標題</td>
        <td colspan="2">
          <textarea name="title" cols="60" rows="10"><?=$_POST['title']?></textarea></td>
      </tr>
          <tr>
            <td>內容</td>
        <td width="399"><textarea name="content" cols="60" rows="10"><?=$_POST['content']?></textarea></td>
        <td width="417"><p>段落樣式</p>
	      <p style="font-size:12px; color:#333333;">&lt;p style=&quot;font-size:12px; color:#333333;&quot;&gt;<br />
	        您訂購的商品已為您寄出，訂單編號為:<br />
	        &lt;/p&gt;</p>  </tr>
          <tr>
            <td>&nbsp;</td>
        <td colspan="2">
          <input type="submit" name="preview_mail" value="預覽" />　　
          <input type="submit" name="send_mail" value="送出" />　　
          <input type="submit" name="news_mail" value="發送電子報" />
          　</td>
      </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2"><?=$preview_mail?></td>
          </tr>
        </table>
      </form>
    </blockquote>
  </blockquote>
</div>

</body>
</html>
