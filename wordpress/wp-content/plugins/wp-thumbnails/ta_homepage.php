<?php

/*
* 功能：首页、分类页、标签页等显示日志缩略图
*/

add_filter("the_excerpt","wp_thumbnails_for_homepage_auto_excerpt");
add_filter("the_content","wp_thumbnails_for_homepage_auto_content");
add_filter('get_the_excerpt', 'wp_thumbnails_excerpt');

function thumbnails_anywhere_for_smart_homepage ($args = '')
{
	wp_thumbnails_for_smart_homepage($args);
}

//根据数量智能选择调用wp_thumbnails_for_homepage还是wp_thumbnails_for_single_post
function wp_thumbnails_for_smart_homepage ($args = '')
{
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	global $wpdb, $post;
	$now = current_time('mysql', 1);
	$album_by_sql = "AND $wpdb->postmeta.meta_value NOT LIKE '%NoPicturesFound%'
									 AND $wpdb->postmeta.meta_value NOT LIKE '%NoMediaFound%'";// 显示缩略图

	if($post->post_status == 'publish' && $post->post_date_gmt < $now ) {
		$sql = "SELECT distinct post_id, meta_value 
		FROM $wpdb->postmeta
		WHERE meta_key IN ('ta-thumbnail')
		AND post_id = $post->ID 
		$album_by_sql
		";
		
		$resultset = @mysql_query($sql, $wpdb->dbh);
		$row = @mysql_fetch_array($resultset);
		$thumbnail = $row['meta_value'];
		$array = explode(";", $thumbnail);
		$iNumberOfPics = count($array)-1; // 图片数量 
		
		//图片太少，只好显示一张大缩略图
		if($iNumberOfPics < $wp_thumbnails_options['number_of_smart_homepage']) {
			$output = wp_thumbnails_for_homepage ($args);
		}
		else {
			$output = wp_thumbnails_for_single_post($args);
		}
		
		$args2 = array();
		$args2['display'] = "true";
		$args = wp_parse_args($args, $args2);
		if($args['display']=="false"){
			return $output;
		}
		else{
			echo $output;
		}
	}
	else return;
}

function wp_thumbnails_for_homepage ($args = '')
{
	$args2 = array();
	$args2['display'] = "true";
	$args = wp_parse_args($args, $args2);	
	$output = get_wp_thumbnails_for_homepage($args);
	if($args['display']=="false") {
		return $output;
	}
	else{
		echo $output;
	}
}

function thumbnails_anywhere_for_homepage ($args = '')
{
	wp_thumbnails_for_homepage($args);
}

function wp_thumbnails_for_homepage_auto_content($excerpt)
{
	$auto = get_option('thumbnails_anywhere_options');
	if((is_home() && $auto['auto_home']=="true") 
	|| (is_category() && $auto['auto_category']=="true")
	|| (is_search() && $auto['auto_search']=="true")
	|| (is_tag() && $auto['auto_tag_page']=="true")){
		
		$style="single";
		if(is_home()) $style=$auto['auto_home_style'];
		else if(is_category()) $style=$auto['auto_cat_style'];
		else if(is_tag()) $style=$auto['auto_tag_style'];
		else if(is_search()) $style=$auto['auto_search_style'];
		
		if($style=="multiple") {
			$thumbnail = wp_thumbnails_for_single_post('display=false');
		}
		else if($style=="smart") {
			$thumbnail = wp_thumbnails_for_smart_homepage('display=false');
		}
		else {
			$thumbnail = wp_thumbnails_for_homepage('display=false');
		}
		
		//当前的 filter 也就是 $wp_current_filter 数组中的最后一个，end() 操作数组指针返回数组中最后一个值。
		//global $wp_current_filter;
		//echo "current filters in auto_content: ".end( $wp_current_filter )."<br>";
		if($auto['auto_excerpt']=="true") {
			$excerpt = wp_thumbnails_excerpt($excerpt);
		}
				
		return $thumbnail.$excerpt;
		
	}
	else {
		return $excerpt;
	}
}	

function wp_thumbnails_for_homepage_auto_excerpt($excerpt)
{
	$auto = get_option('thumbnails_anywhere_options');
	if((is_home() && $auto['auto_home']=="true") 
	|| (is_category() && $auto['auto_category']=="true")
	|| (is_search() && $auto['auto_search']=="true")
	|| (is_tag() && $auto['auto_tag_page']=="true")){
		
		$style="single";
		if(is_home()) $style=$auto['auto_home_style'];
		else if(is_category()) $style=$auto['auto_cat_style'];
		else if(is_tag()) $style=$auto['auto_tag_style'];
		else if(is_search()) $style=$auto['auto_search_style'];
		
		if($style=="multiple") {
			$thumbnail = wp_thumbnails_for_single_post('display=false');
		}
		else if($style=="smart") {
			$thumbnail = wp_thumbnails_for_smart_homepage('display=false');

		}
		else {
			$thumbnail = wp_thumbnails_for_homepage('display=false');
		}
		
		//当前的 filter 也就是 $wp_current_filter 数组中的最后一个，end() 操作数组指针返回数组中最后一个值。
		//global $wp_current_filter;
		//echo "current filters in auto_excerpt: ".end( $wp_current_filter )."<br>";
		
		return $thumbnail.$excerpt;
		
	}
	else {
		return $excerpt;
	}
}	

