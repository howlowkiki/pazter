<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<script type="text/javascript">
	$(document).ready(function() {
   		$('#caution_logo_2').hide();
   		$('#caution_text_2').hide();

   		setTimeout(function(){
	   		$('#caution_logo_2').fadeIn(300);
	   		$('#caution_text_2').fadeIn(300);
   		},800);

   		setTimeout(function(){
   			$('#caution_logo_2').hide().attr('src','images/loader.gif').fadeIn(300);
   			$('#caution_text_2').hide().html('loading...').fadeIn(300);
   		},2200);

   		setTimeout(function(){
   			location.replace("payment.php?od=<?=$_POST['txid']?>");
   		},6000);
	});
</script>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<div id="content_bg">
    <div id="content_wrap">
    	<div id="oops_wrap">
<?
	if($GLOBALS["PAY_STATUS"]){
		if($GLOBALS["PAY_OFFLINE"]){
	    	echo "<div id=\"caution_text_2\">訂購成功！</div>";
		}else{
	    	echo "<div id=\"caution_text_2\">付款成功！</div>";
		}

	}else{

        echo "<div id=\"caution_text_2\">付款失敗！</div>";

	}
?>
        	<img src="images/info_icon.png" id="caution_logo_2" />

      </div>
  </div>
</div>
