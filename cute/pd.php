<?
	session_start();
	require('controller/product_controller.php');
	require('controller/daily_controller.php');
	
	$work	=	new	daily();
	$work->check_daily();
	$work->check_monthly();
	
	$pd	=	new product();

	$rs_pd_type=$pd->show_pd_type();
	is_referer_pazter();
	is_admin();
	//判斷type
	if(($_GET['action']=='new' || $_GET['action']=='update' || $_GET['action']=='del')){		
	}else{
		$_GET['action']='new';
	}
	
	//設定css
	if($_GET['action']=='new'){
		$sub_type_css[0]='active';
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
    	<li><a href="pd.php" class="active">產品</a></li>
   	<li><a href="bg.php" >Blog</a></li>
    	<li><a href="od.php">訂單</a></li>
			<li><a href="member.php">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>
			<li><a href="coupon.php">E-COUPON</a></li>
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
  <ul id="sub_function">
    	<li><a href="pd.php?action=new" class="<?=$sub_type_css[0]?>">新增</a></li>
   	<li><a href="pd.php?action=update" class="<?=$sub_type_css[1]?>">修改</a></li>
   	<li><a href="pd.php?action=del" class="<?=$sub_type_css[2]?>">刪除 </a></li>
  </ul>
</div>
<?
	//new
	if($_GET['action']=='new'){
?>
<div class="functions_wrap">
<form action="controller/product_controller.php?action=new" method="post" enctype="multipart/form-data" name="new_pd_form">
	<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td width="112">類別</td>
    <td colspan="2"><label>
      <select name="pd_type" id="select">
        <?
        	while($row_type=mysqli_fetch_array($rs_pd_type)){
		?>
        <option value="<?=$row_type['type_id']?>"><?=$row_type['type_name']?></option>
        <?
        	}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>名稱</td>
    <td colspan="2">
      <input name="pd_name" type="text" id="pd_name" size="90" />    </td>
  </tr>
  <tr>
    <td>售價</td>
    <td colspan="2"><input type="text" name="pd_price" id="pd_price" /></td>
  </tr>
  <tr>
    <td>尺寸</td>
    <td colspan="2"><input name="pd_size" type="text" id="pd_size" size="90" /></td>
  </tr>
  <tr>
    <td>材質</td>
    <td colspan="2"><input name="pd_material" type="text" id="pd_material" size="90" /></td>
  </tr>
  <tr>
    <td>國家</td>
    <td colspan="2"><input type="radio" name="pd_nation" value="台灣" />
      台灣 
					<input type="radio" name="pd_nation" value="日本" />
        日本
					<input type="radio" name="pd_nation" value="韓國" />
        韓國</td>
  </tr>
  <tr>
    <td>內含張數</td>
    <td colspan="2"><input type="text" name="pd_val" id="pd_val" /></td>
  </tr>
  <tr>
    <td>顏色</td>
    <td colspan="2">
<?
	$rs_color=$pd->get_all_colors();
	while($color_data=mysqli_fetch_array($rs_color) and mysql_num_rows($rs_color)>0){
?>    
    	<label><input type="checkbox" name="color[]" value="<?=$color_data['color_id']?>" /><?=$color_data['color']?></label>
<?
	}
?>    </td>
  </tr>
  <tr>
    <td>標籤</td>
    <td colspan="2"><input name="pd_tag" type="text" id="pd_tag" size="90" /> 
      用,隔開 ex 標籤1,標籤2</td>
  </tr>
  <tr>
    <td>說明(特色)</td>
    <td width="399"><textarea name="pd_feature" id="pd_feature" cols="60" rows="10"></textarea></td>
    <td width="417"><p>段落樣式</p>
      <p>&lt;p&gt;段落一段落一段落一段落一段落一&lt;/p&gt;</p>
      <p>&lt;p&gt;段落二段落二段落二段落二段落二&lt;/p&gt;</p></td>
  </tr>
<!--  <tr>
    <td>圖片</td>
    <td colspan="2">
   		<p>40*40<input type="file" name="upload_file_1" id="pic_40" /></p>
		<p>140*140<input type="file" name="upload_file_2" id="pic_140" /></p>
		<p>400*300<input type="file" name="upload_file_3" id="pic_400_1" /></p>
		<p>400*300<input type="file" name="upload_file_4" id="pic_400_2" /></p>
		<p>400*300<input type="file" name="upload_file_5" id="pic_400_3" /></p></td>
  </tr>	-->
  <tr>
    <td>顯示</td>
    <td colspan="2"><input name="display" type="radio" id="radio" value="0" checked="checked" />
      不顯示 
        <input type="radio" name="display" id="radio2" value="1" />
顯示</td>
  </tr>
  <tr>
    <td><input type="hidden" name="action" value="new" /></td>
    <td colspan="2"><input type="submit" name="button" id="button" value="送出" /></td>
  </tr>
</table>

</form>
</div>
<?
	}
	
	//update
	if($_GET['action']=='update'){
		
			$pic_path=$pd->get_p_images_path($_GET['u_id']);
		
?>
<div class="functions_wrap">

<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td>查詢產品</td>
    <td colspan="2">
    <form name="form2" method="post" action="controller/product_controller.php">
      
      <select name="pd_id">
      <?
	  $rs_pd_name=$pd->show_pd_name();		
		while($row_pd_name=mysqli_fetch_array($rs_pd_name)){
			$name_opteion_select="";
			$row_pd_name['product_name']=stripslashes($row_pd_name['product_name']);
			if($_GET['u_id']==$row_pd_name['product_id']){$name_opteion_select="selected";}				
      ?>
        <option value="<?=$row_pd_name['product_id']?>" <?=$name_opteion_select?>><?=$row_pd_name['product_name']?></option>
       <?
	   }
       ?>
      </select>
        <input name="action" type="hidden" value="select_update_item" />
        <input type="submit" name="button2" id="button2" value="送出" />
    </form>    </td>
  </tr>
<form name="form_update" enctype="multipart/form-data" method="post" action="controller/product_controller.php">
  <tr>
  <?
  			$pd_data=$pd->get_one_pd($_GET['u_id']);
			$result=$pd->get_this_pd_tag($_GET['u_id']);
			$pd_data['product_tag']=$pd->rs_tag_to_string($result);
  ?>
    <td colspan="3"><div class="modify_item_info">修改:<?=$_GET['u_id']?> -- <?=$pd_data['product_name']?></div></td>
    </tr>
  <tr>
    <td width="112">類別</td>
    <td colspan="2"><label>
      <select name="pd_type" id="select">
        <?
			
			if($pd_data['display']=='0'){$display[0]="checked=\"checked\"";}else{$display[1]="checked=\"checked\"";}
        	while($row_type=mysqli_fetch_array($rs_pd_type)){
				$type_option_select="";
				if($pd_data['product_type']==$row_type['type_id']){ $type_option_select="selected";}
		?>
        <option value="<?=$row_type['type_id']?>" <?=$type_option_select?>><?=$row_type['type_name']?></option>
        <?
        	}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>名稱</td>
    <td colspan="2">
      <input name="pd_name" type="text" id="pd_name" value="<?=$pd_data['product_name']?>" size="90" />    </td>
  </tr>
  <tr>
    <td>售價</td>
    <td colspan="2"><input type="text" name="pd_price" id="pd_price" value="<?=$pd_data['product_price']?>"/></td>
  </tr>
  <tr>
    <td>尺寸</td>
    <td colspan="2"><input name="pd_size" type="text" id="pd_size" value="<?=$pd_data['product_size']?>" size="90"/></td>
  </tr>
  <tr>
    <td>材質</td>
    <td colspan="2"><input name="pd_material" type="text" id="pd_material" value="<?=$pd_data['product_material']?>" size="90"/></td>
  </tr>
  <tr>
    <td>國家</td>
    <td colspan="2"><input type="radio" name="pd_nation" value="台灣" <? if($pd_data['nation']==台灣) echo "checked=\"checked\"" ?>/>
台灣
  <input type="radio" name="pd_nation" value="日本" <? if($pd_data['nation']==日本) echo "checked=\"checked\"" ?>/>
日本
<input type="radio" name="pd_nation" value="韓國" <? if($pd_data['nation']==韓國) echo "checked=\"checked\"" ?>/>
韓國</td>
  </tr>
  <tr>
    <td>內含張數</td>
    <td colspan="2"><input type="text" name="pd_val" id="pd_val" value="<?=$pd_data['product_val']?>"/></td>
  </tr>
  <tr>
    <td>顏色</td>
    <td colspan="2">
<?
	$arr_color=explode(",",$pd_data['product_color']);
	$rs_color=$pd->get_all_colors();
	while($color_data=mysqli_fetch_array($rs_color) and mysql_num_rows($rs_color)>0){
		if(in_array($color_data['color_id'],$arr_color)){
			$color_check='checked="checked"';
		}else{
			$color_check='';
		}
?>    
    	<label><input type="checkbox" name="color[]" value="<?=$color_data['color_id']?>" <?=$color_check?> /><?=$color_data['color']?></label>
<?
	}
?>    </td>
  </tr>
  <tr>
    <td>標籤</td>
    <td colspan="2"><input name="pd_ori_tag" type="hidden" value="<?=$pd_data['product_tag']?>" /><input name="pd_tag" type="text" id="pd_tag" value="<?=$pd_data['product_tag']?>" size="90"/> 
      用,隔開 ex 標籤1,標籤2</td>
  </tr>
  <tr>
    <td>說明(特色)</td>
    <td width="399"><textarea name="pd_feature" id="pd_feature" cols="60" rows="10"><?=$pd_data['product_feature']?></textarea></td>
    <td width="417"><p>段落樣式</p>
      <p>&lt;p&gt;段落一段落一段落一段落一段落一&lt;/p&gt;</p>
      <p>&lt;p&gt;段落二段落二段落二段落二段落二&lt;/p&gt;</p></td>
  </tr>
  <tr>
    <td>圖片</td>
    <td colspan="2">
		<p>40*40<input type="file" name="upload_file_1" id="pic_40" /><a href="<?=$pic_path[0]?>"><?=$pic_path[0]?></a></p>
		<p>140*140<input type="file" name="upload_file_2" id="pic_140" /><a href="<?=$pic_path[1]?>"><?=$pic_path[1]?></a></p>
		<p>400*300<input type="file" name="upload_file_3" id="pic_400_1" /><a href="<?=$pic_path[2]?>"><?=$pic_path[2]?></a></p>
		<p>400*300<input type="file" name="upload_file_4" id="pic_400_2" /><a href="<?=$pic_path[3]?>"><?=$pic_path[3]?></a></p>
		<p>400*300<input type="file" name="upload_file_5" id="pic_400_3" /><a href="<?=$pic_path[4]?>"><?=$pic_path[4]?></a></p></td>
  </tr>
  <tr>
    <td>顯示</td>
    <td colspan="2"><input name="display" type="radio" id="radio" value="0" <?=$display[0]?>/>
      不顯示 
        <input type="radio" name="display" id="radio2" value="1" <?=$display[1]?>/>
顯示</td>
  </tr>
  <tr>
    <td>庫存</td>
    <td colspan="2"><input type="text" name="pd_stock" value="<?=$pd_data['stock']?>" /></td>
  </tr>
  <tr>
    <td><input type="hidden" name="action" value="update_pd" /><input type="hidden" name="pd_id" value="<?=$_GET['u_id']?>" /></td>
    <td colspan="2"><input type="submit" name="button" id="button" value="儲存" /></td>
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
	  	$rs_pd_name=$pd->show_pd_name();
		echo $rs_pd_name;
		while($row_pd_name=mysqli_fetch_array($rs_pd_name)){
			$name_opteion_select="";
			if($_GET['u_id']==$row_pd_name['product_id']){$name_opteion_select="selected";}				
      ?>
        <option value="<?=$row_pd_name['product_id']?>" <?=$name_opteion_select?>><?=$row_pd_name['product_name']?></option>
       <?
	   }
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
