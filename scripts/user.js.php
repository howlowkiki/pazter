<? session_start();
	require('../controller/user_controller.php');	
 ?>
$(document).ready(function(){
	
/*-------------------------
//	USER INFO ZIPCODE DISPLAY
//-------------------------*/
<?
	if(isset($_SESSION['user_email'])){
		$u=new user();
		$row_data=$u->get_user_data();
?>
$(function(){
  $('#user_info_zip_container').twzipcode({
  	zipReadonly: false,
  	zipSel: <?=$row_data['user_zipcode']?>
  	});
});
<?
	}else{
?>
$(function(){
  $('#user_info_zip_container').twzipcode({
  	zipReadonly: false
  	});
});
<?
	}
?>

/*-------------------------
//	MEMBER LOGIN
//-------------------------*/

$('#user_form_status').html('請先登入').fadeIn(600);

/*-------------------------
//	ORDER CANCELED MSG
//-------------------------*/
setTimeout(function(){
	$('#cancel_order_msg').fadeIn(600);		
},500);


/*-------------------------
//	NOT-MENBER CUSTOMER
//-------------------------*/
$('#consumer_form_status').html('請登入會員或利用非會員購買').fadeIn(600);	
$('#forget_id_link').tipsy({gravity: 'w'});
$('#forget_id_tips').tipsy({gravity: 'w'});
$('#forget_pw_link').tipsy({gravity: 'w'});
$('#forget_pw_link').click(function(){
	
});


/*-------------------------
//	BLOG REPLY
//-------------------------*/
	
	$("#blog_reply").submit(function(){
		
		if($('input#reply_name').val()==""){
			$('span#blog_reply_name').html('請輸入姓名').fadeIn(300);		
			$('input#reply_name').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#blog_reply_name').fadeOut();
			$('input#reply_name').removeClass("text_field_error");
		}
		
		if($('input#reply_email').val()==""){
				$('span#blog_reply_email').html('請輸入E-MAIL').fadeIn(300);
				$('input#reply_email').addClass("text_field_error");
				$('input#reply_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#reply_email').val()))){				
					    $('span#blog_reply_email').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#reply_email').addClass("text_field_error");
					    $('input#reply_email').focus();
					    return false;
						}else{
							$('span#blog_reply_email').fadeOut(300);
							$('input#reply_email').removeClass("text_field_error");
						}
			}
		
		if($('textarea#reply_content').val()==""){
			
			$('span#blog_reply_content').html('請輸入內容').fadeIn(300);
			$('textarea#reply_content').addClass("text_field_error").focus();
			return false;
			
		}else{
		
			if($('textarea#reply_content').val().length > 500)	{
				$('span#blog_reply_content').html('請勿超過500字。').fadeIn(300);
				return false;
			}else{
				$('span#blog_reply_content').fadeOut(300);
				$('textarea#reply_content').removeClass("text_field_error");
			}
			
		}
		
		if($('input#check_cha').val()==""){
			$('span#blog_reply_check').html('請輸入驗證碼').fadeIn(300);		
			$('input#check_cha').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#blog_reply_check').fadeOut();
			$('input#check_cha').removeClass("text_field_error");
		}
		
		$('#blog_reply :input').attr("disabled","true");
		$('#blog_reply :input:submit').addClass("login_submit_disabled");
		$('#reply_loading').show();
		
			setTimeout(function(){
								
							$.ajax({	//	AJAX
								type: "POST",  
								url: "controller/blog_controller.php",  
								data: {
									action:"blog_reply",
									reply_name:$('input#reply_name').val(),
									reply_email:$('input#reply_email').val(),
									reply_content:$('textarea#reply_content').val(),
									blog_id:$('input#blog_id').val(),
									check_cha:$('input#check_cha').val()
								},
								success: function(the_response) {
									if(the_response=="-10"){
										$('#blog_reply :input').removeAttr("disabled");
										$('#blog_reply :input').removeClass("text_field_error");
										$('#blog_reply :input:submit').removeClass("login_submit_disabled");
										$('#blog_reply .register_help_msg').hide();					
										$('input#check_cha').addClass("text_field_error");
										$('span#blog_reply_check').html("驗證碼錯誤").fadeIn(300);							
										$('input#check_cha').focus();
										$('#reply_loading').hide();
									}
									if(the_response=="10"){
										location.reload();
									}
									if(the_response=="-20"){
										location.replace('oops.php');
									}																		
								
									
								}
									
							});//	AJAX
									
			},1000);
		return false;
	});
	
	
/*-------------------------
//	PAYMENT PAGE
//-------------------------*/	
	$('#payment_show_detail').click(function(){
				
		if( $('.payment_hidel_block').css("display")== "none"){
			$('#payment_show_detail').html("隱藏詳細清單");
			$('.payment_hidel_block').fadeIn(300);
		}else{
			$('#payment_show_detail').html("顯示詳細清單");
			$('.payment_hidel_block').fadeOut(300);
		}
		
	});
	
	
/*-------------------------
//	REGISTER FORM
//-------------------------*/

//	ON BLUR CHECK
$('input#register_name').blur(function(){
		if($('input#register_name').val()==""){
			$('span#register_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#register_name').addClass("text_field_error");
		}else{
			$('span#register_name_msg').fadeOut();
			$('input#register_name').removeClass("text_field_error");
		}
	});

