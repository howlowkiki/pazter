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
      <div id="form_update_status"></div>
      <form name="form_user_info" id="form_user_info">
      <h3>帳號與密碼</h3>
      <div class="user_info_left">
      	<p>E-MAIL：<span id="modify_email_msg" class="register_help_msg"></span></p>
   	    <input type="text" id="user_info_email" class="user_text_field" maxlength="40" value="<?=$user_data['user_email']?>" disabled="disabled"/>
      </div>
      <div class="user_info_right">
      	<p>密碼：<span id="modify_pw_msg" class="register_help_msg"></span></p>
      	  <input type="password" id="user_info_pw" class="user_text_field" maxlength="20" value="<?=$member->user_pw_sample?>"/>
      	<p>再次輸入密碼：(*)<span id="modify_repw_msg" class="register_help_msg"></span></p>
      	  <input type="password" id="user_info_pw_confirm" class="user_text_field" maxlength="20" value="<?=$member->user_pw_sample?>"/>
      </div>
      <h3>個人資料</h3>
      <div class="user_info_left">
      	<p>姓名：(*)<span id="modify_name_msg" class="register_help_msg"></span></p>
   	    <input type="text" id="user_info_name" class="user_text_field" maxlength="10" value="<?=$user_data['user_name']?>"/>
      	<p>電話：(*)<span id="modify_tel_msg" class="register_help_msg"></span></p>
   	    <input type="text" id="user_info_tel" class="user_text_field" maxlength="20" value="<?=$user_data['user_tel']?>" onkeyup="return ValidateNumber($(this),value)" />
      	<p>手機：<span id="modify_mobile_msg" class="register_help_msg"></span></p>
   	    <input type="text" id="user_info_mobile" class="user_text_field" maxlength="10" value="<?=$user_data['user_mobile']?>" onkeyup="return ValidateNumber($(this),value)" />
      </div>
      <div class="user_info_right">
      	<p>地址：<span id="modify_zipcode_msg" class="register_help_msg"></span></p>
       	<div id="user_info_zip_container"></div>
      	  <input type="text" id="user_info_add" class="user_text_field" maxlength="200" value="<?=$user_data['user_add']?>"/>
      </div>
	  <div class="user_info_dotline"></div>
    	<input type="submit" value="儲存" class="user_info_save_bt" />
        <div id="modify_userdata_loading" ><img src="images/loader.gif" /></div>
        </form>
    </div><!--	USER_WRAP	-->

  </div>
</div>

