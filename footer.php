<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="wrap_footer">
  <div class="footer">

    <div class="footer_cate_wrap">
    	<h3>壁貼標籤搜尋</h3>
	      <form action="<?=$page_links['tag']?>" method="get" id="footer_tag">
		        <input name="tag" value="粉紅 , love" type="text" class="search_text" id="tag" onFocus="if (value =='粉紅 , love'){value =''}" />
                <input type="submit" value="GO" class="search_button" />
          </form>
    	<h3>更多顏色</h3>
			<div id="footer_tag_color"><!--	TAG_COLOR_SELECT	-->
	      <a href="<?=$page_links['tag']?>?tag=red,紅" class="color_tag_red" title="RED"></a>
	      <a href="<?=$page_links['tag']?>?tag=pink,粉紅" class="color_tag_pink" title="PINK"></a>
	      <a href="<?=$page_links['tag']?>?tag=purple,紫" class="color_tag_purple" title="PURPLE"></a>
	      <a href="<?=$page_links['tag']?>?tag=blue,藍"  class="color_tag_blue" title="BLUE"></a>
	      <a href="<?=$page_links['tag']?>?tag=green,綠" class="color_tag_green" title="GREEN"></a>
	      <a href="<?=$page_links['tag']?>?tag=yellow,黃" class="color_tag_yellow" title="YELLOW"></a>
	      <a href="<?=$page_links['tag']?>?tag=orange,橘" class="color_tag_orange" title="ORANGE"></a>
	      <a href="<?=$page_links['tag']?>?tag=brown,咖啡,棕" class="color_tag_brown" title="BROWN"></a>
	      <a href="<?=$page_links['tag']?>?tag=gray,灰" class="color_tag_gray" title="GRAY"></a>
	      <a href="<?=$page_links['tag']?>?tag=white,白" class="color_tag_white" title="WHITE"></a>
	      <a href="<?=$page_links['tag']?>?tag=black,黑" class="color_tag_black" title="BLACK"></a>
       </div><!--	TAG_COLOR_SELECT	-->

    </div>
    <div class="footer_cate_wrap">
        <?=get_member_center_list()?>
    </div>

    <div class="footer_cate_wrap">
    	<h3>關於我們</h3>
    	<ul><?=get_about_list()?></ul>
    </div>
    <div class="allrights">pazter.com Copyright © 2010 All Rights Reserved </div>
  </div>
</div>

<!--<div id="mask"></div>-->
</body>
</html>