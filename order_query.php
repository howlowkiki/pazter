<?
	require_once('controller/common_func.php');
	is_refer_domain($page);
?>
<script type="text/javascript" src="scripts/jquery-latest.js"></script>
<script type="text/javascript" src="scripts/jquery.corner.js"></script>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	function ValidateNumber(e, pnumber){
		if (!/^\d+$/.test(pnumber))
		{
			$(e).val(/^\d+/.exec($(e).val()));
		}
		return false;
	}

	function CheckEmailFormat(account_id,msg_id){
		if(account_id.val()==""){
				msg_id.html('請輸入E-MAIL').fadeIn(300);
				account_id.addClass("text_field_error");
				return false;
		}else{
				if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(account_id.val()))){
					msg_id.html("請輸入正確的E-MAEL格式").fadeIn(300);
					account_id.addClass("text_field_error");
					return false;
				}else{
						msg_id.fadeOut(300);
						account_id.removeClass("text_field_error");
				}
		}
	}

	function CheckTelNum(tel,tel_msg){
		if(tel.val()==""){
			tel_msg.html('請輸入連絡電話').fadeIn(300);
			tel.addClass("text_field_error");
			return false;
		}else{
			tel_msg.fadeOut();
			tel.removeClass("text_field_error");
		}
	}

	function DisableOrderForm(){
			$('#order_query_form :input').attr("disabled","true");
			$('#order_query_form :input:submit').addClass("login_submit_disabled");
			$('#epaper_form :input').attr("disabled","true");
			$('#epaper_form :input:submit').addClass("login_submit_disabled");
	}

	function EnableOrderForm(){
			$('#order_query_form :input').removeAttr("disabled");
			$('#order_query_form :input:submit').removeClass("text_field_error");
			$('#order_query_form :input:submit').removeClass("login_submit_disabled");
			$('#epaper_form :input').removeAttr("disabled");
			$('#epaper_form :input:submit').removeClass("text_field_error");
			$('#epaper_form :input:submit').removeClass("login_submit_disabled");
	}

	$(document).ready(function(){

		var epaper_token="<?=$_SESSION['epaper_token'] = md5(uniqid(rand(), true));?>";
		$('#login_wrap').corner("12px");
		$('#order_query_form').submit(function(){

			if( CheckEmailFormat($('input#order_query_account'),$('span#order_query_account_msg'))==false ){
				return false;
			}
			if( CheckTelNum($('input#user_query_tel'),$('span#user_query_tel_msg')) ==false){
				return false;
			}

			DisableOrderForm();
			$('#query_loading').show();

			setTimeout(function(){
					$.ajax({	//	AJAX
						type: "POST",
						url: "buyer_order.php",
						data: {
							action:"query_buyer_order",
							buyer_id:$('input#order_query_account').val(),
							buyer_tel:$('input#user_query_tel').val()
						},
						success: function(the_response) {

							if(the_response=="10"){
								$('#query_loading').hide();
								location.replace('buyer_order.php');
							}else{
								$('#query_loading').hide();
								$('span#order_query_account_msg').html("查無資料").fadeIn(300);
								EnableOrderForm();
							}

						}

					});//	AJAX

			},1000)
			return false;
		});

		$('#order_epaper_button').click(function(){

			if( CheckEmailFormat($('input#epaper_email'),$('span#epaper_email_msg'))==false ){
				return false;
			}else{
				$('#epaper_loading').show();

				DisableOrderForm();

				setTimeout(function(){
					$.ajax({	//	AJAX
						type: "POST",
						url: "add_to_epaper.php",
						data: {
							act:"add",
							epaper_mail:$('input#epaper_email').val(),
							epaper_token:epaper_token
						},
						success: function(the_response){

							if(the_response=="10"){
								$('#epaper_loading').hide();
								$('span#epaper_email_msg').html('訂閱成功').fadeIn(300);
								setTimeout(function(){
									$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
									$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
								},3000)
							}else{
								$('#epaper_loading').hide();
								$('span#epaper_email_msg').html("請輸入正確的E-MAIL格式").fadeIn(300);
								$('input#epaper_email').focus();
							}
						}

					});//	AJAX
					EnableOrderForm();
				},1000)
			}

		});

		$('#cancel_epaper_button').click(function(){

			if( CheckEmailFormat($('input#epaper_email'),$('span#epaper_email_msg'))==false ){
				return false;
			}else{
				$('#epaper_loading').show();

				DisableOrderForm();

				setTimeout(function(){
					$.ajax({	//	AJAX
						type: "POST",
						url: "add_to_epaper.php",
						data: {
							act:"cancel",
							epaper_mail:$('input#epaper_email').val(),
							epaper_token:epaper_token
						},
						success: function(the_response){

							if(the_response=="10"){
								$('#epaper_loading').hide();
								$('span#epaper_email_msg').html('已取消電子報發送。').fadeIn(300);
								setTimeout(function(){
									$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
									$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
								},3000)
							}else{
								$('#epaper_loading').hide();
								$('span#epaper_email_msg').html("請輸入正確的E-MAIL格式").fadeIn(300);
								$('input#epaper_email').focus();
							}
						}

					});//	AJAX
					EnableOrderForm();
				},1000)
			}
		});


	});

</script>


  <div id="login_wrap"><!--	LOGIN_WRAP	-->

    <div id="login_title">非會員訂單查詢/電子報訂閱</div>
    	<div id="form_status" class="form_status_msg"></div>
  <div id="login_content"><!--	LOGIN_CONTENT	-->
            <div id="login_type_left" >
        		<form id="order_query_form" >
                <p class="login_p">非會員訂單查詢</p>
                <div class="login_dot_line"></div>
                <p>非會員僅提供查詢最近一筆訂單資料</p>
                  <p>E-MAIL： <span id="order_query_account_msg" class="register_help_msg"></span></p>
                    <input type="text" class="login_text_field" id="order_query_account" maxlength="50" />
                    <p>聯絡電話： <span id="user_query_tel_msg" class="register_help_msg"></span></p>
                  <input type="text" class="login_text_field" id="user_query_tel" onkeyup="return ValidateNumber($(this),value)" maxlength="30" />
                  <input type="submit" class="login_submit_img" value="查詢" />
                  <div id="query_loading" class="loader_img"><img src="images/loader.gif" /></div>
		        </form>
            </div>

			<div id="login_type_right" >
        		<form id="epaper_form">
                <p class="login_p">訂閱/取消電子報</p>
                <div class="login_dot_line"></div>
                <p>E-MAIL：<span id="epaper_email_msg" class="register_help_msg"></span></p>
                <input id="epaper_email"type="text" class="login_text_field" maxlength="50" maxlength="50" />
                <input type="button" id="order_epaper_button" name="register_submit" class="login_submit_img" value="訂閱"/>
                <input type="button" id="cancel_epaper_button" name="register_submit" class="login_submit_img" value="取消訂閱" />
                <div id="epaper_loading" class="loader_img"><img src="images/loader.gif" /></div>
              </form>
	        </div>

			<div class="login_bottom_line"></div>
        <div id="login_submit_img"></div>
      </div>
      <!--	LOGIN_CONTENT	-->
  </div><!--	LOGIN_WRAP	-->
<div id="about_page_shadow"></div>