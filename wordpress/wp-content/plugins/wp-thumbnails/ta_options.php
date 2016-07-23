<?php

/*
* 后台设置
*/

function wp_thumbnails_add_page()
{ 
    //在后台options添加wp_thumbnails, 使用函数wp_thumbnails_configuration_page生成后台设置页面。
    add_options_page('wp_thumbnails', __('WP-Thumbnails','wp_thumbnails'), 8, __FILE__, 'wp_thumbnails_configuration_page');
}
add_action('admin_menu', 'wp_thumbnails_add_page');

// 辅助函数, 设置默认参数值并返回。
function get_wp_thumbnails_options()
{
    $wp_thumbnails_options = get_option('thumbnails_anywhere_options');

    // 初始化默认参数, 如果还没初始化的话。 
    if (!isset($wp_thumbnails_options["number_of_smart_homepage"])) {
        $wp_thumbnails_options["number_of_smart_homepage"] = 4;
    }
    
    if (!isset($wp_thumbnails_options["disable_external"])) {
        $wp_thumbnails_options["disable_external"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["picasa"])) {
        $wp_thumbnails_options["picasa"] = "external";
    }
    
    if (!isset($wp_thumbnails_options["yupoo"])) {
        $wp_thumbnails_options["yupoo"] = "external";
    }
    
    if (!isset($wp_thumbnails_options["flickr"])) {
        $wp_thumbnails_options["flickr"] = "external";
    }

    if (!isset($wp_thumbnails_options["limit"])) {
        $wp_thumbnails_options["limit"] = 10;
    }
    
    if (!isset($wp_thumbnails_options["releated_fill"])) {
        $wp_thumbnails_options["releated_fill"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["rand_pic_from"])) {
        $wp_thumbnails_options["rand_pic_from"] = "all";
    }
         
    if (!isset($wp_thumbnails_options["homepage_position"])) {
        $wp_thumbnails_options["homepage_position"] = "right";
    }
    
    if (!isset($wp_thumbnails_options["single_position"])) {
        $wp_thumbnails_options["single_position"] = "none";
    }

    if (!isset($wp_thumbnails_options["tag_position"])) {
        $wp_thumbnails_options["tag_position"] = "none";
    }

    if (!isset($wp_thumbnails_options["homepage_link_target"])) {
        $wp_thumbnails_options["homepage_link_target"] = "post";
    }
          
    if (!isset($wp_thumbnails_options["random_link_target"])) {
        $wp_thumbnails_options["random_link_target"] = "post";
    }

    if (!isset($wp_thumbnails_options["recent_link_target"])) {
        $wp_thumbnails_options["recent_link_target"] = "post";
    }
    
    if (!isset($wp_thumbnails_options["album_link_target"])) {
        $wp_thumbnails_options["album_link_target"] = "image";
    }
    
    if (!isset($wp_thumbnails_options["category_link_target"])) {
        $wp_thumbnails_options["category_link_target"] = "post";
    }

    if (!isset($wp_thumbnails_options["related_link_target"])) {
        $wp_thumbnails_options["related_link_target"] = "post";
    }

    if (!isset($wp_thumbnails_options["single_link_target"])) {
        $wp_thumbnails_options["single_link_target"] = "post";
    }

    if (!isset($wp_thumbnails_options["popular_link_target"])) {
        $wp_thumbnails_options["popular_link_target"] = "post";
    }

    if (!isset($wp_thumbnails_options["tag_link_target"])) {
        $wp_thumbnails_options["tag_link_target"] = "post";
    }
              
    if (!isset($wp_thumbnails_options["random_pic_interval"])) {
        $wp_thumbnails_options["random_pic_interval"] = 15;
    }

    if (!isset($wp_thumbnails_options["recent_pic_interval"])) {
        $wp_thumbnails_options["recent_pic_interval"] = 15;
    }
    
    if (!isset($wp_thumbnails_options["album_pic_interval"])) {
        $wp_thumbnails_options["album_pic_interval"] = 15;
    }
    
    if (!isset($wp_thumbnails_options["category_pic_interval"])) {
        $wp_thumbnails_options["category_pic_interval"] = 15;
    }

    if (!isset($wp_thumbnails_options["related_pic_interval"])) {
        $wp_thumbnails_options["related_pic_interval"] = 15;
    }
    
    if (!isset($wp_thumbnails_options["single_pic_interval"])) {
        $wp_thumbnails_options["single_pic_interval"] = 15;
    }

    if (!isset($wp_thumbnails_options["popular_pic_interval"])) {
        $wp_thumbnails_options["popular_pic_interval"] = 15;
    }

    if (!isset($wp_thumbnails_options["tag_pic_interval"])) {
        $wp_thumbnails_options["tag_pic_interval"] = 15;
    }
            
    if (!isset($wp_thumbnails_options["random_title_pos"])) {
        $wp_thumbnails_options["random_title_pos"] = "bottom";
    }

    if (!isset($wp_thumbnails_options["recent_title_pos"])) {
        $wp_thumbnails_options["recent_title_pos"] = "bottom";
    }
    
    if (!isset($wp_thumbnails_options["album_title_pos"])) {
        $wp_thumbnails_options["album_title_pos"] = "bottom";
    }
    
    if (!isset($wp_thumbnails_options["category_title_pos"])) {
        $wp_thumbnails_options["category_title_pos"] = "bottom";
    }

    if (!isset($wp_thumbnails_options["related_title_pos"])) {
        $wp_thumbnails_options["related_title_pos"] = "bottom";
    }

    if (!isset($wp_thumbnails_options["popular_title_pos"])) {
        $wp_thumbnails_options["popular_title_pos"] = "bottom";
    }

    if (!isset($wp_thumbnails_options["tag_title_pos"])) {
        $wp_thumbnails_options["tag_title_pos"] = "bottom";
    }
            
    if (!isset($wp_thumbnails_options["homepage_new_window"])) {
        $wp_thumbnails_options["homepage_new_window"] = "_blank";
    }
    
    if (!isset($wp_thumbnails_options["random_new_window"])) {
        $wp_thumbnails_options["random_new_window"] = "_self";
    }

    if (!isset($wp_thumbnails_options["recent_new_window"])) {
        $wp_thumbnails_options["recent_new_window"] = "_self";
    }
    
    if (!isset($wp_thumbnails_options["album_new_window"])) {
        $wp_thumbnails_options["album_new_window"] = "_blank";
    }
    
    if (!isset($wp_thumbnails_options["category_new_window"])) {
        $wp_thumbnails_options["category_new_window"] = "_blank";
    }

    if (!isset($wp_thumbnails_options["related_new_window"])) {
        $wp_thumbnails_options["related_new_window"] = "_blank";
    }
    
    if (!isset($wp_thumbnails_options["single_new_window"])) {
        $wp_thumbnails_options["single_new_window"] = "_blank";
    }
    
    if (!isset($wp_thumbnails_options["popular_new_window"])) {
        $wp_thumbnails_options["popular_new_window"] = "_blank";
    }  

    if (!isset($wp_thumbnails_options["tag_new_window"])) {
        $wp_thumbnails_options["tag_new_window"] = "_blank";
    }
                  
    if (!isset($wp_thumbnails_options["number_of_random_images"])) {
        $wp_thumbnails_options["number_of_random_images"] = 6;
    }

    if (!isset($wp_thumbnails_options["number_of_recent_images"])) {
        $wp_thumbnails_options["number_of_recent_images"] = 6;
    }
    
    if (!isset($wp_thumbnails_options["number_of_album_images"])) {
        $wp_thumbnails_options["number_of_album_images"] = 40;
    }

    if (!isset($wp_thumbnails_options["number_of_category_images"])) {
        $wp_thumbnails_options["number_of_category_images"] = 8;
    }
    
    if (!isset($wp_thumbnails_options["number_of_related_images"])) {
        $wp_thumbnails_options["number_of_related_images"] = 8;
    }   
 
    if (!isset($wp_thumbnails_options["number_of_single_images"])) {
        $wp_thumbnails_options["number_of_single_images"] = $wp_thumbnails_options["limit"];
    }   

    if (!isset($wp_thumbnails_options["number_of_popular_images"])) {
        $wp_thumbnails_options["number_of_popular_images"] = 8;
    }
    
    if (!isset($wp_thumbnails_options["single_row_limit"])) {
        $wp_thumbnails_options["single_row_limit"] = $wp_thumbnails_options["number_of_single_images"];
    }  

    if (!isset($wp_thumbnails_options["number_of_tag_images"])) {
        $wp_thumbnails_options["number_of_tag_images"] = 8;
    }
                  
    if (!isset($wp_thumbnails_options["width_of_home_images"])) {
        $wp_thumbnails_options["width_of_home_images"] = 120;
    }

    if (!isset($wp_thumbnails_options["height_of_home_images"])) {
        $wp_thumbnails_options["height_of_home_images"] = 100;
    }
    
    if (!isset($wp_thumbnails_options["crop_home_images"])) {
        $wp_thumbnails_options["crop_home_images"] = "crop";
    }

    if (!isset($wp_thumbnails_options["crop_random_images"])) {
        $wp_thumbnails_options["crop_random_images"] = "crop";
    }    

    if (!isset($wp_thumbnails_options["crop_recent_images"])) {
        $wp_thumbnails_options["crop_recent_images"] = "crop";
    }   

    if (!isset($wp_thumbnails_options["crop_album_images"])) {
        $wp_thumbnails_options["crop_album_images"] = "crop";
    }

    if (!isset($wp_thumbnails_options["crop_related_images"])) {
        $wp_thumbnails_options["crop_related_images"] = "crop";
    }    

    if (!isset($wp_thumbnails_options["crop_category_images"])) {
        $wp_thumbnails_options["crop_category_images"] = "crop";
    }  

    if (!isset($wp_thumbnails_options["crop_popular_images"])) {
        $wp_thumbnails_options["crop_popular_images"] = "crop";
    }  

    if (!isset($wp_thumbnails_options["crop_tag_images"])) {
        $wp_thumbnails_options["crop_tag_images"] = "crop";
    }  
            
   	if (!isset($wp_thumbnails_options["width_of_random_images"])) {
        $wp_thumbnails_options["width_of_random_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_random_images"])) {
        $wp_thumbnails_options["height_of_random_images"] = 75;
    }
    
    if (!isset($wp_thumbnails_options["width_of_recent_images"])) {
        $wp_thumbnails_options["width_of_recent_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_recent_images"])) {
        $wp_thumbnails_options["height_of_recent_images"] = 75;
    }
    
    if (!isset($wp_thumbnails_options["width_of_album_images"])) {
        $wp_thumbnails_options["width_of_album_images"] = 120;
    }

    if (!isset($wp_thumbnails_options["height_of_album_images"])) {
        $wp_thumbnails_options["height_of_album_images"] = 80;
    }
  
    if (!isset($wp_thumbnails_options["width_of_category_images"])) {
        $wp_thumbnails_options["width_of_category_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_category_images"])) {
        $wp_thumbnails_options["height_of_category_images"] = 75;
    }
    
    if (!isset($wp_thumbnails_options["width_of_related_images"])) {
        $wp_thumbnails_options["width_of_related_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_related_images"])) {
        $wp_thumbnails_options["height_of_related_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["width_of_single_images"])) {
        $wp_thumbnails_options["width_of_single_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_single_images"])) {
        $wp_thumbnails_options["height_of_single_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["width_of_popular_images"])) {
        $wp_thumbnails_options["width_of_popular_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_popular_images"])) {
        $wp_thumbnails_options["height_of_popular_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["width_of_tag_images"])) {
        $wp_thumbnails_options["width_of_tag_images"] = 75;
    }

    if (!isset($wp_thumbnails_options["height_of_tag_images"])) {
        $wp_thumbnails_options["height_of_tag_images"] = 75;
    }
                          
    if (!isset($wp_thumbnails_options["homepage_default_image"])) {
        $wp_thumbnails_options["homepage_default_image"] = "";
    }

    if (!isset($wp_thumbnails_options["web_album"])) {
        $wp_thumbnails_options["web_album"] = "";
    }
    
    if (!isset($wp_thumbnails_options["category_filter_way"])) {
        $wp_thumbnails_options["category_filter_way"] = "none";
    }

    if (!isset($wp_thumbnails_options["category_filter"])) {
        $wp_thumbnails_options["category_filter"] = array();
    }
    if (!isset($wp_thumbnails_options["tags_filter"])) {
        $wp_thumbnails_options["tags_filter"] = "";
    }
    
    if (!isset($wp_thumbnails_options["random_show_title"])) {
        $wp_thumbnails_options["random_show_title"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["recent_show_title"])) {
        $wp_thumbnails_options["recent_show_title"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["album_show_title"])) {
        $wp_thumbnails_options["album_show_title"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["category_show_title"])) {
        $wp_thumbnails_options["category_show_title"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["related_show_title"])) {
        $wp_thumbnails_options["related_show_title"] = "true";
    }

    if (!isset($wp_thumbnails_options["popular_show_title"])) {
        $wp_thumbnails_options["popular_show_title"] = "true";
    }

    if (!isset($wp_thumbnails_options["tag_show_title"])) {
        $wp_thumbnails_options["tag_show_title"] = "true";
    }
            
    if (!isset($wp_thumbnails_options["random_wrap"])) {
        $wp_thumbnails_options["random_wrap"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["recent_wrap"])) {
        $wp_thumbnails_options["recent_wrap"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["category_wrap"])) {
        $wp_thumbnails_options["category_wrap"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["album_wrap"])) {
        $wp_thumbnails_options["album_wrap"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["related_wrap"])) {
        $wp_thumbnails_options["related_wrap"] = "false";
    }

    if (!isset($wp_thumbnails_options["popular_wrap"])) {
        $wp_thumbnails_options["popular_wrap"] = "false";
    }

    if (!isset($wp_thumbnails_options["tag_wrap"])) {
        $wp_thumbnails_options["tag_wrap"] = "false";
    }
            
    if (!isset($wp_thumbnails_options["homepage_nofollow"])) {
        $wp_thumbnails_options["homepage_nofollow"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["random_nofollow"])) {
        $wp_thumbnails_options["random_nofollow"] = "false";
    }

    if (!isset($wp_thumbnails_options["recent_nofollow"])) {
        $wp_thumbnails_options["recent_nofollow"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["album_nofollow"])) {
        $wp_thumbnails_options["album_nofollow"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["category_nofollow"])) {
        $wp_thumbnails_options["category_nofollow"] = "false";
    }  
    
    if (!isset($wp_thumbnails_options["related_nofollow"])) {
        $wp_thumbnails_options["related_nofollow"] = "false";
    }  

    if (!isset($wp_thumbnails_options["popular_nofollow"])) {
        $wp_thumbnails_options["popular_nofollow"] = "false";
    } 

    if (!isset($wp_thumbnails_options["tag_nofollow"])) {
        $wp_thumbnails_options["tag_nofollow"] = "false";
    } 
            
    if (!isset($wp_thumbnails_options["show_page"])) {
        $wp_thumbnails_options["show_page"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["related_style"])) {
        $wp_thumbnails_options["related_style"] = "linkwithin";
    }

    if (!isset($wp_thumbnails_options["show_views"])) {
        $wp_thumbnails_options["show_views"] = "true";
    }

    if (!isset($wp_thumbnails_options["views_text"])) {
        $wp_thumbnails_options["views_text"] = " views";
    }

    if (!isset($wp_thumbnails_options["auto_home"])) {
        $wp_thumbnails_options["auto_home"] = "true";
    }
    
    if (!isset($wp_thumbnails_options["auto_category"])) {
        $wp_thumbnails_options["auto_category"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["auto_search"])) {
        $wp_thumbnails_options["auto_search"] = "false";
    }

    if (!isset($wp_thumbnails_options["auto_tag_page"])) {
        $wp_thumbnails_options["auto_tag_page"] = "false";
    }

    if (!isset($wp_thumbnails_options["auto_home_style"])) {
        $wp_thumbnails_options["auto_home_style"] = "single";
    }
    
    if (!isset($wp_thumbnails_options["auto_cat_style"])) {
        $wp_thumbnails_options["auto_cate_style"] = "single";
    }
    
    if (!isset($wp_thumbnails_options["auto_search_style"])) {
        $wp_thumbnails_options["auto_search_style"] = "single";
    }

    if (!isset($wp_thumbnails_options["auto_tag_style"])) {
        $wp_thumbnails_options["auto_tag_style"] = "single";
    }

    if (!isset($wp_thumbnails_options["auto_random"])) {
        $wp_thumbnails_options["auto_random"] = "false";
    }
    
    if (!isset($wp_thumbnails_options["auto_related"])) {
        $wp_thumbnails_options["auto_related"] = "false";
    }

    if (!isset($wp_thumbnails_options["auto_single"])) {
        $wp_thumbnails_options["auto_single"] = "false";
    }

    if (!isset($wp_thumbnails_options["auto_random_title"])) {
        $wp_thumbnails_options["auto_random_title"] = __('<h3>随机文章</h3>');
    }

    if (!isset($wp_thumbnails_options["auto_related_title"])) {
        $wp_thumbnails_options["auto_related_title"] = __('<h3>相关文章</h3>');
    }
    
    if (!isset($wp_thumbnails_options["auto_single_title"])) {
        $wp_thumbnails_options["auto_single_title"] = "";
    }

    if (!isset($wp_thumbnails_options["auto_excerpt"])) {
        $wp_thumbnails_options["auto_excerpt"] = "true";
    }

    if (!isset($wp_thumbnails_options["auto_excerpt_length"])) {
        $wp_thumbnails_options["auto_excerpt_length"] = 150;
    }
    
    if (!isset($wp_thumbnails_options["auto_excerpt_tail"])) {
        $wp_thumbnails_options["auto_excerpt_tail"] = "[Read More…]";
    }

    //if (!isset($wp_thumbnails_options["auto_recheck"])) {
        //$wp_thumbnails_options["auto_recheck"] = "false";
    //}
    
    if (!isset($wp_thumbnails_options["auto_replace"])) {
        $wp_thumbnails_options["auto_replace"] = "false";
    }
 
    if (!isset($wp_thumbnails_options["auto_replace_exception"])) {
        $wp_thumbnails_options["auto_replace_exception"] = "";
    }
           
    if (!isset($wp_thumbnails_options["related_order"])) {
        $wp_thumbnails_options["related_order"] = "random";
    }

    if (!isset($wp_thumbnails_options["category_order"])) {
        $wp_thumbnails_options["category_order"] = "random";
    }
    
    if (!isset($wp_thumbnails_options["tag_order"])) {
        $wp_thumbnails_options["tag_order"] = "random";
    }
         
    if (!isset($wp_thumbnails_options["media_page"])) { //页面缩略图显示视频
        $wp_thumbnails_options["media_page"] = "both";
    }

    if (!isset($wp_thumbnails_options["media_post"])) { //文章缩略图不显示视频
        $wp_thumbnails_options["media_post"] = "image";
    }

    if (!isset($wp_thumbnails_options["video_thumb"])) {
        $wp_thumbnails_options["video_thumb"] = "image";
    }
    
    if (!isset($wp_thumbnails_options["video_link_target"])) {
        $wp_thumbnails_options["video_link_target"] = "post";
    }
    
    if (!isset($wp_thumbnails_options["random_excerpt_limit"])) {
        $wp_thumbnails_options["random_excerpt_limit"] = "";
    }

    if (!isset($wp_thumbnails_options["recent_excerpt_limit"])) {
        $wp_thumbnails_options["recent_excerpt_limit"] = "";
    }

    if (!isset($wp_thumbnails_options["popular_excerpt_limit"])) {
        $wp_thumbnails_options["popular_excerpt_limit"] = "";
    }

    if (!isset($wp_thumbnails_options["related_excerpt_limit"])) {
        $wp_thumbnails_options["related_excerpt_limit"] = "";
    }

    if (!isset($wp_thumbnails_options["category_excerpt_limit"])) {
        $wp_thumbnails_options["category_excerpt_limit"] = "";
    }

    if (!isset($wp_thumbnails_options["tag_excerpt_limit"])) {
        $wp_thumbnails_options["tag_excerpt_limit"] = "";
    }
         
    if (!isset($wp_thumbnails_options["random_title_width"])) {
        $wp_thumbnails_options["random_title_width"] = "";
    }

    if (!isset($wp_thumbnails_options["recent_title_width"])) {
        $wp_thumbnails_options["recent_title_width"] = "";
    }

    if (!isset($wp_thumbnails_options["popular_title_width"])) {
        $wp_thumbnails_options["popular_title_width"] = "";
    }

    if (!isset($wp_thumbnails_options["related_title_width"])) {
        $wp_thumbnails_options["related_title_width"] = "";
    }

    if (!isset($wp_thumbnails_options["category_title_width"])) {
        $wp_thumbnails_options["category_title_width"] = "";
    }

    if (!isset($wp_thumbnails_options["tag_title_width"])) {
        $wp_thumbnails_options["tag_title_width"] = "";
    }
                                                    
    // 添加到数据库表项; 如果已经存在, 则什么也不做。
    add_option('thumbnails_anywhere_options', $wp_thumbnails_options);
    return $wp_thumbnails_options;
}

