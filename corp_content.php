<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
    <div id="content_wrap">
    <div id="etc_item">
    	<ul><?=get_about_list()?></ul>
    </div>

  <div id="about_wrap_corp"><!--	ABOUT_WRAP	-->
   <form id="form_corp">
    <div id="about_us_title"><img src="images/title_corp.png" /></div>
      <div id="contact_us_status" class="user_form_status_msg"></div>
      <div id="about_content"><!--	ABOUT_CONTENT	-->
      	<p style="color:#d93d86">若您有任何合作提案，歡迎與我們聯絡！</p>
      	<div id="contact_sub_title">我們非常歡迎商品合作、網站活動、廣告提案及相關行銷活動等任何形式合作，<br/>請提供合作提案內容及公司相關資訊，我們會儘快與您聯繫。</div>
        <div id="contact_memo">(*)為必填欄位</div>

		<div id="contact_type_left" >
       	  <p>聯絡人：(*)<span id="contact_name_msg" class="register_help_msg"></span></p>
            <input name="contact_name" type="text" class="contact_text_field" id="contact_name" maxlength="20" value="<?=$_SESSION['user_name']?>" />
        	<p>聯絡電話：</p>
          <input name="contact_tel" type="text" class="contact_text_field" id="contact_tel" maxlength="20" />
        </div>
		<div id="contact_type_right" >
        	<p>E-MAIL：(*)<span id="contact_email_msg" class="register_help_msg"></span></p>
          <input name="contact_email" type="text" class="contact_text_field" id="contact_email" maxlength="40" value="<?=$_SESSION['user_email']?>"/>
		<p>網站：</p>
          <input name="contact_web" type="text" class="contact_text_field" id="contact_web" maxlength="50" value="http://" />
        </div>
        <div id="contact_p">提案內容：(*)<span id="contact_opinion_msg" class="register_help_msg"></span></div>
        <textarea id="contact_opinion" name="contact_opinion" class="contact_text_area"></textarea>
            <div id="contact_p">驗證碼：(大小寫不分)<span id="contact_chr_msg" class="register_help_msg"></span></div>
            <div class="captcha">
                <img src="captcha.php" id="imgCaptcha" class="captcha_img" />
              <input id="check_cha" name="check_cha" type="text" class="contact_text_check" maxlength="4" />
			</div>
            <div id="contact_dot_line"></div>
            <input name="blog_reply_submit" type="submit" class="login_submit_img" value="送出" />
            <div id="contact_loading" class="loader_img"><img src="images/loader.gif" /></div>

      </div>
      </form>
      <!--	ABOUT_CONTENT	-->
  </div><!--	ABOUT_WRAP	-->
      <div id="about_page_shadow"></div>
  </div>
</div>
