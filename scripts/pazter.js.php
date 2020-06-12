<?php
	session_start();

	require("../controller/banner_controller.php");

	$ban = new banner();
	$rs_banner = $ban->get_banner_data($conn);

?>

//	HEADER FUNCTION
$(document).ready(function(){

/*-------------------------
//	BROWSER VERSION
//-------------------------*/
var userAgent = window.navigator.userAgent.toLowerCase();
$.browser.msie6 = !$.browser.msie8 && !$.browser.msie7 && $.browser.msie && /msie 6\.0/i.test(userAgent);
if($.browser.msie6){
	location.replace('sorry.php');
}
/*-------------------------
//	QUERY LOADER
//-------------------------*/
  $(".pd_img").each(function(){  //for each .img
    var src=$(this).attr('src');  //get the image's URL
    var imgItem=$(this);  //set imgItem as this
    var img = new Image();  //creat the image temp
    $(img).load(function () { //image loaded event
      this.height=140;  //reset the height of image is 240      
      $(imgItem).append(this);  //put the image into the imgItem when the image have been loaded.
    }).attr('src', src);  //set the image's URL, and starting downloading the image
  });

/*-------------------------
//	TAG SEARCH
//-------------------------*/
$('#footer_tag').click(function(){
	if($('#tag').val()==""){
		$('#tag').focus();
		return false;
	}
});

$('#form_tag_search').click(function(){
	if($('#tag').val()==""){
		$('#tag').focus();
		return false;
	}
});

/*-------------------------
//	CUSTOMER REGISTER FORM
//-------------------------*/
	$(function(){
	  $('#zip_container_customer').twzipcode({
	      zipReadonly: false
	    });
	});
/*-------------------------
//	LOGIN STATUS
//-------------------------*/
<?
	if(isset($_SESSION['user_name'])){
	echo
	'$("#li_function_member").show();
	 $("#li_function_logout").show();
	 $("#li_function_login").hide();';
	}
?>
/*-------------------------
//	LOGOUT METHOD
//-------------------------*/
$("#function_login").prettyPopin({});
$("#register_now").prettyPopin({});
$("#function_logout").prettyPopin({});
$(".order_query").prettyPopin({});
$(".add_epaper").prettyPopin({});

$("a.cart_menu").click(function(){
	$(this).toggleClass("menu-open");
		$("#cart_panel").slideDown("fast").toggle();
	});

/*-------------------------
//	SEARCH METHOD
//-------------------------*/
<?
	if($_SESSION['SEARCH']){
?>

	$("#search_content").slideDown(300);
	setTimeout(function(){
		$("#search_title_wrap").show(300);
		},600);

	setTimeout(function(){
		$(".product_item").fadeIn(300);
		$(".product_item_right").fadeIn(300);
		},1600);

<?
	}

	echo $_GET['tag'];
?>

});

// CORNER FOR IE BROSWER
		$('#footer_tag_color,#hot_product a,#tag_color_select').corner("8px");
		$('.my_tag').corner("8px");
		$('.tag_butt').corner("6px");
		$('#about_wrap').corner("10px");
		$('.modify_my_data,#payment_show_detail').corner("10px");

// INDEX PAGE IMAGE SLIDE SHOW
$(function() {
    $('#s4img').after('<div id="nav">').cycle({
        fx:     'fade',
        speed:  600,
        timeout: 4000,
        pager:  '#nav',
        before: function() { if (window.console) console.log(this.src); }
    });
<?
	$seq=0;
	while($banner_data=mysqli_fetch_array($rs_banner)){
		$post_date=date("M d Y",strtotime($banner_data['start_date']));
?>
    	$('#nav div:eq(<?=$seq?>)').append("<?=$banner_data['banner_desc']?>");
    	$('#nav div:eq(<?echo ++$seq?>)').append("<?=$post_date?>");
<?
		$seq++;
	}
?>
});


//	TIPS
$(function() {
	$('#tag_color_select *').tipsy({gravity: 's'});
	$('#footer_tag_color *').tipsy({gravity: 's'});
	$('a#nation_icon').tipsy({gravity: 'w'});
	$('.pd_img').tipsy({gravity: 's'});
});
	
	$('img.tipsy_title').mouseover(function(){
			alert("");
		});

//	ACCORDION	SLIDE MENU
$(function(){
	// 初始化參數
		var _navLi = $("#accordion li"),
		_img = $("#accordion li img"),
		speed = 300,	// 移動速度
		maxWidth = 700,	// 最大寬
		defaultWidth = 192,	// 預設寬
		minWidth = 65
		;  // 最小寬


	_navLi.each(function(i){

			// 先把每一個 li 的位置都放到定位
		// 並把 left 值記錄起來
			var _this = $(this),
			_prev = _this.prev(),
			_left = !_prev.length ? 0 :(_prev.position().left + defaultWidth);

		_this.css("left", _left).data("left", _left);


	}).mouseover(function(){

		// 當滑鼠移到選項時, 把它後面選項都滑動回去
		// 再把自己跟前面的選項都往前滑揩
		var _this = $(this);

		$(this).prevAll().each(function(){

			if(!$(this).index()==0){
//		alert($(this).width());
					$(this).stop().animate({
						left:$(this).index()*minWidth
					},speed);
			}

			$('#accordion img:eq('+$(this).index()+')').stop().animate({
				left:0
			},speed);


		});

		$(this).andSelf().each(function(){

			if(!$(this).index()==0){
					$(this).stop().animate({
						left:$(this).index()*minWidth
					},speed);
			}

			$('#accordion img:eq('+$(this).index()+')').stop().animate({
				left:635
			},speed);


		});

		$(this).nextAll().each(function(){

			if(!$(this).index()==0){
					$(this).stop().animate({
						left:($(this).index()-1)*minWidth+maxWidth
					},speed);
			}
			$('#accordion img:eq('+$(this).index()+')').stop().animate({
				left:0
			},speed);

		});

	});

 _navLi.mouseleave(function(){

		$(this).prevAll().andSelf().nextAll().each(function(){

				$(this).stop().animate({
					left:$(this).index()*192
				},speed);

				$('#accordion img:eq('+$(this).index()+')').stop().animate({
					left:127
				},speed);

			});
		$('#accordion img:eq(0)').stop().animate({
					left:127
		},speed);
 	});



});
