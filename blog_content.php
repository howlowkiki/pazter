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
			if(strlen($row_title['title'],"utf8")>26){
				$title=substr($row_title['title'],0,24,"utf8")."...";
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
			if(strlen($row_type['type_name'])>26){
				$type_name=mb_substr($row_type['type_name'],0,24,"utf8")."...";
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
		$t_month=date("M",mktime(0,0,0,$m,1,$y))
?>
            <li><a href="blog.php?m=<?=$row_monthly['monthly']?>" ><?=$y?> <?=$t_month?></a></li>
<?
	}
?>
        </ul>
    </div>

        <div id="blog_float_right">
<?
		while($row_arti=mysqli_fetch_array($rs_arti)){
			$timestamp=strtotime($row_arti['date']);
			$arr_pic_path=explode(",",$row_arti['pic']);
			$row_arti['content']=stripslashes($row_arti['content']);

			if(strlen($row_arti['content'],"utf8")>200){
				$content=substr($row_arti['content'],0,198,"utf8")."<a class=\"link_more\" href=\"article.php?id=".$row_arti['id']."\">more</a>";
			}else{
				$content=$row_arti['content'];
			}
?>
            <div id="blog_header">
                <div id="subject_date"><?=date("M d",$timestamp)?></div>
                <a href="article.php?id=<?=$row_arti['id']?>"><h1><?=$row_arti['title']?></h1></a>                
                <div id="blog_views_link"><?=get_page_counter($row_arti['id'],$conn)?> views</div>                
                <div id="blog_fb_link"><iframe src="http://www.facebook.com/widgets/like.php?href=http://www.pazter.com/article.php?id=<?=$row_arti['id']?>&layout=button_count" scrolling="no" frameborder="0" style="border:none; width:80px; height:30px"></iframe></div>
             </div>
            <div id="blog_content">
                <a href="article.php?id=<?=$row_arti['id']?>"><img title="<?=$row_arti['title']?>" alt="<?=$row_arti['title']?>" src="b_images/<?=$arr_pic_path[0]?>" /></a>
                <div class="blog_text"><?=$content?></div>
            </div>
          <div id="blog_footer"></div>
<?
		}
?>

        </div>
	</div>


</div>

