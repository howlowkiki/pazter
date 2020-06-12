<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
	<div id="content_wrap">

    <div id="user_menu">
	    <div id="user_option">
            <?=get_member_center_list()?>
        </div>
    </div>

    <div id="user_wrap"><!--	USER_WRAP	-->
   	  <h3>登入 / 註冊</h3>
	  <div id="login_step_content"><!--	USER_STEP_CONTENT	-->
            <div id="user_login_content" class="customer_login_content"><!--	LOGIN_CONTENT	-->
		   	<div id="user_form_status" class="user_form_status_msg"></div>
                <div id="login_type_left" >
                    <form id="user_login_form">
                    <p class="login_p">已經註冊</p>
                    <div class="login_dot_line"></div>
                    <p>請使用您的E-MAIL登入</p>
                      <p>E-MAIL：(*) <span id="user_email_msg" class="register_help_msg"></span></p>
                        <input name="" id="user_email" type="text" class="login_text_field" />
                        <p>密碼：(*) <span id="user_pw_msg" class="register_help_msg"></span></p>
                      <input name="" id="user_pw" type="password" class="login_text_field" />
                      <input type="submit" id="userlogin_button" class="login_submit_img_2" value="登入" />
                      <div id="user_loading" class="loader_img"><img src="images/loader.gif" /></div>
                    </form>

                    <p class="login_p">忘記密碼?</p>
                     <form id="reset_pw_form">
                      <p>忘記密碼?請輸入註冊E-MAIL </p>
                      <p><span id="reset_pw_msg" class="register_help_msg"></span></p>
                       <input id="reset_pw_email" type="text" class="login_text_field" />
                        <input type="submit" id="reset_pw_submit" class="login_submit_img_2" value="送出" />
                       <div id="reset_pw_loader" class="loader_img"><img src="images/loader.gif" /></div>
                     </form>
                    	<p class="help_link"><a id="forget_id_link" title="試著您常用的E-MAIL帳號登入" href="javascript:;">忘記帳號?</a></p>
                </div>

                <div id="login_type_right" >
                    <form id="user_register_form" >
                    <p class="login_p">尚未註冊</p>
                    <div class="login_dot_line"></div>
                    <p>加入會員可以享有更多的折扣</p>
                    <p>姓名：(*) <span id="userreg_name_msg" class="register_help_msg"></span></p>
                    <input id="userreg_name" type="text" class="login_text_field" maxlength="20" />
                    <p>E-MAIL：(*) <span id="userreg_mail_msg" class="register_help_msg"></span></p>
                    <input id="userreg_email"type="text" class="login_text_field" maxlength="50" />
                    <p>密碼：(*)<span id="userreg_pw_msg" class="register_help_msg"></span></p>
                    <input id="userreg_pw" name="" type="password" class="login_text_field" maxlength="14" />
                    <p>再輸入一次密碼：(*) <span id="userreg_confirm_msg" class="register_help_msg"></span></p>
                    <input name="" id="userreg_confirm_pw" type="password" class="login_text_field" maxlength="14" />
                    <p>聯絡電話：</p>
                    <input name="userreg_tel" id="userreg_tel" type="text" class="login_text_field" maxlength="20" onkeyup="return ValidateNumber($(this),value)" />
                    <p>地址：</p>
                    <div id="zip_container_customer"></div>
                    <input name="" id="userreg_add" type="text" class="login_text_field" maxlength="80" />
                    <input name="userreg_provides" id="userreg_provides" type="checkbox" />
                    <span>請勾選同意我們的<a href="help.php#P1" target="_blank">服務條款</a></span><span id="userreg_provides_msg" class="register_help_msg""></span>
                    <input type="submit" id="userreg_button" class="login_submit_img_2" value="註冊" />
                    <div id="userreg_loading" class="loader_img"><img src="images/loader.gif" /></div>
                  </form>
                </div>
                <div class="login_bottom_line"></div>

          </div><!--	LOGIN_CONTENT	-->
      </div><!--	USER_STEP_CONTENT	-->
    </div><!--	USER_WRAP	-->

	</div>
</div>

