<?php

/*
* 功能：随机缩略图、最新缩略图、分类缩略图、相关缩略图、相册缩略图
*/

// 随机缩略图
function wp_thumbnails_for_random_posts ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	$display = "true";
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_random_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_random_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_random_images'];
	  $args2['display'] = $display;
	  $args2['media'] = "";
	  
		$args2['thumb'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$thumb = $args['thumb'];
		
		$display = $args['display'];
		$media = $args['media'];
		$wp_thumbnails_options['number_of_random_images'] = $args['num'];
	  $wp_thumbnails_options['width_of_random_images'] = $args['width'];
	  $wp_thumbnails_options['height_of_random_images'] = $args['height'];
	}

  $number     			= $wp_thumbnails_options['number_of_random_images'];
  $thumbsize				= $wp_thumbnails_options['width_of_random_images'];
  $height						= $wp_thumbnails_options['height_of_random_images'];
  $target    				= $wp_thumbnails_options['random_new_window'];
  $show_title	 			= $wp_thumbnails_options['random_show_title'];
  $nofollow					= $wp_thumbnails_options['random_nofollow'];
  $link_target			= $wp_thumbnails_options['random_link_target'];
  $pic_interval 		= $wp_thumbnails_options['random_pic_interval'];
  $title_pos				= $wp_thumbnails_options['random_title_pos'];
  $wrap							= $wp_thumbnails_options['random_wrap'];
	$sort_images_randomly = true;
	$category_filter_way    = $wp_thumbnails_options['category_filter_way'];
	$show_excerpt_limit			= $wp_thumbnails_options['random_excerpt_limit'];
	$titlewidth			= $wp_thumbnails_options['random_title_width'];

	if (!is_array($wp_thumbnails_options['category_filter']))
  {
      $wp_thumbnails_options['category_filter'] = array();
  }

  // 将数组变成字符串,用英文逗号隔开,由分类的ID号组成
  $category_filter  = implode(",", $wp_thumbnails_options['category_filter']);

	$output = get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "random", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth);
	if($display=="false") {
		return $output;
	}
	else {
		echo $output;
	}
}

// 最新缩略图
function wp_thumbnails_for_recent_posts ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_recent_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_recent_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_recent_images'];
	
		$args2['media'] = "";
		$args2['thumb'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$thumb = $args['thumb'];
		$media = $args['media'];
		
	
		$wp_thumbnails_options['number_of_recent_images'] = $args['num'];
	  $wp_thumbnails_options['width_of_recent_images'] = $args['width'];
	  $wp_thumbnails_options['height_of_recent_images'] = $args['height'];
	}

  $number     	= $wp_thumbnails_options['number_of_recent_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_recent_images'];
  $height				= $wp_thumbnails_options['height_of_recent_images'];
  $target     	= $wp_thumbnails_options['recent_new_window'];
  $show_title 	= $wp_thumbnails_options['recent_show_title'];
  $nofollow			= $wp_thumbnails_options['recent_nofollow'];
  $link_target	= $wp_thumbnails_options['recent_link_target'];
  $pic_interval = $wp_thumbnails_options['recent_pic_interval'];
  $title_pos		= $wp_thumbnails_options['recent_title_pos'];
  $wrap					= $wp_thumbnails_options['recent_wrap'];
  $sort_images_randomly = false;
	$category_filter_way     = $wp_thumbnails_options['category_filter_way'];
	$show_excerpt_limit			= $wp_thumbnails_options['recent_excerpt_limit'];
	$titlewidth			= $wp_thumbnails_options['recent_title_width'];

	if (!is_array($wp_thumbnails_options['category_filter']))
  {
      $wp_thumbnails_options['category_filter'] = array();
  }

  // 将数组变成字符串,用英文逗号隔开,由分类的ID号组成
  $category_filter  = implode(",", $wp_thumbnails_options['category_filter']);

	echo get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "recent", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth);
}

// 相关缩略图
function wp_thumbnails_for_related_posts ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	$display = "true";
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_related_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_related_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_related_images'];
	  $args2['order'] 	= $wp_thumbnails_options['related_order'];
		$args2['display'] = $display;
		
		$args2['media'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$media = $args['media'];
		$display = $args['display'];
	
		$wp_thumbnails_options['number_of_related_images'] 	= $args['num'];
	  $wp_thumbnails_options['width_of_related_images'] 	= $args['width'];
	  $wp_thumbnails_options['height_of_related_images'] 	= $args['height'];
	  $wp_thumbnails_options['related_order'] 						= $args['order'];
	}

  $number     	= $wp_thumbnails_options['number_of_related_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_related_images'];
  $height				= $wp_thumbnails_options['height_of_related_images'];
  $order 				= $wp_thumbnails_options['related_order'];
  $target     	= $wp_thumbnails_options['related_new_window'];
  $show_title 	= $wp_thumbnails_options['related_show_title'];
  $nofollow			= $wp_thumbnails_options['related_nofollow'];
  $link_target	= $wp_thumbnails_options['related_link_target'];
  $pic_interval = $wp_thumbnails_options['related_pic_interval'];
  $title_pos		= $wp_thumbnails_options['related_title_pos'];
  $wrap					= $wp_thumbnails_options['related_wrap'];
  
  if($order == "random") {  $sort_images_randomly = true; }
  else  {  $sort_images_randomly = false; }

	$category_filter_way     = $wp_thumbnails_options['category_filter_way'];
	$show_excerpt_limit			= $wp_thumbnails_options['related_excerpt_limit'];
	$titlewidth			= $wp_thumbnails_options['related_title_width'];

	if (!is_array($wp_thumbnails_options['category_filter']))
  {
      $wp_thumbnails_options['category_filter'] = array();
  }

  // 将数组变成字符串,用英文逗号隔开,由分类的ID号组成
  $category_filter  = implode(",", $wp_thumbnails_options['category_filter']);

	$output = get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "related", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth);
	if($display=="false") {
		return $output;
	}
	else {
		echo $output;
	}
}