$('input#register_email').blur(function(){
		if($('input#register_email').val()==""){
				$('span#register_mail_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#register_email').addClass("text_field_error");
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#register_email').val()))){				
					    $('span#register_mail_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#register_email').addClass("text_field_error");
					    return false;
						}else{
							$('span#register_mail_msg').fadeOut(300);
							$('input#register_email').removeClass("text_field_error");
						}
			}
	});
	
$('input#register_pw').blur(function(){
			if($('input#register_pw').val()==""){
				$('span#register_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#register_pw').addClass("text_field_error");
				return false;
			}else{
				$('span#register_pw_msg').fadeOut();
				$('input#register_pw').removeClass("text_field_error");
			}
	});
	
$('input#register_confirm_pw').blur(function(){
			if($('input#register_pw').val()!=$('input#register_confirm_pw').val()){
					$('span#register_confirm_msg').html('密碼不相符').fadeIn(300);
					$('input#register_confirm_pw').addClass("text_field_error");
					return false;
			}else{			
					$('span#register_confirm_msg').fadeOut();
					$('input#register_confirm_pw').removeClass("text_field_error");
			}
	});
	
//	ON SUBMIT CHECK
$("#register_form").submit(function(){
	
	if($('input#register_name').val()==""){		
		$('span#register_name_msg').html('請輸入姓名').fadeIn(300);
		$('input#register_name').addClass("text_field_error");
		return false;
	}
	
	if($('input#register_email').val()==""){
		$('span#register_mail_msg').html('請輸入E-MAIL').fadeIn(300);		
		$('input#register_email').addClass("text_field_error");
		return false;
	}else{
			if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#register_email').val()))){				
			    $('span#register_mail_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);		    
			    $('input#register_email').addClass("text_field_error");
			    return false;
				}
	}
 
	if($('input#register_pw').val()==""){
		$('span#register_pw_msg').html('請輸密碼').fadeIn(300);		
		$('input#register_pw').addClass("text_field_error");
		return false;
	}
	
	if($('input#register_pw').val()!=$('input#register_confirm_pw').val()){
		$('span#register_confirm_msg').html('密碼不相符').fadeIn(300);
		$('input#register_confirm_pw').addClass("text_field_error");
		return false;
	}
	
	if($('#service_provides').attr("checked")==false){		
		$('span#provides_msg').fadeIn(300);		
		return false;
	}


//if(!form_check){return false;}	// IF WRONG INPUT DATA STOP SUBMIT
	
	
$('#login_content :input').attr("disabled","true");
$('#login_content :input:submit').addClass("login_submit_disabled");
$('#register_loading').show();


	setTimeout(function(){
		
					$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"add_new_user",
							register_name:$('input#register_name').val(),
							register_email:$('input#register_email').val(),
							register_pw:$('input#register_pw').val(),
							register_tel:$('input#register_tel').val(),
							register_zipcode:$('input#zip_code').val(),
							register_county:$('select#zip_county').val(),
							register_area:$('select#zip_area').val(),
							register_add:$('input#register_add').val()							
						},
						success: function(the_response) {								
							
							if(the_response=="10"){
								$('#register_loading').hide();
								$('#form_status').html("註冊成功，請至您的信箱收取確認信~").fadeIn(300);
								$('#login_content :input').removeAttr("disabled").val("");;
								$('#service_provides').attr("checked",false);
								
								setTimeout(function(){
									$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
									$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
									location.reload();
								},3000);
								
							}
							
							if(the_response=="-10"){
								$('#login_content :input').removeAttr("disabled");
								$('#login_content :input').removeClass("text_field_error");
								$('#login_content :input:submit').removeClass("login_submit_disabled");
								$('#login_content .register_help_msg').hide();
								$('input#register_pw').attr("value","");
								$('input#register_confirm_pw').val("");
								$('input#register_email').addClass("text_field_error");
								$('span#register_mail_msg').html("這個E-MAIL已經註冊過了").fadeIn(300);								
								$('input#register_email').focus();
								$('#register_loading').hide();
							}
							
							if(the_response=="-70"){
								$('#login_content :input').removeAttr("disabled");
								$('#login_content :input').removeClass("text_field_error");
								$('#login_content :input:submit').removeClass("login_submit_disabled");
								$('#login_content .register_help_msg').hide();
								$('input#register_pw').attr("value","");
								$('input#register_confirm_pw').val("");
								$('#form_status').html("註冊資料只能包含中文及英數字~").fadeIn(300);
								$('#register_loading').hide();
							}							
							
						}
							
					});//	AJAX
							
	},1000);
return false;
}); // end submit event


/*-------------------------
//	LOGININ FORM
//-------------------------*/
$('input#login_email').change(function(){
		if($('input#login_email').val()==""){
				$('span#login_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#login_email').addClass("text_field_error");
				$('input#login_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#login_email').val()))){				
					    $('span#login_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#login_email').addClass("text_field_error");
					    $('input#login_email').focus();
					    return false;
						}else{
							$('span#login_email_msg').fadeOut(300);
							$('input#login_email').removeClass("text_field_error");
						}
			}
	});
	
$('input#login_pw').blur(function(){
			if($('input#login_pw').val()==""){
				$('span#login_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#login_pw').addClass("text_field_error").focus();
				return false;
			}else{
				$('span#login_pw_msg').fadeOut();
				$('input#login_pw').removeClass("text_field_error");
			}
	});
	