// 生成后台设置页面
function wp_thumbnails_configuration_page()
{
    $wp_thumbnails_options = get_wp_thumbnails_options();
    
    // 如果表单已经提交, 则清理。($_POST为PHP超全局变量(PHP版本 >= 5.0))
    if (isset($_POST['clean']))
    {
    	ta_clean(); 
    }
    
    if (isset($_POST['clean-custom-fields']))
    {
    	ta_clean_custom_fields(); 
    }
    
    if (isset($_POST['clean-download']))
    {
    	ta_clean_download(); 
    }
    
    if (isset($_POST['clean-old-thumbnails']))
    {
    	ta_clean_old_thumbnails(); 
    }
    
    if (isset($_POST['reset-configuration']))
    {
    	delete_option("thumbnails_anywhere_options"); //delete option
    	$wp_thumbnails_options = get_wp_thumbnails_options(); //reset option
    }
    // 如果表单已经提交, 则保存数据。($_POST为PHP超全局变量(PHP版本 >= 5.0))
    else if (isset($_POST['submit']))
    {        
				// 过滤分类和标签
        if (!is_array($_POST['category_filter']))
        {
            $_POST['category_filter'] = array();
        }

        
        // 创建选项的数组
        $wp_thumbnails_options = array(
    		    "number_of_smart_homepage"	=> $_POST['number_of_smart_homepage'], 
            "number_of_random_images"		=> $_POST['number_of_random_images'], 
            "number_of_recent_images"		=> $_POST['number_of_recent_images'], 
            "number_of_album_images"		=> $_POST['number_of_album_images'], 
            "number_of_category_images"	=> $_POST['number_of_category_images'], 
            "number_of_related_images"	=> $_POST['number_of_related_images'], 
            "number_of_single_images"		=> $_POST['number_of_single_images'], 
            "number_of_popular_images"	=> $_POST['number_of_popular_images'], 
            "number_of_tag_images"			=> $_POST['number_of_tag_images'], 
            "single_row_limit"					=> $_POST['single_row_limit'], 
            "random_pic_interval"				=> $_POST['random_pic_interval'], 
            "recent_pic_interval"				=> $_POST['recent_pic_interval'], 
            "album_pic_interval"				=> $_POST['album_pic_interval'], 
            "category_pic_interval"			=> $_POST['category_pic_interval'], 
            "related_pic_interval"			=> $_POST['related_pic_interval'], 
            "single_pic_interval"				=> $_POST['single_pic_interval'], 
            "popular_pic_interval"			=> $_POST['popular_pic_interval'], 
            "tag_pic_interval"					=> $_POST['tag_pic_interval'], 
            "width_of_home_images"			=> $_POST['width_of_home_images'], 
            "height_of_home_images"     => $_POST['height_of_home_images'], 
            "width_of_random_images"		=> $_POST['width_of_random_images'], 
            "height_of_random_images"		=> $_POST['height_of_random_images'], 
            "width_of_recent_images"		=> $_POST['width_of_recent_images'], 
            "height_of_recent_images"		=> $_POST['height_of_recent_images'], 
            "width_of_album_images"			=> $_POST['width_of_album_images'], 
            "height_of_album_images"		=> $_POST['height_of_album_images'], 
            "width_of_category_images"	=> $_POST['width_of_category_images'], 
            "height_of_category_images"	=> $_POST['height_of_category_images'], 
            "width_of_related_images"		=> $_POST['width_of_related_images'], 
            "height_of_related_images"	=> $_POST['height_of_related_images'], 
            "width_of_single_images"		=> $_POST['width_of_single_images'], 
            "height_of_single_images"		=> $_POST['height_of_single_images'], 
            "width_of_popular_images"		=> $_POST['width_of_popular_images'], 
            "height_of_popular_images"	=> $_POST['height_of_popular_images'], 
            "width_of_tag_images"				=> $_POST['width_of_tag_images'], 
            "height_of_tag_images"			=> $_POST['height_of_tag_images'], 
            "limit"											=> $_POST['limit'], 
            "random_title_pos"					=> $_POST['random_title_pos'], 
            "recent_title_pos"					=> $_POST['recent_title_pos'], 
            "album_title_pos"						=> $_POST['album_title_pos'], 
            "category_title_pos"				=> $_POST['category_title_pos'], 
            "related_title_pos"					=> $_POST['related_title_pos'],   
            "popular_title_pos"					=> $_POST['popular_title_pos'],    
            "tag_title_pos"							=> $_POST['tag_title_pos'],      
            "disable_external"					=> $_POST['disable_external'],      
            "picasa"										=> $_POST['picasa'], 
            "flickr"										=> $_POST['flickr'], 
            "yupoo"											=> $_POST['yupoo'], 
            "releated_fill"							=> $_POST['releated_fill'], 
            "rand_pic_from"							=> $_POST['rand_pic_from'], 
            "homepage_default_image"		=> $_POST['homepage_default_image'], 
	    			"web_album"									=> $_POST['web_album'], 
	    			"homepage_position"					=> $_POST['homepage_position'], 
	    			"single_position"						=> $_POST['single_position'], 
	    			"crop_home_images"					=> $_POST['crop_home_images'], 
	    			"crop_random_images"				=> $_POST['crop_random_images'], 
	    			"crop_recent_images"				=> $_POST['crop_recent_images'], 
	    			"crop_category_images"			=> $_POST['crop_category_images'], 
	    			"crop_related_images"				=> $_POST['crop_related_images'], 
	    			"crop_album_images"					=> $_POST['crop_album_images'], 
	    			"crop_popular_images"				=> $_POST['crop_popular_images'], 
	    			"crop_tag_images"						=> $_POST['crop_tag_images'], 
	    			"homepage_new_window"				=> $_POST['homepage_new_window'], 
	    			"random_new_window"					=> $_POST['random_new_window'], 
	    			"recent_new_window"					=> $_POST['recent_new_window'], 
	    			"album_new_window"					=> $_POST['album_new_window'], 
	    			"category_new_window"				=> $_POST['category_new_window'], 
	    			"related_new_window"				=> $_POST['related_new_window'], 
	    			"single_new_window"					=> $_POST['single_new_window'], 
	    			"popular_new_window"				=> $_POST['popular_new_window'], 
	    			"tag_new_window"						=> $_POST['tag_new_window'], 
	    			"homepage_link_target"			=> $_POST['homepage_link_target'], 
	    			"random_link_target"				=> $_POST['random_link_target'], 
	    			"recent_link_target"				=> $_POST['recent_link_target'], 
	    			"album_link_target"					=> $_POST['album_link_target'], 
	    			"category_link_target"			=> $_POST['category_link_target'], 
	    			"related_link_target"				=> $_POST['related_link_target'], 	  
	    			"single_link_target"				=> $_POST['single_link_target'],  		
	    			"popular_link_target"				=> $_POST['popular_link_target'],  	
	    			"tag_link_target"						=> $_POST['tag_link_target'],
	    			"random_show_title"					=> $_POST['random_show_title'], 
	    			"recent_show_title"					=> $_POST['recent_show_title'], 
	    			"album_show_title"					=> $_POST['album_show_title'], 
	    			"category_show_title"				=> $_POST['category_show_title'], 
	    			"related_show_title"				=> $_POST['related_show_title'], 
	    			"popular_show_title"				=> $_POST['popular_show_title'], 
	    			"tag_show_title"						=> $_POST['tag_show_title'], 
	    			"random_wrap"								=> $_POST['random_wrap'], 
	    			"recent_wrap"								=> $_POST['recent_wrap'], 
	    			"album_wrap"								=> $_POST['album_wrap'], 
	    			"category_wrap"							=> $_POST['category_wrap'], 
	    			"related_wrap"							=> $_POST['related_wrap'], 
	    			"popular_wrap"							=> $_POST['popular_wrap'], 
	    			"tag_wrap"									=> $_POST['tag_wrap'], 
	    			"show_page"									=> $_POST['show_page'], 
	    			"homepage_nofollow"					=> $_POST['homepage_nofollow'], 
	    			"random_nofollow"						=> $_POST['random_nofollow'], 
	    			"recent_nofollow"						=> $_POST['recent_nofollow'], 
	    			"album_nofollow"						=> $_POST['album_nofollow'], 
	    			"popular_nofollow"					=> $_POST['popular_nofollow'], 
	    			"tag_nofollow"							=> $_POST['tag_nofollow'], 
	    			"related_nofollow"					=> $_POST['related_nofollow'], 
	    			"category_nofollow"					=> $_POST['category_nofollow'], 
	    			"category_filter_way"				=> $_POST['category_filter_way'], 
	    			"related_style"							=> $_POST['related_style'], 
	    			"show_views"								=> $_POST['show_views'], 
	    			"views_text"								=> $_POST['views_text'], 
	    			"auto_home"									=> $_POST['auto_home'], 
	    			"auto_category"							=> $_POST['auto_category'], 
	    			"auto_search"								=> $_POST['auto_search'], 
	    			"auto_tag_page"							=> $_POST['auto_tag_page'], 
	    			"auto_home_style"						=> $_POST['auto_home_style'],  
	    			"auto_cat_style"						=> $_POST['auto_cat_style'], 
	    			"auto_search_style"					=> $_POST['auto_search_style'], 
	    			"auto_tag_style"						=> $_POST['auto_tag_style'], 
	    			"auto_random"								=> $_POST['auto_random'], 
	    			"auto_related"							=> $_POST['auto_related'], 
	    			"auto_single"								=> $_POST['auto_single'], 
	    			"auto_random_title"					=> $_POST['auto_random_title'], 
	    			"auto_related_title"				=> $_POST['auto_related_title'], 
	    			"auto_single_title"					=> $_POST['auto_single_title'], 
	    			"auto_excerpt"							=> $_POST['auto_excerpt'], 
	    			"auto_excerpt_length"				=> $_POST['auto_excerpt_length'], 
	    			"auto_excerpt_tail"					=> $_POST['auto_excerpt_tail'], 
	    			//"auto_recheck"							=> $_POST['auto_recheck'], 
	    			"auto_replace"							=> $_POST['auto_replace'], 
	    			"auto_replace_exception"		=> trim($_POST['auto_replace_exception']), 
	    			"related_order"							=> $_POST['related_order'], 
	    			"category_order"						=> $_POST['category_order'], 
	    			"tag_order"									=> $_POST['tag_order'], 
	    			"media_page"								=> $_POST['media_page'], 
	    			"media_post"								=> $_POST['media_post'], 
	    			"video_thumb"								=> $_POST['video_thumb'], 
	    			"video_link_target"					=> $_POST['video_link_target'],
	    			"category_filter"						=> $_POST['category_filter'], 
	    			"tags_filter"								=> $_POST['tags_filter'], 
	    			"random_excerpt_limit"			=> $_POST['random_excerpt_limit'], 
	    			"recent_excerpt_limit"			=> $_POST['recent_excerpt_limit'], 
	    			"popular_excerpt_limit"			=> $_POST['popular_excerpt_limit'], 
	    			"related_excerpt_limit"			=> $_POST['related_excerpt_limit'], 
	    			"category_excerpt_limit"		=> $_POST['category_excerpt_limit'], 
	    			"tag_excerpt_limit"					=> $_POST['tag_excerpt_limit'], 
	    			"random_title_width"				=> $_POST['random_title_width'], 
	    			"recent_title_width"				=> $_POST['recent_title_width'], 
	    			"popular_title_width"				=> $_POST['popular_title_width'], 
	    			"related_title_width"				=> $_POST['related_title_width'], 
	    			"category_title_width"			=> $_POST['category_title_width'], 
	    			"tag_title_width"						=> $_POST['tag_title_width'], 
        );
        update_option('thumbnails_anywhere_options', $wp_thumbnails_options);
    }

?>

<div class="wrap">
<h2><?php _e("设置缩略图 WP-Thumbnails", 'wp_thumbnails'); ?></h2>

<form method="post" action="">
	
<div style="clear: both;padding-top:10px;"></div>
<p><strong><?php _e("插件名称", 'wp_thumbnails'); ?> :</strong> WP-Thumbnails
<p><strong><?php _e("插件作者", 'wp_thumbnails'); ?> :</strong> <?php _e("布谷鸟", 'wp_thumbnails'); ?> (9000birds@gmail.com) <a href="http://niaolei.org.cn/" target="_blank"><?php _e("鸟类网|分享鸟趣", 'wp_thumbnails'); ?></a>
<p><strong><?php _e("插件主页", 'wp_thumbnails'); ?> :</strong> <a href="http://niaolei.org.cn/wp-thumbnails" target="_blank">http://niaolei.org.cn/wp-thumbnails</a>
<p><strong><?php _e("插件提示", 'wp_thumbnails'); ?> :</strong> <?php _e("插件原理是每打开一篇文章生成相应的缩略图, 所以安装插件后请回首页点开几篇文章, 插件就会自动生成缩略图。遇到问题请先看", 'wp_thumbnails'); ?><a href="http://niaolei.org.cn/wp-thumbnails/faq" target="_blank"><?php _e("《插件常见问题》", 'wp_thumbnails'); ?></a>。 
<p><?php _e("安装遇到困难、安装成功、发现错误、问题咨询、特别需求和功能建议请到", 'wp_thumbnails'); ?><a href="http://niaolei.org.cn/wp-thumbnails" target="_blank" ><strong> <?php _e("插件主页", 'wp_thumbnails'); ?> </strong></a><?php _e("留言。", 'wp_thumbnails'); ?>
<p><strong><?php _e("插件捐赠", 'wp_thumbnails'); ?> :</strong> <a href="http://item.taobao.com/item.htm?id=5198053841" target="_blank" ><?php _e("如果你觉得插件不错，愿意支持插件的继续发展，请到WP-Thumbnails的淘宝网店购买一份支持，1元起，聊表心意即可，在此深表感谢。", 'wp_thumbnails'); ?></a>.

<div style="clear: both;padding-top:10px;"></div>
<hr>
<p><h2><?php _e("排除和指定", 'wp_thumbnails'); ?>: </h2> 
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="rand_pic_from"><?php _e("缩略图来源", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="rand_pic_from" name="rand_pic_from" <?php if ($wp_thumbnails_options["rand_pic_from"] == "first") print "checked='checked'"; ?> value="first" />  <?php _e("来自每篇文章的第一张图片", 'wp_thumbnails'); ?> <input type="radio" id="rand_pic_from" name="rand_pic_from" <?php if ($wp_thumbnails_options["rand_pic_from"] == "all") print "checked='checked'"; ?> value="all" />  <?php _e("来自任意图片", 'wp_thumbnails'); ?> (<?php _e("每篇文章至多提取", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="limit" name="limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["limit"]) print "value='" . $wp_thumbnails_options["limit"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="show_page"><?php _e("缩略图来源", 'wp_thumbnails'); ?> :  </label>
<div style="float:left;"><input type="radio" id="show_page" name="show_page" <?php if ($wp_thumbnails_options["show_page"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("只显示文章缩略图", 'wp_thumbnails'); ?> <input type="radio" id="show_page" name="show_page" <?php if ($wp_thumbnails_options["show_page"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章缩略图和页面缩略图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tags_filter"><?php _e("排除标签", 'wp_thumbnails'); ?> :  </label>
<div style="float:left;"><input style="border:1px solid #D1D1D1; width:165px;"  id="tags_filter" name="tags_filter"  value="<?php echo $wp_thumbnails_options["tags_filter"]; ?>"/> (<?php _e("不显示该标签所在文章的缩略图, 多个标签以英文逗号, 隔开", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_filter_way"><?php _e("指定分类", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_filter_way" name="category_filter_way" <?php if ($wp_thumbnails_options["category_filter_way"] == "include") print "checked='checked'"; ?> value="include" />  <?php _e("仅显示选中分类", 'wp_thumbnails'); ?> <input type="radio" id="category_filter_way" name="category_filter_way" <?php if ($wp_thumbnails_options["category_filter_way"] == "none") print "checked='checked'"; ?> value="none" />  <?php _e("显示全部分类", 'wp_thumbnails'); ?> </div>
</div>
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" ></div>
<div style='overflow:auto;height:20em;width:200px;background-color:#efefef;border:1px solid #b2b2b2;padding:2px 0 0 3px;'>
<?php
    // create WordPress-style category multi-select list
    global $wpdb, $wp_version;

    $categories = $wpdb->get_results("SELECT $wpdb->terms.term_id as cat_ID, $wpdb->terms.name as cat_name
                                          FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
                                          WHERE $wpdb->term_taxonomy.taxonomy IN ('category')
                                          ORDER BY $wpdb->terms.name");


    foreach ($categories as $category) {
        print "<label style='display:block;' for='category-$category->cat_ID'><input type='checkbox' value='$category->cat_ID' name='category_filter[]' id='category-$category->cat_ID'" . (in_array( $category->cat_ID, $wp_thumbnails_options["category_filter"] ) ? ' checked="checked"' : "") . " />" .  wp_specialchars($category->cat_name) . "</label>\n";
    }
?>
</div>
</div>


<div style="clear: both;padding-top:10px;"></div>
<hr>
<p><h2><?php _e("设置外链图片", 'wp_thumbnails'); ?>: </h2>
<h3><?php _e("更改外链图片设置后, 需要点击本页底部“彻底清理插件”才能生效。", 'wp_thumbnails'); ?></h3>
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="disable_external"><?php _e("如何处理外链图片", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="disable_external" name="disable_external" <?php if ($wp_thumbnails_options["disable_external"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("自动下载外链图片到本地, 自动生成缩略图。(Yupoo等相册可以直接调用外链缩略图)", 'wp_thumbnails'); ?><br /><input type="radio" id="disable_external" name="disable_external" <?php if ($wp_thumbnails_options["disable_external"] == "fake") print "checked='checked'"; ?> value="fake" />  <?php _e("不制作缩略图, 将原图片缩小显示, 节省空间和流量, 但图片有可能变形。 ", 'wp_thumbnails'); ?><br /><input type="radio" id="disable_external" name="disable_external" <?php if ($wp_thumbnails_options["disable_external"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("(有些博客受主机环境所限制, 无法下载远程图片) 忽略一切外链图片, 完全不显示。", 'wp_thumbnails'); ?></div>
</div>
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="picasa"><?php _e("Picasa图片", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="picasa" name="picasa" <?php if ($wp_thumbnails_options["picasa"] == "external") print "checked='checked'"; ?> value="external" />  <?php _e("使用外链缩略图", 'wp_thumbnails'); ?> <input type="radio" id="picasa" name="picasa" <?php if ($wp_thumbnails_options["picasa"] == "local") print "checked='checked'"; ?> value="local" />  <?php _e("制作本地缩略图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="flickr"><?php _e("Flickr图片", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="flickr" name="flickr" <?php if ($wp_thumbnails_options["flickr"] == "external") print "checked='checked'"; ?> value="external" />  <?php _e("使用外链缩略图", 'wp_thumbnails'); ?> <input type="radio" id="flickr" name="flickr" <?php if ($wp_thumbnails_options["flickr"] == "local") print "checked='checked'"; ?> value="local" />  <?php _e("制作本地缩略图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="yupoo"><?php _e("Yupoo图片", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="yupoo" name="yupoo" <?php if ($wp_thumbnails_options["yupoo"] == "external") print "checked='checked'"; ?> value="external" />  <?php _e("使用外链缩略图", 'wp_thumbnails'); ?> <input type="radio" id="yupoo" name="yupoo" <?php if ($wp_thumbnails_options["yupoo"] == "local") print "checked='checked'"; ?> value="local" />  <?php _e("制作本地缩略图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;"></div>
<hr>
<p><h2><?php _e("设置视频缩略图", 'wp_thumbnails'); ?>: </h2>
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("提取视频缩略图，支持优酷、酷6、土豆三大视频网站。请注意设置合适的图片显示大小：", 'wp_thumbnails'); ?><br><?php _e("优酷视频缩略图大小为128像素*96像素，酷6缩略图大小为132像素*99像素，土豆缩略图大小为120像素*90像素。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="media_page"><?php _e("显示", 'wp_thumbnails'); ?> : </label> <div style="float:left;"><input type="radio" id="media_page" name="media_page" <?php if ($wp_thumbnails_options["media_page"] == "both") print "checked='checked'"; ?> value="both" />  <?php _e("与页面缩略图一起显示", 'wp_thumbnails'); ?> <input type="radio" id="media_page" name="media_page" <?php if ($wp_thumbnails_options["media_page"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("不显示", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="media_post"><?php _e("显示", 'wp_thumbnails'); ?> : </label> <div style="float:left;"><input type="radio" id="media_post" name="media_post" <?php if ($wp_thumbnails_options["media_post"] == "both") print "checked='checked'"; ?> value="both" />  <?php _e("与文章缩略图一起显示", 'wp_thumbnails'); ?> <input type="radio" id="media_post" name="media_post" <?php if ($wp_thumbnails_options["media_post"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("不显示", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:5px;" for="video_thumb"><?php _e("显示", 'wp_thumbnails'); ?> : </label> <div style="float:left;"><input type="radio" id="video_thumb" name="video_thumb" <?php if ($wp_thumbnails_options["video_thumb"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("显示缩略图", 'wp_thumbnails'); ?> <input type="radio" id="video_thumb" name="video_thumb" <?php if ($wp_thumbnails_options["video_thumb"] == "video") print "checked='checked'"; ?> value="video" />  <?php _e("显示缩小的视频", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="video_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="video_link_target" name="video_link_target" <?php if ($wp_thumbnails_options["video_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="video_link_target" name="video_link_target" <?php if ($wp_thumbnails_options["video_link_target"] == "video") print "checked='checked'"; ?> value="video" />  <?php _e("点击打开视频", 'wp_thumbnails'); ?> </div>
</div>


<div style="clear: both;padding-top:10px;"></div>
<hr>
<p><h2><?php _e("自动启用", 'wp_thumbnails'); ?>: </h2>
	
	<div style="clear: both;padding-top:10px; padding-left:150px;">
					<label>
				<input name="auto_home" type="checkbox" id="auto_home" value="true"  <?php echo ($wp_thumbnails_options["auto_home"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动启用首页页面缩略图",'wp_thumbnails');?>
				</label>: <input type="radio" id="auto_home_style" name="auto_home_style" <?php if ($wp_thumbnails_options["auto_home_style"] == "single") print "checked='checked'"; ?> value="single" />  <?php _e("单张", 'wp_thumbnails'); ?> <input type="radio" id="auto_home_style" name="auto_home_style" <?php if ($wp_thumbnails_options["auto_home_style"] == "multiple") print "checked='checked'"; ?> value="multiple" />  <?php _e("全排", 'wp_thumbnails'); ?> <input type="radio" id="auto_home_style" name="auto_home_style" <?php if ($wp_thumbnails_options["auto_home_style"] == "smart") print "checked='checked'"; ?> value="smart" />  <?php _e("智能", 'wp_thumbnails'); ?> 
				<br /> 
				<label>
				<input name="auto_category" type="checkbox" id="auto_category" value="true"  <?php echo ($wp_thumbnails_options["auto_category"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动启用分类页面缩略图",'wp_thumbnails');?>
				</label>: <input type="radio" id="auto_cat_style" name="auto_cat_style" <?php if ($wp_thumbnails_options["auto_cat_style"] == "single") print "checked='checked'"; ?> value="single" />  <?php _e("单张", 'wp_thumbnails'); ?> <input type="radio" id="auto_cat_style" name="auto_cat_style" <?php if ($wp_thumbnails_options["auto_cat_style"] == "multiple") print "checked='checked'"; ?> value="multiple" />  <?php _e("全排", 'wp_thumbnails'); ?> <input type="radio" id="auto_cat_style" name="auto_cat_style" <?php if ($wp_thumbnails_options["auto_cat_style"] == "smart") print "checked='checked'"; ?> value="smart" />  <?php _e("智能", 'wp_thumbnails'); ?> 
				<br />		
				<label>
				<input name="auto_tag_page" type="checkbox" id="auto_tag_page" value="true"  <?php echo ($wp_thumbnails_options["auto_tag_page"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动启用标签页面缩略图",'wp_thumbnails');?>
				</label>: <input type="radio" id="auto_tag_style" name="auto_tag_style" <?php if ($wp_thumbnails_options["auto_tag_style"] == "single") print "checked='checked'"; ?> value="single" />  <?php _e("单张", 'wp_thumbnails'); ?> <input type="radio" id="auto_tag_style" name="auto_tag_style" <?php if ($wp_thumbnails_options["auto_tag_style"] == "multiple") print "checked='checked'"; ?> value="multiple" />  <?php _e("全排", 'wp_thumbnails'); ?> <input type="radio" id="auto_tag_style" name="auto_tag_style" <?php if ($wp_thumbnails_options["auto_tag_style"] == "smart") print "checked='checked'"; ?> value="smart" />  <?php _e("智能", 'wp_thumbnails'); ?> 		
				<br />
				<label>
				<input name="auto_search" type="checkbox" id="auto_search" value="true"  <?php echo ($wp_thumbnails_options["auto_search"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动启用搜索页面缩略图",'wp_thumbnails');?>
				</label>: <input type="radio" id="auto_search_style" name="auto_search_style" <?php if ($wp_thumbnails_options["auto_search_style"] == "single") print "checked='checked'"; ?> value="single" />  <?php _e("单张", 'wp_thumbnails'); ?> <input type="radio" id="auto_search_style" name="auto_search_style" <?php if ($wp_thumbnails_options["auto_search_style"] == "multiple") print "checked='checked'"; ?> value="multiple" />  <?php _e("全排", 'wp_thumbnails'); ?> <input type="radio" id="auto_search_style" name="auto_search_style" <?php if ($wp_thumbnails_options["auto_search_style"] == "smart") print "checked='checked'"; ?> value="smart" />  <?php _e("智能", 'wp_thumbnails'); ?> 	
				<br />		
				<label>
				<input name="auto_excerpt" type="checkbox" id="auto_excerpt" value="true"  <?php echo ($wp_thumbnails_options["auto_excerpt"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("以上内容截断摘要",'wp_thumbnails');?> , 
				</label>
				<label for="auto_excerpt_length"><?php _e("摘要长度", 'wp_thumbnails'); ?> : <input type="text" style="width:40px;" id="auto_excerpt_length" name="auto_excerpt_length" size="1" maxlength="3" <?php if ($wp_thumbnails_options["auto_excerpt_length"]) print "value='" . $wp_thumbnails_options["auto_excerpt_length"] . "'"; ?>/><?php _e("汉字", 'wp_thumbnails'); ?> , 
				</label>
				<label for="auto_excerpt_tail"><?php _e("摘要结尾链接文字", 'wp_thumbnails'); ?> : 
				<input type="text" id="auto_excerpt_tail" name="auto_excerpt_tail"  style="width:200px;" <?php if ($wp_thumbnails_options["auto_excerpt_tail"]) print "value='" . $wp_thumbnails_options["auto_excerpt_tail"] . "'"; ?>/>
				</label><?php _e("(清空则不显示)",'wp_thumbnails');?>
				<br />		
				<br />			
				<label>
				<input name="auto_random" type="checkbox" id="auto_random" value="true"  <?php echo ($wp_thumbnails_options["auto_random"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动在文章后面插入",'wp_thumbnails');?><?php _e("随机文章缩略图",'wp_thumbnails');?> ,
				</label>				
<label for="auto_random_title"><?php _e("显示标题为", 'wp_thumbnails'); ?> : </label>
<input type="text" id="auto_random_title" name="auto_random_title"  style="width:150px;" <?php if ($wp_thumbnails_options["auto_random_title"]) print "value='" . $wp_thumbnails_options["auto_random_title"] . "'"; ?>/><?php _e("(清空则不显示)",'wp_thumbnails');?>
				<br />		
				<label>
				<input name="auto_related" type="checkbox" id="auto_related" value="true"  <?php echo ($wp_thumbnails_options["auto_related"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动在文章后面插入",'wp_thumbnails');?><?php _e("相关文章缩略图",'wp_thumbnails');?> ,
				</label>
<label for="auto_related_title"><?php _e("显示标题为", 'wp_thumbnails'); ?> : </label>
<input type="text" id="auto_related_title" name="auto_related_title"  style="width:150px;" <?php if ($wp_thumbnails_options["auto_related_title"]) print "value='" . $wp_thumbnails_options["auto_related_title"] . "'"; ?>/>				
				<br />		
				<label>
				<input name="auto_single" type="checkbox" id="auto_single" value="true"  <?php echo ($wp_thumbnails_options["auto_single"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动在文章后面插入",'wp_thumbnails');?><?php _e("该文章自身全部缩略图",'wp_thumbnails');?> ,
				</label>
<label for="auto_single_title"><?php _e("显示标题为", 'wp_thumbnails'); ?> : </label>
<input type="text" id="auto_single_title" name="auto_single_title"  style="width:150px;" <?php if ($wp_thumbnails_options["auto_single_title"]) print "value='" . $wp_thumbnails_options["auto_single_title"] . "'"; ?>/>			
				<br />		
				<br />		
				<label>
				<input name="auto_replace" type="checkbox" id="auto_replace" value="true"  <?php echo ($wp_thumbnails_options["auto_replace"] == 'true') ? 'checked' : ''; ?>>
				<?php _e("自动将文章中的远程图片替换为本地图片, 本操作消耗资源比较大，慎用。",'wp_thumbnails');?>
				</label>
				<br />
				<label for="auto_replace_exception"><?php _e("图片本地化排除如下域名，如排除你使用的外链图床。<br />请注意：如果你的网站是example.com, 那么子域名图床如img.example.com也算作外链图床，需要排除。<br />多个域名用英文分号 ; 隔开", 'wp_thumbnails'); ?></label>
				<br />
				<input type="text" id="auto_replace_exception" name="auto_replace_exception"  style="width:550px;" <?php if ($wp_thumbnails_options["auto_replace_exception"]) print "value='" . $wp_thumbnails_options["auto_replace_exception"] . "'"; ?>/>		
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:10px;"></div>
<hr>
<p><h2><?php _e("首页缩略图", 'wp_thumbnails'); ?>: </h2>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;"  for="size_of_homeimages"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_home_images" name="width_of_home_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_home_images"]) print "value='" . $wp_thumbnails_options["width_of_home_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_home_images" name="height_of_home_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_home_images"]) print "value='" . $wp_thumbnails_options["height_of_home_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="crop_home_images"><?php _e("裁剪", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="crop_home_images" name="crop_home_images" <?php if ($wp_thumbnails_options["crop_home_images"] == "uncrop") print "checked='checked'"; ?> value="uncrop" />  <?php _e("智能保持原图比例", 'wp_thumbnails'); ?> <input type="radio" id="crop_home_images" name="crop_home_images" <?php if ($wp_thumbnails_options["crop_home_images"]!= "uncrop") print "checked='checked'"; ?> value="crop" />  <?php _e("精确裁剪成上述宽度和高度", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="homepage_position"><?php _e("位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="homepage_position" name="homepage_position" <?php if ($wp_thumbnails_options["homepage_position"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("靠左显示", 'wp_thumbnails'); ?> <input type="radio" id="homepage_position" name="homepage_position" <?php if ($wp_thumbnails_options["homepage_position"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("靠右显示", 'wp_thumbnails'); ?> <input type="radio" id="homepage_position" name="homepage_position" <?php if ($wp_thumbnails_options["homepage_position"] == "random") print "checked='checked'"; ?> value="random" />  <?php _e("左右随机出现", 'wp_thumbnails'); ?> <br /><input type="radio" id="homepage_position" name="homepage_position" <?php if ($wp_thumbnails_options["homepage_position"] == "center") print "checked='checked'"; ?> value="center" />  <?php _e("居中显示(居中的时候文字无法环绕, 图片尺寸设大一点会比较美观)", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="homepage_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="homepage_new_window" name="homepage_new_window" <?php if ($wp_thumbnails_options["homepage_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="homepage_new_window" name="homepage_new_window" <?php if ($wp_thumbnails_options["homepage_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="homepage_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="homepage_link_target" name="homepage_link_target" <?php if ($wp_thumbnails_options["homepage_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="homepage_link_target" name="homepage_link_target" <?php if ($wp_thumbnails_options["homepage_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;" for="homepage_default_image"><?php _e("默认图片", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="text" id="homepage_default_image" name="homepage_default_image"  style="width:400px;" <?php if ($wp_thumbnails_options["homepage_default_image"]) print "value='" . $wp_thumbnails_options["homepage_default_image"] . "'"; ?>/> <br /><?php _e("文章中不存在图片时, 如果要显示默认缩略图片, 请填写图片地址。", 'wp_thumbnails'); ?><br /><?php _e("例如", 'wp_thumbnails'); ?>http://niaolei.org.cn/wp-content/uploads/icon/taiji.jpg <br /><?php _e("最好是本地图片。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_homepage')) { wp_thumbnails_for_homepage(); } ?&gt</code> <br /><?php _e("代码放置于首页模板, 置于the_content或the_excerpt之前。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div class="wrap" style="clear: both;">
<h2><?php _e("预览", 'wp_thumbnails'); ?>:</h2>
<?php wp_thumbnails_for_homepage_preview(); ?>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:0px;"></div>
<p><h2><?php _e("全排列缩略图", 'wp_thumbnails'); ?>: </h2> 
	
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("将该文章内所有的图片以缩略图形式排列出来, 也即一次显示多张图片的“首页缩略图”。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="size_of_single_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_single_images" name="width_of_single_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_single_images"]) print "value='" . $wp_thumbnails_options["width_of_single_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_single_images" name="height_of_single_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_single_images"]) print "value='" . $wp_thumbnails_options["height_of_single_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_single_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示至多", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_single_images" name="number_of_single_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_single_images"]) print "value='" . $wp_thumbnails_options["number_of_single_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("每行不超过", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="single_row_limit" name="single_row_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["single_row_limit"]) print "value='" . $wp_thumbnails_options["single_row_limit"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="single_pic_interval" name="single_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["single_pic_interval"]) print "value='" . $wp_thumbnails_options["single_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="single_position"><?php _e("位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="single_position" name="single_position" <?php if ($wp_thumbnails_options["single_position"] == "none") print "checked='checked'"; ?> value="none" />  <?php _e("普通显示, 不环绕", 'wp_thumbnails'); ?> <input type="radio" id="single_position" name="single_position" <?php if ($wp_thumbnails_options["single_position"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("靠左显示, 文字环绕", 'wp_thumbnails'); ?> <input type="radio" id="single_position" name="single_position" <?php if ($wp_thumbnails_options["single_position"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("靠右显示, 文字环绕", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="single_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="single_new_window" name="single_new_window" <?php if ($wp_thumbnails_options["single_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="single_new_window" name="single_new_window" <?php if ($wp_thumbnails_options["single_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="single_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="single_link_target" name="single_link_target" <?php if ($wp_thumbnails_options["single_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="single_link_target" name="single_link_target" <?php if ($wp_thumbnails_options["single_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_single_post')) { wp_thumbnails_for_single_post(); } ?&gt</code> <br /><?php _e("代码放置于首页模板, 置于the_content或the_excerpt之后, 以实现首页排列出每篇文章多张缩略图的效果。当然, 也可以放置在单篇文章页single.php的任意位置。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:0px;"></div>
<p><h2><?php _e("智能方式调用首页缩略图: (结合上面两种方式)", 'wp_thumbnails'); ?></h2> 

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_smart_homepage"><?php _e("智能调用", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("如果文章内图片少于", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_smart_homepage" name="number_of_smart_homepage" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_smart_homepage"]) print "value='" . $wp_thumbnails_options["number_of_smart_homepage"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("那么调用", 'wp_thumbnails'); ?><?php _e("首页缩略图", 'wp_thumbnails'); ?>, <?php _e("否则排列显示该文章内所有缩略图。", 'wp_thumbnails'); ?><br /><?php _e("仍然在上面两种方式处进行设置。", 'wp_thumbnails'); ?></div>
</div>


<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_smart_homepage')) { wp_thumbnails_for_smart_homepage(); } ?&gt</code> <br /><?php _e("代码放置于首页模板, 置于the_content或the_excerpt之后。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:0px;"></div>
<p><h2><?php _e("随机缩略图", 'wp_thumbnails'); ?>: </h2> 

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("显示随机文章的缩略图。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="width_of_random_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_random_images" name="width_of_random_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_random_images"]) print "value='" . $wp_thumbnails_options["width_of_random_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_random_images" name="height_of_random_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_random_images"]) print "value='" . $wp_thumbnails_options["height_of_random_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_random_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_random_images" name="number_of_random_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_random_images"]) print "value='" . $wp_thumbnails_options["number_of_random_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="random_pic_interval" name="random_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["random_pic_interval"]) print "value='" . $wp_thumbnails_options["random_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="random_show_title" name="random_show_title" <?php if ($wp_thumbnails_options["random_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="random_show_title" name="random_show_title" <?php if ($wp_thumbnails_options["random_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="random_title_pos" name="random_title_pos" <?php if ($wp_thumbnails_options["random_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="random_title_pos" name="random_title_pos" <?php if ($wp_thumbnails_options["random_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="random_title_pos" name="random_title_pos" <?php if ($wp_thumbnails_options["random_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="random_title_pos" name="random_title_pos" <?php if ($wp_thumbnails_options["random_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="random_title_width" name="random_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["random_title_width"]) print "value='" . $wp_thumbnails_options["random_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="random_wrap" name="random_wrap" <?php if ($wp_thumbnails_options["random_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="random_wrap" name="random_wrap" <?php if ($wp_thumbnails_options["random_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="random_excerpt_limit" name="random_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["random_excerpt_limit"]) print "value='" . $wp_thumbnails_options["random_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>


<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="random_new_window" name="random_new_window" <?php if ($wp_thumbnails_options["random_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="random_new_window" name="random_new_window" <?php if ($wp_thumbnails_options["random_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="random_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="random_link_target" name="random_link_target" <?php if ($wp_thumbnails_options["random_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="random_link_target" name="random_link_target" <?php if ($wp_thumbnails_options["random_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_random_posts')) { wp_thumbnails_for_random_posts(); } ?&gt</code> <br /><?php _e("代码放置于任意位置", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div class="wrap" style="clear: both;">
<h2><?php _e("预览", 'wp_thumbnails'); ?>:</h2>
<?php wp_thumbnails_for_random_posts(); ?>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:10px;"></div>
<p><h2><?php _e("最新缩略图", 'wp_thumbnails'); ?>: </h2> 

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("显示最新文章的缩略图。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="width_of_recent_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_recent_images" name="width_of_recent_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_recent_images"]) print "value='" . $wp_thumbnails_options["width_of_recent_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_recent_images" name="height_of_recent_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_recent_images"]) print "value='" . $wp_thumbnails_options["height_of_recent_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_recent_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;">显示<input type="text" style="width:40px;" id="number_of_recent_images" name="number_of_recent_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_recent_images"]) print "value='" . $wp_thumbnails_options["number_of_recent_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="recent_pic_interval" name="recent_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["recent_pic_interval"]) print "value='" . $wp_thumbnails_options["recent_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="recent_show_title" name="recent_show_title" <?php if ($wp_thumbnails_options["recent_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="recent_show_title" name="recent_show_title" <?php if ($wp_thumbnails_options["recent_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="recent_title_pos" name="recent_title_pos" <?php if ($wp_thumbnails_options["recent_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="recent_title_pos" name="recent_title_pos" <?php if ($wp_thumbnails_options["recent_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="recent_title_pos" name="recent_title_pos" <?php if ($wp_thumbnails_options["recent_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="recent_title_pos" name="recent_title_pos" <?php if ($wp_thumbnails_options["recent_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="recent_title_width" name="recent_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["recent_title_width"]) print "value='" . $wp_thumbnails_options["recent_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="recent_wrap" name="recent_wrap" <?php if ($wp_thumbnails_options["recent_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="recent_wrap" name="recent_wrap" <?php if ($wp_thumbnails_options["recent_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="recent_excerpt_limit" name="recent_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["recent_excerpt_limit"]) print "value='" . $wp_thumbnails_options["recent_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="recent_new_window" name="recent_new_window" <?php if ($wp_thumbnails_options["recent_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="recent_new_window" name="recent_new_window" <?php if ($wp_thumbnails_options["recent_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="recent_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="recent_link_target" name="recent_link_target" <?php if ($wp_thumbnails_options["recent_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="recent_link_target" name="recent_link_target" <?php if ($wp_thumbnails_options["recent_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_recent_posts')) { wp_thumbnails_for_recent_posts(); } ?&gt</code> <br /><?php _e("代码放置于任意位置", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div class="wrap" style="clear: both;">
<h2><?php _e("预览", 'wp_thumbnails'); ?>:</h2>
<?php wp_thumbnails_for_recent_posts(); ?>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:10px;"></div>
<p><h2><?php _e("最热门文章缩略图: (必须先启用访问统计插件WP-PostViews)", 'wp_thumbnails'); ?></h2> 
	
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("根据访客点击数, 显示最热门文章的缩略图。", 'wp_thumbnails'); ?></div>
</div>
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="width_of_popular_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_popular_images" name="width_of_popular_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_popular_images"]) print "value='" . $wp_thumbnails_options["width_of_popular_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_popular_images" name="height_of_popular_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_popular_images"]) print "value='" . $wp_thumbnails_options["height_of_popular_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_popular_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_popular_images" name="number_of_popular_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_popular_images"]) print "value='" . $wp_thumbnails_options["number_of_popular_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="popular_pic_interval" name="popular_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["popular_pic_interval"]) print "value='" . $wp_thumbnails_options["popular_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="popular_show_title" name="popular_show_title" <?php if ($wp_thumbnails_options["popular_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="popular_show_title" name="popular_show_title" <?php if ($wp_thumbnails_options["popular_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="popular_title_pos" name="popular_title_pos" <?php if ($wp_thumbnails_options["popular_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="popular_title_pos" name="popular_title_pos" <?php if ($wp_thumbnails_options["popular_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="popular_title_pos" name="popular_title_pos" <?php if ($wp_thumbnails_options["popular_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="popular_title_pos" name="popular_title_pos" <?php if ($wp_thumbnails_options["popular_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="popular_title_width" name="popular_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["popular_title_width"]) print "value='" . $wp_thumbnails_options["popular_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="popular_wrap" name="popular_wrap" <?php if ($wp_thumbnails_options["popular_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="popular_wrap" name="popular_wrap" <?php if ($wp_thumbnails_options["popular_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="popular_excerpt_limit" name="popular_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["popular_excerpt_limit"]) print "value='" . $wp_thumbnails_options["popular_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="show_views"><?php _e("统计显示", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="show_views" name="show_views" <?php if ($wp_thumbnails_options["show_views"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?><?php _e("文章统计数", 'wp_thumbnails'); ?> <input type="radio" id="show_views" name="show_views" <?php if ($wp_thumbnails_options["show_views"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章统计数 , 统计结果显示为", 'wp_thumbnails'); ?> : 100<input type="text" id="views_text" name="views_text"  style="width:80px;" <?php if ($wp_thumbnails_options["views_text"]) print "value='" . $wp_thumbnails_options["views_text"] . "'"; ?>/> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="popular_new_window" name="popular_new_window" <?php if ($wp_thumbnails_options["popular_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="popular_new_window" name="popular_new_window" <?php if ($wp_thumbnails_options["popular_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="popular_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="popular_link_target" name="popular_link_target" <?php if ($wp_thumbnails_options["popular_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="popular_link_target" name="popular_link_target" <?php if ($wp_thumbnails_options["popular_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_popular_posts')) { wp_thumbnails_for_popular_posts(); } ?&gt</code> <br /><?php _e("代码放置于任意位置", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div class="wrap" style="clear: both;">
<h2><?php _e("预览", 'wp_thumbnails'); ?>:</h2>
<?php wp_thumbnails_for_popular_posts(); ?>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:0px;"></div>
<p><h2><?php _e("相关缩略图", 'wp_thumbnails'); ?>: </h2> 

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("根据标签的相关性, 显示相关文章的缩略图。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="size_of_related_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_related_images" name="width_of_related_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_related_images"]) print "value='" . $wp_thumbnails_options["width_of_related_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_related_images" name="height_of_related_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_related_images"]) print "value='" . $wp_thumbnails_options["height_of_related_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_related_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_related_images" name="number_of_related_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_related_images"]) print "value='" . $wp_thumbnails_options["number_of_related_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>(<?php _e("当相关缩略图数量不足时", 'wp_thumbnails'); ?> : <input type="radio" id="releated_fill" name="releated_fill" <?php if ($wp_thumbnails_options["releated_fill"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("用随机缩略图补全", 'wp_thumbnails'); ?> <input type="radio" id="releated_fill" name="releated_fill" <?php if ($wp_thumbnails_options["releated_fill"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不补全", 'wp_thumbnails'); ?>), <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="related_pic_interval" name="related_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["related_pic_interval"]) print "value='" . $wp_thumbnails_options["related_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="related_show_title" name="related_show_title" <?php if ($wp_thumbnails_options["related_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="related_show_title" name="related_show_title" <?php if ($wp_thumbnails_options["related_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="related_title_pos" name="related_title_pos" <?php if ($wp_thumbnails_options["related_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="related_title_pos" name="related_title_pos" <?php if ($wp_thumbnails_options["related_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="related_title_pos" name="related_title_pos" <?php if ($wp_thumbnails_options["related_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="related_title_pos" name="related_title_pos" <?php if ($wp_thumbnails_options["related_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="related_title_width" name="related_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["related_title_width"]) print "value='" . $wp_thumbnails_options["related_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="related_wrap" name="related_wrap" <?php if ($wp_thumbnails_options["related_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="related_wrap" name="related_wrap" <?php if ($wp_thumbnails_options["related_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="related_excerpt_limit" name="related_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["related_excerpt_limit"]) print "value='" . $wp_thumbnails_options["related_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>


<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="related_new_window" name="related_new_window" <?php if ($wp_thumbnails_options["related_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="related_new_window" name="related_new_window" <?php if ($wp_thumbnails_options["related_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="related_link_target" name="related_link_target" <?php if ($wp_thumbnails_options["related_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="related_link_target" name="related_link_target" <?php if ($wp_thumbnails_options["related_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="related_order"><?php _e("顺序", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="related_order" name="related_order" <?php if ($wp_thumbnails_options["related_order"] == "random") print "checked='checked'"; ?> value="random" />  <?php _e("随机", 'wp_thumbnails'); ?> <input type="radio" id="related_order" name="related_order" <?php if ($wp_thumbnails_options["related_order"] == "recent") print "checked='checked'"; ?> value="recent" />  <?php _e("按时间", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_related_posts')) { wp_thumbnails_for_related_posts();} ?&gt</code><br /> <?php _e("代码放置于文章页面模板任意位置", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>


<div style="clear: both;padding-top:10px;"></div>
<p><h2><?php _e("分类缩略图", 'wp_thumbnails'); ?>: </h2> 
	
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("显示当前分类下的文章缩略图, 也可以显示指定分类id下的缩略图。", 'wp_thumbnails'); ?></div>
</div>
	
<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="size_of_category_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_category_images" name="width_of_category_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_category_images"]) print "value='" . $wp_thumbnails_options["width_of_category_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_category_images" name="height_of_category_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_category_images"]) print "value='" . $wp_thumbnails_options["height_of_category_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_category_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_category_images" name="number_of_category_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_category_images"]) print "value='" . $wp_thumbnails_options["number_of_category_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="category_pic_interval" name="category_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["category_pic_interval"]) print "value='" . $wp_thumbnails_options["category_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_show_title" name="category_show_title" <?php if ($wp_thumbnails_options["category_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="category_show_title" name="category_show_title" <?php if ($wp_thumbnails_options["category_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="category_title_pos" name="category_title_pos" <?php if ($wp_thumbnails_options["category_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="category_title_pos" name="category_title_pos" <?php if ($wp_thumbnails_options["category_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="category_title_pos" name="category_title_pos" <?php if ($wp_thumbnails_options["category_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="category_title_pos" name="category_title_pos" <?php if ($wp_thumbnails_options["category_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="category_title_width" name="category_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["category_title_width"]) print "value='" . $wp_thumbnails_options["category_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_wrap" name="category_wrap" <?php if ($wp_thumbnails_options["category_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="category_wrap" name="category_wrap" <?php if ($wp_thumbnails_options["category_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="category_excerpt_limit" name="category_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["category_excerpt_limit"]) print "value='" . $wp_thumbnails_options["category_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>


<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_new_window" name="category_new_window" <?php if ($wp_thumbnails_options["category_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="category_new_window" name="category_new_window" <?php if ($wp_thumbnails_options["category_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_link_target" name="category_link_target" <?php if ($wp_thumbnails_options["category_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="category_link_target" name="category_link_target" <?php if ($wp_thumbnails_options["category_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="category_order"><?php _e("顺序", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="category_order" name="category_order" <?php if ($wp_thumbnails_options["category_order"] == "random") print "checked='checked'"; ?> value="random" />  <?php _e("随机", 'wp_thumbnails'); ?> <input type="radio" id="category_order" name="category_order" <?php if ($wp_thumbnails_options["category_order"] == "recent") print "checked='checked'"; ?> value="recent" />  <?php _e("按时间", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_category')) { wp_thumbnails_for_category(); } ?&gt</code> <br /><?php _e("代码放置于分类页面模板任意位置", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

<div style="clear: both;padding-top:0px;"></div>
<p><h2><?php _e("标签缩略图", 'wp_thumbnails'); ?>: </h2> 
	
<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("功能说明", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><?php _e("显示当前标签下的文章缩略图, 也可以显示多个指定标签id下的缩略图。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="width_of_tag_images"><?php _e("尺寸", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("宽度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="width_of_tag_images" name="width_of_tag_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["width_of_tag_images"]) print "value='" . $wp_thumbnails_options["width_of_tag_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?>, <?php _e("高度", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="height_of_tag_images" name="height_of_tag_images" size="1" maxlength="3" <?php if ($wp_thumbnails_options["height_of_tag_images"]) print "value='" . $wp_thumbnails_options["height_of_tag_images"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?><br /></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="number_of_tag_images"><?php _e("数量", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="number_of_tag_images" name="number_of_tag_images" size="1" maxlength="4" <?php if ($wp_thumbnails_options["number_of_tag_images"]) print "value='" . $wp_thumbnails_options["number_of_tag_images"] . "'"; ?>/><?php _e("张", 'wp_thumbnails'); ?>, <?php _e("图片间距", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="tag_pic_interval" name="tag_pic_interval" size="1" maxlength="4" <?php if ($wp_thumbnails_options["tag_pic_interval"]) print "value='" . $wp_thumbnails_options["tag_pic_interval"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_show_title"><?php _e("标题", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="tag_show_title" name="tag_show_title" <?php if ($wp_thumbnails_options["tag_show_title"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("不显示", 'wp_thumbnails'); ?> <input type="radio" id="tag_show_title" name="tag_show_title" <?php if ($wp_thumbnails_options["tag_show_title"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("显示文章标题", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_title_pos"><?php _e("标题位置", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("标题显示在图片的", 'wp_thumbnails'); ?> <input type="radio" id="tag_title_pos" name="tag_title_pos" <?php if ($wp_thumbnails_options["tag_title_pos"] == "top") print "checked='checked'"; ?> value="top" />  <?php _e("上方", 'wp_thumbnails'); ?> <input type="radio" id="tag_title_pos" name="tag_title_pos" <?php if ($wp_thumbnails_options["tag_title_pos"] == "bottom") print "checked='checked'"; ?> value="bottom" />  <?php _e("下方", 'wp_thumbnails'); ?><input type="radio" id="tag_title_pos" name="tag_title_pos" <?php if ($wp_thumbnails_options["tag_title_pos"] == "left") print "checked='checked'"; ?> value="left" />  <?php _e("左边", 'wp_thumbnails'); ?> <input type="radio" id="tag_title_pos" name="tag_title_pos" <?php if ($wp_thumbnails_options["tag_title_pos"] == "right") print "checked='checked'"; ?> value="right" />  <?php _e("右边", 'wp_thumbnails'); ?>, <?php _e("标题在左右时,必须指定标题宽度为", 'wp_thumbnails'); ?> <input type="text" style="width:40px;" id="tag_title_width" name="tag_title_width" size="1" maxlength="4" <?php if ($wp_thumbnails_options["tag_title_width"]) print "value='" . $wp_thumbnails_options["tag_title_width"] . "'"; ?>/><?php _e("像素", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_wrap"><?php _e("标题截断", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="tag_wrap" name="tag_wrap" <?php if ($wp_thumbnails_options["tag_wrap"] == "true") print "checked='checked'"; ?> value="true" />  <?php _e("完整显示(对多行多列的显示效果有影响) ", 'wp_thumbnails'); ?><input type="radio" id="tag_wrap" name="tag_wrap" <?php if ($wp_thumbnails_options["tag_wrap"] == "false") print "checked='checked'"; ?> value="false" />  <?php _e("截断标题使之显示在一行内", 'wp_thumbnails'); ?>)</div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_excerpt_limit"><?php _e("摘要", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><?php _e("显示", 'wp_thumbnails'); ?><input type="text" style="width:40px;" id="tag_excerpt_limit" name="tag_excerpt_limit" size="1" maxlength="4" <?php if ($wp_thumbnails_options["tag_excerpt_limit"]) print "value='" . $wp_thumbnails_options["tag_excerpt_limit"] . "'"; ?>/><?php _e("个汉字长度的文章摘要，清空则不显示摘要。注意: 仅当标题显示在图片左右方向时才显示摘要。", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_new_window"><?php _e("窗口", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="tag_new_window" name="tag_new_window" <?php if ($wp_thumbnails_options["tag_new_window"] == "_blank") print "checked='checked'"; ?> value="_blank" />  <?php _e("在新窗口中打开", 'wp_thumbnails'); ?> <input type="radio" id="tag_new_window" name="tag_new_window" <?php if ($wp_thumbnails_options["tag_new_window"] != "_blank") print "checked='checked'"; ?> value="_self" />  <?php _e("在当前窗口打开", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_link_target"><?php _e("链接目标", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="tag_link_target" name="tag_link_target" <?php if ($wp_thumbnails_options["tag_link_target"] == "post") print "checked='checked'"; ?> value="post" />  <?php _e("点击打开文章", 'wp_thumbnails'); ?> <input type="radio" id="tag_link_target" name="tag_link_target" <?php if ($wp_thumbnails_options["tag_link_target"] == "image") print "checked='checked'"; ?> value="image" />  <?php _e("点击打开原始大图", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<label style="float:left;width:150px;text-align:right;padding-right:6px;padding-top:7px;" for="tag_order"><?php _e("顺序", 'wp_thumbnails'); ?> : </label>
<div style="float:left;"><input type="radio" id="tag_order" name="tag_order" <?php if ($wp_thumbnails_options["tag_order"] == "random") print "checked='checked'"; ?> value="random" />  <?php _e("随机", 'wp_thumbnails'); ?> <input type="radio" id="tag_order" name="tag_order" <?php if ($wp_thumbnails_options["tag_order"] == "recent") print "checked='checked'"; ?> value="recent" />  <?php _e("按时间", 'wp_thumbnails'); ?> </div>
</div>

<div style="clear: both;padding-top:10px;">
<div style="float:left;width:150px;text-align:right;padding-right:6px;"><?php _e("调用代码", 'wp_thumbnails'); ?> : </div>
<div style="float:left;"><code>&lt?php if(function_exists('wp_thumbnails_for_tag')) { wp_thumbnails_for_tag(); } ?&gt</code> <br /><?php _e("代码放置于任意位置", 'wp_thumbnails'); ?>, <?php _e("参数为tag的id，可以显示多个标签的缩略图", 'wp_thumbnails'); ?></div>
</div>

<div style="clear: both;padding-top:2px;padding-bottom:2px;text-align:center;">
<p class="submit"><input type="submit" name="submit" value="<?php _e("点击更新设置 &raquo;", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;padding-top:20px;">
<hr>
</div>

</form>


<form method="post" action="">

<div style="clear: both;padding-top:10px;"></div>
<p><h2><?php _e("清理和重置", 'wp_thumbnails'); ?>: </h2> 
<div style="clear: both;padding-top:10px;"></div>

<div style="float:left;padding-left:100px;padding-top:7px;">
<p class="submit"><input type="submit" name="reset-configuration" value="<?php _e("点击还原后台所有选项到默认设置 &raquo;  安全！", 'wp_thumbnails'); ?>" /></p>
<p class="submit"><input type="submit" name="clean-old-thumbnails" value="<?php _e("点击清理不再用到的缩略图 &raquo;  安全！", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;"></div>

<div style="float:left;padding-left:100px;padding-top:7px;">
<?php _e("如果你的空间有限, 可以一键清理下载到本地的远程图片, 即删除/wp-content/uploads/ta-thumbnails-cache/TAdownload文件夹下所有图片: ", 'wp_thumbnails'); ?><br>
<?php _e("这样做的前提是:后台的缩略图尺寸都不再改动, 即不需要生成和调用新尺寸的图片。只要不再改动尺寸, 已有的缩略图仍然可以正常显示: ", 'wp_thumbnails'); ?>
<p class="submit"><input type="submit" name="clean-download" value="<?php _e("点击清理下载的远程文件 &raquo;  慎用！", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;"></div>

<div style="float:left;padding-left:100px;padding-top:7px;">
<?php _e("一键清理全部自定义域ta-thumbnail, 使插件重新检测缩略图: ", 'wp_thumbnails'); ?>
<p class="submit"><input type="submit" name="clean-custom-fields" value="<?php _e("点击清理自定义域 &raquo;  慎用！", 'wp_thumbnails'); ?>" /></p>
</div>

<div style="clear: both;"></div>

<div style="float:left;padding-left:100px;padding-top:7px;">
<?php _e("一键清理数据库并删除所有下载的和生成的缩略图。之后逐一点开文章, 插件会重新生成缩略图: ", 'wp_thumbnails'); ?>
<p class="submit"><input type="submit" name="clean" value="<?php _e("点击彻底清理 &raquo;  慎用！", 'wp_thumbnails'); ?>" /></p>
</div>

</form>

</div>

<?php
}

function wp_thumbnails_for_homepage_preview()
{
	global $wpdb;
	$now = current_time('mysql', 1);
	$sql = "SELECT distinct post_id
	FROM $wpdb->postmeta, $wpdb->posts
	WHERE post_id = ID
	AND meta_key IN ('ta-thumbnail')
	AND meta_value NOT LIKE '%NOIMAGEINPOST%'
	AND post_status = 'publish' 
	AND post_date_gmt < '$now' 
	AND post_type = 'post'
	ORDER BY post_id DESC
	LIMIT 1";
	
	$result = @mysql_query($sql, $wpdb->dbh);
	$number = @mysql_num_rows($result);
	if ($number == 1)
	{
		$row = mysql_fetch_array($result);
		$post_id = $row['post_id'];
		$post = get_post($post_id);
		$post_content = "wp-thumbnails-preview".$post->post_content;
		$post_content = wp_thumbnails_excerpt($post_content);
		$post_content = str_replace("wp-thumbnails-preview", "", $post_content);
		
		$post_id = "id=".$post_id;
		echo "<div style=\"width:550px;\">";
		wp_thumbnails_for_homepage($post_id);
		echo $post_content;
		echo "</div>";
	}
}

?>