// 最热门日志缩略图
function wp_thumbnails_for_popular_posts ($args = ''){
	
	if (!function_exists('the_views')) { //必须结合WP-PostViews
		return;
	}

	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_popular_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_popular_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_popular_images'];
	
		$args2['media'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$media = $args['media'];
		
	
		$wp_thumbnails_options['number_of_popular_images'] = $args['num'];
	  $wp_thumbnails_options['width_of_popular_images'] = $args['width'];
	  $wp_thumbnails_options['height_of_popular_images'] = $args['height'];
	}

  $number     	= $wp_thumbnails_options['number_of_popular_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_popular_images'];
  $height				= $wp_thumbnails_options['height_of_popular_images'];
  $target     	= $wp_thumbnails_options['popular_new_window'];
  $show_title 	= $wp_thumbnails_options['popular_show_title'];
  $nofollow			= $wp_thumbnails_options['popular_nofollow'];
  $link_target	= $wp_thumbnails_options['popular_link_target'];
  $pic_interval = $wp_thumbnails_options['popular_pic_interval'];
  $title_pos		= $wp_thumbnails_options['popular_title_pos'];
  $wrap					= $wp_thumbnails_options['popular_wrap'];
  $sort_images_randomly = false;
	$category_filter_way     = $wp_thumbnails_options['category_filter_way'];
	$show_excerpt_limit			= $wp_thumbnails_options['popular_excerpt_limit'];
	$titlewidth			= $wp_thumbnails_options['popular_title_width'];

	if (!is_array($wp_thumbnails_options['category_filter']))
  {
      $wp_thumbnails_options['category_filter'] = array();
  }

  // 将数组变成字符串,用英文逗号隔开,由分类的ID号组成
  $category_filter  = implode(",", $wp_thumbnails_options['category_filter']);

	echo get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "popular", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth);
}


// 单篇日志缩略图
function wp_thumbnails_for_single_post ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	$display = "true";
	$wrap = "true";
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_single_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_single_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_single_images'];
		$args2['wrap'] 	= $wrap;
		$args2['display'] = $display;
		
		$args2['media'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$media = $args['media'];
		$display = $args['display'];
		$wrap = $args['wrap'];
		$wp_thumbnails_options['number_of_single_images'] = $args['num'];
	  $wp_thumbnails_options['width_of_single_images'] = $args['width'];
	  $wp_thumbnails_options['height_of_single_images'] = $args['height'];
	}

  $number     	= $wp_thumbnails_options['number_of_single_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_single_images'];
  $height				= $wp_thumbnails_options['height_of_single_images'];
  $target     	= $wp_thumbnails_options['single_new_window'];
  $show_title 	= "false"; //无效参数
  $nofollow			= "false"; //无效参数
  $link_target	= $wp_thumbnails_options['single_link_target'];
  $pic_interval = $wp_thumbnails_options['single_pic_interval'];
  $title_pos		= "bottom"; //无效参数
  $sort_images_randomly = false; //无效参数
	$category_filter_way  = "none";  //无效参数 single post不过滤分类
  $category_filter  		= "";  //无效参数 single post不过滤分类


	$output = get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "single", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap);
	if($display=="false") {
		return $output;
	}
	else {
		echo $output;
	}
}


// 分类缩略图
// 参数：分类id、随机或最新、数量、高度、宽度
function wp_thumbnails_for_category ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	if(!empty($args)) { 
		
		$category_filter = $args; //只传递分类ID作为参数
		if((string)$category_filter === (string)(int)$category_filter) { //如果只传递了一个数字
			$category_filter = $args;
		} 
		else { 
			$args2 = array();	  
		  $args2['num'] 		= $wp_thumbnails_options['number_of_category_images'];
		  $args2['width'] 	= $wp_thumbnails_options['width_of_category_images'];
		  $args2['height'] 	= $wp_thumbnails_options['height_of_category_images'];
		  $args2['order'] 	= $wp_thumbnails_options['category_order'];
		  
		  $cat_name = single_cat_title("", false);  //默认为当前分类id
		  $args2['id'] 	= get_cat_id($cat_name);
		
			$args2['media'] = "";
			$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
			$media = $args['media'];
			
			$wp_thumbnails_options['number_of_category_images'] = $args['num'];
		  $wp_thumbnails_options['width_of_category_images'] 	= $args['width'];
		  $wp_thumbnails_options['height_of_category_images'] = $args['height'];
		  $wp_thumbnails_options['category_order'] 						= $args['order'];
		  $category_filter = $args['id']; 
		}
	}
	else {
		if (!is_category()) {
			return;//参数为空，则只针对当前分类
		}
		$cat_name = single_cat_title("", false);  //默认为当前分类id
		$category_filter = get_cat_id($cat_name);
	}

  $number     	= $wp_thumbnails_options['number_of_category_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_category_images'];
  $height				= $wp_thumbnails_options['height_of_category_images'];
  $order     		= $wp_thumbnails_options['category_order'];
  $target     	= $wp_thumbnails_options['category_new_window'];
  $show_title 	= $wp_thumbnails_options['category_show_title'];
  $nofollow			= $wp_thumbnails_options['category_nofollow'];
  $link_target	= $wp_thumbnails_options['category_link_target'];
  $pic_interval = $wp_thumbnails_options['category_pic_interval'];
  $title_pos		= $wp_thumbnails_options['category_title_pos'];
  $wrap					= $wp_thumbnails_options['category_wrap'];
  $category_filter_way = "include"; 
  
  if($order == "random") {  $sort_images_randomly = true; }
  else  {  $sort_images_randomly = false; }
  
  $show_excerpt_limit			= $wp_thumbnails_options['category_excerpt_limit'];
  $titlewidth			= $wp_thumbnails_options['category_title_width'];

	echo get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "category", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth);
}