$("#login_form").submit(function(){
		
		if($('input#login_email').val()==""){
				$('span#login_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#login_email').addClass("text_field_error").focus();				
				return false;
		}else{
				if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#login_email').val()))){				
				    $('span#login_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
				    $('input#login_email').addClass("text_field_error").focus();				    
				    return false;
					}else{
						$('span#login_email_msg').fadeOut(300);
						$('input#login_email').removeClass("text_field_error");
					}
		}
		
		if($('input#login_pw').val()==""){
			$('span#login_pw_msg').html('請輸密碼').fadeIn(300);		
			$('input#login_pw').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#login_pw_msg').fadeOut();
			$('input#login_pw').removeClass("text_field_error");
		}
		
		$('#login_content :input').attr("disabled","true");
		$('#login_content :input:submit').addClass("login_submit_disabled");
		$('#login_loading').show();
		
		setTimeout(function(){
			
			$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"user_login",							
							login_email:$('input#login_email').val(),
							login_pw:$('input#login_pw').val()						
						},
						success: function(the_response) {								
							
							if(the_response=="20"){
								$('#register_loading').hide();
								$('#form_status').html("登入成功~").fadeIn(300);
								$('#login_content :input').removeAttr("disabled").val("");;
								$('#service_provides').attr("checked",false);
								$('#login_loading').hide();
								$('#li_function_member').show();
								$('#li_function_logout').show();
								$('#li_function_login').hide();
								
								
								setTimeout(function(){
									$('#overlay').fadeOut('fast',function(){ $(this).remove(); });
									$('.prettyPopin').fadeOut('fast',function(){ $(this).remove();});
									location.reload();
								},1000);
								
							}
							
							if(the_response=="-20"){
								$('#login_content :input').removeAttr("disabled");
								$('#login_content :input').removeClass("text_field_error");
								$('#login_content :input:submit').removeClass("login_submit_disabled");
								$('#login_content .register_help_msg').hide();
								$('input#register_pw').attr("value","");
								$('input#login_pw').val("");
								$('input#login_pw').addClass("text_field_error");
								$('span#login_pw_msg').html("帳號或密碼錯誤。").fadeIn(300);							
								$('input#login_pw').focus();
								$('#login_loading').hide();
							}							
							
						}
							
				});//	AJAX
								
		},1000);
		
		return false;
	});
	
