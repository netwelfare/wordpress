<?php get_header(); ?>
	<div id="wrapper" class="clearfix">
	<div id="breadcrumbs" class="con_box clearfix">
		<div class="bcrumbs"><strong><a href="<?php bloginfo('home'); ?>" title="返回首页">home</a></strong>
		<?php 
		    global $wpdb;
		    date_default_timezone_set('PRC');
		    $ip = $_SERVER['REMOTE_ADDR'];
		    $key = get_search_query();
		    $wpdb->insert('wp_search_record', array('keywords' => $key,'ip' => $ip,'create_time' =>date("Y-m-d H:i:s")));
			/*【表结构】
			 * create table wp_search_record(
			id int(6) not null primary key auto_increment,
			keywords varchar(64) not null,
			ip varchar(20) not null,
			create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL
			);*/
		    ?>
			<a>含有搜索词<strong><span style="color:#E53333;">【<?php the_search_query(); ?>】</span></strong>的文章</a>
		</div>
	</div><!-- //search -->
 		<div id="art_container clearfix">
 			<div id="art_main" class="fl"> 
		<?php if (get_option('swt_list') == 'Blog样式') { ?>
		<?php include(TEMPLATEPATH . '/includes/blog_list.php');?>
		<?php } else { include(TEMPLATEPATH . '/includes/title_list.php'); } ?>
            </div><!--内容-->


</div> 
</div>

<?php get_footer(); ?>