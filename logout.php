<?
	require_once('controller/common_func.php');
	is_refer_domain($page);
?>
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript">
	$("#logout_loading").show();

	setTimeout(function(){
					$.ajax({	//	AJAX
						type: "POST",
						url: "controller/user_controller.php",
						data: {
							action:"user_logout"
						},
						success: function(the_response) {

							if(the_response=="30"){

								$('#logout_loading').hide();
								$('#logout_loading img').attr("src","images/info_icon_mini.png");
								$('#logout_msg').html("已登出...").fadeIn(100);
								$('#logout_loading').fadeIn(100);
								$('#li_function_member').hide();
								$('#li_function_logout').hide();
								$('#li_function_login').show();

								setTimeout(function(){
									$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
									$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
									location.reload();
								},1000);

							}
						}

					});//	AJAX
	},1000);
</script>

<link href="css/pazter.css" rel="stylesheet" type="text/css" />
  <div id="login_wrap"><!--	LOGIN_WRAP	-->
        <div id="logout_loading" ><img src="images/loader.gif" /></div>
    <div id="logout_msg">登出中...</div>

   	<div id="form_status"></div>
        <!--	LOGIN_CONTENT	-->
  </div>
  <!--	LOGIN_WRAP	-->
<div id="about_page_shadow"></div>
