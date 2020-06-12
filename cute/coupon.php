<?
	session_start();
	require('controller/member_controller.php');
	require_once('controller/coupon_controller.php');
	require_once('controller/common.php');

	$coupon=new coupon();
	$member=new member();

	is_referer_pazter();
	is_admin();

	if($_POST['action']=="new_coupon"){

		if( !intval($_POST['coupon_value']) ) $_POST['coupon_value']="";
		$coupon_data['user_id']=mysql_real_escape_string($_POST['user_id']);
		$coupon_data['coupon_value']=$_POST['coupon_value'];

		if($coupon->new_coupon($coupon_data)){
			page_alert("OK");			
		}else{
			page_alert_togo("fail","coupon.php");
		}

	}


	//判斷type
	if(($_GET['action']=='new' || $_GET['action']=='update' || $_GET['action']=='del')){
	}else{
		$_GET['action']='new';
	}

	//設定css
	if($_GET['action']=='new'){
		$sub_type_css[0]='active';
		$rs_all_user=$member->get_all_user();
	}
	if($_GET['action']=='update'){
		$sub_type_css[1]='active';
	}
	if($_GET['action']=='del'){
		$sub_type_css[2]='active';
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

label{
	margin-right:16px;
}
-->
</style>
<body>
<div class="functions_wrap">
  <ul id="function">
    	<li><a href="pd.php">產品</a></li>
   		<li><a href="bg.php" >Blog</a></li>
    	<li><a href="od.php">訂單</a></li>
			<li><a href="member.php">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>
			<li><a href="coupon.php" class="active">E-COUPON</a></li>
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
  <ul id="sub_function">
    	<li><a href="coupon.php?action=new" class="<?=$sub_type_css[0]?>">新增</a></li>
   	<li><a href="coupon.php?action=update" class="<?=$sub_type_css[1]?>">修改</a></li>
   	<li><a href="coupon.php?action=del" class="<?=$sub_type_css[2]?>">刪除 </a></li>
  </ul>
</div>
<?
	//new
	if($_GET['action']=='new'){
?>
<div class="functions_wrap">
<form action="coupon.php" method="post" enctype="multipart/form-data" name="new_pd_form">
	<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td width="112">會員</td>
    <td width="816"><label>
      <select name="user_id" id="select">
        <?
					while($user_data=mysqli_fetch_array($rs_all_user)){
		?>
        <option value="<?=$user_data['user_email']?>"><?=$user_data['user_email']?>-<?=$user_data['user_name']?></option>
        <?
					}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>coupon</td>
    <td><input name="coupon_value" type="text" id="pd_price" value="50" /></td>
  </tr>
  <tr>
    <td><input type="hidden" name="action" value="new_coupon" /></td>
    <td><input type="submit" name="button" id="button" value="送出" /></td>
  </tr>
</table>

</form>
</div>
<?
	}

	//update
	if($_GET['action']=='update'){
?>
<div class="functions_wrap">

<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td width="112">會員</td>
    <td width="816">
    <form name="form2" method="post" action="controller/product_controller.php">

      <select name="pd_id">
      <?

      ?>
        <option value="<?=$row_pd_name['product_id']?>" <?=$name_opteion_select?>><?=$row_pd_name['product_name']?></option>
       <?

       ?>
      </select>
        <input name="action" type="hidden" value="select_update_item" />
        <input type="submit" name="button2" id="button2" value="送出" />
    </form>    </td>
  </tr>
<form name="form_update" enctype="multipart/form-data" method="post" action="controller/product_controller.php">
  <tr>
  <?

  ?>
    <td colspan="2"><div class="modify_item_info">修改:<?=$_GET['u_id']?> -- <?=$pd_data['product_name']?></div></td>
    </tr>
  <tr>
    <td>使用</td>
    <td>
      <input name="pd_name" type="text" id="pd_name" value="<?=$pd_data['product_name']?>" size="10" />    </td>
  </tr>
  <tr>
    <td>訂單編號</td>
    <td><input type="text" name="pd_price" id="pd_price" value="<?=$pd_data['product_price']?>"/></td>
  </tr>
  <tr>
    <td><input type="hidden" name="action" value="update_pd" /><input type="hidden" name="pd_id" value="<?=$_GET['u_id']?>" /></td>
    <td><input type="submit" name="button" id="button" value="儲存" /></td>
  </tr>
</form>
</table>

</div>
<?
}

	//delete
	if($_GET['action']=='del'){
?>
<div class="functions_wrap">
  <table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td width="105">刪除產品</td>
    <td width="839"><form name="form1" method="post" action="controller/product_controller.php">
      <input type="hidden" name="action" value="del_pd" />
      <select name="pd_id">
      <?

      ?>
        <option value="<?=$row_pd_name['product_id']?>" <?=$name_opteion_select?>><?=$row_pd_name['product_name']?></option>
       <?

       ?>
      </select>
        <input type="submit" name="button3" id="button3" value="刪除" />
    </form>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
<?
	}
?>
</body>
</html>