/*-------------------------
//	UPDATE FORM
//-------------------------*/
	$('input#user_info_email').change(function(){
		if($('input#user_info_email').val()==""){
				$('span#modify_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#user_info_email').addClass("text_field_error");
				$('input#user_info_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#user_info_email').val()))){				
					    $('span#modify_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#user_info_email').addClass("text_field_error");
					    $('input#user_info_email').focus();
					    return false;
						}else{
							$('span#modify_email_msg').fadeOut(300);
							$('input#user_info_email').removeClass("text_field_error");
						}
			}
	});
	
	
	$('input#user_info_pw').change(function(){
			if($('input#user_info_pw').val()==""){
				$('span#modify_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#user_info_pw').addClass("text_field_error").focus();
				return false;
			}else{
				if($('input#user_info_pw').val() != $('input#user_info_pw_confirm').val()){
					$('span#modify_repw_msg').html('密碼不相符').fadeIn(300);	
					return false;
				}else{
					$('span#modify_pw_msg').fadeOut();
					$('span#modify_repw_msg').fadeOut();
					$('input#user_info_pw').removeClass("text_field_error");
					$('input#user_info_pw_confirm').removeClass("text_field_error");
				}					
			}
	});
	
	$('input#user_info_pw_confirm').change(function(){
			if($('input#user_info_pw_confirm').val()==""){
				$('span#modify_repw_msg').html('請輸密碼').fadeIn(300);		
				$('input#user_info_pw_confirm').addClass("text_field_error").focus();
				return false;
			}else{
				if($('input#user_info_pw').val() != $('input#user_info_pw_confirm').val()){
					$('span#modify_repw_msg').html('密碼不相符').fadeIn(300);	
					return false;
				}else{
					$('span#modify_pw_msg').fadeOut();
					$('span#modify_repw_msg').fadeOut();
					$('input#user_info_pw').removeClass("text_field_error");
					$('input#user_info_pw_confirm').removeClass("text_field_error");
				}
					
			}
	});

	$('input#user_info_name').change(function(){
		if($('input#user_info_name').val()==""){
			$('span#modify_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#user_info_name').addClass("text_field_error");
			return false;
		}else{
			$('span#modify_name_msg').fadeOut();
			$('input#user_info_name').removeClass("text_field_error");
		}
	});
	
	$('input#user_info_tel').change(function(){
		if($('input#user_info_tel').val()==""){
			$('span#modify_tel_msg').html('請輸入聯絡電話').fadeIn(300);
			$('input#user_info_tel').addClass("text_field_error");
			return false;
		}else{
			$('span#modify_tel_msg').fadeOut();
			$('input#user_info_tel').removeClass("text_field_error");
		}
	});
	
	
	$("#form_user_info").submit(function(){
		
		if($('input#user_info_email').val()==""){
				$('span#modify_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#user_info_email').addClass("text_field_error");
				$('input#user_info_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#user_info_email').val()))){
					    $('span#modify_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#user_info_email').addClass("text_field_error");
					    $('input#user_info_email').focus();
					    return false;
						}else{
							$('span#modify_email_msg').fadeOut(300);
							$('input#user_info_email').removeClass("text_field_error");
						}
		}
		
		if($('input#user_info_pw').val()==""){
				$('span#modify_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#user_info_pw').addClass("text_field_error").focus();
				return false;
		}else{
				if($('input#user_info_pw').val() != $('input#user_info_pw_confirm').val()){
					$('span#modify_repw_msg').html('密碼不相符').fadeIn(300);	
					return false;
				}else{
					$('span#modify_pw_msg').fadeOut();
					$('span#modify_repw_msg').fadeOut();
					$('input#user_info_pw').removeClass("text_field_error");
					$('input#user_info_pw_confirm').removeClass("text_field_error");
				}					
		}
		
			if($('input#user_info_pw_confirm').val()==""){
				$('span#modify_repw_msg').html('請輸密碼').fadeIn(300);		
				$('input#user_info_pw_confirm').addClass("text_field_error").focus();
				return false;
			}else{
				if($('input#user_info_pw').val() != $('input#user_info_pw_confirm').val()){
					$('span#modify_repw_msg').html('密碼不相符').fadeIn(300);	
					return false;
				}else{				
					$('span#modify_pw_msg').fadeOut();
					$('span#modify_repw_msg').fadeOut();
					$('input#user_info_pw').removeClass("text_field_error");
					$('input#user_info_pw_confirm').removeClass("text_field_error");
				}					
			}
			
		if($('input#user_info_name').val()==""){
			$('span#modify_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#user_info_name').addClass("text_field_error");
			return false;
		}else{
			$('span#modify_name_msg').fadeOut();
			$('input#user_info_name').removeClass("text_field_error");
		}

		if($('input#user_info_tel').val()==""){
			$('span#modify_tel_msg').html('請輸入聯絡電話').fadeIn(300);
			$('input#user_info_tel').addClass("text_field_error");
			return false;
		}else{
			$('span#modify_tel_msg').fadeOut();
			$('input#user_info_tel').removeClass("text_field_error");
		}		
		
		$('#form_user_info :input').attr("disabled","true");
		$('#form_user_info :input:submit').addClass("login_submit_disabled");
		$('#modify_userdata_loading').show();
		
		setTimeout(function(){
		
			$.ajax({	//	AJAX
				
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"user_update",							
							update_email:$('input#user_info_email').val(),
							update_pw:$('input#user_info_pw').val(),
							update_confirm_pw:$('input#user_info_pw_confirm').val(),
							update_name:$('input#user_info_name').val(),
							update_tel:$('input#user_info_tel').val(),
							update_mobile:$('input#user_info_mobile').val(),
							update_zipcode:$('input#zip_code').val(),
							update_area:$('select#zip_area').val(),
							update_county:$('select#zip_county').val(),
							update_add:$('input#user_info_add').val()							
						},
						success: function(the_response){			
							
							if(the_response=="40"){								
								setTimeout(function(){
									$('#form_update_status').html("資料修改成功~").fadeIn(300);
									//location.reload();
									$('#form_user_info :input').removeAttr("disabled")
									$('#form_user_info :input:submit').removeClass("login_submit_disabled");
									$('#modify_userdata_loading').hide();
								},1000);
								
							}
							
							if(the_response=="50"){
								
								$('#form_update_status').html("密碼已更改，請重新登入~").fadeIn(300);
																								
								setTimeout(function(){
									$("#function_logout").click().prettyPopin({});
								},2000);
								
							}
							
							if(the_response=="-10"){
								$('#form_update_status').html("這個EMAIL帳號已經有人使用了~").fadeIn(300);
								$('#form_user_info :input').removeAttr("disabled")
								$('#form_user_info :input:submit').removeClass("login_submit_disabled");
								$('#modify_userdata_loading').hide();								
								$('input#user_info_email').focus();
								$('input#user_info_email').addClass("text_field_error");
							}							

							if(the_response=="-40"){
								$('#form_update_status').html("資料格式不符~").fadeIn(300);
								$('#form_user_info :input').removeAttr("disabled")
								$('#form_user_info :input:submit').removeClass("login_submit_disabled");
								$('#modify_userdata_loading').hide();								
								$('input#user_info_email').focus();
								$('input#user_info_email').addClass("text_field_error");
							}									
						
						}
							
				});//	AJAX
								
		},1000);
		
		return false;
	});
	
/*-------------------------
//	非會員購物
//-------------------------*/
	
//	ON BLUR CHECK
$('input#not_member_name').blur(function(){
		if($('input#not_member_name').val()==""){
			$('span#not_member_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#not_member_name').addClass("text_field_error");
			return false;
		}else{
			$('span#not_member_name_msg').fadeOut();
			$('input#not_member_name').removeClass("text_field_error");
		}
	});

$('input#not_member_email').blur(function(){
		if($('input#not_member_email').val()==""){
				$('span#not_member_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#not_member_email').addClass("text_field_error");
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#not_member_email').val()))){				
					    $('span#not_member_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#not_member_email').addClass("text_field_error");
					    return false;
						}else{
							$('span#not_member_email_msg').fadeOut(300);
							$('input#not_member_email').removeClass("text_field_error");
						}
			}
	});

