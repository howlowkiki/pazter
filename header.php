<?
	require_once('controller/common_func.php');
	is_full_page();
	if($page_title==""){$page_title="壁貼商品/壁貼DIY/藝術壁貼/創意壁貼 -";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="壁貼,貼紙,家飾,佈置,壁貼DIY,藝術壁貼,創意壁貼,韓國壁貼,日本壁貼,wallsticker,sticker,paster">
<meta name="Description" content="<?=$meta_desc?>">
<link rel="shortcut icon" href="favicon.ico" />
<meta property="fb:admins" content="1693155997"/>
<meta property="fb:app_id" content="205407766137194">
<title><?=$page_title ?>pazter.com</title>
<link href="css/pazter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery-latest.js"></script>
<script type="text/javascript" src="scripts/jquery.cycle.all.2.72.js"></script>
<script type="text/javascript" src="scripts/jquery.corner.js"></script>
<script type="text/javascript" src="scripts/jquery.tipsy.js"></script>
<script type="text/javascript" src="scripts/jquery.prettyPopin.js"></script>
<script type="text/javascript" src="scripts/jquery.lazyload.mini.js"></script>
<script type="text/javascript" src="scripts/cart_view.js"></script>
<script type="text/javascript" src="scripts/order.js"></script>
<script type="text/javascript" src="scripts/capreload.js"></script>
<script type="text/javascript" src="scripts/twzipcode-1.3.js"></script>
<script type="text/javascript" src="scripts/user.js.php"></script>
<script type="text/javascript" src="scripts/pazter.js.php"></script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19657347-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<?
	if(isset($_SESSION['register_for_yahoo'])){
?>
<SCRIPT language="JavaScript" type="text/javascript">
<!-- Yahoo! Taiwan Inc.
window.ysm_customData = new Object();
window.ysm_customData.conversion = "transId=,currency=,amount=";
var ysm_accountid  = "15CCR6B078LJITA2CFMUTD8JC4O";
document.write("<SCR" + "IPT language='JavaScript' type='text/javascript' "
+ "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid
+ "></SCR" + "IPT>");
// -->
</SCRIPT>
<?
		unset($_SESSION['register_for_yahoo']);
	}
?>

</head>
<body>
<div id="reg_wrap_bg"></div>
<div id="reg_wrap">
    <div class="reg">
    	<ul id="function_list">
      	<li id="li_function_member"><a id="function_member" href="user.php" class="function_member">會員中心</a></li>
        <li id="li_function_logout"><a id="function_logout" href="logout.php" rel="prettyPopin"  class="signout">登出</a></li>
        <li id="li_function_login"><a id="function_login" href="login_form.php" rel="prettyPopin" class="signin">註冊 / 登入</a></li>
        <li><a href="javascript:;" class="cart_menu">購物車</a></li>
      </ul>
        <div id="cart_panel" class="cart_panel">
            <div id="basketItemsWrap">
                <ul>
                    <li></li>
                  <?

                  	$user_cart=new cart($conn);
                    $user_cart->get_basket($conn); ?>
	            </ul>
            </div>
        </div>
    </div>
</div>

<div id="wrap">
  <div id="header">
    	<div class="logo"><a href="index.php"><img src="images/logo.png" alt="home" /></a> </div>
       <div id="fb_like_box">
			<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpaztercom&amp;width=260&amp;colorscheme=dark&amp;show_faces=false&amp;stream=false&amp;header=false&amp;height=50" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:260px; height:56px;" allowTransparency="true"></iframe>
			</div>
       <ul id="topnav">
           <li><a title="首頁" alt="首頁" href="<?=$page_links['index']?>">首頁</a></li>
           <li><a title="產品" alt="產品" href="<?=$page_links['category']?>">產品</a></li>
           <li><a title="網誌" alt="網誌" href="<?=$page_links['blog']?>">網誌</a></li>
           <li><a title="標籤" alt="標籤" href="<?=$page_links['tag']?>">標籤</a></li>
           <li><a title="關於我們" alt="關於我們" href="<?=$page_links['about']?>">關於我們</a></li>
           <li><a title="連絡我們" alt="連絡我們" href="<?=$page_links['contact']?>">聯絡我們</a></li>
       </ul>
  </div>
</div>