function get_wp_thumbnails_for_homepage ($args = '')
{
	// 如果调用时没有指定参数，则采用后台设置的参数; 否则采用函数自身的默认参数。
	$wp_thumbnails_options = get_option('thumbnails_anywhere_options');
	
	//只允许传递参数：宽、高、位置、裁剪
	if(!empty($args)) {
		$args2 = array();	  
	  $args2['width'] 		= $wp_thumbnails_options['width_of_home_images'];
	  $args2['height']		= $wp_thumbnails_options['height_of_home_images'];
	  $args2['position']	= $wp_thumbnails_options['homepage_position'];
	  $args2['crop'] 			= $wp_thumbnails_options['crop_home_images'];
	
		$args = wp_parse_args($args, $args2);//合并参数，若args中无某参数，则以默认参数args2代替
		$post_preview_id = $args['id'];
				
		$wp_thumbnails_options['width_of_home_images'] 	= $args['width'];
	  $wp_thumbnails_options['height_of_home_images'] = $args['height'];
	  $wp_thumbnails_options['homepage_position'] 		= $args['position'];
	  $wp_thumbnails_options['crop_home_images'] 			= $args['crop'];
	}
	
	$thumbsize 				= $wp_thumbnails_options['width_of_home_images'];
	$height 					= $wp_thumbnails_options['height_of_home_images'];
	$position 				= $wp_thumbnails_options['homepage_position'];
	$target 					= $wp_thumbnails_options['homepage_new_window'];
	$defaultimage 		= $wp_thumbnails_options['homepage_default_image'];
	$crop 						= $wp_thumbnails_options['crop_home_images'];
	$disable_external = $wp_thumbnails_options['disable_external'];
	$rand_pic_from 		= $wp_thumbnails_options['rand_pic_from'];//表示每篇日志选第一张图片还是任选一张图片
	$permalink 				= $wp_thumbnails_options['homepage_link_target'];
	$media 						= $wp_thumbnails_options['media_page'];
	$video_thumb 			= $wp_thumbnails_options['video_thumb'];
	$video_link_target= $wp_thumbnails_options['video_link_target'];
	
	if($crop == "crop") $crop = true;
	else $crop = false;
			
	global $thumbDir, $downloadDir, $siteurl, $rooturl, $destpath, $downloadpath, $wpdb; //申明全局变量
	
	if($post_preview_id) { //后台预览
		$post = get_post($post_preview_id);
		$post_id = $post_preview_id;
	}
	else {
		global $post;
		$post_id = $post->ID;
	}
	
	
	//获取年、月
	global $wpdb,$post; // 访问数据库
	$now = current_time('mysql', 1);
	$the_post_date = $wpdb->get_row("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) AS year 
		FROM $wpdb->posts 
		WHERE ID = $post_id
		AND post_status = 'publish' 
		AND post_date_gmt < '$now' 
		LIMIT 1");
	$year = $the_post_date->year;
	$month = $the_post_date->month;
	
	//---------- 从自定义域中获取图片地址
	$thumbnail = get_post_meta($post_id, "ta-thumbnail", true);
	if ($thumbnail == ''  or strstr($thumbnail,'NoPicturesFound')) { //这篇日志还没有扫描过
		update_wp_thumbnails_meta($post); // 自动填充自定义域
		$thumbnail = get_post_meta($post_id, "ta-thumbnail", true);
	}
	if ($thumbnail == '') { return; }
	
	
	if ($thumbnail == 'NoMediaFound') { //无图片
		if ($defaultimage == "") return;
		$thumbnail = $defaultimage;
	}
	else {	
		$array = explode(";", $thumbnail);
		$iNumberOfPics = count($array)-1; // 图片数量 
		if ($rand_pic_from == "first") { 	//选取第一张图片
			$seq = 0;
		}
		else {  //随机选取图片
			$seq = mt_rand(0,$iNumberOfPics-1);		
		}
		$thumbnail = $array[$seq];
	}		
	
	$original = $thumbnail; //原始大图
	
	//---------- 对图片地址进一步解析，得到缩略图地址
	
	// 符合picasa图片条件
	if (strstr($thumbnail,'thumbnails_anywhere_picasa')) {
		if ($crop == true) {
		  if ($thumbsize <= 32)  $realsize = 32; 
		  else if ($thumbsize <= 48)  $realsize = 48;
		  else if ($thumbsize <= 64)  $realsize = 64;
		  else if ($thumbsize <= 72)  $realsize = 72;
		  else if ($thumbsize <= 144)  $realsize = 144;
		  else if ($thumbsize <= 160)  $realsize = 160;
		  else $realsize = 160;

		  $realsize = '?imgmax='.$realsize.'&crop=1';
		}
		else {
		  if ($thumbsize <= 32)  $realsize = 32; 
		  else if ($thumbsize <= 48)  $realsize = 48;
		  else if ($thumbsize <= 64)  $realsize = 64;
		  else if ($thumbsize <= 72)  $realsize = 72;
		  else if ($thumbsize <= 144)  $realsize = 144;
		  else if ($thumbsize <= 160)  $realsize = 160;
		  else if ($thumbsize <= 200)  $realsize = 200;
		  else if ($thumbsize <= 288)  $realsize = 288;
		  else if ($thumbsize <= 320)  $realsize = 320;
		  else if ($thumbsize <= 400)  $realsize = 400;
		  else if ($thumbsize <= 512)  $realsize = 512;
		  else if ($thumbsize <= 576)  $realsize = 576;
		  else if ($thumbsize <= 640)  $realsize = 640;
		  else if ($thumbsize <= 720)  $realsize = 720;
		  else if ($thumbsize <= 800)  $realsize = 800;
		  else if ($thumbsize <= 912)  $realsize = 912;
		  else if ($thumbsize <= 1024)  $realsize = 1024;
		  else if ($thumbsize <= 1152)  $realsize = 1152;
		  else if ($thumbsize <= 1280)  $realsize = 1280;
		  else if ($thumbsize <= 1440)  $realsize = 1440;
		  else if ($thumbsize <= 1600)  $realsize = 1600;
		  else $realsize = 1600;

		  $realsize = '?imgmax='.$realsize;
		}
		
		$original = str_replace("thumbnails_anywhere_picasa", "800", $thumbnail); //原始大图
		$thumbnail = str_replace("thumbnails_anywhere_picasa", $realsize, $thumbnail);
	}
	// 符合picasa图片条件
	else if  (strstr($thumbnail,'picasa_thumbnails_anywhere')) {
		if ($crop == true) {
		  if ($thumbsize <= 32)  $realsize = 32; 
		  else if ($thumbsize <= 48)  $realsize = 48;
		  else if ($thumbsize <= 64)  $realsize = 64;
		  else if ($thumbsize <= 72)  $realsize = 72;
		  else if ($thumbsize <= 144)  $realsize = 144;
		  else if ($thumbsize <= 160)  $realsize = 160;
		  else if ($thumbsize <= 200)  $realsize = 200;
		  else if ($thumbsize <= 288)  $realsize = 288;
		  else if ($thumbsize <= 320)  $realsize = 320;
		  else if ($thumbsize <= 400)  $realsize = 400;
		  else if ($thumbsize <= 512)  $realsize = 512;
		  else if ($thumbsize <= 576)  $realsize = 576;
		  else if ($thumbsize <= 640)  $realsize = 640;
		  else if ($thumbsize <= 720)  $realsize = 720;
		  else if ($thumbsize <= 800)  $realsize = 800;
		  else $realsize = 800;
		  
		  if ($thumbsize > 160) $realsize = 's'.$realsize;
		  else $realsize = 's'.$realsize.'-c';
		}
		else {
		  if ($thumbsize <= 32)  $realsize = 32; 
		  else if ($thumbsize <= 48)  $realsize = 48;
		  else if ($thumbsize <= 64)  $realsize = 64;
		  else if ($thumbsize <= 72)  $realsize = 72;
		  else if ($thumbsize <= 144)  $realsize = 144;
		  else if ($thumbsize <= 160)  $realsize = 160;
		  else if ($thumbsize <= 200)  $realsize = 200;
		  else if ($thumbsize <= 288)  $realsize = 288;
		  else if ($thumbsize <= 320)  $realsize = 320;
		  else if ($thumbsize <= 400)  $realsize = 400;
		  else if ($thumbsize <= 512)  $realsize = 512;
		  else if ($thumbsize <= 576)  $realsize = 576;
		  else if ($thumbsize <= 640)  $realsize = 640;
		  else if ($thumbsize <= 720)  $realsize = 720;
		  else if ($thumbsize <= 800)  $realsize = 800;
		  else $realsize = 800;

		  $realsize = 's'.$realsize;
		}
		
		$original = str_replace("picasa_thumbnails_anywhere", "800", $thumbnail); //原始大图
		$thumbnail = str_replace("picasa_thumbnails_anywhere", $realsize, $thumbnail);
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
		$original = str_replace("yupoo_thumbnails_anywhere", "big", $thumbnail); //原始大图
		$thumbnail = str_replace("yupoo_thumbnails_anywhere", $type, $thumbnail);
	}
	// 符合flickr图片条件
	else if  (strstr($thumbnail,'flickr_thumbnails_anywhere')) {
		if ($thumbsize <= 75)  $type = "_s"; 
		else if ($thumbsize <= 100)  $type = "_t";
		else if ($thumbsize <= 240)  $type = "_m";
		else if ($thumbsize <= 500)  $type = "";
		else $type = "";
		// 1024大图和原图无法获取
		$original = str_replace("flickr_thumbnails_anywhere", "_m", $thumbnail); //原始大图
		$thumbnail = str_replace("flickr_thumbnails_anywhere", $type, $thumbnail);
	}
	else if ($thumbnail == $defaultimage) { //默认图片单独处理
		$original = $thumbnail;
		$filename = "default";
		$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, $crop, $year, $month);
		if ($thumbnail == false) { //如果图片不存在
			$thumbnail = $original;
			$thumbnail = str_replace($siteurl,$rooturl,$thumbnail);//转换为绝对路径	
			
			//将图片按照规格存放在不同子目录
			if ($crop == true) $subfolder = "{$thumbsize}x{$height}-c/";
			else $subfolder = "{$thumbsize}x{$height}-u/";
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
				
			$thumbnail = ta_image_resize($thumbnail, $thumbsize, $height, $crop, null, $subfolder, 90, $filename); //制作缩略图
			$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, $crop, $year, $month);
			if ($thumbnail == false)  { $thumbnail =  $original;} //如果缩略图尺寸过大，只好显示原图
		}
	}
	else if(!strstr($thumbnail,'ta_video_')){ //普通图片(非视频)
		if (!strstr($thumbnail,$siteurl)) { // 图片
			if ($disable_external == "true") { //禁止显示外链图片
				return;
			} 
		}
		else if (strstr($thumbnail,$siteurl) or ($disable_external == "false")) {//本地图片或允许外链图片
			$original = $thumbnail;
			$filename = $post_id."-".($seq+1);
			$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, $crop, $year, $month);
			if ($thumbnail == false) { //如果图片不存在
				$thumbnail = $original;
				$thumbnail = str_replace($siteurl,$rooturl,$thumbnail);//转换为绝对路径	
				
				//将图片按照规格存放在不同子目录
				if ($crop == true) $subfolder = "{$thumbsize}x{$height}-c/";
				else $subfolder = "{$thumbsize}x{$height}-u/";
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
			
				$thumbnail = ta_image_resize($thumbnail, $thumbsize, $height, $crop, null, $subfolder, 90, $filename); //制作缩略图
				$thumbnail = ta_is_thumb_exist($filename, $thumbsize, $height, $crop, $year, $month);

				if ($thumbnail == false)  { $thumbnail =  $original;} //如果缩略图尺寸过大，只好显示原图
			}
		}
	}
	// 符合视频缩略图
	else if($media!="image"){
		
		$result = wp_thumbnails_getvideo($thumbnail); 
		$thumbnail = $result['imageurl'];

		if($video_link_target=="video") { //点击打开视频
			$permalink = $result['video'];
		}
		if($video_thumb=="video") { //直接显示视频
			$video = $result['video'];
			$thumbnail_video = "<embed src=\"$video\" quality=\"high\" width=\"$thumbsize\" height=\"$height\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\"></embed>";
			$thumbnail = "";
		}
	}
	else {
		return;
	}
	
	//控制显示位置
	if ($position == "random") {
		if (mt_rand(1,100) < 50) $css = "ta-pageimg-left";
		else $css = "ta-pageimg-right";
	}
	else $css = "ta-pageimg-".$position;
	
	$title=$post->post_title;

	if($permalink=="post") {
		$permalink = get_permalink($post_id);
	} 
	else if($permalink=="image") {
		$permalink = $original;
	}
	
	$mouse = "onmouseover=\"this.style.backgroundColor='#39b8ff'\" onmouseout=\"this.style.backgroundColor='#FFFFFF'\"";
	
	if($thumbnail) {
		$output="<div class=\"$css\">
		<a href=\"$permalink\"  rel=\"nofollow\"  title=\"$title\" target=\"$target\"><img alt=\"$title\" width=\"$thumbsize\" src=\"$thumbnail\" $mouse /></a>
		</div>";
	}
	else { //视频缩略图
		$output="<div class=\"$css\">".$thumbnail_video."</div>";
	}

	return $output; // 显示日志中的第一张图片 
}

?>