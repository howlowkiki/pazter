<?
	require('controller/cute_controller.php');
	require_once('controller/common.php');
	if($_POST['textfield']!="" && $_POST['textfield2']!=""){
		
	if( strlen($_POST['textfield'])>8 || str_len($_POST['textfield2'])>8 || $_POST['code']>5){
		session_destroy();
		page_togo('../index.php');
	}
		
		$admin = new admin();
		$id=mysql_real_escape_string(trim($_POST['textfield']));
		$pw=mysql_real_escape_string(trim($_POST['textfield2']));
		$verify=mysql_real_escape_string(trim($_POST['code']));
		$admin->admin_login($id,md5($pw),$verify);	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cute</title>
<style type="text/css">
<!--
body {
	background-color: #666666;
}
.textfield {
	background-color: #FFFFFF;
	color: #000000;
	border: 0;
	margin-top: 20px;
}
-->

</style>
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#id').focus();
});
</script>

</head>

<body>
<form id="form1" name="form1" method="post" action="" >
  <label>
  <div align="center">id:<input name="action" type="hidden" value="cute" />
    <input id="id" type="text" name="textfield" class="textfield"/>
  </div>
  </label>
  <label>
  <div align="center">
    pw:<input type="password" name="textfield2" class="textfield" />
  </div>
  </label>
  <label>
  <div align="center"><br /><br /><br />

  	<img src="securimage/securimage_show.php?sid=<?php echo md5(uniqid(time()));?>"><br />
    verify:<input type="text" name="code" class="textfield" />
  </div>
  </label>
  <label>
  <div align="center">
    <input class="textfield" type="submit" name="button" id="button" value="submit" />
  </div>
  </label>
</form>
</body>
</html>
