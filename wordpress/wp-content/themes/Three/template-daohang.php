<?php
/*
Template Name: 博客导航
*/
?>
<?php get_header();?>
<div id="content" class="site-content">	
<div class="clear"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php while ( have_posts() ) : the_post(); ?>
			
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>	
		<div class="single_info">
					<span class="date"><?php the_time( 'Y年m月d日' ) ?></span>
					<span class="views"><?php if( function_exists( 'the_views' ) ) { the_views(); print '人阅读 '; } ?></span>
					<span class="comment"><?php comments_popup_link( '暂无评论', ' 1 条评论', ' % 条评论' ); ?></span>				
					<span class="edit"><?php edit_post_link('编辑', '  ', '  '); ?></span>
				</div>			
	</header><!-- .entry-header -->

	<div class="entry-content">
					<div class="single-content">									
	<?php the_content(); ?>
	<div id="plinks">
<ul class="plinks">
	<li class="linkcat">		
	<ul class="xoxo blogroll">
		<?php 
		$dh_id = get_post_meta($post->ID, 'dhid', true); 
		wp_list_bookmarks('title_li=&categorize=0&orderby=link_id&category='.$dh_id.''); ?>
</ul>
	</li>
</ul>
</div>
			</div>
<div class="clear"></div>
<?php get_template_part( 'inc/social' ); ?>
			
<?php include('inc/file.php'); ?>
				<div class="clear"></div>
	</div><!-- .entry-content -->

	</article><!-- #post -->															
	<?php if (get_option('ygj_adt') == '关闭') { ?>
		<?php { echo ''; } ?>
	<?php } else { include(TEMPLATEPATH . '/inc/ad/ad_single_d.php'); } ?>
	
		<?php if (get_option('ygj_tuijian') == '关闭') { ?>
		<?php { echo ''; } ?>
	<?php } else { include(TEMPLATEPATH . '/inc/tuijian.php');}?>

	<?php if (get_option('ygj_zwdb') == '关闭') { ?>
		<?php { echo ''; } ?>
	<?php } else { ?>
		<div id="single-widget">
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<?php comments_template( '', true ); ?>			
			<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<?php get_sidebar();?>
<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>