// 标签缩略图
function wp_thumbnails_for_tag ($args = ''){
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	//只允许传递参数：数量、宽、高
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['num'] 		= $wp_thumbnails_options['number_of_tag_images'];
	  $args2['width'] 	= $wp_thumbnails_options['width_of_tag_images'];
	  $args2['height'] 	= $wp_thumbnails_options['height_of_tag_images'];
	  $args2['order'] 	= $wp_thumbnails_options['tag_order'];
		$args2['id'] = get_query_var('tag_id'); 
		  
		$args2['media'] = "";
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$media = $args['media'];
		
		$id_list	= $args['id']; 
		$order = $args['order'];
		$wp_thumbnails_options['number_of_tag_images'] 	= $args['num'];
	  $wp_thumbnails_options['width_of_tag_images'] 	= $args['width'];
	  $wp_thumbnails_options['height_of_tag_images'] 	= $args['height'];
	  $wp_thumbnails_options['tag_order'] 						= $args['order'];
	}
	else {
		$id_list = get_query_var('tag_id'); 
	}
	
  $number     	= $wp_thumbnails_options['number_of_tag_images'];
  $thumbsize		= $wp_thumbnails_options['width_of_tag_images'];
  $height				= $wp_thumbnails_options['height_of_tag_images'];
  $order				= $wp_thumbnails_options['tag_order'];
  $target     	= $wp_thumbnails_options['tag_new_window'];
  $show_title 	= $wp_thumbnails_options['tag_show_title'];
  $nofollow			= $wp_thumbnails_options['tag_nofollow'];
  $link_target	= $wp_thumbnails_options['tag_link_target'];
  $pic_interval = $wp_thumbnails_options['tag_pic_interval'];
  $title_pos		= $wp_thumbnails_options['tag_title_pos'];
  $show_excerpt_limit = $wp_thumbnails_options['tag_excerpt_limit'];
  $wrap					= "false"; //无效参数
	$category_filter_way  = "none";  //无效参数 tag缩略图不过滤分类
  $category_filter  		= "";  //无效参数 tag缩略图不过滤分类
  
  if($order == "random") {  $sort_images_randomly = true; }
  else  {  $sort_images_randomly = false; }
  
  $show_excerpt_limit			= $wp_thumbnails_options['tag_excerpt_limit'];
  $titlewidth			= $wp_thumbnails_options['tag_title_width'];

	echo get_wp_thumbnails_for_post($media, $thumb, $number, $thumbsize, $height, $target, $sort_images_randomly, $category_filter_way,  $category_filter, "tag", $show_title, $nofollow, $link_target, $pic_interval, $title_pos, $wrap, $show_excerpt_limit, $titlewidth, $id_list);
}


/*
* 功能：上述函数的辅助函数
*/

