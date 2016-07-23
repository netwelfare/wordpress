<?php

/*
* 侧边栏
*/
function widget_sidebar_ta_random() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_ta_random($args) {
	  extract($args);
		echo $before_widget;
		
		$ta_random_options = get_option('widget_ta_random');
		$title = $ta_random_options['title'];

		if ( empty($title) )	$title = '随机文章'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$output = wp_thumbnails_for_random_posts();
		echo $output;

		echo $after_widget;
	}

	register_sidebar_widget('随机缩略图', 'widget_ta_random');
	
	function widget_ta_random_options() {			
		$ta_random_options = $new_ta_random_options = get_option('widget_ta_random'); //获取数据库中的 widget_ta_random
		if ( $_POST["ta_random_submit"] ) { //如果提交更新
			$new_ta_random_options['title'] = strip_tags(stripslashes($_POST["ta_random_title"]));
			if ( $ta_random_options != $new_ta_random_options ) { //如果有更新
				$ta_random_options = $new_ta_random_options;
				update_option('widget_ta_random', $ta_random_options);
			}
		}
		$title = attribute_escape($ta_random_options['title']);
?>
		<p><label for="wp_ta_random_title"><?php _e('Title:'); ?> <input style="width: 250px;" id="ta_random_title" name="ta_random_title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="ta_random_submit" name="ta_random_submit" value="1" />
<?php
	}
	
	register_widget_control('随机缩略图', 'widget_ta_random_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_ta_random');


function widget_sidebar_ta_recent() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_ta_recent($args) {
	    extract($args);
		echo $before_widget;
		
		$ta_recent_options = get_option('widget_ta_recent');
		$title = $ta_recent_options['title'];

		if ( empty($title) )	$title = '最新文章'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$output = wp_thumbnails_for_recent_posts();
		echo $output;

		echo $after_widget;
	}

	register_sidebar_widget('最新缩略图', 'widget_ta_recent');
	
	function widget_ta_recent_options() {			
		$ta_recent_options = $new_ta_recent_options = get_option('widget_ta_recent'); //获取数据库中的 widget_ta_recent
		if ( $_POST["ta_recent_submit"] ) { //如果提交更新
			$new_ta_recent_options['title'] = strip_tags(stripslashes($_POST["ta_recent_title"]));
			if ( $ta_recent_options != $new_ta_recent_options ) { //如果有更新
				$ta_recent_options = $new_ta_recent_options;
				update_option('widget_ta_recent', $ta_recent_options);
			}
		}
		$title = attribute_escape($ta_recent_options['title']);
?>
		<p><label for="wp_ta_recent_title"><?php _e('Title:'); ?> <input style="width: 250px;" id="ta_recent_title" name="ta_recent_title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="ta_recent_submit" name="ta_recent_submit" value="1" />
<?php
	}
	
	register_widget_control('最新缩略图', 'widget_ta_recent_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_ta_recent');

function widget_sidebar_ta_related() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_ta_related($args) {
	  extract($args);
		echo $before_widget;
		
		$ta_related_options = get_option('widget_ta_related');
		$title = $ta_related_options['title'];

		if ( empty($title) )	$title = '相关文章'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$output = wp_thumbnails_for_related_posts();
		echo $output;

		echo $after_widget;
	}

	register_sidebar_widget('相关缩略图', 'widget_ta_related');
	
	function widget_ta_related_options() {			
		$ta_related_options = $new_ta_related_options = get_option('widget_ta_related'); //获取数据库中的 widget_ta_related
		if ( $_POST["ta_related_submit"] ) { //如果提交更新
			$new_ta_related_options['title'] = strip_tags(stripslashes($_POST["ta_related_title"]));
			if ( $ta_related_options != $new_ta_related_options ) { //如果有更新
				$ta_related_options = $new_ta_related_options;
				update_option('widget_ta_related', $ta_related_options);
			}
		}
		$title = attribute_escape($ta_related_options['title']);
?>
		<p><label for="wp_ta_related_title"><?php _e('Title:'); ?> <input style="width: 250px;" id="ta_related_title" name="ta_related_title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="ta_related_submit" name="ta_related_submit" value="1" />
<?php
	}
	
	register_widget_control('相关缩略图', 'widget_ta_related_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_ta_related');


function widget_sidebar_ta_popular() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_ta_popular($args) {
	    extract($args);
		echo $before_widget;
		
		$ta_popular_options = get_option('widget_ta_popular');
		$title = $ta_popular_options['title'];

		if ( empty($title) )	$title = '最热门文章'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$output = wp_thumbnails_for_popular_posts();
		echo $output;

		echo $after_widget;
	}

	register_sidebar_widget('最热门缩略图', 'widget_ta_popular');
	
	function widget_ta_popular_options() {			
		$ta_popular_options = $new_ta_popular_options = get_option('widget_ta_popular'); //获取数据库中的 widget_ta_popular
		if ( $_POST["ta_popular_submit"] ) { //如果提交更新
			$new_ta_popular_options['title'] = strip_tags(stripslashes($_POST["ta_popular_title"]));
			if ( $ta_popular_options != $new_ta_popular_options ) { //如果有更新
				$ta_popular_options = $new_ta_popular_options;
				update_option('widget_ta_popular', $ta_popular_options);
			}
		}
		$title = attribute_escape($ta_popular_options['title']);
?>
		<p><label for="wp_ta_popular_title"><?php _e('Title:'); ?> <input style="width: 250px;" id="ta_popular_title" name="ta_popular_title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="ta_popular_submit" name="ta_popular_submit" value="1" />
<?php
	}
	
	register_widget_control('最热门缩略图', 'widget_ta_popular_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_ta_popular');

?>