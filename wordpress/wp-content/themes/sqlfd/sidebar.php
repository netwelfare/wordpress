<div id="sider" class="fr">
	


			
			

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
			<li><a href="#" title="hot_art">热门文章</a></li>
		</ul>
        <div id="new_art" class="com_cont">  
            <ul>
				<?php query_posts('posts_per_page=8&caller_get_posts=1'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li>
				<a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" 

class="title"><?php echo cut_str($post->post_title,34); ?></a>
				</li>
				<?php endwhile; ?>
			</ul>      
		</div>			
		<div id="hot_art" class="com_cont">   
			<ul>
			    <?php query_posts('posts_per_page=8&caller_get_posts=1&orderby=comment_count'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li>
				<a target="_blank" href="<?php the_permalink(); ?>" class="title" title="<?php the_title(); 

?>"><?php echo cut_str($post->post_title,34); ?></a>
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
	
	
	
	
	<div class="con_box hot_box"> 				
			<h3>随机推荐</h3>		
		<div id="rand_art" >  
			<ul>
				<?php query_posts('posts_per_page=8&caller_get_posts=1&orderby=rand'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li>
				<a target="_blank" href="<?php the_permalink(); ?>" class="title" title="<?php the_title(); 

?>"><?php echo cut_str($post->post_title,34); ?></a>
				</li>
				<?php endwhile; ?>
			</ul>
		</div>   
	</div>
	
	<!-- 侧边栏(下)广告开始-->
	<?php wp_reset_query();  ?>
		<?php if (get_option('swt_adnnn') == 'Hide') { ?>
		<?php { echo ''; } ?>
		<?php } else { include(TEMPLATEPATH . '/includes/ad_nnn.php'); } ?>
		<div class="clear"></div>
	
	
	
	<div id="rollstart"></div>
	
	</div>
</div> 
</div><!-- //wrapper -->