function get_wp_thumbnails_for_post ($media = "",
																				$video_thumb = "",
																				$number = 6, 
																				$thumbsize = 75, 
																				$height = 75,
																				$target = "_blank", 
																				$sort_images_randomly = true, 
																				$category_filter_way = "none",
																				$category_filter = "",
																				$articles = "random",
																				$show_title = "true",
																				$nofollow = "false",
																				$link_target = "post",
																				$pic_interval = 10,
																				$title_pos = "bottom",
																				$wrap = "false",
																				$show_excerpt_limit = "",
																				$titlewidth = 75,
																				$id_list = ""
																				)
{
	global $thumbDir, $downloadDir, $siteurl, $rooturl, $destpath, $downloadpath; //申明全局变量
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	$rand_pic_from		 = $wp_thumbnails_options['rand_pic_from'];//表示每篇日志选第一张图片还是任选一张图片
	$releated_fill		 = $wp_thumbnails_options['releated_fill'];//是否用随机日志补足相关日志
	$disable_external = $wp_thumbnails_options['disable_external'];
	
	if($media=="")
		$media = $wp_thumbnails_options['media_post'];
	if($video_thumb=="")
		$video_thumb = $wp_thumbnails_options['video_thumb'];
	$video_link_target= $wp_thumbnails_options['video_link_target'];
	if($titlewidth=="") {
		$titlewidth = $thumbsize;
	}

	// 访问数据库
	global $wpdb,$post;;
	$now = current_time('mysql', 1);

	$custom_field_by_sql = "AND $wpdb->postmeta.meta_value NOT LIKE '%NoMediaFound%'
									 AND $wpdb->postmeta.meta_value NOT LIKE '%NoPicturesFound%'
									 ";// 显示缩略图
	if($media=="image") {
		$media_by_sql = "AND $wpdb->postmeta.meta_value NOT LIKE 'ta_video%'"; //排除以ta_video开头的
	}
	else if($media=="video") {
		$media_by_sql = "AND $wpdb->postmeta.meta_value LIKE '%ta_video%'"; //包含视频的
	}
	
	if($articles == "single") { //单篇日志
		if($post->post_status == 'publish' && $post->post_date_gmt < $now ) {
			$sql = "SELECT distinct post_id, meta_value 
			FROM $wpdb->postmeta
			WHERE meta_key IN ('ta-thumbnail')
			AND post_id = $post->ID 
			$custom_field_by_sql
			$media_by_sql
			";
		}
		else {
			return "";
		}
		$rand_pic_from = "all"; 
	}
	else {
	
				//过滤标签
				$tags_filter = $wp_thumbnails_options['tags_filter'];
				if($articles != "tag") {
					if (strstr($tags_filter, ',')) {
						$tags_array = explode(',',$tags_filter);  //转化为数组
						$tags_num = count($tags_array);
						$tags_count = 0;	
						$tags_filter = "";
						for ($tags_count = 0; $tags_count < $tags_num; $tags_count++) {
							$tag_name = $tags_array[$tags_count];
							$tags_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '$tag_name'");
							if ($tags_id == 0 || $tags_id === FALSE) continue;
							$tags_filter = $tags_id.",".$tags_filter;
						}
						if (substr($tags_filter, -1) == ",") $tags_filter = substr($tags_filter, 0, -1); //去掉结尾的','
					}
					else if ($tags_filter != "") {
						$tags_filter = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '$tags_filter'");
					}
					if ($tags_filter == "") $tags_filter = -1; 
				}
				
				
				//随机日志缩略图还是最新日志缩略图
				if ($sort_images_randomly)
				{
				    $order_by_sql = "rand()";
				}
				else
				{
				    $order_by_sql = "$wpdb->postmeta.post_id DESC";
				}
				
				//是否显示页面缩略图
			  $show_page = $wp_thumbnails_options['show_page'];
			  if ($show_page == "false") $page_by_sql =  "AND $wpdb->posts.post_type = 'post'"; 
			  else $page_by_sql =  "";
			  
			  $related_num = 0;
			  if ($articles == "tag") { //标签缩略图，注意sql中使用id_list
					$sql = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value 
					FROM $wpdb->postmeta,$wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
					WHERE t.taxonomy ='post_tag'
					AND $wpdb->postmeta.meta_key IN ('ta-thumbnail')
					AND $wpdb->postmeta.post_id = r.object_id 
					AND r.term_taxonomy_id = t.term_taxonomy_id
					AND $wpdb->postmeta.post_id = p.ID
					AND (t.term_id IN ($id_list)) 
					AND p.post_status = 'publish' 
					AND p.post_date_gmt < '$now' 
					$custom_field_by_sql
					$media_by_sql
					ORDER BY $order_by_sql
					LIMIT $number
					";
			  }
			  else if ($articles == "related") { //相关日志缩略图，从tag寻找相关日志	
			  	global $post;
					$tags = wp_get_post_tags($post->ID);
					//print_r($tags);	
					$taglist = "'" . $tags[0]->term_id. "'";
					$tagcount = count($tags);
					if ($tagcount > 1) {
						for ($i = 1; $i <= $tagcount; $i++) {
							$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
						}
					}
					
					$sql = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value 
					FROM $wpdb->postmeta,$wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
					WHERE t.taxonomy ='post_tag'
					AND $wpdb->postmeta.meta_key IN ('ta-thumbnail')
					AND $wpdb->postmeta.post_id = r.object_id 
					AND r.term_taxonomy_id = t.term_taxonomy_id
					AND $wpdb->postmeta.post_id = p.ID
					AND (t.term_id IN ($taglist)) 
					AND p.ID != $post->ID 
					AND p.post_status = 'publish' 
					AND p.post_date_gmt < '$now' 
					$custom_field_by_sql
					$media_by_sql
					ORDER BY $order_by_sql
					LIMIT $number
					";
					
					$related_resultset = @mysql_query($sql, $wpdb->dbh);
					$related_num = @mysql_num_rows($related_resultset);
			
					if (($related_num < $number) && ($releated_fill == "true")) { // 补全随机缩略图
						//排除相关日志，以免重复
						$image_count = 0;
						$postlist = "'" . $post->ID. "'";
						while ($image_count++ < $related_num)
						{	
							$row = mysql_fetch_array($related_resultset);
							$post_id = $row['post_id'];
							$postlist = $postlist . ", '" . $post_id . "'";
						}
						
						$extra_num = $number - $related_num;
						$limit_num = $extra_num + $extra_num;
					
						$sql_related = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value
						FROM $wpdb->postmeta, $wpdb->posts
						WHERE meta_key IN ('ta-thumbnail')
						AND $wpdb->postmeta.post_id = $wpdb->posts.ID
						AND $wpdb->posts.post_status = 'publish' 
						AND $wpdb->posts.post_date_gmt < '$now' 
						$custom_field_by_sql
						$media_by_sql
						$page_by_sql
						AND $wpdb->postmeta.post_id NOT IN (SELECT p.ID
								FROM $wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
								WHERE t.taxonomy ='post_tag'
								AND p.ID = r.object_id 
								AND r.term_taxonomy_id = t.term_taxonomy_id
								AND p.ID IN ($postlist)
						)
						ORDER BY $order_by_sql
						LIMIT $limit_num";
						
						if ($related_num == 0) 	{ //无相关缩略图时
							$sql = $sql_related;
							$related_num = $number;
							$extra_num = 0;
							$releated_fill = "false";
						}
					}
			  }
				else if ($category_filter != "" && $category_filter_way != "none" ) {
					if ($articles == "popular") { //最热日志，需要提取views
							$sql = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value
								FROM $wpdb->postmeta,$wpdb->term_relationships,$wpdb->term_taxonomy,$wpdb->posts
								WHERE $wpdb->postmeta.meta_key IN ('views')
								AND $wpdb->postmeta.post_id = $wpdb->term_relationships.object_id 
								AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
								AND $wpdb->term_taxonomy.term_id IN ($category_filter)
								AND $wpdb->postmeta.post_id = $wpdb->posts.ID
								AND $wpdb->posts.post_status = 'publish' 
								AND $wpdb->posts.post_date_gmt < '$now' 
								$page_by_sql
								$media_by_sql
								AND $wpdb->postmeta.post_id NOT IN (SELECT p.ID
									FROM $wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
									WHERE t.taxonomy ='post_tag'
									AND p.ID = r.object_id 
									AND r.term_taxonomy_id = t.term_taxonomy_id
									AND t.term_id IN ($tags_filter)
								)
								AND $wpdb->postmeta.post_id IN (SELECT q.post_id
									FROM $wpdb->postmeta q
									WHERE q.meta_key IN ('ta-thumbnail')
									AND (q.meta_value LIKE '%http%')
								)
								ORDER BY $wpdb->postmeta.meta_value DESC
								LIMIT $number";
					}
					else {
							$sql = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value
								FROM $wpdb->postmeta,$wpdb->term_relationships,$wpdb->term_taxonomy,$wpdb->posts
								WHERE $wpdb->postmeta.meta_key IN ('ta-thumbnail')
								AND $wpdb->postmeta.post_id = $wpdb->term_relationships.object_id 
								AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
								AND $wpdb->term_taxonomy.term_id IN ($category_filter)
								AND $wpdb->postmeta.post_id = $wpdb->posts.ID
								AND $wpdb->posts.post_status = 'publish' 
								AND $wpdb->posts.post_date_gmt < '$now' 
								$custom_field_by_sql
								$media_by_sql
								$page_by_sql
								AND $wpdb->postmeta.post_id NOT IN (SELECT p.ID
									FROM $wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
									WHERE t.taxonomy ='post_tag'
									AND p.ID = r.object_id 
									AND r.term_taxonomy_id = t.term_taxonomy_id
									AND t.term_id IN ($tags_filter)
								)
								ORDER BY $order_by_sql
								LIMIT $number";
					}
				}
			  else {
			  	if ($articles == "popular") { //最热日志，需要提取views
							$sql = "SELECT distinct post_id, (meta_value+0) AS views
							FROM $wpdb->postmeta, $wpdb->posts
							WHERE meta_key IN ('views')
							AND post_id = ID
							AND post_status = 'publish' 
							AND post_date_gmt < '$now' 
							$custom_field_by_sql
							$media_by_sql
							$page_by_sql
							AND post_id NOT IN (SELECT p.ID
								FROM $wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
								WHERE t.taxonomy ='post_tag'
								AND p.ID = r.object_id 
								AND r.term_taxonomy_id = t.term_taxonomy_id
								AND t.term_id IN ($tags_filter)
							)
							AND post_id IN (SELECT q.post_id
									FROM $wpdb->postmeta q
									WHERE q.meta_key IN ('ta-thumbnail')
									AND (q.meta_value LIKE '%http%')
								)
							ORDER BY views DESC
							LIMIT $number";
					}
			  	else {
							$sql = "SELECT distinct $wpdb->postmeta.post_id, $wpdb->postmeta.meta_value
							FROM $wpdb->postmeta, $wpdb->posts
							WHERE meta_key IN ('ta-thumbnail')
							AND $wpdb->postmeta.post_id = $wpdb->posts.ID
							AND $wpdb->posts.post_status = 'publish' 
							AND $wpdb->posts.post_date_gmt < '$now' 
							$custom_field_by_sql
							$media_by_sql
							$page_by_sql
							AND $wpdb->postmeta.post_id NOT IN (SELECT p.ID
									FROM $wpdb->term_relationships r,$wpdb->term_taxonomy t,$wpdb->posts p
									WHERE t.taxonomy ='post_tag'
									AND p.ID = r.object_id 
									AND r.term_taxonomy_id = t.term_taxonomy_id
									AND t.term_id IN ($tags_filter)
							)
							ORDER BY $order_by_sql
							LIMIT $number";
					}
			 	}
	}

	$resultset = @mysql_query($sql, $wpdb->dbh);
	$image_number = min($number,@mysql_num_rows($resultset));
	if($articles == "single") { $image_number = min($image_number,1); } //single只需要一个记录
	$image_count = 0;
	$output = '';

	while ($image_count++ < $image_number)
	{
		$row = mysql_fetch_array($resultset);
		$post_id = $row['post_id'];
		
		//获取年、月
		$the_post_date = $wpdb->get_row("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) AS year 
			FROM $wpdb->posts 
			WHERE ID = $post_id
			AND post_status = 'publish' 
			AND post_date_gmt < '$now' 
			LIMIT 1");
		$year = $the_post_date->year;
		$month = $the_post_date->month;
		
		$views = "";
		if ($articles == "popular") {
			$views = $row['views'];
			$thumbnail = get_post_meta($post_id, "ta-thumbnail", true);
		}
		else {
			$thumbnail = $row['meta_value'];
		}
		//$thumbnail = str_replace("@3@","",$thumbnail);
		
		$post_title = get_the_title($post_id);
		$post_permalink = get_permalink($post_id);
		
		$array = explode(";", $thumbnail);
		$iNumberOfPics = count($array)-1; // 图片数量 
		
		if ($articles == "single") {
			$seq = 0;
			$iNumberOfPics = min($iNumberOfPics, $number); // 图片数量 
		}
		
		//每篇日志中选取一张图片
		else if ($rand_pic_from == "first") { 	//选取第一张图片
			$seq = 0;
		}
		else {  //随机选取图片
			$seq = mt_rand(0,$iNumberOfPics-1);	 //[0, iNumberOfPics-1]	
		}
		

		while ($seq < $iNumberOfPics) {
	
			$thumbnail = $array[$seq];
			
			// 符合picasa图片条件
			if (strstr($thumbnail,'thumbnails_anywhere_picasa')) {
			  if ($thumbsize <= 32)  $realsize = 32; 
			  else if ($thumbsize <= 48)  $realsize = 48;
			  else if ($thumbsize <= 64)  $realsize = 64;
			  else if ($thumbsize <= 72)  $realsize = 72;
			  else if ($thumbsize <= 144)  $realsize = 144;
			  else if ($thumbsize <= 160)  $realsize = 160;
			  else $realsize = 160;
			  
			  $bigimage = str_replace("thumbnails_anywhere_picasa", '?imgmax=800', $thumbnail);
			  $thumbnail = str_replace("thumbnails_anywhere_picasa", '?imgmax='.$realsize.'&crop=1', $thumbnail);		  
			}
			else if  (strstr($thumbnail,'picasa_thumbnails_anywhere')) {
			  if ($thumbsize <= 32)  $realsize = 32; 
			  else if ($thumbsize <= 48)  $realsize = 48;
			  else if ($thumbsize <= 64)  $realsize = 64;
			  else if ($thumbsize <= 72)  $realsize = 72;
			  else if ($thumbsize <= 144)  $realsize = 144;
			  else if ($thumbsize <= 160)  $realsize = 160;
			  else $realsize = 160;
	
				$bigimage = str_replace("picasa_thumbnails_anywhere", 's800', $thumbnail);	
			  $thumbnail = str_replace("picasa_thumbnails_anywhere", 's'.$realsize.'-c', $thumbnail);	
			}
			// 符合yupoo图片条件
			else if  (strstr($thumbnail,'yupoo_thumbnails_anywhere')) {
				if ($thumbsize <= 75)  $type = "square"; 
				else if ($thumbsize <= 100)  $type = "thumb";
				else if ($thumbsize <= 240)  $type = "small";
				else if ($thumbsize <= 500)  $type = "medium";
				else if ($thumbsize <= 1024)  $type = "big";
				else $type = "big";
				// 原图无法获取
				
				$bigimage = str_replace("yupoo_thumbnails_anywhere", 'big', $thumbnail);	
				$thumbnail = str_replace("yupoo_thumbnails_anywhere", $type, $thumbnail);
			}
			// 符合flickr图片条件
			else if  (strstr($thumbnail,'flickr_thumbnails_anywhere')) {  
				if ($thumbsize <= 75)  $type = "_s"; 
				else if ($thumbsize <= 100)  $type = "_t";
				else if ($thumbsize <= 240)  $type = "_m";
				else $type = "";
				$bigimage = str_replace("flickr_thumbnails_anywhere", '_m', $thumbnail);	
			  $thumbnail = str_replace("flickr_thumbnails_anywhere", $type, $thumbnail);
			}
			else if(!strstr($thumbnail,'ta_video_')){ //普通图片(非视频)
				$bigimage = $thumbnail;	
				if ((!strstr($thumbnail,$siteurl)) and ($disable_external != "false")) { // 外链图片且不制作缩略图
					//什么也不做
				}
				else {
					$thumbnailbackup = $thumbnail;
					$filename = $post_id."-".($seq+1);
					$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, true, $year, $month);
					if ($thumbnail == false) { //如果图片不存在
						$thumbnail = $thumbnailbackup;
						$thumbnail = str_replace($siteurl,$rooturl,$thumbnail);//转换为绝对路径	
						
						//将图片按照规格存放在不同子目录
						$subfolder = "{$thumbsize}x{$height}-c/";
						$subfolder = $destpath.$subfolder;
						
						if(!file_exists($subfolder)) { 
						if(!(@mkdir($subfolder,0755))) { 
							if(is_admin()) {
								echo "提示：很抱歉，无法创建缩略图子目录，请手动创建目录".$subfolder."，权限设置为755。<br>"; 
								return; 
								}
							} 
						}
						
						$subfolder .= "$year/";
						if(!file_exists($subfolder)) { 
							if(!(@mkdir($subfolder,0755))) { 
								if(is_admin()) {
									echo "提示：很抱歉，无法创建缩略图子目录，请手动创建目录".$subfolder."，权限设置为755。<br>"; 
									return; 
								}
							} 
						}
						
						$subfolder .= "$month/";
						if(!file_exists($subfolder)) { 
							if(!(@mkdir($subfolder,0755))) { 
								if(is_admin()) {
									echo "提示：很抱歉，无法创建缩略图子目录，请手动创建目录".$subfolder."，权限设置为755。<br>"; 
									return; 
								}
							} 
						}
	
						$thumbnail = ta_image_resize($thumbnail, $thumbsize, $height, true, null, $subfolder, 90, $filename); //制作缩略图
						$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, true, $year, $month);
						if ($thumbnail == false)  { $thumbnail =  $thumbnailbackup;} //如果缩略图尺寸过大，只好显示原图
					}
				}
			}
			// 符合视频缩略图
			else if($media!="image"){
				$result = wp_thumbnails_getvideo($thumbnail); 
				
				if($video_link_target=="video") { //点击打开视频
					$bigimage = $result['video'];
				}
				else {
					$bigimage = $result['imageurl'];
				}
				
				if($video_thumb=="video") { //直接显示视频
					$video = $result['video'];
					$videowidth = $thumbsize;
					$videoheight = $height;
					//padding=4，因为图片padding3+border1
					$thumbnail_video = "<embed src=\"$video\" style=\"padding:4px;\"quality=\"high\" width=\"$videowidth\" height=\"$videoheight\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\"></embed>";
					$thumbnail = "";
				}
				else {
					$thumbnail = $result['imageurl'];
				}
			}
			else {
				$seq = $iNumberOfPics; //break
				continue;
			}
			
			
			if($articles == "single") { //全排列缩略图
				
				$single_position = $wp_thumbnails_options['single_position'];
				$single_row_limit = $wp_thumbnails_options['single_row_limit'];
				
				if($seq>0 && $seq%$single_row_limit==0 && $single_position != "none") {
					$output = $output."
					<div class=\"clear-float\"> </div>
					";
				}
				
				$seq++;								
			}
			else {
				$seq = $iNumberOfPics; //break
			}
			
			
			
			//------------- 输出 -----------------//
			
			/** 摘要 **/		
			$outexcerpt = "";
			if($show_excerpt_limit != "") {
				$queried_post = get_post($post_id);
				$post_content = $queried_post->post_content;
				$outexcerpt = wp_thumbnails_excerpt($post_content, false, $show_excerpt_limit, true, "");
			}
			
			/** 标题 **/		
			if ($show_title == "false") {
				$out_title = "";
			}
			else if ($title_pos == "bottom") {
				$out_title = '<br />'.$post_title;
			}
			else if ($title_pos == "top") {
				$out_title = $post_title.'<br />';
			}
			else {
				$out_title = $post_title;
				$pic_interval = 0;
			}
			
			//$out_title = $post_title;
			
			if ($nofollow == "true") $outnofollow = "rel=\"nofollow\"";
			else $outnofollow = "";
			
			if ($link_target == "post") $outlink_target = "href=\"$post_permalink\"";
			else $outlink_target = "href='$bigimage'";
			
			
			if($title_pos == "bottom" || $title_pos == "top")
				$pwidth = $thumbsize + $pic_interval; //图片宽度间距
			$pwidth = "width:".$pwidth."px;";
			$twidth = "width:".$titlewidth."px;";
			
			$show_views = $wp_thumbnails_options['show_views'];
			if($show_title != "false") {
				$out_title = "<a $outlink_target $outnofollow title=\"$post_title\" target=\"$target\">$out_title</a>";
			}
			else { //不显示标题时，规定高度间距
				$outexcerpt = "";
				$pheight = $height + $pic_interval; //图片高度间距
				if($articles == "popular" && $out_title != "" && $show_views == "true") {
					$pheight = $pheight + 14; //保证字体高度
				}
				if($title_pos == "bottom" || $title_pos == "top")
					$pwidth = $pwidth."height:".$pheight."px;";
			}
			
			$out_views = "";
			if($articles == "popular" && $show_views == "true") {
				$views_text = $wp_thumbnails_options['views_text'];
				$out_views = "<br />".$views.$views_text;
			}

			$nowrap = " white-space:nowrap; overflow:hidden; text-overflow:ellipsis; ";
			if($articles == "single" ) { //全排列缩略图
				if($single_position=="left" && $wrap=="true") {
					$pwidth = $pwidth. "float:left; margin:0; padding:0;text-indent:0px;"; //列表内部float left
				}
				else if ($single_position=="right" && $wrap=="true") {
					$pwidth = $pwidth. "float:right; margin:0; padding:0;text-indent:0px;"; //列表内部float right
				}
				else {
					$pwidth = $pwidth. "margin:0; padding:0;"; //不环绕
				}
			}
			else if($wrap == "false") { //使用截断
				$pwidth = $pwidth.$nowrap;
			}
			
			if($thumbnail) {
				$mouse = "onmouseover=\"this.style.backgroundColor='#39b8ff'\" onmouseout=\"this.style.backgroundColor='#FFFFFF'\"";
				$thumbnail = "<img src=\"$thumbnail\" alt=\"$post_title\" width= \"$thumbsize\" height=\"$height\" $mouse />";
			}
			else {
				$thumbnail = $thumbnail_video;
			}
			
			if ($title_pos == "bottom") {
				$output = $output."<p style=\"$pwidth\">
				<a $outlink_target $outnofollow title=\"$post_title\" target=\"$target\">$thumbnail</a>
				$out_title $out_views 
				</p>";
			}
			else if ($title_pos == "top"){
				$output = $output."<p style=\"$pwidth\">
				$out_title $out_views 
				<a $outlink_target $outnofollow title=\"$post_title\" target=\"$target\">$thumbnail</a>
				</p>";
			}   
			else if ($title_pos == "right"){
				$output = $output."<div> <div class=\"ta-entry-left\"> 
    	<div class=\"img_holder\" style=\"$pwidth\"><a $outlink_target $outnofollow title=\"$post_title\" target=\"$target\">$thumbnail</a></div> 
        <div class=\"ta_info\" style=\"$twidth\"> 
            <div class=\"ta_title\" style=\"$twidth\"><a $outlink_target><span>$out_title</span></a></div> 
            <div class=\"ta_text\" style=\"$twidth\">$outexcerpt </div> 
        </div> 
        <div class=\"clear-float\"></div> 
    </div></div>
    ";
			}
			else if ($title_pos == "left"){
				$output = $output."<div> <div class=\"ta-entry-right\"> 
    	<div class=\"img_holder\" style=\"$pwidth\"><a $outlink_target $outnofollow title=\"$post_title\" target=\"$target\">$thumbnail</a></div> 
        <div class=\"ta_info\" style=\"$twidth\"> 
            <div class=\"ta_title\" style=\"$twidth\"><a $outlink_target><span>$out_title</span></a></div> 
            <div class=\"ta_text\"  style=\"$twidth\">$outexcerpt </div> 
        </div> 
        <div class=\"clear-float\"></div> 
    </div></div>
    ";
			}
			
			
			if (($releated_fill == "true") && ($articles == "related") && ($image_count == $image_number) && ($related_num < $number)) {
				$resultset = @mysql_query($sql_related, $wpdb->dbh);
				$image_number = min($extra_num,@mysql_num_rows($resultset));
				$image_count = 0;
				$articles = "none"; //防止死循环
			}
		}//elihw
	}//elihw

	if($articles == "single" ) { 
		
		if($single_position=="left" && $wrap=="true") {
			return "<div id=\"ta-single-list\" style=\"float:left; padding: 2px 8px 2px 2px; \">
			".$output."
			</div>
			"; 
			
		}
		else if($single_position=="right" && $wrap=="true") {
			return "<div id=\"ta-single-list\" style=\"float:right; padding: 2px 0px 2px 8px; \">
			".$output."
			</div>
			"; 
		} 
		else {
			return "<div id=\"ta-post\" class=\"clearfix\" style=\"padding: 2px 0px 2px 8px; \">
			".$output."
			</div>
			"; 
		}
	}
	
	if ($output) {
		if($title_pos == "bottom" || $title_pos == "top") {
			$output = "<div id=\"ta-post\" class=\"clearfix\">
			".$output."
			</div>
			";
		}
	}
	
	return $output;
}

