<?
	require_once('controller/common_func.php');
	is_full_page();
?>
<div id="content_bg">
	<div id="content_wrap">

    <div id="blog_menu">
    	<h3>最新文章</h3>
    	<ul>
<?
	while($row_title=mysqli_fetch_array($rs_arti_title)){
			$row_title['title']=stripslashes($row_title['title']);
			if(mb_strlen($row_title['title'],"utf8")>13){
				$title=mb_substr($row_title['title'],0,12,"utf8")."...";
			}else{
				$title=$row_title['title'];
			}
?>
      	<li><a href="article.php?id=<?=$row_title['id']?>" ><?=$title?></a></li>
<?
	}
?>
      </ul>
    	<h3>文章分類</h3>
    	<ul>
<?
	while($row_type=mysqli_fetch_array($rs_type)){
			if(mb_strlen($row_type['type_name'])>13){
				$type_name=mb_substr($row_type['type_name'],0,12,"utf8")."...";
			}else{
				$type_name=$row_type['type_name'];
			}
?>
            <li><a href="blog.php?type=<?=$row_type['type_id']?>" ><?=$type_name?></a></li>
<?
	}
?>

        </ul>
    	<h3>所有文章</h3>
    	<ul>
<?
	while($row_monthly=mysqli_fetch_array($rs_monthly)){
		$y=substr($row_monthly['monthly'],0,4);
		$m=substr($row_monthly['monthly'],4,2);
		$t_month=date("M",mktime(0,0,0,$m,0,$y))
?>
            <li><a href="blog.php?m=<?=$row_monthly['monthly']?>" ><?=$t_month?> <?=$y?></a></li>
<?
	}
?>
        </ul>
    </div>

      <div id="blog_float_right">
            <div id="blog_header">
                <div id="subject_date"><?=date("M d",$timestamp)?></div>
                <h1><?=$row_arti['title']?></h1>
                <div id="blog_comments_link"><fb:comments-count href="http://pazter.com/article.php?id=<?=$_GET['id']?>"></fb:comments-count> comments</div>
                <div id="blog_views_link"><?=get_page_counter($row_arti['id'])?> views</div>
				<div id="blog_fb_link">
                	<iframe src="http://www.facebook.com/widgets/like.php?href=http://www.pazter.com/article.php?id=<?=$row_arti['id']?>&layout=button_count" scrolling="no" frameborder="0" style="border:none; width:80px; height:30px"></iframe>
                </div>
				<div id="blog_share_link">
                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style ">
                    <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=pazter" class="addthis_button_compact">Share</a>
                    <span class="addthis_separator">|</span>
                    <a class="addthis_button_preferred_1"></a>
                    <a class="addthis_button_preferred_2"></a>
                    <a class="addthis_button_preferred_3"></a>
                    <a class="addthis_button_preferred_4"></a>
                    </div>
                    <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=pazter"></script>
                    <!-- AddThis Button END -->
				</div>
            </div>

            <div id="blog_content">
                <img title="<?=$row_arti['title']?>" alt="<?=$row_arti['title']?>" src="b_images/<?=$arr_img_path[0]?>" />
                <div class="blog_text"><?=stripslashes($row_arti['content'])?></div>
<?
	if($arr_img_path[1]!=""){echo "<img src=\"b_images/".$arr_img_path[1]."\" />";}
	if($arr_img_path[2]!=""){echo "<img src=\"b_images/".$arr_img_path[2]."\" />";}
	if($arr_img_path[3]!=""){echo "<img src=\"b_images/".$arr_img_path[3]."\" />";}
	if($arr_img_path[4]!=""){echo "<img src=\"b_images/".$arr_img_path[4]."\" />";}
?>

<?
								if($row_arti['rel_product']!=""){

										$sql="SELECT product_id,product_name,product_image,product_price FROM products WHERE display='1' AND ".$row_arti['rel_product'];
										$rs_pd=mysql_query($sql);
										$pd_count=1;
										while($row_pd=mysqli_fetch_array($rs_pd)){
											$pd_class=($pd_count%4==0)?"product_item":"product_item_right product_item_for_new";
											$arr_image_name=$pd->get_product_image_name($row_pd['product_image']);
											$img_140=($arr_image_name[1]=="")?"default_img_140.jpg":$arr_image_name[1];
											$row_pd['product_name']=stripslashes($row_pd['product_name']);
											$alt=$row_pd['product_name'];
											if(mb_strlen($row_pd['product_name'])>10){
												$row_pd['product_name']	=	mb_substr($row_pd['product_name'],0,10,'UTF-8');
												$row_pd['product_name']	=	$row_pd['product_name']."...";
											}else{
												$row_pd['product_name']	=	stripslashes($row_pd['product_name']);
											}
?>
                <div class="<?=$pd_class?>">
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>" title="加入購物車" class="item_add"></a>
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>"><img class="product_tipsy" title="<?=$alt?>" alt="<?=$alt?>" src="p_images/<?=$img_140?>" /></a>
                    <a href="<?=$page_links['detail']."?id=".$row_pd['product_id']?>"><h2 class="item_name"><?=$row_pd['product_name']?></h2></a>
               </div>
<?
											$pd_count++;
										}
								}
?>
            </div>
          <div id="blog_footer"></div>
<?
		while( $arr_reply=mysqli_fetch_array($rs_reply) and mysql_num_rows($rs_reply)>0 ){
			$arr_reply['reply_msg']=stripslashes($arr_reply['reply_msg']);
			$arr_reply['reply_name']=stripslashes($arr_reply['reply_name']);
?>
          <div class="comment">
          	<div class="comment_content"><?=$arr_reply['reply_msg']?></div>
            <div class="comment_bg_to"></div>
            <div class="reply_user"><?=$arr_reply['reply_name']?></div>
            <div class="reply_time"><?=$arr_reply['reply_date']?></div>
          </div>
<?
		}
?>

          <div class="reply_form" id="reply_form">
          	<!-- <div id="fb-root"></div><script src="http://connect.facebook.net/zh_TW/all.js#appId=205407766137194&amp;xfbml=1"></script><fb:comments href="http://pazter.com/article.php?id=<?=$_GET['id']?>" num_posts="10" width="660"></fb:comments> -->
          	<div id="fb-root"></div><script src="http://connect.facebook.net/zh_TW/all.js#appId=205407766137194&amp;xfbml=1"></script><fb:comments href="http://pazter.com/article.php?id=2010120004" num_posts="10" width="660"></fb:comments>
          </div>

        </div>
	</div>


</div>

