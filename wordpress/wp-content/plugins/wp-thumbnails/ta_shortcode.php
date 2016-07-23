<?php

/*
* shortcode
* 支持的参数有：数量num、宽width、高height
*/

//插入[wp-thumbnails type="random" id="237" width="" height="" crop="" ]

add_shortcode('wp-thumbnails', 'wp_thumbnails_handler');

function wp_thumbnails_handler($atts) {
	$def = "null";
	extract(shortcode_atts(array(
			'type'		=> 'random',
			'num'			=> $def,
			'width' 	=> $def,
			'height' 	=> $def,
			'id'			=> $def,
			'media'		=> $def,
			'thumb'		=> $def,
			'order'		=> $def,
		), $atts));
	
	$args = "display=false";
	if($num!=$def) $args .= "&num=$num"; 
	if($width!=$def) { 
		$args .= "&width=$width"; 
		if ($height!=$def) $args .= "&height=$height"; 
	}
	if($id!=$def) $args .= "&id=$id"; 
	if($media!=$def) { 
		$args .= "&media=$media"; 
		if ($thumb!=$def) $args .= "&thumb=$thumb"; 
	}
	if($order!=$def) $args .= "&order=$order"; 
	
	if($type=='random') {
		return wp_thumbnails_for_random_posts ($args);
	}
	else if($type=='recent') {
		return wp_thumbnails_for_recent_posts ($args);
	}
	else if($type=='popular') {
		return wp_thumbnails_for_popular_posts ($args);
	}
	else if($type=='related') {
		return wp_thumbnails_for_related_posts ($args);
	}
	else if($type=='single') {
		return wp_thumbnails_for_single_post ($args);
	}
	else if($type=='category') {
		return wp_thumbnails_for_category ($args);
	}
	else if($type=='tag') {
		return wp_thumbnails_for_tag ($args);
	}
}

?>