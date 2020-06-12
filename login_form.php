<?
	require_once('controller/common_func.php');
//	is_refer_domain($page);
?>
<script type="text/javascript" src="scripts/jquery-latest.js"></script>
<script type="text/javascript" src="scripts/jquery.corner.js"></script>
<script type="text/javascript" src="scripts/jquery.tipsy.js"></script>
<script type="text/javascript" src="scripts/twzipcode-1.3.js"></script>
<script type="text/javascript" src="scripts/user.js.php"></script>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	$('#login_wrap').corner("12px");

	$('#forget_pw').click(function(){
		$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
		$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
	});
	
	function ValidateNumber(e, pnumber){
		if (!/^\d+$/.test(pnumber))
		{
			$(e).val(/^\d+/.exec($(e).val()));
		}
		return false;
	}

</script>

  <div id="login_wrap"><!--	LOGIN_WRAP	-->
<!--  	<a href="#" id="b_close" rel="close"></a>-->
    <div id="login_title">登入 / 註冊</div>
    	<div id="form_status" class="form_status_msg"></div>
  <div id="login_content"><!--	LOGIN_CONTENT	-->
        <div id="login_memo">(*)為必填欄位</div>
            <div id="login_type_left" >
        		<form id="login_form" >
                <p class="login_p">已經註冊</p>
                <div class="login_dot_line"></div>
                <p>請使用您的E-MAIL登入</p>
                  <p>E-MAIL：(*) <span id="login_email_msg" class="register_help_msg"></span></p>
                    <input name="" id="login_email" type="text" class="login_text_field" />
                    <p>密碼：(*) <span id="login_pw_msg" class="register_help_msg"></span></p>
                  <input name="" id="login_pw" type="password" class="login_text_field" />
                  <input type="submit" name="login_submit" class="login_submit_img" value="登入" />
                  <div id="login_loading" class="loader_img"><img src="images/loader.gif" /></div>
		        </form>
									<p class="help_link"><a id="forget_id_tips" title="試著您常用的E-MAIL帳號登入" href="javascript:;">忘記帳號?</a></p>
                  <p class="help_link"><a id="forget_pw" href="user_login.php?action=reset_pw&#user_email">忘記密碼?</a></p>

            </div>

			<div id="login_type_right" >
        		<form id="register_form">
                <p class="login_p">尚未註冊</p>
                <div class="login_dot_line"></div>
                <p>加入會員可以享有更多的折扣</p>
                <p>姓名：(*) <span id="register_name_msg" class="register_help_msg"></span></p>
                <input id="register_name" type="text" class="login_text_field" maxlength="20" />
                <p>E-MAIL：(*) <span id="register_mail_msg" class="register_help_msg"></span></p>
                <input id="register_email"type="text" class="login_text_field" maxlength="50" />
                <p>密碼：(*) 4-16位英數字<span id="register_pw_msg" class="register_help_msg"></span> </p>
                <input id="register_pw" name="" type="password" class="login_text_field" maxlength="14" />
                <p>再輸入一次密碼：(*) <span id="register_confirm_msg" class="register_help_msg"></span></p>
                <input name="" id="register_confirm_pw" type="password" class="login_text_field" maxlength="14" />
                <p>聯絡電話：</p>
                <input name="" id="register_tel" type="text" class="login_text_field" maxlength="20" onkeyup="return ValidateNumber($(this),value)"/>
                <p>地址：</p>
                <div id="zip_container"></div>
                <input name="" id="register_add" type="text" class="login_text_field" maxlength="80" />
                <input name="" id="service_provides" type="checkbox" /><span>請勾選同意我們的<a href="help.php#P1" target="_blank">服務條款</a></span><span id="provides_msg" class="register_help_msg""></span>
                <input type="submit" id="register_button" name="register_submit" class="login_submit_img" value="註冊" />
                <div id="register_loading" class="loader_img"><img src="images/loader.gif" /></div>
              </form>
	        </div>
			<div class="login_bottom_line"></div>
        <div id="login_submit_img"></div>
      </div>
      <!--	LOGIN_CONTENT	-->
  </div><!--	LOGIN_WRAP	-->
<div id="about_page_shadow"></div>