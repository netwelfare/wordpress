<div id="sider" class="fr">
	

<!--<div class="twitbar">
                     <ul>分享到:</ul>
                    <div class="bshare-custom icon-medium">
                          <a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到腾讯微博" 

class="bshare-qqmb" href="javascript:void(0);"></a><a title="分享到QQ空间" class="bshare-qzone" href="javascript:void

(0);"></a><a title="分享到人人网" class="bshare-renren"></a><a title="更多平台" class="bshare-more bshare-more-icon more-

style-addthis"></a><span class="BSHARE_COUNT bshare-share-count" style="float: none; ">11.5K</span>
                     </div>
</div>-->
			
			

<?php if (get_option('swt_email') == 'Hide') { ?>
		<?php { echo ''; } ?>
		<?php } else { include(TEMPLATEPATH . '/includes/feed_email.php'); } ?>
<div class="clear"></div>


<!--
<div class="huanden4"> 			         
		<li>	<script async src=""></script>

<ins class="adsbygoogle"
     style="display:inline-block;width:250px;height:250px"
     data-ad-client="ca-pub-3970134420592904"
     data-ad-slot="8764736073"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</li>
</div>
<div class="clear"></div>
-->

<!-- 图片缩略图-->
<!--				
<div class="twitbar-2">
<?php //if(function_exists('wp_thumbnails_for_popular_posts')) { wp_thumbnails_for_popular_posts(); } 
?>
</div>	
-->
<!-- 侧边栏(上)广告开始-->  
		
		<?php wp_reset_query();  ?>
		<?php if (get_option('swt_admm') == 'Hide') { ?>
		<?php { echo ''; } ?>
		<?php } else { include(TEMPLATEPATH . '/includes/ad_mm.php'); } ?>
		<div class="clear"></div>
	      

	<div class="con_box htabs_art clearfix"> 
		<ul class="cooltab_nav sj_nav clearfix">	
		   		
			<li><a href="#" class="active" title="new_art">最新文章</a></li>
			<li><a href="#" title="hot_art">学习方法</a></li>
		</ul>
        <div id="new_art" class="com_cont">  
            <ul>
				<?php query_posts('posts_per_page=10&caller_get_posts=1&cat=-64'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li>
				<a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"class="title"><?php echo cut_str($post->post_title,34); ?></a>
				</li>
				<?php endwhile; ?>
			</ul>      
		</div>	
       	
		<div id="hot_art" class="com_cont">   
			<ul>
			    <?php /* query_posts('posts_per_page=8&caller_get_posts=1&orderby=comment_count'); */  query_posts('posts_per_page=10&caller_get_posts=1&cat=73'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li>
				<a target="_blank" href="<?php the_permalink(); ?>" class="title" title="<?php the_title(); ?>"><?php echo cut_str($post->post_title,34); ?></a>
				</li>
				<?php endwhile; ?>
			</ul>                    
		</div>
      	
	</div>
	
	<!-- 侧边栏(中)广告开始-->  
	<?php wp_reset_query();  ?>
		<?php if (get_option('swt_adnn') == 'Hide') { ?>
		<?php { echo ''; } ?>
		<?php } else { include(TEMPLATEPATH . '/includes/ad_nn.php'); } ?>
		<div class="clear"></div>
	
	
	
	<!--
	<div class="con_box hot_box"> 				
		<h3>热门文章</h3>		
		<div id="rand_art" >  
			<ul>
				<?php  //query_posts('meta_key=post_views_count&orderby=meta_value_num&order=DESC'); //query_posts('posts_per_page=8&caller_get_posts=1&orderby=rand'); ?>
				<?php  //while (have_posts()) : the_post(); ?>
				<li>
				    <a target="_blank" href="<?php //the_permalink(); ?>" class="title" title="<?php //the_title();?>"><?php //echo cut_str($post->post_title,34); ?></a>
				</li>
				<?php //endwhile; ?>
			</ul>
		</div>   
	</div>
	-->  
	
	<!-- 侧边栏(下)广告开始-->
	<?php wp_reset_query();  ?>
		<?php if (get_option('swt_adnnn') == 'Hide') { ?>
		<?php { echo ''; } ?>
		<?php } else { include(TEMPLATEPATH . '/includes/ad_nnn.php'); } ?>
		<div class="clear"></div>
	
	
	
	<div id="rollstart"></div>
	
	</div> <!-- //侧栏结束标签 -->
</div> <!-- //文章结束标签 -->
</div><!-- //wrapper -->


