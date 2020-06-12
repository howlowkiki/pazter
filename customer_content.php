<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('select#zip_county').attr("disabled","true");
		$('select#zip_area').attr("disabled","true");
		$('input#zip_code').attr("disabled","true");
	});
</script>
<div id="content_bg">
  <div id="content_wrap">
    <div id="user_menu">
      <div id="user_option">
      	<?=get_member_center_list()?>
      </div>
    </div>
    <div id="user_wrap">
      <ul id="order_step_status">
      	<li><a href="#"><span class="selected">1</span>購物清單</a></li>
      	<li><a href="#"><span class="selected">2</span>資料確認</a></li>
      	<li><a href="#"><span>3</span>付款方式</a></li>
      	<li><a href="#"><span>4</span>訂單完成</a></li>
      </ul>

	  <div id="order_step_content"><!--	ORDER_STEP_CONTENT	-->

		<div class="buyer_data"><!--	BUYER_DATA	-->
          <h3>帳號</h3>
          <div class="buyer_data_left">
            <p>E-MAIL：</p>
            <input type="text" id="user_info_email" class="user_text_field" maxlength="40" value="<?=$user_data['user_email']?>" disabled="disabled"/>
            <p>&nbsp;</p>
          </div>
          <h3>個人資料</h3>
          <div class="buyer_data_left">
            <p>姓名：</p>
            <input type="text" id="user_info_name" class="user_text_field" maxlength="10" value="<?=$user_data['user_name']?>" disabled="disabled"/>
            <p>電話：<span id="modify_tel_msg" class="register_help_msg"></span></p>
            <input type="text" id="user_info_tel" class="user_text_field" maxlength="20" value="<?=$user_data['user_tel']?>" disabled="disabled"/>
            <p>手機：<span id="modify_mobile_msg" class="register_help_msg"></span></p>
            <input type="text" id="user_info_mobile" class="user_text_field" maxlength="11" value="<?=$user_data['user_mobile']?>" disabled="disabled"/>
          </div>
          <div class="buyer_data_right">
            <p>地址：<span id="modify_zipcode_msg" class="register_help_msg"></span></p>
		       	<div id="user_info_zip_container"></div>
            <input type="text" id="user_info_add" class="user_text_field" maxlength="200" value="<?=$user_data['user_add']?>" disabled="disabled"/>
          </div>
          <div id="modify_my_data_wrap">
          <a href="<?=$page_links['user_info']?>"><div class="modify_my_data">更改我的資料</div></a></div>
          <div class="user_info_dotline"></div>
          </div><!--	BUYER_DATA	-->

      </div>

	  <!--	ORDER_STEP_CONTENT	-->
		<a id="order_next_step" class="<?=$next_step_css?>" href="<?=$next_step_link?>">下一步</a>
		<a class="order_last_step" href="cart.php">取消</a>
    </div>
		<div class="pay_method_icons"></div>
  </div>
</div>