/*
* 兼容旧版本函数
* 功能：随机缩略图、最新缩略图、分类缩略图、相关缩略图、相册缩略图
*/

// 随机缩略图
function thumbnails_anywhere_for_random_posts ($args = ''){
	wp_thumbnails_for_random_posts ($args);
}

// 最新缩略图
function thumbnails_anywhere_for_recent_posts ($args = ''){
	wp_thumbnails_for_recent_posts ($args);
}

// 相关缩略图
function thumbnails_anywhere_for_related_posts ($args = ''){
	wp_thumbnails_for_related_posts ($args);
}

// 最热门日志缩略图
function thumbnails_anywhere_for_popular_posts ($args = ''){
	wp_thumbnails_for_popular_posts ($args);
}

// 分类缩略图
function thumbnails_anywhere_for_category ($args = ''){
	wp_thumbnails_for_category ($args);
}

// 单篇日志缩略图
function thumbnails_anywhere_for_single_post ($args = ''){
	wp_thumbnails_for_single_post ($args);
}


function wp_thumbnails_posts_auto($content){
	$options = get_option('thumbnails_anywhere_options');
	if (is_single()) {
		$output = "";
		if($options["auto_random"]=="true") {
			$thumbnails = wp_thumbnails_for_random_posts('display=false');
			if($thumbnails) {
				$output .= "<div>".$options["auto_random_title"].$thumbnails."</div>";
			}
		}
		
		if ($options["auto_related"]=="true") {
			$thumbnails = wp_thumbnails_for_related_posts('display=false');
			if($thumbnails) {
				$output .= "<div>".$options["auto_related_title"].$thumbnails."</div>";
			}
		}
		
		if ($options["auto_single"]=="true") {
			$thumbnails = wp_thumbnails_for_single_post('display=false&wrap=false');
			if($thumbnails) {
				$output .= "<div>".$options["auto_single_title"].$thumbnails."</div>";
			}
		}
		$content = $content.$output;
	}
	
	return $content;
}

add_filter('the_content', 'wp_thumbnails_posts_auto', 99);

?>