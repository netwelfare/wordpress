<div id="footer-widget-box">
	<div class="footer-widget">
	<?php wp_reset_query();if ( is_home()){ ?>	
		<div id="links">
	<ul>
		<li id="linkcat-2" class="linkcat"><h2>友情链接</h2>
			<ul class="xoxo blogroll">
				<li><strong>友情链接：</strong></li>
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
			Copyright ©&nbsp; <?php date('Y'); bloginfo('name');?><span class="footer-tag"> &nbsp; | &nbsp;  <?php echo stripslashes(get_option('ygj_icp')); ?></span>
			&nbsp; | &nbsp;
			<?php $users=wp_list_authors('echo=0&exclude_admin=0&hide_empty=0&optioncount=1&style=0');$users=split(',',$users); echo '本站有', count($users), '位注册用户'; ?>
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