$('input#not_member_tel').blur(function(){
			if($('input#not_member_tel').val()==""){
				$('span#not_member_tel_msg').html('請輸入聯絡電話').fadeIn(300);		
				$('input#not_member_tel').addClass("text_field_error");
				return false;
			}else{
				$('span#not_member_tel_msg').fadeOut();
				$('input#not_member_tel').removeClass("text_field_error");
			}
	});			

$('input#zip_code').blur(function(){
			if($('input#zip_code').val()==""){
				$('span#not_member_add_msg').html('請輸入郵遞區號').fadeIn(300);		
				$('input#zip_code').addClass("text_field_error");
				return false;
			}else{
				$('span#not_member_add_msg').fadeOut();
				$('input#zip_code').removeClass("text_field_error");
			}
	});
	
$('input#not_member_add').blur(function(){
			if($('input#not_member_add').val()==""){
					$('span#not_member_add_msg').html('請輸入地址').fadeIn(300);
					$('input#not_member_add').addClass("text_field_error");
					return false;
			}else{			
					$('span#not_member_add_msg').fadeOut();
					$('input#not_member_add').removeClass("text_field_error");
			}
	});
	
//	ON SUBMIT CHECK
$("#not_member_form").submit(function(){	
		
		if($('input#not_member_name').val()==""){
			$('span#not_member_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#not_member_name').addClass("text_field_error");
			return false;
		}


		if($('input#not_member_email').val()==""){
				$('span#not_member_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#not_member_email').addClass("text_field_error");
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#not_member_email').val()))){				
					    $('span#not_member_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#not_member_email').addClass("text_field_error");
					    return false;
					}
			}

			if($('input#not_member_tel').val()==""){
				$('span#not_member_tel_msg').html('請輸入聯絡電話').fadeIn(300);		
				$('input#not_member_tel').addClass("text_field_error");
				return false;
			}


			if($('input#zip_code').val()==""){
				$('span#not_member_add_msg').html('請輸入郵遞區號').fadeIn(300);		
				$('input#zip_code').addClass("text_field_error");
				return false;
			}

			if($('input#not_member_add').val()==""){
					$('span#not_member_add_msg').html('請輸入地址').fadeIn(300);
					$('input#not_member_add').addClass("text_field_error");
					return false;
			}
			
			if($('#userreg_provides').attr("checked")==false){		
				$('span#userreg_provides_msg').fadeIn(300);		
				return false;
			}

	$('#user_login_content :input:submit').addClass("login_submit_disabled");
	$('#userreg_loading').show();
	
}); // end submit event

/*-------------------------
//	會員中心	REGISTER FORM
//-------------------------*/
	
//	ON BLUR CHECK
$('input#userreg_name').blur(function(){
		if($('input#userreg_name').val()==""){
			$('span#userreg_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#userreg_name').addClass("text_field_error");
			return false;
		}else{
			$('span#userreg_name_msg').fadeOut();
			$('input#userreg_name').removeClass("text_field_error");
		}
	});

$('input#userreg_email').blur(function(){
		if($('input#userreg_email').val()==""){
				$('span#userreg_mail_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#userreg_email').addClass("text_field_error");
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#userreg_email').val()))){				
					    $('span#userreg_mail_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#userreg_email').addClass("text_field_error");
					    return false;
						}else{
							$('span#userreg_mail_msg').fadeOut(300);
							$('input#userreg_email').removeClass("text_field_error");
						}
			}
	});
	
$('input#userreg_pw').blur(function(){
			if($('input#userreg_pw').val()==""){
				$('span#userreg_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#userreg_pw').addClass("text_field_error");
				return false;
			}else{
				$('span#userreg_pw_msg').fadeOut();
				$('input#userreg_pw').removeClass("text_field_error");
			}
	});
	
$('input#userreg_confirm_pw').blur(function(){
			if($('input#userreg_confirm_pw').val()!=$('input#userreg_pw').val()){
					$('span#userreg_confirm_msg').html('密碼不相符').fadeIn(300);
					$('input#userreg_confirm_pw').addClass("text_field_error");
					return false;
			}else{			
					$('span#userreg_confirm_msg').fadeOut();
					$('input#userreg_confirm_pw').removeClass("text_field_error");
			}
	});
	
