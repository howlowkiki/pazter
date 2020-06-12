<?
	session_start();
	require('controller/blog_controller.php');	
	is_admin();
	is_referer_pazter();
	$bg=new blog();
		
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
-->
</style>
<body>
<div class="functions_wrap">
  <ul id="function">
    	<li><a href="pd.php" >產品</a></li>
   	<li><a href="bg.php" class="active">Blog</a></li>
    	<li><a href="od.php">訂單</a></li>
			<li><a href="member.php">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>            
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
  <ul id="sub_function">
    	<li><a href="bg.php?action=new" class="<?=$sub_type_css[0]?>">新增</a></li>
   	<li><a href="bg.php?action=update" class="<?=$sub_type_css[1]?>">修改</a></li>
   	<li><a href="bg.php?action=del" class="<?=$sub_type_css[2]?>">刪除 </a></li>
  </ul>
</div>
<?
	//new
	if($_GET['action']=='new'){
?>
<div class="functions_wrap">
<form action="controller/blog_controller.php" method="post" enctype="multipart/form-data" name="new_bg_form">
	<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td width="112">類別</td>
    <td colspan="2"><label>
      <select name="bg_type" id="select">
        <?
			$rs_bg_type=$bg->show_bg_type();
        	while($row_type=mysqli_fetch_array($rs_bg_type)){
		?>
        <option value="<?=$row_type['type_id']?>"><?=$row_type['type_name']?></option>
        <?
        	}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>標題</td>
    <td colspan="2">
      <input type="text" name="bg_title"/>    </td>
  </tr>
  
  
  
  <tr>
    <td>內容</td>
    <td width="399"><textarea name="bg_content" cols="60" rows="10"></textarea></td>
    <td width="417"><p>段落樣式</p>
      <p>&lt;p&gt;段落一段落一段落一段落一段落一&lt;/p&gt;</p>
      <p>&lt;p&gt;段落二段落二段落二段落二段落二&lt;/p&gt;</p></td>
  </tr>
  <tr>
    <td>圖片</td>
    <td colspan="2">
    	<p>690*200  pic1</p>
      <p>690*200 pic2</p>
    </td>
  </tr>
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
		
?>
<div class="functions_wrap">

<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
    <td>查詢文章</td>
    <td colspan="2">
    <form name="form2" method="post" action="controller/blog_controller.php">
      <select name="bg_id">
        <?
	  	$rs_bg_name=$bg->show_bg_name();
		while($row_bg_name=mysqli_fetch_array($rs_bg_name)){
			$name_opteion_select="";
			if($_GET['u_id']==$row_bg_name['id']){$name_opteion_select="selected";}				
      ?>
        <option value="<?=$row_bg_name['id']?>" <?=$name_opteion_select?>>
          <?=$row_bg_name['title']?>
          </option>
        <?
	   }
       ?>
      </select>
      <input name="action" type="hidden" value="select_update_item" />
        <input type="submit" name="button2" id="button2" value="送出" />
    </form>    </td>
  </tr>
<form name="form_update" enctype="multipart/form-data" method="post" action="controller/blog_controller.php">
  <tr>
  <?
  			$bg_data=$bg->get_one_bg($_GET['u_id']);
  			$pic_path=$bg->get_p_images_path($_GET['u_id']);
  ?>
    <td colspan="3"><div class="modify_item_info">修改:<?=$_GET['u_id']?> -- <?=$bg_data['title']?></div></td>
    </tr>
  <tr>
    <td width="112">類別</td>
    <td colspan="2"><label>
      <select name="bg_type" id="select">
        <?
			$rs_bg_type=$bg->show_bg_type();
			if($bg_data['display']=='0'){$display[0]="checked=\"checked\"";}else{$display[1]="checked=\"checked\"";}
        	while($row_type=mysqli_fetch_array($rs_bg_type)){
				$type_option_select="";
				if($bg_data['type']==$row_type['type_id']){ $type_option_select="selected";}
		?>
        <option value="<?=$row_type['type_id']?>" <?=$type_option_select?>><?=$row_type['type_name']?></option>
        <?
        	}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>標題</td>
    <td colspan="2">
      <input type="text" name="bg_title" id="pd_name" value="<?=$bg_data['title']?>" />    </td>
  </tr>
  <tr>
    <td>內容</td>
    <td width="399"><textarea name="bg_content" id="pd_feature" cols="60" rows="10"><?=stripslashes($bg_data['content'])?></textarea></td>
    <td width="417"><p>段落樣式</p>
      <p>&lt;p&gt;段落一段落一段落一段落一段落一&lt;/p&gt;</p>
      <p>&lt;p&gt;段落二段落二段落二段落二段落二&lt;/p&gt;</p></td>
  </tr>
  <tr>
    <td>圖片</td>
    <td colspan="2">
    	<p>690*200 <input type="file" name="upload_file_1" />pic1<a href="<?=$pic_path[0]?>"><?=$pic_path[0]?></a></p>
      <p>690*200 <input type="file" name="upload_file_2" />pic2<a href="<?=$pic_path[1]?>"><?=$pic_path[1]?></a></p>
      <p>690*200 <input type="file" name="upload_file_3" />pic3<a href="<?=$pic_path[2]?>"><?=$pic_path[2]?></a></p>
      <p>690*200 <input type="file" name="upload_file_4" />pic4<a href="<?=$pic_path[3]?>"><?=$pic_path[3]?></a></p>
      <p>690*200 <input type="file" name="upload_file_5" />pic5<a href="<?=$pic_path[4]?>"><?=$pic_path[4]?></a></p>
    </td>
  </tr>
  <tr>
    <td>顯示</td>
    <td colspan="2"><input name="display" type="radio" id="radio" value="0" <?=$display[0]?>/>
      不顯示 
        <input type="radio" name="display" id="radio2" value="1" <?=$display[1]?>/>
顯示</td>
  </tr>
  <tr>
    <td><input type="hidden" name="action" value="update_bg" /><input type="hidden" name="bg_id" value="<?=$_GET['u_id']?>" /></td>
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
    <td width="839"><form name="form1" method="post" action="controller/blog_controller.php">
      <input type="hidden" name="action" value="del_bg" />
      <select name="bg_id">
      <?
	  	$rs_bg_name=$bg->show_bg_name();
		while($row_bg_name=mysqli_fetch_array($rs_bg_name)){
      ?>
        <option value="<?=$row_bg_name['id']?>" ><?=$row_bg_name['title']?></option>
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
