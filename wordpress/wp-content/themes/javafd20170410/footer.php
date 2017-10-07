<div id="footer-widget-box">
	<div class="footer-widget">
	<?php wp_reset_query();{ ?>	
		<div id="links">
	<ul>
		<li id="linkcat-2" class="linkcat"><h2>友情链接</h2>
			<ul class="xoxo blogroll">
				<li><strong>免责声明：Java辅导网是一个非盈利性网站，网站所有作品均由热心网友网上搜集共同更新，仅供读者预览及学习交流使用，下载后请24小时内删除，如果喜欢请购买正版资源。原作者如果认为本站侵犯了您的版权，请QQ告知，我们会立即删除!</strong></li>
				<?php
				if(function_exists('wp_hot_get_links')){
				wp_hot_get_links();
				}else{
				wp_list_bookmarks('title_li=&categorize=0&orderby=rand&show_images=&limit=30&category='.get_option('ygj_link_id'));	
				}
				?>
			</ul>
		</li>
	</ul>
<div class="clear"></div>
</div>
	<?php } ?>
		
<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="site-info">			
			Copyright ©&nbsp; <?php date('Y'); bloginfo('name');?><span class="footer-tag">&nbsp; | &nbsp; Powered by <a href="http://javafd.com/" title="Java辅导网" target="_blank" rel="nofollow">javafd.com</a> &nbsp; | &nbsp;  <?php echo stripslashes(get_option('ygj_icp')); ?></span>
		</div>
</footer>

	</div>
</div>	
<div class="tools">
    <a class="tools_top" title="返回顶部"></a>
    <?php wp_reset_query(); if ( is_single() || is_page() ) { ?>
        <a class="tools_comments" title="发表评论"></a>
    <?php } else {?>
        <!--<a href="<?php echo stripslashes(get_option('ygj_lyburl')); ?>#respond" class="tools_comments" title="给我留言" target="_blank" rel="nofollow"></a>-->
    <?php } ?>
</div>
<?php if (is_single() || is_page() ) { ?>
<?php get_template_part( 'inc/share' ); ?>
<?php get_template_part( 'inc/shang' ); ?>
<script>window._bd_share_config={"common":{"bdSnsKey":{"tsina":"2363344102"},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
<?php } ?>
<?php if (is_home() || is_archive() || is_search()) { ?>
<script type="text/javascript">
    document.onkeydown = chang_page;function chang_page(e) {
        var e = e || event,
        keycode = e.which || e.keyCode;
        if (keycode == 33 || keycode == 37) location = '<?php echo get_previous_posts_page_link(); ?>';
        if (keycode == 34 || keycode == 39) location = '<?php echo get_next_posts_page_link(); ?>';
    }
</script>
<?php } ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
<?php wp_footer(); ?>
</body></html>
<?php  /*include(TEMPLATEPATH . '/inc/bulletin.php');*/ ?>