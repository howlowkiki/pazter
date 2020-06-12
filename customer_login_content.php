<?
	require_once('controller/common_func.php');
	is_full_page();
	$_SESSION['token'] = md5(uniqid(rand(), true));
?>
<div id="content_bg">
  <div id="content_wrap">
    <div id="user_menu">
      <div id="user_option">
	      <?=get_member_center_list()?>
      </div>
    </div>

    <div id="user_wrap"><!--	USER_WRAP	-->
      <ul id="order_step_status">
      	<li><a href="#"><span class="selected">1</span>購物清單</a></li>
      	<li><a href="#"><span class="selected">2</span>資料確認</a></li>
      	<li><a href="#"><span>3</span>付款方式</a></li>
      	<li><a href="#"><span>4</span>訂單完成</a></li>
      </ul>
	  <div id="order_step_content"><!--	USER_STEP_CONTENT	-->
            <div id="user_login_content" class="customer_login_content"><!--	LOGIN_CONTENT	-->
		   	<div id="consumer_form_status" class="user_form_status_msg"></div>

                <div id="login_type_left" >
                    <form id="user_login_form">
                    <p class="login_p">已經註冊</p>
                    <div class="login_dot_line"></div>
                    <p>請使用您的E-MAIL登入</p>
                      <p>E-MAIL：(*) <span id="user_email_msg" class="register_help_msg"></span></p>
                        <input name="" id="user_email" type="text" class="login_text_field" />
                        <p>密碼：(*) <span id="user_pw_msg" class="register_help_msg"></span></p>
                      <input name="" id="user_pw" type="password" class="login_text_field" />
                      <input type="submit" id="userlogin_button" class="login_submit_img" value="登入" />
                      <div id="user_loading" class="loader_img"><img src="images/loader.gif" /></div>
                    </form>
                      <p class="help_link"><a href="javascript:;">忘記密碼?</a></p>
                      <p class="help_link"><a id="forget_id_link" title="試著您常用的E-MAIL帳號登入" href="javascript:;">忘記帳號?</a></p>
                </div>

                <div id="login_type_right" >
                  <form id="not_member_form" name="not_member_form" action="buyer_payment.php" method="post">
                    <p class="login_p">非會員購買</p>
                    <div class="login_dot_line"></div>
                    <p>收件人姓名：<span id="not_member_name_msg" class="register_help_msg"></span></p>
                    <input id="not_member_name" type="text" class="login_text_field" maxlength="20" name="buyer_name" />
                    <p>聯絡電話：<span id="not_member_tel_msg" class="register_help_msg"></span></p>
                    <input id="not_member_tel" type="text" class="login_text_field" maxlength="20" name="buyer_tel" onkeyup="return ValidateNumber($(this),value)"/>
                    <p>聯絡E-MAIL：<span id="not_member_email_msg" class="register_help_msg"></span></p>
                    <input id="not_member_email" type="text" class="login_text_field" maxlength="50" name="buyer_email" />
                    <p>寄送地址：<span id="not_member_add_msg" class="register_help_msg"></span></p>
                    <div id="zip_container_customer"></div>
                    <input name="buyer_add" id="not_member_add" type="text" class="login_text_field" maxlength="80" />
                    <input name="userreg_provides" id="userreg_provides" type="checkbox" />
                    <input type="hidden" id="token" name="token" value="<?=$_SESSION['token']?>" />
                    <span>請勾選同意我們的<a href="help.php?#P1" target="_blank">服務條款</a></span><span id="userreg_provides_msg" class="register_help_msg"></span>
                    <input type="submit" id="userreg_button" class="login_submit_img" value="下一步" />
                    <div id="userreg_loading" class="loader_img"><img src="images/loader.gif" /></div>
                  </form>
                </div>
          </div><!--	LOGIN_CONTENT	-->
      </div><!--	USER_STEP_CONTENT	-->
    </div><!--	USER_STEP_CONTENT	-->
    <div class="pay_method_icons"></div>
  </div>
</div>

