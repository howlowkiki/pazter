<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>pazter.com</title>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript">
	var userAgent = window.navigator.userAgent.toLowerCase();
	$.browser.msie6 = !$.browser.msie8 && !$.browser.msie7 && $.browser.msie && /msie 6\.0/i.test(userAgent);
	if(!$.browser.msie6){
		location.replace('index.php');
	}
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19657347-1']);
  _gaq.push(['_setDomainName', '.pazter.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body>
<div id="reg_wrap">
    <div class="reg">
    	<ul id="function_list">
        	<li id="li_function_member"><a id="function_member" href="#" class="function_member">會員中心</a></li>
        	<li id="li_function_logout"><a id="function_logout" href="#" rel="prettyPopin"  class="signout">登出</a></li>
        	<li id="li_function_login"><a id="function_login" href="#" class="signin">註冊 / 登入</a></li>
        	<li><a href="#" class="cart_menu">購物車</a></li>

        </ul>

        <div id="cart_panel" class="cart_panel">
            <div id="basketItemsWrap">
                <ul>
                    <li></li>
	            </ul>
            </div>
        </div>
    </div>

</div>

<div id="wrap">
  <div id="header">
    	<div class="logo"> <a href="#"><img src="images/logo.png" alt="home" /></a> </div>
  </div>
</div>
<div id="content_bg">
	<div id="content_wrap">
    	<div id="oops_wrap">
            <div id="ie6_caution">SORRY..我們目前不支援您的瀏覽器<br />
              請使用較新版本的瀏覽器以獲得更好的支援<br />
              點選下列圖示下載這些受歡迎的瀏覽器<br />
			</div>
   	      <div id="ie6_info_logo"><img  src="images/info_icon_big.png" /></div>
            <div id="browsers_wrap">
            	<div class="browser_type">
                	<a href="http://www.google.com/chrome"><img src="images/browser_chrome.gif" border="0" /></a>
                    <a href="http://www.mozilla.com/zh-TW/"><img src="images/browser_firefox.gif" border="0" /></a>
                    <a href="http://www.microsoft.com/windows/Internet-explorer/"><img src="images/browser_ie.gif" border="0"/></a>
                    <a href="http://www.opera.com/download/"><img src="images/browser_opera.gif" border="0" /></a>
                    <a href="http://www.apple.com/safari/download/"><img src="images/browser_safari.gif" border="0" /></a> </div>
            </div>
      </div>
    </div>
</div>

<div id="wrap_footer">
  <div class="footer">

    <div class="footer_cate_wrap">
    	<h3>TAGS 搜尋</h3>
	      <form action="<?=$page_links['tags']?>" method="post" id="footer_tag">
		        <input name="search_text" type="text" class="search_text" />
                <input type="submit" value="GO" class="search_button" />
          </form>
    	<h3>更多顏色</h3>
			<div id="footer_tag_color"><!--	TAG_COLOR_SELECT	-->
                <a href="#" class="color_tag_red" title="RED"></a>
                  <a href="#" class="color_tag_pink" title="PINK"></a>
                <a href="#" class="color_tag_purple" title="PURPLE"></a>
                <a href="#" class="color_tag_blue" title="BLUE"></a>
                <a href="#" class="color_tag_green" title="GREEN"></a>
                <a href="#" class="color_tag_yellow" title="YELLOW"></a>
                <a href="#" class="color_tag_orange" title="ORANGE"></a>
                <a href="#" class="color_tag_brown" title="BROWN"></a>
                <a href="#" class="color_tag_gray" title="GRAY"></a>
                <a href="#" class="color_tag_white" title="WHITE"></a>
                <a href="#" class="color_tag_black" title="BLACK"></a>
            </div><!--	TAG_COLOR_SELECT	-->

    </div>
    <div class="footer_cate_wrap">
    	<h3>所有文章</h3>
        <ul>
      </ul>
    	<h3>文章分類</h3>
        <ul>
      </ul>
    </div>

    <div class="footer_cate_wrap">
    	<h3>關於我們</h3>
        <ul>
        	<li><a href="#">關於我們</a></li>
       	  <li><a href="#">常見問題</a></li>
       	  <li><a href="#">隱私權保護</a></li>
       	  <li><a href="#">聯絡我們</a></li>
      </ul>
    </div>
    <div class="allrights">Powered by pazter.com c 2010 pazter Ltd. All Rights Reserved </div>
  </div>
</div>

<!--<div id="mask"></div>-->
</body>
</html>