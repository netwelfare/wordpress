<?php 
require( dirname(__FILE__) . '/wp-load.php' );
?>
<?php get_header(); ?>
<div id="wrapper" class="clearfix">

	<div id="breadcrumbs" class="con_box clearfix">
		<div class="bcrumbs"><strong><a href="<?php bloginfo('home'); ?>" title="返回首页">home</a></strong>
		<a><?php the_title(); ?></a>
		</div>
	</div>
 	<div id="art_container clearfix">
 		<div id="art_main1" class="art_white_bg fl"> 
			
		</div>
		<div class="clear"></div>

	<?php
	query_posts('meta_key=post_views_count&orderby=meta_value_num&order=DESC');
	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
	<?php
	endwhile; endif;
	wp_reset_query();
?>
<?php get_sidebar(); ?>

<?php get_footer(); ?>