//	ON SUBMIT CHECK
$("#user_register_form").submit(function(){
	
		if($('input#userreg_name').val()==""){
			$('span#userreg_name_msg').html('請輸入姓名').fadeIn(300);
			$('input#userreg_name').addClass("text_field_error");
			return false;
		}
	
		if($('input#userreg_email').val()==""){
				$('span#userreg_mail_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#userreg_email').addClass("text_field_error");
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#userreg_email').val()))){				
					    $('span#userreg_mail_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#userreg_email').addClass("text_field_error");
					    return false;
						}
		}
 
		if($('input#userreg_pw').val()==""){
				$('span#userreg_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#userreg_pw').addClass("text_field_error");
				return false;
		}
	
		if($('input#userreg_confirm_pw').val()!=$('input#userreg_pw').val()){
					$('span#userreg_confirm_msg').html('密碼不相符').fadeIn(300);
					$('input#userreg_confirm_pw').addClass("text_field_error");
					return false;
		}
	
	if($('#userreg_provides').attr("checked")==false){		
		$('span#userreg_provides_msg').fadeIn(300);		
		return false;
	}


//if(!form_check){return false;}	// IF WRONG INPUT DATA STOP SUBMIT
	
	
$('#user_login_content :input').attr("disabled","true");
$('#user_login_content :input:submit').addClass("login_submit_disabled");
$('#userreg_loading').show();

	setTimeout(function(){
		
					$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"add_new_user",
							register_name:$('input#userreg_name').val(),
							register_email:$('input#userreg_email').val(),
							register_pw:$('input#userreg_pw').val(),
							register_tel:$('input#userreg_tel').val(),
							register_zipcode:$('input#zip_code').val(),
							register_county:$('select#zip_county').val(),
							register_area:$('select#zip_area').val(),
							register_add:$('input#userreg_add').val()							
						},
						success: function(the_response) {
							
							if(the_response=="10"){
								$('#userreg_loading').hide();
								$('#user_form_status').html("註冊成功，請至您的信箱收取確認信~").fadeIn(300);
								$('#user_login_content :input').removeAttr("disabled").val("");
								
								setTimeout(function(){
									location.reload();
								},2000);
								
							}
							
							if(the_response=="-10"){
								$('#user_login_content :input').removeAttr("disabled");
								$('#user_login_content :input').removeClass("text_field_error");
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								
								$('#user_login_content .register_help_msg').hide();
								$('input#userreg_pw').attr("value","");
								$('input#userreg_confirm_pw').val("");
								$('input#userreg_email').addClass("text_field_error");
								$('span#userreg_mail_msg').html("這個E-MAIL已經註冊過了").fadeIn(300);							
								$('input#userreg_email').focus();
								$('#userreg_loading').hide();
							}
							
							if(the_response=="-70"){
								$('#user_login_content :input').removeAttr("disabled");
								$('#user_login_content :input').removeClass("text_field_error");
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");								
								$('#user_login_content .register_help_msg').hide();
								$('input#userreg_pw').attr("value","");
								$('input#userreg_confirm_pw').val("");
								$('#user_form_status').html("註冊資料只能包含中文及英數字~").fadeIn(300);
								$('#userreg_loading').hide();
								
							}
							
						}
							
					});//	AJAX
							
	},1000);
	return false;
}); // end submit event


/*-------------------------
//	會員中心LOGININ FORM
//-------------------------*/
$('input#user_email').change(function(){
		if($('input#user_email').val()==""){
				$('span#user_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#user_email').addClass("text_field_error");
				$('input#user_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#user_email').val()))){				
					    $('span#user_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#user_email').addClass("text_field_error");
					    $('input#user_email').focus();
					    return false;
						}else{
							$('span#user_email_msg').fadeOut(300);
							$('input#user_email').removeClass("text_field_error");
						}
			}
	});
	
$('input#user_pw').blur(function(){
			if($('input#user_pw').val()==""){
				$('span#user_pw_msg').html('請輸密碼').fadeIn(300);		
				$('input#user_pw').addClass("text_field_error").focus();
				return false;
			}else{
				$('span#user_pw_msg').fadeOut();
				$('input#user_pw').removeClass("text_field_error");
			}
	});
	
$("#user_login_form").submit(function(){
		
		if($('input#user_email').val()==""){
				$('span#user_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#user_email').addClass("text_field_error").focus();				
				return false;
		}else{
				if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#user_email').val()))){				
				    $('span#user_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
				    $('input#user_email').addClass("text_field_error").focus();				    
				    return false;
					}else{
						$('span#user_email_msg').fadeOut(300);
						$('input#user_email').removeClass("text_field_error");
					}
		}
		
		if($('input#user_pw').val()==""){
			$('span#user_pw_msg').html('請輸密碼').fadeIn(300);		
			$('input#user_pw').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#user_pw_msg').fadeOut();
			$('input#user_pw').removeClass("text_field_error");
		}
		
		$('#user_login_content :input').attr("disabled","true");
		$('#user_login_content :input:submit').addClass("login_submit_disabled");
		$('#user_loading').show();
		
		
		
		
		setTimeout(function(){
			
			$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"user_login",							
							login_email:$('input#user_email').val(),
							login_pw:$('input#user_pw').val()
						},
						success: function(the_response) {								
							
							if(the_response=="20"){
								$('#user_loading').hide();
								$('#user_form_status').html("登入成功~").fadeIn(300);
								$('#user_login_content :input').removeAttr("disabled").val("");;
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								$('#service_provides').attr("checked",false);
								$('#login_loading').hide();
								$('#li_function_member').show();
								$('#li_function_logout').show();
								$('#li_function_login').hide();
								
								setTimeout(function(){									
									location.reload();									
								},1200);
										
							}
							
							if(the_response=="-20"){
								
								$('#user_login_content :input').removeAttr("disabled");
								$('#user_login_content :input').removeClass("text_field_error");
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								$('#user_login_content .register_help_msg').hide();
								$('input#user_pw').attr("value","");								
								$('input#user_pw').addClass("text_field_error");
								$('span#user_pw_msg').html("帳號或密碼錯誤。").fadeIn(300);							
								$('input#user_pw').focus();
								$('#user_loading').hide();
							}							
							
						}
							
				});//	AJAX
								
		},1000);
		
		return false;
	});
	
