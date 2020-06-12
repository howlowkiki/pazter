<?
	session_start();	
	require('controller/order_controller.php');
	is_referer_pazter();
	is_admin();
	$od	=	new order();
	$rs_od_year	=	$od->get_order_year();
	
	//取得年份
	if($_GET['y']!=""){
		$_GET['y']=mysql_real_escape_string($_GET['y']);
		$rs_od_month=$od->get_order_month($_GET['y']);
	}
	
	$show_data=false;
	if($_GET['m']!='' && $_GET['y']!=''){		
		$_GET['y']=mysql_real_escape_string(trim($_GET['y']));
		$_GET['m']=mysql_real_escape_string(trim($_GET['m']));
		$rs_by_month=$od->get_order_by_month($_GET['y'],$_GET['m']);
		if($rs_by_month) $show_data=true;
	}
	
	//更改訂單狀態
	if(	$_GET['od']!="" &&	$_GET['status']!=""){
		$_GET['od']=mysql_real_escape_string($_GET['od']);
		$_GET['status']=mysql_real_escape_string($_GET['status']);
		
		if(	$od->update_order_status($_GET['od'],$_GET['status'])	){
			$page="od.php?y=".$_GET['y']."&m=".$_GET['m'];
			page_togo($page);
		}else{
			page_alert("無法修改。發生錯誤!");
		}
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
span.modify{
	margin:0 4px;
	font-size: 0.8em;
}

-->
</style>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<body>
<div class="functions_wrap">
  <ul id="function">
    	<li><a href="pd.php" >產品</a></li>
   	<li><a href="bg.php" >Blog</a></li>
    	<li><a href="od.php" class="active">訂單</a></li>
    	<li><a href="member.php">會員</a></li>
			<li><a href="mailer.php">EMAIL</a></li>
			<li><a href="banner.php">BANNER</a></li>
    	<li class="logout"><a href="controller/cute_controller.php?action=admin_logout">登出</a></li>
  </ul>
<!--  
  <ul id="sub_function">
    	<li><a href="od.php?action=new" class="<?=$sub_type_css[0]?>">新增</a></li>
   	<li><a href="od.php?action=update" class="<?=$sub_type_css[1]?>">修改</a></li>
   	<li><a href="od.php?action=del" class="<?=$sub_type_css[2]?>">刪除 </a></li>
  </ul>
-->
</div>

<div class="functions_wrap">
<table width="710" border="1" cellpadding="6" class="pd_table">
  <tr>
  <form action="od.php?y=<?=$_GET['y']?>&m=<?=$_GET['m']?>" method="POST" enctype="multipart/form-data" name="new_pd_form">
    <td width="89"><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
        <option value="">年份</option>
<?
	while($row=mysqli_fetch_array($rs_od_year)	){
		if($_GET['y']==$row['y']){
?>        
        <option <?='selected="selected"'?> value="od.php?y=<?=$row['y']?>"><?=$row['y']?></option>        
<?
		}else{
?>
        <option value="od.php?y=<?=$row['y']?>"><?=$row['y']?></option>        
<?		
		}
	}
?>
			</select></td>
    <td width="105">
			<select name="jumpMenu3" id="jumpMenu3" onchange="MM_jumpMenu('parent',this,0)">
			      <option>月份</option>
			<?
				while($row=mysqli_fetch_array($rs_od_month)){
					if($row['m']==$_GET['m']){
			?>
			      <option selected="selected" value="od.php?y=<?=$_GET['y']?>&m=<?=$row['m']?>"><?=$row['m']?></option>
			<?
					}else{
			?>      
			 			<option value="od.php?y=<?=$_GET['y']?>&m=<?=$row['m']?>"><?=$row['m']?></option>
			<?	
					}
				}
			?>      
			</select>    </td>
    <td colspan="7"><label>
      <input type="submit" name="button" id="button" value="送出" />
      <input type="hidden" name="y" value="<?=$_GET['y']?>" />
      <input type="hidden" name="m" value="<?=$_GET['m']?>" />
    </label></td>
  </form>
  </tr>

  <tr>
    <td>訂單編號</td>
    <td>ID</td>
    <td width="81">金額</td>
    <td width="53">付款單</td>
    <td width="126">付款方式</td>
    <td width="95">付款狀態</td>
    <td width="85">台灣里<br />
      交易編號</td>
    <td width="64">訂單狀態</td>
    <td width="134">修改訂單</td>
  </tr>

<?
	$order_status=array("1"=>"新訂單","2"=>"已付款","3"=>"已確認","4"=>"已出貨","5"=>"已取消");
	$payment_type=array("1"=>"信用卡","2"=>"虛擬帳號轉帳","4"=>"WEB-ATM","9"=>"7-11 ibon","12"=>"全家 FamiPort");
	$payment_status=array("1"=>"已完成付款","2"=>"付款失敗","3"=>"尚未付款");
	
	while( $rs_by_month and $row=mysqli_fetch_array($rs_by_month)){
		$order_id=$row['order_date'].$row['order_seq'];
//		$total_price=$od->get_total_price($order_id);
		
		if(	$row['pay_status']=='3'	||	$row['order_status']=='1'	){
			$payment_date	=	"<font color='red'>".$row['pay_deadline']."</font>";
		}
		
		if($row['pay_status']=='1'){			
			$pay_timestamp	=	strtotime($row['pay_date']);
			$payment_date		=	date("Y-m-d",$pay_timestamp);
		}
		
		if($row['pay_status']=='3'){
			$bill_url="<a href=\"".$row['bill_url']."\" target=\"_blank\">付款單</a>";
		}else{
			$bill_url="&nbsp;";
		}
		
		if($row['order_status']=='4'){
			$order_status[$row['order_status']]="<font color='#999999'>".$order_status[$row['order_status']]."</font>";
		}
		if($row['order_status']=='2'){
			$order_status[$row['order_status']]="<font color='#FF0000'>".$order_status[$row['order_status']]."</font>";
		}
		
?>  
  <tr>
    <td><a href="od_detail.php?od=<?=$order_id?>" target="_blank"><?=$order_id?></a></td>
    <td><?=$row['user_id']?></td>
    <td><?=$row['pay_price']?><? if($row['memo']!="") echo "<br/>".$row['memo'] ?></td>
    <td><?=$bill_url?></td>
    <td><?=$payment_type[$row['pay_type']]?></td>
    <td><?=$payment_status[$row['pay_status']]?><br/><?=$payment_date?></td>
    <td><?=$row['tid']?></td>
    <td><?=$order_status[$row['order_status']]?></td>
    <td><?
		if($row['order_status']>='2'){
?>    
    	<span class="modify"><a href="od.php?y=<?=$_GET['y']?>&m=<?=$_GET['m']?>&od=<?=$order_id?>&status=3" onClick="return(confirm('確定金額？'))">確認金額</a></span>
    	<span class="modify"><a href="od.php?y=<?=$_GET['y']?>&m=<?=$_GET['m']?>&od=<?=$order_id?>&status=4" onClick="return(confirm('確定出貨？'))">確認出貨</a></span>
<?
	}else{
		echo " ";
	}
?>    </td>

  </tr>
<?
	}
?>

</table>

</div>

	
</body>
</html>
