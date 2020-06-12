<?
	require_once ('controller/product_controller.php');
	require_once ('controller/common_func.php');
	$pd=new product();
	$rs_pd=$pd->get_cate_product('0');


	while($row_pd=mysqli_fetch_array($rs_pd)){
?>

		<a target="_blank" style="color:#CCCCCC;" href="http://pazter.com/<?=$page_links['detail']."?id=".$row_pd['product_id']?>"><?=$row_pd['product_name']?></a>

		<div class="fb_like_button"><iframe src="http://www.facebook.com/widgets/like.php?href=http://www.pazter.com/<?=$page_links['detail']."?id=".$row_pd['product_id']?>&layout=button_count"
        scrolling="no" frameborder="0" style="border:none; width:200px; height:80px"></iframe>
    </div>

		<iframe src="http://www.facebook.com/plugins/comments.php?href=pazter.com/sticker.php?id=<?=$row_pd['product_id']?>&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe>


		<br>

<?
	}

	echo "<br><<<<<<----------->>>>>><br>";

	$sql="SELECT * FROM blog ORDER BY date DESC";
	$rs=mysql_query($sql);
	while($row_bg=mysqli_fetch_array($rs)){

?>

	<a target="_blank" style="color:#CCCCCC;" href="http://pazter.com/article.php?id=<?=$row_bg['id']?>"><?=$row_bg['title']?></a>
	<iframe src="http://www.facebook.com/plugins/comments.php?href=pazter.com/article.php?id=<?=$row_bg['id']?>&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe>
	<br>

<?
	}
?>







<iframe src="http://www.facebook.com/plugins/activity.php?site=pazter.com&amp;width=400&amp;height=2000&amp;header=true&amp;colorscheme=light&amp;font&amp;border_color&amp;recommendations=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:2000px;" allowTransparency="true"></iframe>