/*-------------------------
//	會員中心RESET PW FORM
//-------------------------*/
$('input#reset_pw_email').change(function(){
		if($('input#reset_pw_email').val()==""){
				$('span#reset_pw_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#reset_pw_email').addClass("text_field_error");
				$('input#reset_pw_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#reset_pw_email').val()))){				
					    $('span#reset_pw_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#reset_pw_email').addClass("text_field_error");
					    $('input#reset_pw_email').focus();
					    return false;
						}else{
							$('span#reset_pw_msg').fadeOut(300);
							$('input#reset_pw_email').removeClass("text_field_error");
						}
			}
	});

	
$("#reset_pw_form").submit(function(){
		
		if($('input#reset_pw_email').val()==""){
				$('span#reset_pw_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#reset_pw_email').addClass("text_field_error");
				$('input#reset_pw_email').focus();
				return false;
			}else{
					if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#reset_pw_email').val()))){				
					    $('span#reset_pw_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
					    $('input#reset_pw_email').addClass("text_field_error");
					    $('input#reset_pw_email').focus();
					    return false;
					}						
			}
		
		
		$('#user_login_content :input').attr("disabled","true");
		$('#user_login_content :input:submit').addClass("login_submit_disabled");
		$('#reset_pw_loader').show();
		
		setTimeout(function(){
			
			$.ajax({	//	AJAX
						type: "POST",  
						url: "controller/user_controller.php",  
						data: {
							action:"reset_password",							
							reset_pw_email:$('input#reset_pw_email').val()
						},
						success: function(the_response) {								
							
							if(the_response=="50"){
								$('#reset_pw_loader').hide();
								$('#user_form_status').html("請至信箱收取密碼後重新登入").fadeIn(300);
								$('#user_login_content :input').removeAttr("disabled").val("");;
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								$('#reset_pw_submit').removeClass("login_submit_disabled").val("送出");
								$('#service_provides').attr("checked",false);				
								
								setTimeout(function(){									
									location.reload();									
								},2000);
										
							}
							
							if(the_response=="-30"){
								$('#reset_pw_loader').hide();								
								$('#user_login_content :input').removeAttr("disabled").val("");;
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								$('#reset_pw_submit').removeClass("login_submit_disabled").val("送出");
								$('#service_provides').attr("checked",false);
								$('span#reset_pw_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
								$('input#reset_pw_email').focus();
							}
							
							if(the_response=="-10"){
								$('#reset_pw_loader').hide();								
								$('#user_login_content :input').removeAttr("disabled").val("");;
								$('#userreg_button').removeClass("login_submit_disabled").val("註冊");
								$('#userlogin_button').removeClass("login_submit_disabled").val("登入");
								$('#reset_pw_submit').removeClass("login_submit_disabled").val("送出");
								$('#service_provides').attr("checked",false);
								$('span#reset_pw_msg').html("我們找不到這個信箱。").fadeIn(300);
								$('input#reset_pw_email').focus();
							}
							
						}
							
				});//	AJAX
								
		},1000);
		
		return false;
	});
	
	
/*-------------------------
//	CONTACT US
//-------------------------*/
	
	$("#form_contact").submit(function(){
		
		if($('input#contact_name').val()==""){
			$('span#contact_name_msg').html('請輸入姓名').fadeIn(300);		
			$('input#contact_name').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#contact_name_msg').fadeOut();
			$('input#contact_name').removeClass("text_field_error");
		}
		
		if($('input#contact_email').val()==""){
				$('span#contact_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#contact_email').addClass("text_field_error");
				$('input#contact_email').focus();
				return false;
		}else{
			if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#contact_email').val()))){				
			    $('span#contact_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
			    $('input#contact_email').addClass("text_field_error");
			    $('input#contact_email').focus();
			    return false;
				}else{
					$('span#contact_email_msg').fadeOut(300);
					$('input#contact_email_msg').removeClass("text_field_error");
					$('input#contact_email').removeClass("text_field_error");
				}
		}
		
		if($('textarea#contact_opinion').val()==""){
			
			$('span#contact_opinion_msg').html('請輸入內容').fadeIn(300);
			$('textarea#contact_opinion').addClass("text_field_error").focus();
			return false;
			
		}else{
		
			if($('textarea#contact_opinion').val().length > 500)	{
				$('span#contact_opinion_msg').html('請勿超過500字。').fadeIn(300);
				return false;
			}else{
				$('span#contact_opinion_msg').fadeOut(300);
				$('textarea#contact_opinion').removeClass("text_field_error");
			}
			
		}
		
		if($('input#check_cha').val()==""){
			$('span#contact_chr_msg').html('請輸入驗證碼').fadeIn(300);		
			$('input#check_cha').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#contact_chr_msg').fadeOut();
			$('input#check_cha').removeClass("text_field_error");
		}
		
		$('#form_contact :input').attr("disabled","true");
		$('#form_contact :input:submit').addClass("login_submit_disabled");
		$('#contact_loading').show();
		
			setTimeout(function(){
								
							$.ajax({	//	AJAX
								type: "POST",  
								url: "controller/user_controller.php",  
								data: {
									action:"contact_us",
									contact_name:$('input#contact_name').val(),
									contact_email:$('input#contact_email').val(),
									contact_opinion:$('textarea#contact_opinion').val(),
									contact_tel:$('input#contact_tel').val(),
									contact_odno:$('input#contact_odno').val(),
									check_cha:$('input#check_cha').val()
								},
								success: function(the_response) {
																		
									if(the_response=="-10"){
										$('#form_contact :input').removeAttr("disabled");
										$('#form_contact :input').removeClass("text_field_error");
										$('#form_contact :input:submit').removeClass("login_submit_disabled");
										$('#form_contact .register_help_msg').hide();					
										$('input#check_cha').addClass("text_field_error");
										$('span#contact_chr_msg').html("驗證碼錯誤").fadeIn(300);							
										$('input#check_cha').focus();
										$('#contact_loading').hide();
									}
									
									if(the_response=="-20"){
										location.replace('oops.php');
									}
									
									if(the_response=="10"){
										$('#form_contact :input').val("");
										$('#form_contact :input').removeAttr("disabled");
										$('#form_contact :input').removeClass("text_field_error");
										$('#form_contact :input:submit').removeClass("login_submit_disabled");
										$('#form_contact .register_help_msg').hide();
										$('#contact_us_status').html('訊息已送出').fadeIn(300);
										$('#contact_loading').hide();
										
										setTimeout(function(){
											$('#contact_us_status').fadeOut(300);
											location.reload();
										},3000);
									}
								}
									
							});//	AJAX
									
			},1000);
		return false;
	});
	
/*-------------------------
//	合作提案
//-------------------------*/
	
	$("#form_corp").submit(function(){
		
		if($('input#contact_name').val()==""){
			$('span#contact_name_msg').html('請輸入姓名').fadeIn(300);		
			$('input#contact_name').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#contact_name_msg').fadeOut();
			$('input#contact_name').removeClass("text_field_error");
		}
		
		if($('input#contact_email').val()==""){
				$('span#contact_email_msg').html('請輸入E-MAIL').fadeIn(300);
				$('input#contact_email').addClass("text_field_error");
				$('input#contact_email').focus();
				return false;
		}else{
			if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('input#contact_email').val()))){				
			    $('span#contact_email_msg').html("請輸入正確的E-MAEL格式").fadeIn(300);
			    $('input#contact_email').addClass("text_field_error");
			    $('input#contact_email').focus();
			    return false;
				}else{
					$('span#contact_email_msg').fadeOut(300);
					$('input#contact_email_msg').removeClass("text_field_error");
					$('input#contact_email').removeClass("text_field_error");
				}
		}
		
		if($('textarea#contact_opinion').val()==""){
			
			$('span#contact_opinion_msg').html('請輸入內容').fadeIn(300);
			$('textarea#contact_opinion').addClass("text_field_error").focus();
			return false;
			
		}else{
		
			if($('textarea#contact_opinion').val().length > 500)	{
				$('span#contact_opinion_msg').html('請勿超過500字。').fadeIn(300);
				return false;
			}else{
				$('span#contact_opinion_msg').fadeOut(300);
				$('textarea#contact_opinion').removeClass("text_field_error");
			}
			
		}
		
		if($('input#check_cha').val()==""){
			$('span#contact_chr_msg').html('請輸入驗證碼').fadeIn(300);		
			$('input#check_cha').addClass("text_field_error").focus();
			return false;
		}else{
			$('span#contact_chr_msg').fadeOut();
			$('input#check_cha').removeClass("text_field_error");
		}
		
		$('#form_corp :input').attr("disabled","true");
		$('#form_corp :input:submit').addClass("login_submit_disabled");
		$('#contact_loading').show();
		
			setTimeout(function(){
								
							$.ajax({	//	AJAX
								type: "POST",  
								url: "controller/user_controller.php",  
								data: {
									action:"corp_mail",
									contact_name:$('input#contact_name').val(),
									contact_email:$('input#contact_email').val(),
									contact_opinion:$('textarea#contact_opinion').val(),
									contact_tel:$('input#contact_tel').val(),
									contact_web:$('input#contact_web').val(),
									check_cha:$('input#check_cha').val()
								},
								success: function(the_response) {
																		
									if(the_response=="-10"){
										$('#form_corp :input').removeAttr("disabled");
										$('#form_corp :input').removeClass("text_field_error");
										$('#form_corp :input:submit').removeClass("login_submit_disabled");
										$('#form_corp .register_help_msg').hide();					
										$('input#check_cha').addClass("text_field_error");
										$('span#contact_chr_msg').html("驗證碼錯誤").fadeIn(300);							
										$('input#check_cha').focus();
										$('#contact_loading').hide();
									}
									
									if(the_response=="-20"){
										location.replace('oops.php');
									}
									
									if(the_response=="10"){
										$('#form_corp :input').val("");
										$('#form_corp :input').removeAttr("disabled");
										$('#form_corp :input').removeClass("text_field_error");
										$('#form_corp :input:submit').removeClass("login_submit_disabled");
										$('#form_corp .register_help_msg').hide();
										$('#contact_us_status').html('訊息已送出').fadeIn(300);
										$('#contact_loading').hide();
										
										setTimeout(function(){
											$('#contact_us_status').fadeOut(300);
											location.reload();
										},3000);
									}
								}
									
							});//	AJAX
									
			},1000);
		return false;
	});
	
});

	//電話輸入限制
	function ValidateNumber(e, pnumber){
		if (!/^\d+$/.test(pnumber))
		{
			$(e).val(/^\d+/.exec($(e).val()));
		}
		return false;
	}