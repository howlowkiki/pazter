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
   			location.replace("index.php?");
   		},3300);
	});
</script>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<div id="content_bg">
    <div id="content_wrap">
    	<div id="oops_wrap">
<?
		if($GLOBALS['USER_CONFIRM']){
	    	echo "<div id=\"caution_text_2\">認證完成！</div>";
		}else{
	    	echo "<div id=\"caution_text_2\">已完成認證或無法認證。</div>";
		}
?>
        	<img src="images/info_icon.png" id="caution_logo_2" />

      </div>
  </div>
</div>
